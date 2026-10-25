# MONOLITH — Project Decisions & Business Rules

**Purpose:** Living source of truth for decisions made during MONOLITH product, policy, UX, and website interviews.

**Rule:** New agents must distinguish confirmed business decisions from recommendations and unresolved questions. Do not promote a recommendation into policy until it is explicitly approved.

**Status date:** October 2026

**Related research:** `docs/COMPETITOR-FIT-WARRANTY-RESEARCH.md` contains competitor benchmarks. Competitor policies are research only and do not become MONOLITH policy unless approved here.

**Architecture research:** `docs/CHATGPT-SITES-ECOMMERCE-EVALUATION.md` evaluates ChatGPT Sites vs Shopify vs WooCommerce. It is research only; the approved architecture remains unchanged unless explicitly revised.

---

## Status Labels

- **APPROVED** — explicitly decided by the project owner.
- **PROVISIONAL** — current working direction; may change.
- **RECOMMENDED / AWAITING APPROVAL** — suggested by research or the agent, but not yet approved.
- **OPEN QUESTION** — must be resolved before production copy or business logic is finalized.

---

## 0. Core Ecommerce Platform

### WooCommerce remains the commerce engine
**Status: APPROVED**

After evaluating ChatGPT Sites, Shopify, and WooCommerce, the project owner confirmed that MONOLITH will **keep WooCommerce** as the core ecommerce platform.

Current architecture remains:

- WordPress = CMS/admin
- WooCommerce = products, variants, inventory, cart, checkout, orders, taxes, shipping, customer/order state
- MONOLITH custom theme = visual/frontend experience
- MONOLITH Core = brand-specific metadata and custom business logic
- ChatGPT Sites may still be considered later for separate interactive tools, prototypes, calculators, quizzes, or microsites, but **not as the primary MONOLITH commerce backend**
- Shopify is not the current target platform

Core principle remains:

> **WooCommerce owns commerce. MONOLITH owns the experience.**

---

## 0B. WordPress AI Bridge

### Direct agent-to-WordPress control
**Status: RECOMMENDED / AWAITING IMPLEMENTATION APPROVAL**

A proposed architecture for secure AI-driven WordPress/WooCommerce operations is documented in `docs/WORDPRESS-AI-BRIDGE.md`.

The preferred model uses WordPress Abilities/MCP for controlled site/data operations and GitHub + staging deployment for code changes. This would allow an authorized agent to create/edit pages, media, products, product metadata, approved settings, and plugin operations while preserving auditable code deployment and rollback.

---

## 0C. Temporary WordPress Theme

### Clean interim theme before MONOLITH custom theme
**Status: RECOMMENDED / AWAITING OWNER CONFIRMATION**

Use the official **Twenty Twenty-Five** WordPress block theme as the temporary/staging theme instead of Divi or another page-builder theme.

Reasoning:

- core WordPress theme with minimal dependency footprint
- no Divi/Elementor-style builder lock-in
- native Site Editor / block structure
- simple enough not to dictate MONOLITH's final visual system
- easy to replace once the custom MONOLITH theme is ready

Avoid building permanent MONOLITH layouts into the temporary theme. The final production presentation remains the custom MONOLITH theme.

Divi / Divi Builder should be removed or kept inactive once legacy content has been migrated and verified.

---

## 1. Checkout / Transactional UI

### Approved visual direction
**Status: APPROVED**

The checkout design direction is approved and should be reused as the transactional MONOLITH UI language:

- warm mineral / off-white primary surfaces
- graphite / obsidian contrast areas
- restrained warm-metal / copper accents
- refined serif headlines
- minimal sans-serif UI text
- thin borders
- generous spacing
- clean, calm forms
- very little decorative imagery
- dark sticky order summary on desktop
- compact expandable order summary on mobile
- luxury, architectural, masculine, calm, trustworthy
- clarity takes priority over cinematic effects during checkout

This styling should inform cart, mini-cart, account, order confirmation, sizing forms, and other transactional screens.

### Architecture
**Status: APPROVED**

WooCommerce remains responsible for actual cart, checkout, taxes, shipping, payment, order creation, validation, inventory, and transactional logic. MONOLITH provides the presentation layer.

---

## 2. Support / Contact Experience

### Approved visual direction
**Status: APPROVED**

Support/contact should feel like the support-oriented sibling of checkout:

- same warm mineral / graphite / restrained copper system
- slightly more editorial than checkout
- still calm, minimal, and architectural
- function as a support hub, not just a plain contact form

Current support prototype includes:

- support routing
- help-center topics
- substantial contact form
- pre-purchase product guidance
- FAQ area
- restrained footer

No unverified service promises such as response time, warranty coverage, phone availability, or return windows should be published until approved.

---

## 3. Prototype Navigation

### Current preview behavior
**Status: APPROVED FOR PROTOTYPE**

- MONOLITH logo → homepage
- Bands → homepage Bands section
- Materials → homepage Materials section
- The Ceremony → homepage Ceremony section
- Support / Contact → support page
- Bag → cart page
- Cart → checkout
- Checkout logo / Continue Shopping → homepage
- Checkout Contact link → support page

Pages not yet built should remain placeholders rather than linking to fake destinations.

---

## 4. Ring Sizing Strategy

### Standalone Fit Kit concept
**Status: PROVISIONAL**

Current owner direction:

- Offer a physical sizing kit for approximately **$20**.
- The customer pays for the kit upfront so people do not order free sizers with no purchase intent.
- When the customer later buys a MONOLITH ring, the sizing-kit amount should be credited back against the ring purchase.
- The intended result is that the Fit Kit is effectively free for a genuine ring buyer.

### Recommended implementation
**Status: RECOMMENDED / AWAITING APPROVAL**

Working names:

- **MONOLITH Fit Assurance** — name of the sizing service/system
- **MONOLITH Fit Kit** — name of the physical sizing kit

Recommended customer-facing model:

- Fit Kit price: **$20**
- Customer receives **$20 Band Credit**
- Prefer automatic account/email-linked credit over a coupon the buyer must remember
- If a customer has already chosen a ring but is unsure of size, consider allowing them to place the ring order now, send the Fit Kit first, and hold final ring fulfillment until they confirm size

Recommended premium kit direction:

- use substantial sample-sizing rings rather than only paper or thin belt-style sizers
- match likely MONOLITH widths / comfort-fit feel as closely as practical
- clearly mark ring sizes
- instruct customer to test fit at different times of day

### Open implementation questions
**Status: OPEN QUESTION**

- Is the $20 Fit Kit credit automatic by account/email, or issued as a code?
- Does the ring-order-first / Fit-Kit-before-fulfillment flow become official?
- Which widths / sample sizes should physically ship in the Fit Kit?
- Does the Fit Kit need to be returned or is it kept by the customer?

---

## 5. Wrong-Size Exchange Policy

### First size exchange
**Status: APPROVED**

If the customer receives the ring and the size is wrong:

- MONOLITH offers **one free first size exchange**
- the original ring must be received back by MONOLITH first
- only after MONOLITH receives the returned ring is the replacement size sent

### Additional size exchanges
**Status: APPROVED**

- Any additional size exchange after the first costs **$30**

### Policy wording recommendation
**Status: RECOMMENDED / AWAITING APPROVAL**

Prefer describing the $30 charge as a **size-exchange fee** rather than a “restocking fee” in customer-facing copy, because the charge is tied specifically to repeated sizing changes.

Working service name:

- **MONOLITH First Fit Exchange**

Recommended scope:

- first complimentary size exchange applies to the same ring/configuration
- switching model, width, finish, or other product configuration should be treated as a normal return/exchange rather than a sizing exchange

### Open questions
**Status: OPEN QUESTION**

- Who pays return shipping for the first complimentary size exchange?
- Does MONOLITH provide a prepaid return label?
- What condition must the returned ring be in?
- Is original packaging required?
- Does using the MONOLITH Fit Kit change any exchange eligibility or cost?
- For the one-time lifetime replacement, must the original ring be returned?
- Does a lifetime replacement include replacement engraving performed by MONOLITH, or only the replacement ring itself?
- Is the paid replacement / store-credit amount finalized at $100?
- Does store credit expire, transfer, or have product/category restrictions?

---


## 6. Engraved Ring + Wrong Size / Lifetime Fit Replacement

### One-time lifetime replacement concept
**Status: APPROVED IN PRINCIPLE**

MONOLITH plans to include a **one-time lifetime replacement ring benefit** intended for a future fit change, such as the wearer's finger becoming larger or otherwise changing over time.

Owner intent:

- the benefit is for **one additional replacement ring**
- it may be used later in life when the customer's finger size changes
- if an engraved ring turns out to be the wrong size, the customer may choose to use this one-time lifetime replacement immediately
- if the customer uses the lifetime replacement for that engraved wrong-size ring, the one-time lifetime replacement benefit is considered consumed
- third-party engraving performed by the customer after purchase is not reimbursed by MONOLITH

This concept should be kept separate from ordinary returns and ordinary first size exchange rules.

### Additional replacement after the lifetime benefit is used
**Status: PROVISIONAL**

Current owner direction is to allow another replacement through a **paid replacement contribution that converts into MONOLITH store credit**.

Working model:

- customer pays approximately **$100** for an additional replacement ring
- MONOLITH supplies the replacement ring
- the same **$100 becomes store credit** the customer can use later on MONOLITH merchandise
- economically, the customer is not simply paying a replacement fee; the payment remains available to them as future purchasing power with the brand
- current internal logic assumes the replacement ring may cost MONOLITH roughly $30, leaving margin while also encouraging a future purchase

The owner initially considered $50, then moved toward **$100**; therefore the exact amount is not yet final.

### Recommended customer-facing framing
**Status: RECOMMENDED / AWAITING APPROVAL**

Avoid describing the one-time lifetime benefit as a generic “lifetime warranty,” because customers may interpret that as unlimited defect/damage coverage.

Consider separating the concepts:

- **Lifetime Fit Replacement** — one replacement ring for a future size change
- **Replacement Credit** — after that benefit has been used, an additional replacement can be obtained with a contribution that is returned as MONOLITH store credit

This keeps fit-change coverage distinct from manufacturing-defect warranty coverage.

### Open questions
**Status: OPEN QUESTION**

- Must the original ring be returned to use the one-time Lifetime Fit Replacement?
- Does the replacement need to be the same ring model/configuration?
- If MONOLITH engraved the original ring, is the same engraving included on the replacement?
- Does the one-time benefit apply to loss/theft, or only physical possession + size change?
- Is the additional replacement/store-credit amount **$100**?
- Does store credit expire?
- Is store credit transferable?
- Can the credit be used on any MONOLITH product or only future ring purchases?
- Is shipping included or charged separately?

---

## 7. Product / Policy Communication Principle

**Status: APPROVED PROJECT PRINCIPLE**

MONOLITH policy and product copy should prioritize:

- certainty
- engineering clarity
- material honesty
- fit confidence
- transparent limitations
- no surprises after checkout

Avoid vague overpromising. Product-specific claims must be verified before they are treated as MONOLITH facts.

---

## 8. Interview-to-Repository Workflow

**Status: APPROVED**

During ongoing owner interviews:

1. Capture the owner's raw operational answer.
2. Translate it into the correct ecommerce concept, feature, policy, or service.
3. Research market practice when useful.
4. Clearly separate:
   - owner-approved decisions
   - provisional directions
   - recommendations
   - open questions
5. Update this document after meaningful decisions.
6. Update dedicated policy/schema documents later when a topic is mature enough.
7. Do not rely on chat memory as the only storage location.

---

## 9. Topics Still To Resolve

Current interview queue:

- return policy for non-sizing dissatisfaction
- warranty scope (manufacturing defects vs Lifetime Fit Replacement)
- finalize Lifetime Fit Replacement mechanics
- finalize paid replacement + store-credit mechanics
- accidental damage / discounted replacement
- shipping / fulfillment timing
- wedding-deadline / rush-order service
- post-order change window
- engraving price, limits, fonts, symbols, and preview
- repair / restoration / refinishing
- support channels and realistic response times
- exact Fit Kit contents and logistics



---

## 0D. Temporary Demo Catalog for Live Design Development

### Live-site demo data is allowed during the design phase
**Status: APPROVED**

The project owner confirmed that `monolithbands.com` is currently an empty, non-indexed development storefront with effectively no visitors, and that temporary demo products may be published directly on the live site while the MONOLITH theme and WooCommerce layouts are being designed.

Implementation direction:

- MONOLITH Theme v0.2 may import the temporary demo catalog automatically on first activation/admin load when WooCommerce is active.
- Demo products should be normal published WooCommerce products so real shop, PDP, cart, and checkout layouts can be evaluated.
- Demo products and demo media must be internally tagged so the whole temporary catalog can be removed cleanly after design approval.
- Re-running the importer must update/reuse the same demo SKUs rather than duplicate products.
- Demo copy, pricing, and specifications are placeholders and must not be treated as approved product facts.
- The Drive demo imagery is the source for the temporary visual catalog; source-reference and QA-marked assets should not be mistaken for final production media.
- DEMO-003 may intentionally remain sparse to test one-image/missing-optional-content behavior.

This temporary workflow does not change the production architecture: WooCommerce remains the product/commerce owner and the MONOLITH theme remains the presentation layer.


---

## 0E. MONOLITH Theme v0.3 — Prototype Migration

**Status: IMPLEMENTED FOR DESIGN TESTING**

Theme v0.3 migrates the approved static prototype language into the WordPress/WooCommerce theme:

- homepage returns to an image-led cinematic presentation rather than abstract CSS-only objects
- Support/Contact page ports the approved warm-mineral / graphite support hub design and prototype copy
- Cart/Bag page ports the approved transactional visual language while keeping WooCommerce cart data and calculations native
- Checkout ports the approved warm-mineral main surface + dark order-summary direction while keeping WooCommerce checkout, shipping, tax, payment and order logic native
- responsive/mobile behavior is retained as a first-class requirement
- demo catalog importer from v0.2 remains included

The original static homepage used very large Base64-embedded image assets. v0.3 does not reintroduce those Base64 production assumptions; it uses bundled MONOLITH demo imagery as fallbacks and keeps homepage imagery replaceable through WordPress. Exact archival prototype image extraction can be handled separately if those exact photographs are required.


---

## 0F. MONOLITH Theme v0.4 — Photo-Led Homepage Migration

**Status: IMPLEMENTED FOR DESIGN TESTING**

The live-theme homepage screenshots exposed that the prior WordPress migration was still rendering development placeholders (abstract CSS ring/slab objects and internal language such as “WooCommerce native”) instead of the established MONOLITH visual assets.

Theme v0.4 corrects that by:

- replacing the abstract homepage hero with the existing coastal-man MONOLITH hero image
- using the existing sunset architectural ring imagery for the selected-bands atmosphere
- using the existing brushed-ring sunset image for Materials
- using the existing coastal wedding-embrace image for The Ceremony
- using the existing gunmetal presentation-box image for Ownership
- using existing architectural ring imagery for the closing CTA
- keeping real WooCommerce products/data in the product cards
- removing customer-facing implementation language from homepage trust blocks
- adding stronger full-bleed/overflow rules so section imagery does not leak at the viewport edge

The architecture remains unchanged: WooCommerce owns commerce; the theme owns the visual presentation. Homepage images remain replaceable through WordPress Customizer controls where already supported.


---

## 0F. Product Variation Rule — Size Only

**Status: APPROVED**

MONOLITH will use **Ring Size as the only standard WooCommerce product variation**.

- Ring Size is the only normal customer-selectable WooCommerce variation.
- Width is a global attribute/specification, not a variation.
- If one design is sold in multiple widths (for example 6 mm and 8 mm), each width should normally be a separate product with its own photography/cinematic PDP, linked as sibling designs.
- Color, base material, coating/plating, inlay, finish, profile, edge style, fit, and interior material are standardized WooCommerce attributes/taxonomies for filtering/comparison/specification, not variation generators.
- A materially different colorway/material treatment should be a separate product when it changes the product's visual identity or story.
- This preserves the MONOLITH strategy that each ring feels like a distinct character rather than a large configurable matrix.



---

## 0G. Core Men's Ring Size Range — US 7–13

**Status: APPROVED**

MONOLITH standard men's wedding-band size range is **US 7–13 in half sizes**:

7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.5, 13.

This replaces the earlier broad 4–15 master range for normal catalog use. Ring Size remains the only standard WooCommerce variation attribute.



---

## 0H. Product Taxonomy Architecture v1

**Status: APPROVED WORKING SCHEMA**

MONOLITH uses **11 global WooCommerce product attributes** as the standardized product-fact layer:

1. Ring Size
2. Band Width
3. Base Material
4. Band Color
5. Coating / Plating
6. Inlay & Accent Material
7. Surface Finish
8. Profile
9. Fit
10. Edge Style
11. Interior Material

Only **Ring Size** generates standard WooCommerce variations. Width and all other attributes are descriptive/filterable facts. A materially different width/color/material expression should normally be a separate product with its own cinematic PDP.

The live WooCommerce vocabulary currently contains **105 controlled attribute terms** and all attribute archives are disabled.

Curated product categories use **Wedding Bands** as the parent with these children: Tungsten Bands, Titanium Bands, Damascus Steel Bands, Black Wedding Bands, Meteorite Rings, Wood Rings, Bone & Antler Rings, Gemstone & Opal Rings, Minimalist Bands, Hammered & Textured Bands, and Gold-Tone Bands.

Normal Woo product tags are not used for product facts. Production merchandising states such as New, Best Seller, Limited, Featured, Made to Order, and Ready to Ship should live as MONOLITH Core flags/custom fields instead of public tag archives.

The authoritative detailed term list and ingestion rules live in the Drive document **MONOLITH — WooCommerce Product Data Architecture — v1.0**.

