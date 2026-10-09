<?php

defined( 'ABSPATH' ) || exit;

final class Monolith_Bridge {
	const CATEGORY = 'monolith-bridge';

	public static function init(): void {
		add_action( 'wp_abilities_api_categories_init', array( __CLASS__, 'register_category' ) );
		add_action( 'wp_abilities_api_init', array( __CLASS__, 'register_abilities' ) );
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_page' ) );
		add_action( 'admin_notices', array( __CLASS__, 'dependency_notice' ) );
	}

	public static function register_category(): void {
		if ( ! function_exists( 'wp_register_ability_category' ) ) {
			return;
		}

		wp_register_ability_category(
			self::CATEGORY,
			array(
				'label'       => __( 'MONOLITH Bridge', 'monolith-bridge' ),
				'description' => __( 'Controlled MONOLITH WordPress and WooCommerce abilities for authorized AI clients.', 'monolith-bridge' ),
			)
		);
	}

	public static function register_abilities(): void {
		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		Monolith_Bridge_Abilities::register();
	}

	public static function dependency_notice(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! class_exists( 'WP_Ability' ) ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'MONOLITH Bridge requires WordPress 6.9 or newer.', 'monolith-bridge' ) . '</p></div>';
			return;
		}

		if ( ! class_exists( 'WP\\MCP\\Core\\McpAdapter' ) ) {
			echo '<div class="notice notice-warning"><p>' . esc_html__( 'MONOLITH Bridge abilities are registered, but MCP Adapter is not active. Install and activate the official MCP Adapter plugin to expose them to AI clients.', 'monolith-bridge' ) . '</p></div>';
		}
	}

	public static function register_admin_page(): void {
		add_management_page(
			__( 'MONOLITH Bridge', 'monolith-bridge' ),
			__( 'MONOLITH Bridge', 'monolith-bridge' ),
			'manage_options',
			'monolith-bridge',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	public static function render_admin_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$wp_ready  = class_exists( 'WP_Ability' );
		$mcp_ready = class_exists( 'WP\\MCP\\Core\\McpAdapter' );
		$endpoint  = rest_url( 'mcp/mcp-adapter-default-server' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'MONOLITH Bridge', 'monolith-bridge' ); ?></h1>
			<p><?php esc_html_e( 'Version 0.1.0 exposes a deliberately small, read-only ability set for initial connection testing.', 'monolith-bridge' ); ?></p>
			<table class="widefat striped" style="max-width:900px">
				<tbody>
					<tr><th><?php esc_html_e( 'WordPress Abilities API', 'monolith-bridge' ); ?></th><td><?php echo $wp_ready ? esc_html__( 'Ready', 'monolith-bridge' ) : esc_html__( 'Unavailable', 'monolith-bridge' ); ?></td></tr>
					<tr><th><?php esc_html_e( 'MCP Adapter', 'monolith-bridge' ); ?></th><td><?php echo $mcp_ready ? esc_html__( 'Ready', 'monolith-bridge' ) : esc_html__( 'Not active', 'monolith-bridge' ); ?></td></tr>
					<tr><th><?php esc_html_e( 'Default MCP endpoint', 'monolith-bridge' ); ?></th><td><code><?php echo esc_html( $endpoint ); ?></code></td></tr>
					<tr><th><?php esc_html_e( 'Ability mode', 'monolith-bridge' ); ?></th><td><?php esc_html_e( 'Read-only foundation', 'monolith-bridge' ); ?></td></tr>
				</tbody>
			</table>
			<h2><?php esc_html_e( 'Exposed abilities', 'monolith-bridge' ); ?></h2>
			<ul>
				<li><code>monolith-bridge/site-status</code></li>
				<li><code>monolith-bridge/list-pages</code></li>
				<li><code>monolith-bridge/get-page</code></li>
				<li><code>monolith-bridge/list-plugins</code></li>
				<li><code>monolith-bridge/list-products</code></li>
				<li><code>monolith-bridge/get-product</code></li>
			</ul>
			<p><?php esc_html_e( 'Write abilities will be added only after this read-only connection is verified on staging.', 'monolith-bridge' ); ?></p>
		</div>
		<?php
	}
}
