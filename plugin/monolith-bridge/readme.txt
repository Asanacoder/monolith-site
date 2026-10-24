=== MONOLITH Bridge ===
Contributors: monolith
Tags: mcp, ai, abilities-api, woocommerce, automation
Requires at least: 6.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.3.0
License: GPLv2 or later

Secure WordPress Abilities API bridge for MONOLITH site operations through MCP-compatible AI clients.

== Description ==

MONOLITH Bridge exposes a deliberately controlled set of WordPress and WooCommerce abilities for the private MONOLITH ChatGPT connector.

Version 0.3.0 supports read/write site administration on the MONOLITH WordPress installation. The dedicated MCP endpoint is authenticated with the existing private request-header credential; WordPress and WooCommerce permissions are enforced for each operation. ChatGPT remains the model layer; no OpenAI API key is needed inside WordPress.

Exposed abilities:

* monolith-bridge/site-status
* monolith-bridge/list-pages
* monolith-bridge/get-page
* monolith-bridge/list-plugins
* monolith-bridge/list-products
* monolith-bridge/get-product
* monolith-bridge/rest-request (GET/POST/PUT/PATCH/DELETE within /wp/v2 and /wc/v3)
* monolith-bridge/upload-media-url (HTTPS only)
* monolith-bridge/upload-media-base64 (images up to 16 MiB)
* monolith-bridge/audit-log

== Requirements ==

* WordPress 6.9+
* Official WordPress MCP Adapter plugin
* WooCommerce is optional; Woo abilities return a clear error when WooCommerce is inactive

== Installation ==

1. Install and activate the official MCP Adapter plugin.
2. Upload and activate MONOLITH Bridge.
3. Visit Tools > MONOLITH Bridge while logged in as the administrator who should represent the connector.
4. Confirm the MONOLITH ChatGPT endpoint is shown as ready.
5. Configure a separate, private ChatGPT MCP connection for MONOLITH. Do not replace or edit the Asana Crystals connector.
6. Verify the site-status home_url is the MONOLITH site before writing.
7. Test reading a product, uploading a test image, creating a draft product, updating it, and trashing it using the REST bridge.

== Security ==

* Version 0.3.0 allows writes permitted by WordPress REST /wp/v2 and WooCommerce /wc/v3, including supported destructive routes. This is broad administrator access: give the private MCP credential only to a trusted user and keep it separate from other WordPress sites.
* The dedicated ChatGPT MCP endpoint requires a private connector credential.
* The credential is never displayed in WordPress and the repository stores only its SHA-256 hash.
* The connector maps successful requests to the administrator who initialized the bridge so normal WordPress capability checks still run.
* Rotate the credential by releasing a matched WordPress Bridge + private ChatGPT connector update.

== Changelog ==

= 0.3.0 =
* Added administrator REST bridge, including WooCommerce product and variation management.
* Added HTTPS image and base64 image uploads for the Media Library.
* Added a bounded write audit log with method/path/status/operator and no payloads.
* Preserved private connector authentication and site-specific WordPress permission checks.
* Code deployments should still be managed in GitHub; arbitrary PHP/SQL execution is not provided.

= 0.2.0 =
* Added dedicated MONOLITH ChatGPT MCP server endpoint.
* Added private request-header authentication without OpenAI API usage.
* Added operator-user binding so WordPress capability checks remain active.
* Kept all exposed MONOLITH abilities read-only.

= 0.1.0 =
* Initial read-only bridge.
* Added WordPress/site health inspection.
* Added page inspection.
* Added plugin inventory inspection.
* Added WooCommerce product inspection.
