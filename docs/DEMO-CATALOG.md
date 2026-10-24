# MONOLITH — Temporary WooCommerce Demo Catalog

**Purpose:** Visual-development catalog used while building the MONOLITH WordPress/WooCommerce experience.

**Status:** Approved for temporary live use during design development.

## Source

Google Drive demo-product folder:

`1W4k6AGdVtiRDIbAYLSQFSl8IwKIFQE6v`

Current demo sets bundled in MONOLITH Theme v0.2:

- DEMO-001 — Tungsten Koa Product Photos
- DEMO-002 — Hammered Tungsten Meteorite Ring
- DEMO-003 — Tungsten Dinosaur Bone Meteorite 8mm
- DEMO-004 — Tungsten Whiskey Barrel Antler 8mm
- DEMO-006 — Black Faceted Galaxy Inlay Ring
- DEMO-007 — Black Gold Silver Fleck Ring

There is currently no DEMO-005 source folder.

## Import behavior

- Requires WooCommerce.
- Imports automatically once when MONOLITH Theme v0.2 is active and an administrator loads wp-admin.
- Products are published and visible.
- Stable SKUs make the import idempotent:
  - MONO-DEMO-001
  - MONO-DEMO-002
  - MONO-DEMO-003
  - MONO-DEMO-004
  - MONO-DEMO-006
  - MONO-DEMO-007
- Re-importing refreshes existing demo products rather than creating duplicates.
- Bundled demo images are optimized WebP copies of the Drive source imagery and are imported into the WordPress Media Library.
- Demo products and imported attachments are tagged with MONOLITH-specific metadata for cleanup.
- Appearance > MONOLITH Demo Setup provides:
  - Import / Refresh Demo Catalog
  - Remove All Demo Products

## Product-data rule

All demo pricing, product text, material labels, and specifications are placeholders for layout testing unless separately verified and approved. They must not become production product claims merely because they exist in WooCommerce during development.

## DEMO-003

DEMO-003 currently has only its source-reference image. It is intentionally included as a sparse-gallery test case so the product page is validated against missing optional imagery.

## Removal

After layout/design approval, use the MONOLITH Demo Setup removal action. It deletes:

- all products marked as MONOLITH demo products,
- their imported demo media,
- the temporary Demo Bands category,
- the temporary MONOLITH Demo product tag.

Production products must never use the demo-product metadata flag.
