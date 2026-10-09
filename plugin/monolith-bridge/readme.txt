=== MONOLITH Bridge ===
Contributors: monolith
Tags: mcp, ai, abilities-api, woocommerce, automation
Requires at least: 6.9
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later

Secure WordPress Abilities API bridge for MONOLITH site operations through MCP-compatible AI clients.

== Description ==

MONOLITH Bridge exposes a deliberately controlled set of WordPress and WooCommerce abilities for authorized MCP-compatible AI clients.

Version 0.1.0 is intentionally read-only. It is designed to validate the WordPress -> Abilities API -> MCP Adapter -> AI-client connection before any write capability is enabled.

Exposed abilities:

* monolith-bridge/site-status
* monolith-bridge/list-pages
* monolith-bridge/get-page
* monolith-bridge/list-plugins
* monolith-bridge/list-products
* monolith-bridge/get-product

All abilities require an authenticated WordPress user with the `manage_options` capability.

== Requirements ==

* WordPress 6.9+
* Official WordPress MCP Adapter plugin
* WooCommerce is optional; Woo abilities return a clear error when WooCommerce is inactive

== Installation ==

1. Install and activate the official MCP Adapter plugin.
2. Upload and activate MONOLITH Bridge.
3. Open Tools > MONOLITH Bridge to verify status.
4. Connect an MCP client to the MCP Adapter endpoint.
5. Confirm the six MONOLITH abilities are discoverable before enabling future write abilities.

== Security ==

Version 0.1.0 does not expose write, plugin-update, deployment, or destructive abilities.

The plugin intentionally exposes abilities through MCP while keeping them out of the normal WordPress REST ability channel.

== Changelog ==

= 0.1.0 =
* Initial read-only bridge.
* Added WordPress/site health inspection.
* Added page inspection.
* Added plugin inventory inspection.
* Added WooCommerce product inspection.
