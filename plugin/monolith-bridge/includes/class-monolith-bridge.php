<?php

defined( 'ABSPATH' ) || exit;

final class Monolith_Bridge {
	const CATEGORY = 'monolith-bridge';
	const SERVER_ID = 'monolith-chatgpt';
	const SERVER_NAMESPACE = 'monolith-mcp';
	const SERVER_ROUTE = 'chatgpt';
	const USER_OPTION = 'monolith_bridge_mcp_user_id';

	public static function init(): void {
		add_action( 'wp_abilities_api_categories_init', array( __CLASS__, 'register_category' ) );
		add_action( 'wp_abilities_api_init', array( __CLASS__, 'register_abilities' ) );
		add_action( 'mcp_adapter_init', array( __CLASS__, 'register_mcp_server' ) );
		add_action( 'admin_init', array( __CLASS__, 'ensure_operator_user' ) );
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

	public static function register_mcp_server( $adapter ): void {
		if ( ! class_exists( '\\WP\\MCP\\Transport\\HttpTransport' ) ) {
			return;
		}

		$adapter->create_server(
			self::SERVER_ID,
			self::SERVER_NAMESPACE,
			self::SERVER_ROUTE,
			'MONOLITH ChatGPT',
			'Private MONOLITH read-only MCP server for the ChatGPT connector.',
			MONOLITH_BRIDGE_VERSION,
			array( \WP\MCP\Transport\HttpTransport::class ),
			\WP\MCP\Infrastructure\ErrorHandling\ErrorLogMcpErrorHandler::class,
			\WP\MCP\Infrastructure\Observability\NullMcpObservabilityHandler::class,
			array(
				'monolith-bridge/site-status',
				'monolith-bridge/list-pages',
				'monolith-bridge/get-page',
				'monolith-bridge/list-plugins',
				'monolith-bridge/list-products',
				'monolith-bridge/get-product',
			),
			array(),
			array(),
			array( __CLASS__, 'authorize_mcp_request' )
		);
	}

	public static function authorize_mcp_request() {
		$provided = isset( $_SERVER['HTTP_X_MONOLITH_KEY'] ) ? trim( (string) wp_unslash( $_SERVER['HTTP_X_MONOLITH_KEY'] ) ) : '';

		if ( '' === $provided || ! hash_equals( MONOLITH_BRIDGE_CONNECTOR_KEY_HASH, hash( 'sha256', $provided ) ) ) {
			return new WP_Error(
				'monolith_bridge_unauthorized',
				__( 'Invalid MONOLITH connector credential.', 'monolith-bridge' ),
				array( 'status' => 401 )
			);
		}

		$user_id = absint( get_option( self::USER_OPTION ) );
		if ( ! $user_id ) {
			return new WP_Error(
				'monolith_bridge_operator_missing',
				__( 'MONOLITH Bridge has no authorized WordPress operator. Visit Tools > MONOLITH Bridge while logged in as an administrator.', 'monolith-bridge' ),
				array( 'status' => 503 )
			);
		}

		wp_set_current_user( $user_id );

		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'monolith_bridge_operator_forbidden',
				__( 'The configured MONOLITH Bridge operator no longer has administrator access.', 'monolith-bridge' ),
				array( 'status' => 403 )
			);
		}

		return true;
	}

	public static function ensure_operator_user(): void {
		if ( get_option( self::USER_OPTION ) ) {
			return;
		}

		if ( current_user_can( 'manage_options' ) && get_current_user_id() ) {
			update_option( self::USER_OPTION, get_current_user_id(), false );
		}
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

		self::ensure_operator_user();

		$wp_ready  = class_exists( 'WP_Ability' );
		$mcp_ready = class_exists( 'WP\\MCP\\Core\\McpAdapter' );
		$endpoint  = rest_url( self::SERVER_NAMESPACE . '/' . self::SERVER_ROUTE );
		$user_id   = absint( get_option( self::USER_OPTION ) );
		$user      = $user_id ? get_user_by( 'id', $user_id ) : false;
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'MONOLITH Bridge', 'monolith-bridge' ); ?></h1>
			<p><?php esc_html_e( 'Version 0.2.0 exposes a deliberately small, read-only ability set through a private ChatGPT MCP endpoint protected by a connector credential.', 'monolith-bridge' ); ?></p>
			<table class="widefat striped" style="max-width:900px">
				<tbody>
					<tr><th><?php esc_html_e( 'WordPress Abilities API', 'monolith-bridge' ); ?></th><td><?php echo $wp_ready ? esc_html__( 'Ready', 'monolith-bridge' ) : esc_html__( 'Unavailable', 'monolith-bridge' ); ?></td></tr>
					<tr><th><?php esc_html_e( 'MCP Adapter', 'monolith-bridge' ); ?></th><td><?php echo $mcp_ready ? esc_html__( 'Ready', 'monolith-bridge' ) : esc_html__( 'Not active', 'monolith-bridge' ); ?></td></tr>
					<tr><th><?php esc_html_e( 'MONOLITH ChatGPT endpoint', 'monolith-bridge' ); ?></th><td><code><?php echo esc_html( $endpoint ); ?></code></td></tr>
					<tr><th><?php esc_html_e( 'Authorized WordPress operator', 'monolith-bridge' ); ?></th><td><?php echo $user ? esc_html( $user->user_login . ' (#' . $user_id . ')' ) : esc_html__( 'Not configured', 'monolith-bridge' ); ?></td></tr>
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
			<p><?php esc_html_e( 'The connector credential is intentionally never displayed in WordPress. Write abilities will be added only after this read-only connection is verified.', 'monolith-bridge' ); ?></p>
		</div>
		<?php
	}
}
