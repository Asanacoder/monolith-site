<?php
/**
 * Plugin Name: MONOLITH Bridge
 * Plugin URI: https://github.com/Asanacoder/monolith-site
 * Description: Secure WordPress Abilities API bridge for MONOLITH site operations through MCP-compatible AI clients.
 * Version: 0.1.0
 * Requires at least: 6.9
 * Requires PHP: 7.4
 * Requires Plugins: mcp-adapter
 * Author: MONOLITH
 * License: GPL-2.0-or-later
 * Text Domain: monolith-bridge
 */

defined( 'ABSPATH' ) || exit;

define( 'MONOLITH_BRIDGE_VERSION', '0.1.0' );
define( 'MONOLITH_BRIDGE_FILE', __FILE__ );
define( 'MONOLITH_BRIDGE_DIR', plugin_dir_path( __FILE__ ) );

require_once MONOLITH_BRIDGE_DIR . 'includes/class-monolith-bridge.php';
require_once MONOLITH_BRIDGE_DIR . 'includes/class-monolith-bridge-abilities.php';

Monolith_Bridge::init();
