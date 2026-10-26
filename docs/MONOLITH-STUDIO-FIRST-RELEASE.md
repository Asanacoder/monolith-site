# MONOLITH Studio — First Live Release

Date: 2026-10-26
Status: LIVE / PILOT
Theme version: 0.7.0
Pilot WooCommerce product: The Koa, product ID 82

## Admin URLs

- Studio dashboard: https://monolithbands.com/wp-admin/admin.php?page=monolith-studio
- Koa product editor: https://monolithbands.com/wp-admin/post.php?post=82&action=edit

## Shipped

1. Theme-integrated MONOLITH Studio admin page with a dark branded header.
2. Drag-and-drop and up/down sorting of seven existing editorial product sections.
3. Per-section Show checkboxes to hide sections globally.
4. Global editorial text and image controls, grouped in native accordion panels.
5. Per-product MONOLITH Studio metabox allowing copy/image overrides.
6. WordPress Media Library picker, attachment IDs, and clear-to-inherit behavior.
7. Inheritance: product override -> global text -> existing template fallback.
8. Dynamic front-end output wired into Koa for the initial template.
9. Existing WooCommerce commerce, product facts, taxonomy specimens and character image layers retained.
10. Draft-theme publishing and rollback backup handled by WPVibe.

## Sections in v0.1

- Material Study + Character
- The Object
- The MONOLITH Standard
- In the World
- Details & Fit
- The Fit
- Final CTA

The gallery, size variants, cart and checkout are intentionally not draggable.

## Editable copy

Purchase headline/introduction, material headings, specimen section introductions,
Character heading/introduction, and editorial eyebrow/headline/paragraph/button labels.
The hero, brand, lifestyle, hand-scale and CTA images have media pickers.
Taxonomy term names/images/descriptions remain in native WordPress taxonomy records.

## Architecture and limits

The MVP is **theme-integrated** in `inc/monolith-studio.php`. It is not yet a standalone plugin.
The next architectural pass should extract the Studio backend and option/post-meta
logic into **MONOLITH Core**, keeping rendering in the theme and preserving
the existing option keys `monolith_studio_global_copy`,
`monolith_studio_section_order`, `monolith_studio_disabled_sections`
and product meta key `_monolith_studio_copy`.

Only Koa's custom product template is connected so far; broader template assignment is not yet present.
The current system reorders and hides *registered* sections but does not yet add
arbitrary new block types or user-authored FAQ repeaters.
Default copy remains in PHP as a fallback when nothing is entered.

## QA

- Draft Koa HTML smoke-tested with WPVibe preview and no fatal errors.
- Published Koa HTML smoke-tested with unique query string and all seven
  text section markers present, including Material Study and Character.
- Four product images remain 83, 85, 163, 138.
- WooCommerce Coming Soon restored to `yes`; `blog_public=0`.
- Ordinary clean product URL may still return older cached HTML from
  an upstream hosting/CDN cache, separately tracked from the Studio implementation.

## Next stages

1. Human admin usability QA and field save/readback.
2. Extract MONOLITH Studio into the MONOLITH Core plugin with exact same storage.
3. Add reusable FAQ/accordion module and controlled section-add interface.
4. Add per-product layout override and multiple product template assignment.
5. Add preview-before-publish, revisions and role-specific editor permissions.

Files tracked here:
- `wordpress-theme/inc/monolith-studio.php`
- `wordpress-theme/inc/koa-pdp-prototype.php`
- `wordpress-theme/functions.php`
- `wordpress-theme/style.css`
