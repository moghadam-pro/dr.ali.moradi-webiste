# Innovation source links

Current source: `Innovation-list.docx`, supplied on 2026-08-23.

The source contains **14 records and 15 links**. Bionic Hand H3 is one record with
two references, so the product reference must not be counted as a separate
innovation.

## Records and destinations

1. [Dynamometer](https://dralimoradi.com/external-fixators-1/) — migrated to
   `/innovation/dynamometer`.
2. [Magnetic Joint Distraction](https://dralimoradi.com/magnetic-distractor/) —
   migrated to `/innovation/magnetic-joint-distraction`.
3. [Dynamic Distal Radius External Fixator](https://dralimoradi.com/external-fixators/)
   — migrated to `/innovation/dynamic-distal-radius-external-fixator`.
4. [Dynamic Hip External Fixator](https://avisa-med.com/index.php/products/external-fixators/dynamic-hip-external-fixator).
5. [Intra-osseous DRUJ Prosthesis](https://avisa-med.com/index.php/products/high-technological-in-process-products/intra-osseous-distal-radioulnar-prosthesis).
6. [Lag Plate](https://avisa-med.com/index.php/products/high-technological-in-process-products/lag-plate).
7. [Artificial Finger Pulley](https://avisa-med.com/index.php/products/high-technological-in-process-products/artificial-finger-pulley).
8. Bionic Hand H3 — migrated to `/innovation/bionic-hand-h3`, preserving both
   the [archived Dr. Moradi page](https://dralimoradi.com/bionic-hand-h3/) and
   [Avisa product reference](https://avisa-med.com/index.php/products/bionic-hand/integlim-hand).
9. [Bionic Hand Software](https://avisa-med.com/index.php/products/bionic-hand/integlearn-software).
10. [Bionic Hand H5](https://dralimoradi.com/bionic-hand-h5/) — migrated to
    `/innovation/bionic-hand-h5`.
11. [Magnetic Control System for Artificial Limb](https://orthopresearch.com/index.php/innovative-projects-inventions/robotics/bionic-limb).
12. [Integrated Stem](https://dralimoradi.com/integrated-stem/) — migrated to
    `/innovation/integrated-stem`.
13. [Hip Exoskeleton HEXA](https://dralimoradi.com/hip-exoskeleton-hexa/) —
    migrated to `/innovation/hip-exoskeleton-hexa`.
14. [Coated Schanz Pins](http://avisa-med.com/index.php/products/schanz-pins).

## Historical migration rules (superseded by 3.2.0)

- Records hosted on Dr. Moradi's previous domain have an internal page in the
  new interior-page template and retain the archived source link.
- External product and research destinations remain clearly marked external
  links.
- The Schanz Pins source remains HTTP because that is the exact supplied link;
  upgrade it only after the destination is verified to support HTTPS.
- Product descriptions are concise editorial summaries, not clinical claims or
  patient-use instructions.

## Production restoration, 2026-10-06

All 14 records and 15 references were restored to the English, Persian and Arabic
Innovation Pages. The seven historical Dr. Moradi paths above no longer resolve
to their original project on the current site (six 404s and one homepage redirect).
Their corresponding `https://legacy.dralimoradi.com/{original-path}/` references
were checked and return 200 with the correct archived project pages. Production
uses those archived destinations and labels them as archive references.

The external Avisa/Orthop references retain the source URLs. Four responded 200
during the audit; four timed out, so availability of those external destinations
is not claimed. The HTTP Schanz Pins source is preserved as supplied.

### Layout correction

The reference catalogue replaces the original four editorial sections in the
main sidebar/body layout of each language Page. All fourteen titles appear in
the sidebar with matching section anchors. The former Continue exploring card
and the appended duplicate catalogue section are removed. Covers, team and
latest-innovation blocks remain in their existing positions after the main body.


## Independent WordPress project Pages, 2026-10-06

All fifteen primary URLs were fetched successfully (200) for fourteen records.
The previous doctor-domain routes were read from their matching legacy archive
projects. Each project now has source-based EN/FA/AR content prepared for an
ordinary WordPress Page under its translated Innovation hub. Public detail
Pages omit old doctor-domain source hyperlinks. The eight external sources
remain cited in the corresponding project Pages, including the Avisa reference
for H3. Catalogue buttons resolve to the created Page in the current language.
Source illustrations are imported into Media with original-file hash reuse.

The source content is reorganized and translated around the actual project
facts; promotional superlatives, global menus, repeated responsive paragraphs
and unrelated footer/contact content are excluded. Product and research claims
retain their development/manufacturer context. Source HTML captures remain
outside Git; the curated multilingual Page manifest is checked in.
