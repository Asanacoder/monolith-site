# MONOLITH — Website Build Blueprint & Agent Onboarding

**Purpose:** Canonical technical and production blueprint for the MONOLITH website project. Any new agent, developer, designer, or automation should read this before changing code, structure, product pages, assets, or WooCommerce behavior.

**Status:** October 2026  
**Repository:** `Asanacoder/monolith-site`  
**Current visual preview:** `https://asanacoder.github.io/monolith-site/`

---

## 1. What We Are Building

MONOLITH is a premium direct-to-consumer men's wedding-band ecommerce brand. The website should not feel like a normal jewelry catalog or a generic WooCommerce store. It should feel like a curated architectural environment: dark, tactile, cinematic, masculine, restrained, and emotionally serious.

The finished website will use:

- **WordPress** as the CMS/admin platform.
- **WooCommerce** as the commerce engine.
- A **custom MONOLITH theme** as the visual/frontend layer.
- A separate **MONOLITH Core plugin** for brand-specific metadata and business logic.
- Native WordPress/WooCommerce functionality wherever practical.
- Lightweight custom PHP/HTML/CSS/JavaScript rather than a page-builder dependency.
- Full desktop/tablet/mobile responsiveness.
- Long-form, information-rich product pages suitable for premium products in the $500+ range.

Core rule:

> **WooCommerce owns commerce. MONOLITH owns the experience.**

Do not recreate cart, checkout, tax, inventory, orders, payments, product variations, reviews, or authentication from scratch.

---

## 2. Brand Sources of Truth

Before making design, content, product-UX, or merchandising decisions, consult the Project source files.

Primary brand source:

- `ABOUT-MONOLITH.txt`

Primary product/ecommerce research sources:

- `Tungsten rings Keypoints.docx`
- `Tungsten rings General.docx`
- `Tungsten rings.docx`
- Product spreadsheets in the Project when catalog/product decisions are required.

The brand source establishes the visual and emotional world: brutalist architecture, graphite tones, matte textures, cinematic realism, restrained luxury, negative space, visual silence, object-focused presentation, and the “cold outside, warm underneath” structure.

The product research emphasizes material honesty, comfort-fit education, sizing clarity, plating disclosure, engraving rules, warranty, packaging, construction transparency, and reducing purchase risk.

**Important:** Research files contain examples from other sellers and market research. Never assume a material claim, policy, warranty, manufacturing method, shipping promise, or specification applies to a MONOLITH SKU unless verified for that SKU.

---

## 3. Current Project State

### Already created

- GitHub repo: `https://github.com/Asanacoder/monolith-site`
- Permanent GitHub Pages visual preview: `https://asanacoder.github.io/monolith-site/`
- Current repo root contains `index.html` and `README.md`
- Homepage visual prototype exists
- Lightweight homepage motion experiment exists
- Responsive rules exist in the prototype
- Product-page visual concepts have been explored using a sample antler/copper/dark-band ring

### Not created yet

- Functional WordPress/WooCommerce development installation
- Production-ready MONOLITH theme skeleton
- MONOLITH Core plugin
- Public/private WordPress staging site
- Real WooCommerce catalog wiring
- Final custom product backend fields
- Final cart/checkout/account/shop/search/filter implementation
- Production deployment

New agents must not describe unfinished items as already implemented.

---

## 4. Development Environments

MONOLITH uses four environments.

### A. Static Visual Preview — GitHub Pages

Current URL:

`https://asanacoder.github.io/monolith-site/`

Purpose:

- art direction
- homepage/product-page layout prototypes
- typography
- image placement
- animation tests
- responsive concept testing
- approval before WooCommerce implementation

This is **not** the final ecommerce site and contains no live WooCommerce backend.

### B. Local WordPress/WooCommerce Development

Recommended tool: **LocalWP** for the simplest workflow. Docker is acceptable when a developer specifically needs it.

Purpose:

- install WordPress + WooCommerce
- develop the MONOLITH theme
- develop MONOLITH Core
- create sample products
- test variations and custom fields
- test cart/checkout/plugins safely

### C. WordPress Staging

A private browser-accessible staging site will be created on the intended host before launch.

**Status:** not provisioned yet; URL TBD.

Requirements:

- HTTPS
- private/passworded if practical
- search indexing disabled
- same intended PHP/WordPress/WooCommerce stack as production
- payment test mode
- realistic sample catalog/media

### D. Production

Final public MONOLITH domain.

Only approved and tested work moves from staging to production.

---

## 5. Technology Stack

### Core
- WordPress
- WooCommerce

### Languages
- **PHP** — WordPress/Woo hooks, metadata, business logic.
- **HTML5 / WordPress block markup** — structure/templates.
- **CSS** — visual system; CSS variables, Grid, Flexbox, `clamp()`, responsive rules.
- **Vanilla JavaScript (ES6+)** — lightweight interaction, motion, product viewer.
- **JSON** — mainly `theme.json`.

### Database
MySQL/MariaDB through WordPress APIs. Avoid direct SQL unless genuinely necessary.

### Do not introduce by default
- Elementor
- Divi
- WPBakery
- Bootstrap
- Tailwind
- React/Next/Vue as a separate storefront
- large animation frameworks
- custom payment/checkout engines

Dependencies may be added later only when their benefit clearly outweighs long-term maintenance cost.

---

## 6. Website Architecture

```text
WordPress
    │
    ├── WooCommerce
    │     ├── products
    │     ├── variations
    │     ├── inventory
    │     ├── pricing
    │     ├── cart
    │     ├── checkout
    │     ├── orders
    │     └── reviews
    │
    ├── MONOLITH Theme
    │     └── presentation / visual system
    │
    └── MONOLITH Core
          └── brand-specific data and functionality
```

### MONOLITH Theme owns
- visual design system
- typography
- layout
- header/footer
- homepage
- shop/archive presentation
- product-page presentation
- cart/checkout/account styling
- responsive behavior
- visual motion
- reusable patterns/components

### MONOLITH Core owns
- custom product metadata
- product-story fields
- technical/spec fields not covered by normal Woo attributes
- editorial content fields
- engraving business logic
- 360/product-motion metadata
- custom product relationships if needed
- functionality that should survive a future theme redesign

**Business logic must not be buried inside the theme.**

---

## 7. Target Repository Structure

The current repo is still prototype-simple. The target structure is:

```text
monolith-site/
│
├── README.md
│
├── docs/
│   ├── MONOLITH-BUILD-BLUEPRINT.md
│   ├── PRODUCT-PAGE-SCHEMA.md
│   ├── DESIGN-TOKENS.md
│   └── RELEASE-CHECKLIST.md
│
├── preview/
│   ├── index.html
│   ├── product-sample.html
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
│
├── theme/
│   └── monolith/
│       ├── style.css
│       ├── theme.json
│       ├── functions.php
│       ├── screenshot.png
│       ├── templates/
│       │   ├── front-page.html
│       │   ├── page.html
│       │   ├── archive.html
│       │   ├── archive-product.html
│       │   ├── single-product.html
│       │   ├── taxonomy-product_cat.html
│       │   ├── page-cart.html
│       │   ├── page-checkout.html
│       │   └── order-confirmation.html
│       ├── parts/
│       │   ├── header.html
│       │   ├── footer.html
│       │   └── checkout-header.html
│       ├── patterns/
│       │   ├── monolith-hero.php
│       │   ├── trust-strip.php
│       │   ├── material-story.php
│       │   ├── ceremony.php
│       │   └── ownership.php
│       └── assets/
│           ├── css/
│           │   ├── tokens.css
│           │   ├── base.css
│           │   ├── components.css
│           │   ├── woo.css
│           │   ├── home.css
│           │   └── product.css
│           ├── js/
│           │   ├── home-motion.js
│           │   ├── product-ui.js
│           │   └── product-viewer.js
│           └── images/
│               └── theme-only assets
│
└── plugin/
    └── monolith-core/
        ├── monolith-core.php
        ├── includes/
        │   ├── product-meta.php
        │   ├── product-sections.php
        │   ├── engraving.php
        │   ├── product-viewer.php
        │   └── helpers.php
        └── assets/
            ├── admin/
            └── frontend/
```

This is the **target**, not the current repository state.

During refactoring, preserve the existing GitHub Pages URL. Do not break the permanent preview merely to reorganize files.

---

## 8. Asset Storage Strategy

### Google Drive — original/source assets

Current `WEBSITE` workspace structure:

- `01 Brand & Design System`
- `02 Website Assets`
- `03 Product Assets`
- `04 Copy & Content`
- `05 Development`
- `06 SEO`
- `07 Testing`
- `08 Backups & Releases`
- `99 Archive`

Store here:

- original photography
- high-resolution generated imagery
- raw product photography
- source videos
- source 360 sequences
- logos/source artwork
- content documents
- approved creative files
- backups/releases

### GitHub — code + lightweight development assets

Store:

- code
- documentation
- SVG UI assets
- optimized preview assets
- theme-owned visuals
- development fixtures

Do **not** use GitHub as the archive for huge originals.

The current prototype embeds large Base64 images in `index.html` only as a temporary preview convenience. Remove that pattern during production migration.

### WordPress Media Library — production content media

Production media should normally live in WordPress:

- featured/gallery images
- product-story/editorial images
- material macro images
- fit/detail imagery
- packaging imagery
- product videos / 360 poster
- optional 360 sequence media

Custom product fields should store WordPress attachment IDs rather than hardcoded external image URLs.

---

## 9. WooCommerce Integration Rules

The site should look completely custom while remaining native to WooCommerce underneath.

Prefer, in order:

1. WooCommerce / WordPress native data.
2. `theme.json` / global styles.
3. WooCommerce blocks.
4. WooCommerce / WordPress hooks and filters.
5. Reusable MONOLITH components/patterns.
6. Template overrides only when necessary.

Do not custom-build:

- payment processing
- taxes
- inventory
- checkout validation
- orders
- authentication
- commodity shipping/payment logic already handled by reliable extensions

### Native WooCommerce data powers
- title
- price / sale price
- SKU
- stock
- featured image
- gallery
- reviews
- variations
- variation pricing
- cart
- checkout
- orders

### Woo attributes/taxonomies should be used when data is needed for
- filtering
- variations
- catalog comparison
- standardized product facts

Examples:
- size
- width
- material
- finish
- color

### MONOLITH custom metadata is used for editorial modules
- product thesis/story
- design narrative
- material origin/story
- manufacturing explanation
- material macro slots
- fit story
- wear/care
- packaging story
- 360 configuration

---

## 10. The MONOLITH Product Page Engine

The PDP is the highest-priority selling surface.

We are **not** creating 20 individually coded product pages.

We are creating:

> **One master product-page engine with optional modules populated from the WooCommerce backend.**

### Recommended sequence

1. **Purchase Stage**
   - Woo gallery
   - title
   - rating
   - price
   - size / width
   - engraving
   - Add to Cart
   - shipping/warranty/sizing reassurance

2. **Fast Proof Strip**
   - material
   - width
   - fit
   - warranty
   - key construction fact

3. **Product Thesis / Story**
   - product-specific headline
   - concise story
   - editorial image

4. **Design**
   - geometry
   - profile
   - finish
   - detail imagery

5. **Materials**
   - verified material explanation
   - origin when verified
   - macro imagery
   - natural-variation disclosure

6. **360 / Product Inspection — optional**
   - interactive frame sequence
   - or muted loop
   - true 3D only later when justified

7. **On the Hand / Scale**
   - realistic lifestyle image
   - width/scale context

8. **Fit & Sizing**
   - comfort fit
   - width effect
   - sizing guide
   - ring-sizer workflow if offered
   - resize limitation when applicable

9. **Construction / Craft**
   - machining / inlay / finishing
   - verified process only

10. **Wear & Care**
    - realistic durability
    - plating/inlay caveats
    - impact warnings
    - care

11. **Personalization**
    - engraving
    - character limits
    - placement
    - exchange impact

12. **Presentation / What's Included**
    - packaging
    - included items
    - ownership ritual

13. **Warranty / Shipping / Exchanges**
    - clean factual policy blocks

14. **Reviews**
    - native Woo reviews styled by MONOLITH

15. **Related Bands**
    - native Woo recommendations

16. **Final Purchase CTA**

### Optional rendering rule

If a module has no data, do not render an empty section.

Concept:

```php
if ( $material_story ) {
    monolith_render_material_story();
}
```

Simple products can be shorter. Flagship products can use the full architecture.

---

## 11. Product Backend Field Model

### Native WooCommerce
- title
- slug
- price
- sale price
- SKU
- stock
- categories
- attributes
- variations
- featured image
- gallery
- reviews

### MONOLITH custom groups

#### Product Story
- eyebrow
- headline
- body
- editorial image

#### Design
- headline
- body
- detail image 1
- detail image 2
- optional design callouts

#### Materials
Several fixed material slots:
- material name
- material image
- description
- verified origin/source
- variation/disclosure text

#### Dimensions & Fit
- width
- thickness
- approximate weight
- profile
- fit type
- sizing note
- interior/fit image

#### Construction
- headline
- body
- process image(s)
- process steps

#### Wear & Care
- scratch behavior
- water/moisture note
- impact note
- plating/finish note
- care instructions

#### Engraving
- enabled
- character limit
- fonts
- placement
- image
- exchange warning

#### Presentation
- packaging image
- included items
- packaging copy

#### 360 / Motion
- enabled
- type: sequence / loop / 3D
- media
- poster image
- optional copy

Implementation target: WordPress metadata APIs (`register_post_meta()`) plus a lightweight admin UI in MONOLITH Core. Do not make the project dependent on a paid field plugin unless a later decision explicitly chooses it.

---

## 12. Product Image System

### Standard Woo gallery

Typical product:

1. featured hero
2. angled view
3. profile/side view
4. interior/comfort-fit view
5. lifestyle/on-hand view
6. optional packaging/detail view

### Scroll-down content imagery

Separate custom fields supply:

- story image
- material macro images
- design/craft image
- fit/interior image
- technical/spec image
- packaging image
- 360 media

Do not duplicate every gallery image lower on the page unless reuse is intentional.

### Production realism

AI imagery is valid for prototyping and art direction.

Before production, verify actual geometry, material arrangement, finish, inlays, proportions, edges, and construction. Never publish an AI-generated product angle that materially invents the SKU.

---

## 13. 360 / Product-Motion Strategy

Preferred order:

1. **Interactive image sequence**
   - roughly 18–24 optimized frames
   - WebP/AVIF
   - drag desktop / swipe mobile
   - lazy loading

2. **Muted autoplay loop**
   - WebM/MP4
   - `autoplay muted loop playsinline`
   - no visible player controls

3. **True 3D**
   - later option with optimized GLB/USDZ
   - only when a reliable model exists

Avoid GIF for premium product rotation.

---

## 14. Homepage Architecture

Homepage purpose: establish brand world, then lead into commerce.

Current direction:

- cinematic human-led hero
- “Promises have weight.”
- brand thesis
- selected bands
- material intelligence
- The Ceremony
- ownership / packaging
- trust strip
- final CTA

Deep technical selling belongs primarily on product pages.

---

## 15. Motion Rules

Motion should feel like cinematography, not interface spectacle.

Allowed:

- subtle hero reveal
- gentle text staggering
- viewport section reveals
- tiny desktop image parallax
- restrained hover motion
- slow photographic movement

Avoid:

- scroll-jacking
- dramatic effects on every element
- spinning text
- heavy animation libraries without need

Motion must stay isolated and removable. It must honor `prefers-reduced-motion`. Mobile receives less motion than desktop.

---

## 16. Responsive Design Rules

Review at approximately:

- 1440px desktop
- 1024px laptop/tablet landscape
- 768px tablet
- 390px mobile

Do not create desktop first and “shrink later.”

Use Grid, Flexbox, fluid sizing, `clamp()`, responsive images, and component-specific breakpoints.

### Product-page mobile priority

```text
Gallery
Title
Rating / Price
Size / Width
Engraving
Add to Cart
Immediate Trust
Story / Materials / Specs below
```

Avoid heavy parallax on mobile. Keep touch targets comfortable.

---

## 17. Design System

Direction:

- black / obsidian
- graphite
- charcoal
- steel / silver
- mineral / warm white
- restrained warm-metal accent

Typography:

- refined serif for emotional/editorial headings
- restrained modern sans for UI/specs

Final typefaces remain subject to testing across PDP, cart, checkout, and mobile.

Centralize design values in:

- `theme.json`
- CSS custom properties / `tokens.css`

Do not scatter arbitrary hard-coded values across unrelated files.

---

## 18. Plugins / Third-Party Tools

Prefer trusted plugins for commodity infrastructure such as:

- payments
- SEO
- caching
- security
- backups
- forms
- transactional email
- reviews enhancements
- analytics
- privacy/consent
- popups if needed

Every plugin adds maintenance/performance cost. Do not install one when WordPress/Woo already handles the requirement well.

---

## 19. Code Quality & Sanitation

Standing requirement: **do not accumulate dead code.**

When a section/component is removed:

- remove its markup/PHP
- remove CSS
- remove JavaScript
- remove unused assets
- remove obsolete fields when safe
- update docs

Before a phase is considered complete:

- inspect unused files
- inspect stale selectors
- inspect console errors
- inspect redundant dependencies
- confirm mobile behavior
- verify no abandoned experiment remains in production paths

Experimental code should remain isolated until approved.

---

## 20. Git / Version Control

Repo:

`Asanacoder/monolith-site`

Current `main` branch powers the GitHub Pages preview.

As complexity grows, use feature branches:

```text
feature/product-page-engine
feature/monolith-core
feature/shop-template
feature/cart-checkout-style
fix/mobile-product-gallery
```

Use focused commit messages such as:

- `Add product material-story component`
- `Create product meta fields for sizing and craft`
- `Fix mobile gallery spacing`
- `Remove obsolete homepage motion code`

---

## 21. Content/Data Ownership Map

| Content/Data | Owner |
|---|---|
| Product title, price, SKU, stock | WooCommerce |
| Size/width variations | WooCommerce |
| Filterable product attributes | WooCommerce |
| Featured/gallery images | WooCommerce / Media Library |
| Reviews | WooCommerce |
| Editorial product story | MONOLITH Core metadata |
| Material story modules | MONOLITH Core metadata |
| Extra specs | MONOLITH Core metadata |
| 360 media/config | MONOLITH Core metadata |
| Engraving business logic | MONOLITH Core |
| Visual styling | MONOLITH Theme |
| Header/footer/layout | MONOLITH Theme |
| Cart/checkout/order logic | WooCommerce |
| Payments | Trusted payment extension |
| Original creative assets | Google Drive |
| Web-delivered content media | WordPress Media Library |
| Code/documentation | GitHub |

---

## 22. Production Roadmap

### Phase 0 — Canonical Documentation
Current phase.

- architecture blueprint
- source-of-truth rules
- README maintenance
- product schema doc after PDP architecture is approved

### Phase 1 — Repository Refactor

- preserve live Pages URL
- create `/docs`
- create `/preview`
- create `/theme/monolith`
- create `/plugin/monolith-core`
- move prototype code cleanly
- remove Base64 production assumptions

### Phase 2 — Design System Foundation

- tokens
- typography
- buttons/forms
- grid/container rules
- header/footer
- responsive conventions
- icon language

### Phase 3 — Master Product Page Prototype

**Current design priority.**

Use the sample ring to validate:

- gallery
- purchase panel
- long-form story
- materials
- specs
- sizing
- lifestyle
- packaging
- trust
- optional 360
- final CTA
- desktop + mobile

Do not build 20 products yet.

### Phase 4 — MONOLITH Core Product Fields

After PDP visual architecture approval:

- register product metadata
- admin panels
- Media Library selectors
- validation/sanitization
- optional rendering rules
- field documentation

### Phase 5 — Real WooCommerce Product Template

- wire PDP to Woo data
- replace prototype text/prices
- native variations
- reviews
- Add to Cart
- optional modules
- variable-product testing

### Phase 6 — Pilot Catalog

Build 3–5 representative SKUs:

- simple/minimal
- material-rich
- flagship/high-story

Use these to test master-template flexibility before full catalog entry.

### Phase 7 — Shop / Collections

- archive
- category pages
- cards
- filters
- sorting
- search
- material browsing

### Phase 8 — Cart / Checkout / Account

Keep behavior native to WooCommerce.

Test:

- variations
- coupons
- engraving line-item data
- shipping
- taxes
- payment test mode
- confirmation
- transactional email
- account

### Phase 9 — Supporting Content

- About / Our Story
- Materials
- The Ceremony
- Sizing
- Warranty
- Shipping / Returns
- FAQ
- Contact
- Journal/blog

### Phase 10 — WordPress Staging Deployment

- provision staging
- install WP/Woo
- install MONOLITH Theme
- install MONOLITH Core
- sample products
- optimized media
- necessary plugins
- noindex
- test payments

### Phase 11 — QA / Performance / SEO

Test:

- desktop/tablet/mobile
- Safari/Chrome
- keyboard navigation
- reduced motion
- image loading
- Core Web Vitals
- product schema
- metadata
- links
- variations
- cart/checkout
- emails
- analytics
- caching
- security
- backups

### Phase 12 — Production Launch

- prelaunch backup
- deploy approved theme/plugin release
- migrate verified content/media
- live payments
- verify tax/shipping
- remove production noindex
- submit sitemap
- live checkout test
- monitor errors/analytics

---

## 23. Definition of Done

A component is not done merely because it looks good on desktop.

It is done when:

1. It follows MONOLITH brand direction.
2. It uses the correct data source.
3. It works on desktop and mobile.
4. It does not duplicate WooCommerce logic.
5. It adds no unnecessary dependency.
6. It has no abandoned CSS/JS.
7. It respects accessibility/reduced motion where relevant.
8. It loads efficiently.
9. Another agent/developer can maintain it.
10. Editable content lives in the correct backend location.
11. It does not rely on unverified product claims.
12. It survives real Woo states such as sale, out of stock, variations, missing optional content, and long titles.

---

## 24. Instructions for New Agents

Before changing anything:

1. Read this blueprint.
2. Read `docs/PROJECT-DECISIONS.md` for the latest approved, provisional, recommended, and unresolved business rules.
3. Read `ABOUT-MONOLITH.txt`.
4. Read the relevant product research files.
5. Inspect the current GitHub repo instead of assuming structure.
6. Inspect the current live preview when visual behavior matters.
7. Decide whether the task belongs to preview, theme, MONOLITH Core, Woo config, product content, or media.
8. Do not introduce architecture-changing dependencies without justification.
9. Treat desktop and mobile together.
10. Clean obsolete code during meaningful refactors.
11. Preserve the permanent preview URL unless a migration explicitly requires change.

---

## 25. Immediate Next Work

The immediate priority is **MONOLITH Product Page v1** using the current sample ring.

Sequence:

1. Finalize PDP section map.
2. Prepare sample gallery + lower-page imagery.
3. Build static PDP prototype.
4. Build mobile/responsive PDP.
5. Test optional 360 presentation.
6. Review/remove unnecessary modules.
7. Freeze PDP v1 architecture.
8. Write `PRODUCT-PAGE-SCHEMA.md`.
9. Build MONOLITH Core product fields.
10. Build WooCommerce-integrated PDP.
11. Test with 3–5 pilot products.
12. Scale to full catalog.

---

## 26. One-Sentence Technical Summary

**MONOLITH is being designed visually in a lightweight GitHub Pages preview, then converted component-by-component into a custom responsive WordPress/WooCommerce theme, while a separate MONOLITH Core plugin supplies reusable product-story metadata and business logic so one sophisticated master product template can power the premium ring catalog without individually coding each product page.**
