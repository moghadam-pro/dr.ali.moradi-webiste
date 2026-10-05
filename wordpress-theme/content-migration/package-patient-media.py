#!/usr/bin/env python3
"""Package privately downloaded Drive media for the administrator-only importer.

Keep inventory, media, ZIPs and manifests outside Git. ZIP entries are flat
source-ID filenames; import manifests contain checksums, never download tokens.
"""
import argparse
import hashlib
import json
import pathlib
import re
import zipfile

parser = argparse.ArgumentParser()
parser.add_argument("inventory", type=pathlib.Path)
parser.add_argument("files", type=pathlib.Path)
parser.add_argument("output", type=pathlib.Path)
parser.add_argument("--max-mb", type=int, default=150)
args = parser.parse_args()
if args.max_mb < 1:
    parser.error("--max-mb must be positive")
assets = [asset for asset in json.loads(args.inventory.read_text())["media"]
          if not asset["title"].startswith("._")]
missing = []
for asset in assets:
    if not re.fullmatch(r"[A-Za-z0-9_-]{1,100}", asset["id"]) or pathlib.Path(asset["title"]).suffix.lower() not in {".jpg", ".jpeg", ".png", ".bmp", ".heif", ".heic", ".mp4"}:
        raise SystemExit("Unsafe source ID or unsupported media extension.")
    path = args.files / (asset["id"] + pathlib.Path(asset["title"]).suffix.lower())
    if not path.is_file() or path.stat().st_size != int(asset["size"]):
        missing.append(asset["id"])
if missing:
    raise SystemExit(f"Missing/incomplete files: {len(missing)}; finish downloads first.")
args.output.mkdir(parents=True, exist_ok=True)
batches = []
batch, size = [], 0
for asset in assets:
    length = int(asset["size"])
    if batch and size + length > args.max_mb * 1_000_000:
        batches.append(batch)
        batch, size = [], 0
    batch.append(asset)
    size += length
if batch:
    batches.append(batch)
for number, batch in enumerate(batches, 1):
    operations = []
    stem = f"patient-media-{number:02}"
    with zipfile.ZipFile(args.output / (stem + ".zip"), "w", zipfile.ZIP_STORED) as archive:
        for asset in batch:
            name = asset["id"] + pathlib.Path(asset["title"]).suffix.lower()
            path = args.files / name
            digest = hashlib.sha256()
            with path.open("rb") as source:
                for chunk in iter(lambda: source.read(1024 * 1024), b""):
                    digest.update(chunk)
            archive.write(path, name)
            operations.append({"kind": "media", "key": asset["id"],
                               "patient": asset["patient"], "filename": asset["title"],
                               "local": name, "sha256": digest.hexdigest(),
                               "order": asset["order"]})
    (args.output / (stem + ".json")).write_text(json.dumps({"operations": operations}))
    print(f"Batch {number}: {len(batch)} media, {sum(int(a['size']) for a in batch) / 1e6:.1f} MB")
