# MONOLITH — WordPress AI Bridge Plan

**Status:** Recommended architecture / awaiting implementation approval  
**Date:** October 2026

## Implementation Status

**v0.1.0 built on branch `feature/wordpress-ai-bridge`.**

Current implementation is intentionally read-only and exposes six abilities:

- `monolith-bridge/site-status`
- `monolith-bridge/list-pages`
- `monolith-bridge/get-page`
- `monolith-bridge/list-plugins`
- `monolith-bridge/list-products`
- `monolith-bridge/get-product`

The plugin requires WordPress 6.9+ and the official WordPress MCP Adapter. Write/destructive abilities are not included yet. Next step is staging installation and discovery verification before adding write access.

## Cost / Chat-Only Requirement

**Status: APPROVED**

The owner requires the WordPress bridge to work from normal ChatGPT conversations without using the OpenAI API or creating separate per-token model charges.

Architecture requirement:

- ChatGPT itself is the AI/model layer.
- WordPress must **not** call OpenAI or another model provider.
- Do not require `OPENAI_API_KEY`.
- Do not use the WordPress AI Client for MONOLITH Bridge execution.
- The WordPress MCP Adapter only exposes site abilities/tools.
- MONOLITH Bridge executes WordPress/WooCommerce operations locally on the site.
- Normal ChatGPT plan/tool usage limits may still apply; the bridge cannot guarantee unlimited ChatGPT usage.
- The bridge itself should add no OpenAI API-token billing.

Target flow:

`ChatGPT conversation → ChatGPT plugin/connector → WordPress MCP Adapter → MONOLITH Bridge → WordPress/WooCommerce`

---

## Connection Test Status

**Tested:** October 2026

The WordPress MCP connector is functioning, but the currently connected WordPress site is **Asana Crystals** at `crystal-shop.co`, not MONOLITH.

Read-only verification succeeded and exposed hundreds of WordPress/WooCommerce abilities.

**Safety rule:** Do not execute MONOLITH write operations through this connection. A separate MONOLITH WordPress/staging connection must be configured before using the bridge for MONOLITH.

---

## Goal

Create a secure bridge that lets an authorized AI agent work directly with the MONOLITH WordPress/WooCommerce site for day-to-day site operations while preserving GitHub as the source of truth for code.

Desired owner workflow:

> “Create this section.”  
> “Change the product page.”  
> “Upload this plugin.”  
> “Update this page.”  
> “Add a new product field.”  
> “Fix this styling issue.”  
> “Publish these changes.”

The agent should be able to inspect the site, perform the allowed action, verify the result, and report exactly what changed.

---

## Recommended Architecture

### 1. WordPress / WooCommerce

Remain the production CMS and commerce engine.

### 2. MONOLITH Bridge plugin

Create:

`plugin/monolith-bridge/`

Purpose:

- expose safe, typed WordPress/WooCommerce actions
- enforce permissions
- expose read-only diagnostics
- support backups / rollback metadata
- prevent arbitrary unaudited live-code mutation

Use WordPress 6.9+ **Abilities API** as the internal capability registry.

Use the WordPress **MCP Adapter** (or compatible MCP layer) to expose approved abilities to ChatGPT / AI agents.

### 3. GitHub

Remain source of truth for:

- MONOLITH theme code
- MONOLITH Core
- MONOLITH Bridge
- CSS / JS / PHP
- templates / patterns
- release tags
- documentation

Code changes should normally follow:

AI edits GitHub → deploy to staging → test → owner approval when needed → deploy to production.

Do not make routine raw PHP/CSS/JS file edits directly on production.

### 4. WordPress direct-edit lane

Use the Bridge for content/config/data changes that WordPress is meant to own:

- pages
- page content / blocks
- reusable patterns
- menus
- media
- Woo products
- product variations
- attributes
- product metadata
- prices / stock when explicitly requested
- categories / taxonomies
- redirects/config when an approved ability exists
- plugin install/update/activate/deactivate
- cache purge
- diagnostics

---

# Proposed Abilities

## Read / Inspect

- `monolith/site-status`
- `monolith/get-page`
- `monolith/list-pages`
- `monolith/get-template`
- `monolith/get-global-styles`
- `monolith/list-plugins`
- `monolith/plugin-status`
- `monolith/list-media`
- `monolith/get-product`
- `monolith/list-products`
- `monolith/get-product-variations`
- `monolith/get-orders-summary`
- `monolith/get-error-log-summary`
- `monolith/get-site-health`
- `monolith/get-current-release`

## Content / Pages

- `monolith/create-page`
- `monolith/update-page`
- `monolith/publish-page`
- `monolith/create-pattern`
- `monolith/update-pattern`
- `monolith/update-menu`
- `monolith/set-featured-image`
- `monolith/upload-media`

## WooCommerce Products

- `monolith/create-product`
- `monolith/update-product`
- `monolith/create-variation`
- `monolith/update-variation`
- `monolith/set-product-images`
- `monolith/set-product-meta`
- `monolith/update-stock`
- `monolith/update-price`

WooCommerce's REST API already supports read/write product, order, customer, coupon and shipping-zone data; the Bridge should wrap only the operations we actually want the AI to perform.

## Theme / Design Settings

Safe runtime changes:

- `monolith/set-design-token`
- `monolith/update-global-style`
- `monolith/assign-template`
- `monolith/update-page-section-order`
- `monolith/toggle-section`

Actual PHP/CSS/JS/template-code changes should normally go through GitHub and deployment rather than arbitrary live editing.

## Plugins

- `monolith/list-plugins`
- `monolith/install-plugin`
- `monolith/upload-plugin-zip`
- `monolith/update-plugin`
- `monolith/activate-plugin`
- `monolith/deactivate-plugin`

Safety requirements:

- disallow arbitrary plugin-file editing in production
- capture current plugin/version before update
- create backup / rollback point before risky update
- optionally require explicit owner approval before production activation/update

## Operations

- `monolith/clear-cache`
- `monolith/create-backup`
- `monolith/rollback-release`
- `monolith/run-smoke-tests`
- `monolith/deploy-staging-release`
- `monolith/deploy-production-release`

---

# Permission Model

Do not expose one giant “do anything” admin endpoint.

Each action should:

- have its own ability
- have typed input/output schemas
- use WordPress capability checks
- log who/what changed it
- return before/after state where practical
- reject dangerous paths

Recommended roles:

### Read-only
Can inspect site, products, plugins, logs and configuration.

### Editor
Can modify pages, products, media and approved configuration.

### Developer
Can trigger staging deployments and approved operational tasks.

### Production deploy
Separate high-risk permission; can require explicit owner confirmation.

---

# Authentication

Preferred options:

1. authenticated MCP connection through the WordPress MCP Adapter, or
2. HTTPS + revocable WordPress Application Password for API access where appropriate.

Never store the user's main WordPress password in prompts, code, or repository.

WordPress Application Passwords are per-application, revocable credentials intended for programmatic access.

---

# Staging vs Production

## Staging

The AI can have broad permissions:

- create pages
- change templates
- install/update plugins
- deploy theme/plugin changes
- test Woo flows
- clear caches
- run diagnostics

## Production

Use tighter controls:

Safe direct actions can include:

- copy updates
- product content
- images
- inventory/pricing when explicitly requested
- approved page content
- low-risk config

High-risk actions should require an explicit production step:

- PHP/theme/plugin code deployment
- plugin updates
- plugin activation/deactivation
- checkout/payment changes
- database migrations
- destructive actions

---

# Example User Workflow

Owner says:

> “On the product page, add a Fit Assurance card underneath size selection. Use our approved checkout styling.”

Agent workflow:

1. Read Project decisions/brand files.
2. Inspect current product template and production/staging state.
3. Decide whether it is content/config or code.
4. If code:
   - update GitHub feature branch
   - deploy to staging
   - inspect/test desktop + mobile
   - fix problems
   - deploy production after required approval
5. If content/config:
   - execute the relevant WordPress ability directly
6. clear cache if necessary
7. verify page
8. record commit/change log

---

# What This Bridge Can Eventually Let ChatGPT Do

From normal conversation:

- create a page
- redesign a section
- add/remove/reorder sections
- update page copy
- update menus
- upload media
- create products
- edit product content
- create size/width variations
- add custom product fields
- change product-page presentation
- update theme styling
- install/update approved plugins
- activate/deactivate plugins
- check plugin conflicts
- clear cache
- inspect errors
- deploy releases
- roll back a release
- inspect Woo orders/products when needed
- execute repetitive catalog/site maintenance

The distinction is:

**WordPress Bridge = controlled live site/data operations**

**GitHub + deployment = code changes**

This preserves safety, rollback, auditability and maintainability.

---

# Current Connector State

The current ChatGPT environment contains a WordPress MCP connector with the operations:

- discover abilities
- inspect an ability schema
- execute an ability

At the time this plan was written, discovery returned **zero exposed abilities**.

Therefore the connector exists, but the MONOLITH WordPress site has not yet been configured to expose its actionable abilities.

---

# Implementation Sequence

1. Provision MONOLITH WordPress staging.
2. Confirm WordPress 6.9+.
3. Install/configure WordPress MCP Adapter.
4. Create `monolith-bridge` plugin.
5. Register read-only abilities first.
6. Connect ChatGPT and confirm ability discovery.
7. Add page/media/product write abilities.
8. Add WooCommerce abilities.
9. Add plugin-management abilities with safeguards.
10. Connect GitHub deployment pipeline.
11. Add backups, smoke tests and rollback.
12. Test on staging.
13. Enable a constrained production profile.
14. Document every exposed ability.

---

# Architectural Rule

The Bridge must not bypass the existing MONOLITH architecture.

- WooCommerce still owns commerce.
- WordPress still owns CMS content.
- MONOLITH Core owns custom business logic.
- MONOLITH Theme owns presentation.
- GitHub owns code history.
- MONOLITH Bridge exposes safe agent operations.

The Bridge is an interface to the system, not a replacement for it.


---

## ChatGPT Private Connector

**Status:** CREATED

A private ChatGPT plugin named **MONOLITH WordPress** was created for the MONOLITH site.

- Plugin ID: `plugins_6ac8b9dfcb3c8191a7a92b87c0c72f6b`
- Scope: private personal plugin
- MCP endpoint: `https://monolithbands.com/wp-json/mcp/mcp-adapter-default-server`
- The plugin includes a mandatory site-identity safety rule: before any write action, verify the connected site is `monolithbands.com`.
- The plugin does not use an OpenAI API key; ChatGPT remains the model layer.

Next step: user opens the private plugin page, connects/authorizes the WordPress MCP endpoint, then runs a read-only site identity test before enabling any write workflow.


---

## Connector Authentication v0.2

**Status:** BUILT / AWAITING WORDPRESS UPLOAD

MONOLITH Bridge v0.2 uses a dedicated MCP endpoint at `/wp-json/monolith-mcp/chatgpt` instead of exposing the default WordPress MCP endpoint directly to ChatGPT.

Security model:

- ChatGPT sends a high-entropy private connector credential in the `X-Monolith-Key` request header.
- WordPress stores only the SHA-256 hash of that credential in the bridge code; the plaintext credential is not committed to GitHub.
- A successful connector request is mapped to the administrator who initialized the bridge, so normal WordPress capability checks continue to apply.
- Version 0.2 remains read-only: site status, pages, plugins, and WooCommerce products only.
- No OpenAI API key and no model API billing are used.
- The private ChatGPT plugin `MONOLITH WordPress` was updated to v0.2.0 to use the dedicated endpoint and credential header.

Next step: replace the installed MONOLITH Bridge v0.1.0 on `monolithbands.com` with v0.2.0, then visit Tools > MONOLITH Bridge once while logged in as the intended administrator. After that, run a read-only ChatGPT connection test.
