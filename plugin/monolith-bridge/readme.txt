=== MONOLITH Bridge ===
Contributors: monolith
Tags: mcp, ai, abilities-api, woocommerce, automation
Requires at least: 6.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.2.0
License: GPLv2 or later

Secure WordPress Abilities API bridge for MONOLITH site operations through MCP-compatible AI clients.

== Description ==

MONOLITH Bridge exposes a deliberately controlled set of WordPress and WooCommerce abilities for the private MONOLITH ChatGPT connector.

Version 0.2.0 remains read-only. It adds a dedicated MCP server endpoint protected by a high-entropy connector credential passed in a private request header. ChatGPT stays the model layer; WordPress does not call OpenAI or use an OpenAI API key.

Exposed abilities:

* monolith-bridge/site-status
* monolith-bridge/list-pages
* monolith-bridge/get-page
* monolith-bridge/list-plugins
* monolith-bridge/list-products
* monolith-bridge/get-product

== Requirements ==

* WordPress 6.9+
* Official WordPress MCP Adapter plugin
* WooCommerce is optional; Woo abilities return a clear error when WooCommerce is inactive

== Installation ==

1. Install and activate the official MCP Adapter plugin.
2. Upload and activate MONOLITH Bridge.
3. Visit Tools > MONOLITH Bridge while logged in as the administrator who should represent the connector.
4. Confirm the MONOLITH ChatGPT endpoint is shown as ready.
5. Connect the private ChatGPT plugin and verify site identity read-only before enabling future write abilities.

== Security ==

* Version 0.2.0 does not expose write, plugin-update, deployment, order, or destructive abilities.
* The dedicated ChatGPT MCP endpoint requires a private connector credential.
* The credential is never displayed in WordPress and the repository stores only its SHA-256 hash.
* The connector maps successful requests to the administrator who initialized the bridge so normal WordPress capability checks still run.
* Rotate the credential by releasing a matched WordPress Bridge + private ChatGPT connector update.

== Changelog ==

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
