# MONOLITH — Koa Master PDP Prototype

**Status:** LIVE PROTOTYPE / NOT YET APPROVED AS GLOBAL PDP STANDARD  
**Theme version:** 0.5.0  
**Prototype product:** WooCommerce product ID 82 — Koa Tungsten Band / Demo 001  
**Date:** 2026-10-24

## Purpose

Validate the visual language for a reusable cinematic MONOLITH product-page engine before applying the system to the wider catalog.

The prototype deliberately keeps WooCommerce as the purchase/commerce layer while the page below the purchase stage behaves more like an editorial landing page.

## Architecture

The prototype is isolated to product ID 82. Other products continue using the existing WooCommerce presentation.

Current order:

1. Native WooCommerce gallery + purchase controls
2. MONOLITH product title treatment and short emotional intro
3. Fast visual proof strip
4. Full-width product thesis hero
5. Material-contrast split section
6. Lifestyle / on-hand section
7. Presentation / packaging section
8. Scale / fit-imagination section
9. Closing architectural CTA
10. MONOLITH footer

Default WooCommerce tabs, related products and metadata are suppressed only on the Koa prototype so the page can be evaluated as a campaign-style PDP.

## Editorial source assets

Original/source assets remain in Google Drive. Selected assets were copied into the WordPress Media Library for web delivery.

- Attachment 134 — packaging concept
- Attachment 135 — materials editorial
- Attachment 136 — architecture editorial
- Attachment 137 — wide editorial / thesis hero
- Attachment 138 — lifestyle portrait
- Attachment 139 — fingertip scale

Existing WooCommerce gallery attachments remain 83–87.

The blueprint concept image was intentionally not used because it visually states unverified dimensions/material specifications. The packaging image is explicitly labeled as a design-development concept in page copy.

## Prototype copy direction

The Koa page is framed around contrast:

- warm grain
- dark metal
- quiet presence
- natural warmth held inside disciplined structure

This is art-direction copy for the prototype, not final product-description or verified specification copy.

## Technical implementation

Theme module:
- `inc/koa-pdp-prototype.php`
- `assets/css/product-cinematic.css`

The module:
- targets only product ID 82
- keeps WooCommerce price/cart/payment behavior native
- swaps only the presentation title/intro on the Koa page
- injects the cinematic editorial sections through WooCommerce hooks
- is responsive
- uses WordPress Media Library attachment URLs rather than Google Drive URLs

## Visual QA

Automated full-page browser QA passed on 2026-10-24:

- cinematic sections rendered
- editorial assets loaded
- no broken images detected
- no obvious overlap or horizontal overflow detected
- purchase controls remained functional/native
- footer rendered correctly

## Development visibility

After QA:
- WooCommerce Coming Soon restored to `yes`
- WordPress `blog_public` remains `0`

## Approval gate before scaling

Do not apply this structure to other products yet.

Owner review should decide:

- overall section rhythm
- amount of copy
- darkness vs warm-mineral sections
- whether lifestyle is prominent enough
- whether packaging section feels premium enough
- how much Woo/payment UI remains visible in the purchase stage
- whether reviews/FAQ/fit modules belong in the final architecture
- which modules are mandatory vs optional

After approval, move product-specific editorial content out of theme hard-coding and into MONOLITH Core product fields, then convert this prototype into the reusable PDP engine.
