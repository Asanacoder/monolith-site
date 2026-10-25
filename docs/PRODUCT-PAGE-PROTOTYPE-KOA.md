# MONOLITH — Koa Master PDP Prototype

**Status:** LIVE PROTOTYPE / NOT YET APPROVED AS GLOBAL PDP STANDARD  
**Theme version:** 0.6.0  
**Prototype product:** WooCommerce product ID 82 — Koa Tungsten Band / Demo 001  
**Updated:** 2026-10-25

## Purpose

Validate the visual language for a reusable cinematic MONOLITH product-page engine before applying the system to the wider catalog.

The prototype deliberately keeps WooCommerce as the purchase/commerce layer while the page below the purchase stage behaves more like an editorial landing page.

## Architecture

The prototype is isolated to product ID 82. Other products continue using the existing WooCommerce presentation.

Current order:

1. Native WooCommerce gallery + size-only purchase controls
2. MONOLITH product title treatment, emotional intro, and dynamic product signature
3. Dynamic fast-proof strip from Woo attributes
4. Dynamic Product Architecture specifications + curated Explore links
5. Full-width product thesis hero
6. Material-contrast split section using backend attribute values
7. Lifestyle / on-hand section
8. Dynamic Character section from MONOLITH discovery taxonomies
9. Presentation / packaging section
10. Scale / fit-imagination section
11. Closing architectural CTA
12. MONOLITH footer

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

Theme modules:
- `inc/koa-pdp-prototype.php`
- `inc/product-discovery.php`
- `assets/css/product-cinematic.css`
- `assets/js/product-cinematic.js`

The implementation:
- targets only product ID 82 for the cinematic PDP
- keeps WooCommerce price/cart/payment/variation behavior native
- converts Koa Demo 001 to a variable product using Ring Size as the only variation
- renders product signature, proof strip, specifications, and Explore links from Woo attributes/categories rather than duplicated template facts
- renders Atmosphere / Archetype / Presence from custom MONOLITH discovery taxonomies
- renders MONOLITH size buttons as a presentation layer synchronized with WooCommerce's native ring-size select
- omits unpopulated factual attributes automatically
- is responsive
- uses WordPress Media Library attachment URLs rather than Google Drive URLs

### Koa structured product data

Current supported demo mappings:
- Base Material = Tungsten Carbide
- Band Color = Black
- Coating / Plating = Black Coated (generic process only; exact PVD/IP not claimed)
- Inlay & Accent Material = Koa Wood
- Surface Finish = Brushed + Polished
- Fit = Comfort Fit
- Edge Style = Rounded Edge
- Interior Material = Same as Base Material
- Ring Size = US 7–13 in half sizes; 13 live variations

Not assigned because not verified from the DEMO-001 source set:
- Band Width
- Profile

Curated categories:
- Wedding Bands
- Tungsten Bands
- Black Wedding Bands
- Wood Rings

Development category/tag remain stored but are excluded from the customer-facing discovery UI:
- Demo Bands
- MONOLITH Demo

### Initial discovery taxonomy terms

Koa currently carries:
- Atmosphere = Organic, Nocturnal
- Archetype = Grounded, Quiet Romantic
- Presence = Understated, Substantial

These are database terms, not hard-coded display labels. The final discovery vocabulary should expand only as real catalog products justify new terms.

**Architecture note:** discovery taxonomy registration is currently included in the theme as part of the Koa prototype. Before global rollout, move ownership of these taxonomies into the planned MONOLITH Core plugin, consistent with the project architecture.

## Visual QA

Automated full-page browser QA passed again on 2026-10-25 after the structured-data upgrade:

- gallery and purchase panel align correctly on desktop
- all 13 ring sizes render as styled MONOLITH buttons while using native Woo variation logic
- Product Architecture specifications render dynamically and remain readable
- Explore links render for Black Wedding Bands, Tungsten Bands, and Wood Rings
- Character section renders Atmosphere / Archetype / Presence dynamically
- cinematic sections and editorial assets load without overlap or broken imagery
- Add to Cart remains disabled until a size is selected, as expected
- native WooCommerce commerce/payment behavior remains intact

## Development visibility

After QA:
- WooCommerce Coming Soon restored to `yes`
- WordPress `blog_public` confirmed `0`
- public QA response confirmed `noindex, nofollow` while the storefront was temporarily opened for testing

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

After approval, move remaining product-specific editorial copy/content out of theme hard-coding and into MONOLITH Core product fields, move discovery taxonomy registration into MONOLITH Core, then generalize this proven structured-data renderer into the reusable PDP engine.
