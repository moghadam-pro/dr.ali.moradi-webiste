# Brand System Source

## Source

The source brand book is the 18-page PDF at:

`branding/set-2_260520_205659.pdf`

The one-page `Unknown.pdf` contains a clean all-vector logo lockup and was used to
recover the mark geometry. The website does not use the old Persian wordmark or its
legacy Latin typography.

## Brand colors recovered from the brand book

- Primary blue: `#63A8CB`
- Accent orange: `#F7941D`
- Near black: `#231F20`
- Mid gray: `#939598`
- Light gray: `#E6E7E8`

Blue is the approved primary direction. Orange is present in the official brand
book, but its role in the redesigned interface remains selective and secondary.

## Web logo system

- `public/brand/logo-mark.svg`: standalone vector mark extracted from the PDF paths
- `public/brand/logo-lockup.svg`: mark left, English name right, for light surfaces
- `public/brand/logo-lockup-reverse.svg`: footer/dark-surface variant

The English wordmark uses Manrope. The lockup intentionally contains only the name
so it remains legible and compact in both the header and footer.

## Usage

- Header: standard lockup on light or translucent navigation surfaces
- Footer: reverse lockup on dark blue surfaces
- Favicon, social avatar, loading mark: standalone symbol
- Preserve the mark aspect ratio and clear space.
- Never recolor the mark with gradients or distort it.
- Never combine the new English lockup with the old Persian typography.

## Source note

The mark geometry is vector data recovered from the supplied PDF, not an automatic
trace of a bitmap.
