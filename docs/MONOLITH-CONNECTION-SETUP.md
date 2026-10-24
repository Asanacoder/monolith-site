# MONOLITH — Dedicated Read/Write WordPress Connection

**Code status:** 0.3.0 on `feature/monolith-bridge-read-write`, not deployed automatically.
**Scope:** MONOLITH at `https://monolithbands.com` only. Do **not** replace or edit the existing `crystal-shop.co` connection.

## What is already prepared

- MONOLITH Bridge plugin: `plugin/monolith-bridge/` on this branch.
- Dedicated MCP endpoint: `https://monolithbands.com/wp-json/monolith-mcp/chatgpt`.
- Existing private ChatGPT plugin: `monolith-wordpress` (separate from other WordPress connections). Its 0.2.0 metadata may need a refresh to match the 0.3.0 server.
- Read tools: site status, pages, plugins, products, audit log.
- Write tools: WP/WooCommerce REST operations on `/wp/v2/` and `/wc/v3/`, media import from HTTPS URLs or base64 image bytes.
- Write operations are authenticated, checked against WordPress administrator permissions, and logged without request body contents.

## Install on MONOLITH only

1. Sign in to `https://monolithbands.com/wp-admin`. Check **Dashboard → Updates**. Required: WordPress 6.9+; PHP 7.4+.
2. In MONOLITH WordPress admin, open **Plugins → Add New Plugin**. Install and activate the official **MCP Adapter** plugin. Official source: https://wordpress.org/plugins/mcp-adapter/ .
3. Open the GitHub Actions run for **MONOLITH Bridge PHP lint** on this feature branch, and download the artifact **MONOLITH-Bridge-v0.3.0**. The artifact is a ZIP containing another ZIP named `monolith-bridge-v0.3.0.zip`. Extract the outer archive and keep the inner plugin ZIP unchanged.
4. In MONOLITH WordPress, open **Plugins → Add New Plugin → Upload Plugin**. Choose `monolith-bridge-v0.3.0.zip`. If v0.2.0 is installed, choose the replace/update option. Then activate it.
5. Open **Tools → MONOLITH Bridge**. Verify the WordPress Abilities API, MCP Adapter, and dedicated endpoint are **Ready**, and the operator is a MONOLITH administrator.
6. In ChatGPT, enable or refresh the existing **MONOLITH WordPress** private plugin, **without changing the Asana Crystals connection**.
7. First execute `monolith-bridge/site-status`. **Stop immediately** if `home_url` or `site_url` is anything other than `https://monolithbands.com/`.
8. Read existing products. Then test creating one temporary draft product and uploading one image; verify on the MONOLITH site. Only after that, import the demo catalog from Google Drive.

## Supported operations

- `monolith-bridge/rest-request`: method GET, POST, PUT, PATCH, DELETE; path under `/wp/v2/` or `/wc/v3/`, parameters as JSON.
- `monolith-bridge/upload-media-url`: sideload one public HTTPS image, returns an attachment ID.
- `monolith-bridge/upload-media-base64`: upload image contents (maximum 16 MiB), returns an attachment ID.
- `monolith-bridge/audit-log`: inspect recent write operations.

**Examples** (via the REST ability):

- Create a draft WooCommerce product: `POST /wc/v3/products` with name/status/description fields.
- Update a product: `PUT /wc/v3/products/{id}`.
- Set gallery/featured images: `PUT /wc/v3/products/{id}` with `images` array referencing returned attachment IDs.
- Create/update a WordPress page: `POST /wp/v2/pages` or `POST /wp/v2/pages/{id}`.

Do not enter connector credentials in chat, site content, or GitHub. The private ChatGPT plugin already contains a separate endpoint configuration. If its stored credential stops working, rotate it through a private coordinated connection update.

## Limits & rollout controls

- This is broad administrator-level access to supported WordPress and WooCommerce REST routes. It is **not** arbitrary PHP execution, SQL, operating-system access, or an automatic code deployment feature.
- Theme/plugin source changes should still use GitHub, staging, testing, and an approved deploy.
- Before publishing products, verify actual material specs, prices, inventory, shipping, and claims. Demo photos may illustrate concepts, but should not present invented material details as fact.
- The PHP lint check confirms syntax, **not** live MCP/WordPress compatibility, authorization, or WooCommerce functionality. Perform the staged live smoke test above.
- Production access is powerful. Protect WordPress admin accounts, use backups and two-factor authentication, and review the audit log after tests.
