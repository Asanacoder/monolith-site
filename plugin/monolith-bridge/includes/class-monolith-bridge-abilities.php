<?php

defined( 'ABSPATH' ) || exit;

final class Monolith_Bridge_Abilities {
	public static function register(): void {
		self::register_site_status();
		self::register_list_pages();
		self::register_get_page();
		self::register_list_plugins();
		self::register_list_products();
		self::register_get_product();
	}

	private static function meta(): array {
		return array(
			'mcp' => array(
				'public' => true,
				'type'   => 'tool',
			),
			'annotations' => array(
				'readonly'    => true,
				'destructive' => false,
				'idempotent'  => true,
			),
			'show_in_rest' => false,
		);
	}

	private static function can_manage(): bool {
		return current_user_can( 'manage_options' );
	}

	private static function object_output_schema(): array {
		return array(
			'type'                 => 'object',
			'additionalProperties' => true,
		);
	}

	private static function register_site_status(): void {
		wp_register_ability(
			'monolith-bridge/site-status',
			array(
				'label'               => __( 'Get MONOLITH site status', 'monolith-bridge' ),
				'description'         => __( 'Returns WordPress, theme, WooCommerce, MCP Adapter, environment, and URL status for the connected MONOLITH site. Use this before making site decisions.', 'monolith-bridge' ),
				'category'            => Monolith_Bridge::CATEGORY,
				'execute_callback'    => array( __CLASS__, 'site_status' ),
				'permission_callback' => array( __CLASS__, 'can_execute' ),
				'output_schema'       => self::object_output_schema(),
				'meta'                => self::meta(),
			)
		);
	}

	private static function register_list_pages(): void {
		wp_register_ability(
			'monolith-bridge/list-pages',
			array(
				'label'       => __( 'List pages', 'monolith-bridge' ),
				'description' => __( 'Lists WordPress pages with IDs, titles, slugs, statuses, modified timestamps, and links. Supports pagination and an optional title/content search.', 'monolith-bridge' ),
				'category'    => Monolith_Bridge::CATEGORY,
				'input_schema' => array(
					'type'       => 'object',
					'properties' => array(
						'page' => array( 'type' => 'integer', 'minimum' => 1, 'default' => 1 ),
						'per_page' => array( 'type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 20 ),
						'search' => array( 'type' => 'string', 'default' => '' ),
						'status' => array( 'type' => 'string', 'enum' => array( 'any', 'publish', 'draft', 'pending', 'private', 'future' ), 'default' => 'any' ),
					),
				),
				'execute_callback'    => array( __CLASS__, 'list_pages' ),
				'permission_callback' => array( __CLASS__, 'can_execute' ),
				'output_schema'       => self::object_output_schema(),
				'meta'                => self::meta(),
			)
		);
	}

	private static function register_get_page(): void {
		wp_register_ability(
			'monolith-bridge/get-page',
			array(
				'label'       => __( 'Get page', 'monolith-bridge' ),
				'description' => __( 'Returns a WordPress page by ID, including editable block content, title, slug, status, dates, template, and permalink.', 'monolith-bridge' ),
				'category'    => Monolith_Bridge::CATEGORY,
				'input_schema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array( 'type' => 'integer', 'minimum' => 1 ),
					),
					'required' => array( 'id' ),
				),
				'execute_callback'    => array( __CLASS__, 'get_page' ),
				'permission_callback' => array( __CLASS__, 'can_execute' ),
				'output_schema'       => self::object_output_schema(),
				'meta'                => self::meta(),
			)
		);
	}

	private static function register_list_plugins(): void {
		wp_register_ability(
			'monolith-bridge/list-plugins',
			array(
				'label'               => __( 'List plugins', 'monolith-bridge' ),
				'description'         => __( 'Lists installed WordPress plugins, versions, activation state, and update availability. This ability never installs, updates, activates, deactivates, or deletes plugins.', 'monolith-bridge' ),
				'category'            => Monolith_Bridge::CATEGORY,
				'execute_callback'    => array( __CLASS__, 'list_plugins' ),
				'permission_callback' => array( __CLASS__, 'can_execute' ),
				'output_schema'       => self::object_output_schema(),
				'meta'                => self::meta(),
			)
		);
	}

	private static function register_list_products(): void {
		wp_register_ability(
			'monolith-bridge/list-products',
			array(
				'label'       => __( 'List WooCommerce products', 'monolith-bridge' ),
				'description' => __( 'Lists WooCommerce products with IDs, type, status, SKU, price, stock state, and permalink. Returns an error if WooCommerce is not active.', 'monolith-bridge' ),
				'category'    => Monolith_Bridge::CATEGORY,
				'input_schema' => array(
					'type'       => 'object',
					'properties' => array(
						'page' => array( 'type' => 'integer', 'minimum' => 1, 'default' => 1 ),
						'per_page' => array( 'type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 20 ),
						'status' => array( 'type' => 'string', 'enum' => array( 'any', 'publish', 'draft', 'pending', 'private' ), 'default' => 'any' ),
					),
				),
				'execute_callback'    => array( __CLASS__, 'list_products' ),
				'permission_callback' => array( __CLASS__, 'can_execute' ),
				'output_schema'       => self::object_output_schema(),
				'meta'                => self::meta(),
			)
		);
	}

	private static function register_get_product(): void {
		wp_register_ability(
			'monolith-bridge/get-product',
			array(
				'label'       => __( 'Get WooCommerce product', 'monolith-bridge' ),
				'description' => __( 'Returns a WooCommerce product by ID, including editable product data, attributes, dimensions, stock state, image IDs, and variation IDs. Returns an error if WooCommerce is not active.', 'monolith-bridge' ),
				'category'    => Monolith_Bridge::CATEGORY,
				'input_schema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array( 'type' => 'integer', 'minimum' => 1 ),
					),
					'required' => array( 'id' ),
				),
				'execute_callback'    => array( __CLASS__, 'get_product' ),
				'permission_callback' => array( __CLASS__, 'can_execute' ),
				'output_schema'       => self::object_output_schema(),
				'meta'                => self::meta(),
			)
		);
	}

	public static function can_execute( $input = null ) {
		if ( ! self::can_manage() ) {
			return new WP_Error( 'monolith_bridge_forbidden', __( 'You do not have permission to use MONOLITH Bridge abilities.', 'monolith-bridge' ) );
		}

		return true;
	}

	public static function site_status(): array {
		global $wp_version;
		$theme = wp_get_theme();

		return array(
			'bridge_version'      => MONOLITH_BRIDGE_VERSION,
			'home_url'            => home_url( '/' ),
			'site_url'            => site_url( '/' ),
			'wp_version'          => (string) $wp_version,
			'php_version'         => PHP_VERSION,
			'environment'         => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'unknown',
			'multisite'           => is_multisite(),
			'active_theme'        => $theme->get( 'Name' ),
			'active_theme_version'=> $theme->get( 'Version' ),
			'template'            => get_template(),
			'stylesheet'          => get_stylesheet(),
			'woocommerce_active'  => class_exists( 'WooCommerce' ),
			'woocommerce_version' => defined( 'WC_VERSION' ) ? WC_VERSION : null,
			'mcp_adapter_active'  => class_exists( 'WP\\MCP\\Core\\McpAdapter' ),
			'mcp_adapter_version' => defined( 'WP_MCP_VERSION' ) ? WP_MCP_VERSION : null,
			'timezone'            => wp_timezone_string(),
			'permalink_structure' => (string) get_option( 'permalink_structure' ),
			'connector_endpoint'  => rest_url( Monolith_Bridge::SERVER_NAMESPACE . '/' . Monolith_Bridge::SERVER_ROUTE ),
			'connector_mode'      => 'read-only',
		);
	}

	public static function list_pages( $input = array() ): array {
		$input    = is_array( $input ) ? $input : array();
		$page     = max( 1, (int) ( $input['page'] ?? 1 ) );
		$per_page = min( 100, max( 1, (int) ( $input['per_page'] ?? 20 ) ) );
		$status   = sanitize_key( (string) ( $input['status'] ?? 'any' ) );
		$search   = sanitize_text_field( (string) ( $input['search'] ?? '' ) );

		$post_status = 'any' === $status ? array( 'publish', 'draft', 'pending', 'private', 'future' ) : $status;

		$query = new WP_Query(
			array(
				'post_type'      => 'page',
				'post_status'    => $post_status,
				'posts_per_page' => $per_page,
				'paged'          => $page,
				's'              => $search,
				'orderby'        => 'modified',
				'order'          => 'DESC',
			)
		);

		$items = array_map(
			static function ( WP_Post $post ): array {
				return array(
					'id'           => $post->ID,
					'title'        => get_the_title( $post ),
					'slug'         => $post->post_name,
					'status'       => $post->post_status,
					'modified_gmt' => $post->post_modified_gmt,
					'link'         => get_permalink( $post ),
				);
			},
			$query->posts
		);

		return array(
			'items'       => $items,
			'page'        => $page,
			'per_page'    => $per_page,
			'total'       => (int) $query->found_posts,
			'total_pages' => (int) $query->max_num_pages,
		);
	}

	public static function get_page( $input ) {
		$id   = (int) ( is_array( $input ) ? ( $input['id'] ?? 0 ) : 0 );
		$post = get_post( $id );

		if ( ! $post || 'page' !== $post->post_type ) {
			return new WP_Error( 'monolith_bridge_page_not_found', __( 'Page not found.', 'monolith-bridge' ) );
		}

		return array(
			'id'           => $post->ID,
			'title'        => get_the_title( $post ),
			'slug'         => $post->post_name,
			'status'       => $post->post_status,
			'content'      => $post->post_content,
			'excerpt'      => $post->post_excerpt,
			'parent'       => (int) $post->post_parent,
			'template'     => (string) get_page_template_slug( $post ),
			'date_gmt'     => $post->post_date_gmt,
			'modified_gmt' => $post->post_modified_gmt,
			'link'         => get_permalink( $post ),
		);
	}

	public static function list_plugins(): array {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();
		$updates = get_site_transient( 'update_plugins' );
		$items   = array();

		foreach ( $plugins as $file => $data ) {
			$items[] = array(
				'plugin_file'      => $file,
				'name'             => $data['Name'] ?? $file,
				'version'          => $data['Version'] ?? '',
				'active'           => is_plugin_active( $file ),
				'network_active'   => is_multisite() ? is_plugin_active_for_network( $file ) : false,
				'update_available' => isset( $updates->response[ $file ] ),
				'new_version'      => isset( $updates->response[ $file ]->new_version ) ? $updates->response[ $file ]->new_version : null,
			);
		}

		usort(
			$items,
			static function ( array $a, array $b ): int {
				return strcasecmp( (string) $a['name'], (string) $b['name'] );
			}
		);

		return array( 'items' => $items, 'total' => count( $items ) );
	}

	public static function list_products( $input = array() ) {
		if ( ! function_exists( 'wc_get_products' ) ) {
			return new WP_Error( 'monolith_bridge_woocommerce_missing', __( 'WooCommerce is not active.', 'monolith-bridge' ) );
		}

		$input    = is_array( $input ) ? $input : array();
		$page     = max( 1, (int) ( $input['page'] ?? 1 ) );
		$per_page = min( 100, max( 1, (int) ( $input['per_page'] ?? 20 ) ) );
		$status   = sanitize_key( (string) ( $input['status'] ?? 'any' ) );
		$statuses = 'any' === $status ? array( 'publish', 'draft', 'pending', 'private' ) : array( $status );

		$result = wc_get_products(
			array(
				'limit'    => $per_page,
				'page'     => $page,
				'status'   => $statuses,
				'orderby'  => 'modified',
				'order'    => 'DESC',
				'paginate' => true,
			)
		);

		$items = array();
		foreach ( $result->products as $product ) {
			$items[] = self::product_summary( $product );
		}

		return array(
			'items'       => $items,
			'page'        => $page,
			'per_page'    => $per_page,
			'total'       => (int) $result->total,
			'total_pages' => (int) $result->max_num_pages,
		);
	}

	public static function get_product( $input ) {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return new WP_Error( 'monolith_bridge_woocommerce_missing', __( 'WooCommerce is not active.', 'monolith-bridge' ) );
		}

		$id      = (int) ( is_array( $input ) ? ( $input['id'] ?? 0 ) : 0 );
		$product = wc_get_product( $id );

		if ( ! $product ) {
			return new WP_Error( 'monolith_bridge_product_not_found', __( 'Product not found.', 'monolith-bridge' ) );
		}

		$attributes = array();
		foreach ( $product->get_attributes() as $attribute ) {
			$attributes[] = array(
				'name'      => $attribute->get_name(),
				'options'   => $attribute->get_options(),
				'visible'   => $attribute->get_visible(),
				'variation' => $attribute->get_variation(),
			);
		}

		return array_merge(
			self::product_summary( $product ),
			array(
				'description'       => $product->get_description(),
				'short_description' => $product->get_short_description(),
				'featured_image_id' => $product->get_image_id(),
				'gallery_image_ids' => array_values( $product->get_gallery_image_ids() ),
				'attributes'        => $attributes,
				'weight'            => $product->get_weight(),
				'length'            => $product->get_length(),
				'width'             => $product->get_width(),
				'height'            => $product->get_height(),
				'categories'        => wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) ),
				'tags'              => wp_get_post_terms( $product->get_id(), 'product_tag', array( 'fields' => 'names' ) ),
				'variation_ids'     => $product->is_type( 'variable' ) ? array_values( $product->get_children() ) : array(),
			)
		);
	}

	private static function product_summary( WC_Product $product ): array {
		return array(
			'id'             => $product->get_id(),
			'name'           => $product->get_name(),
			'slug'           => $product->get_slug(),
			'status'         => $product->get_status(),
			'type'           => $product->get_type(),
			'sku'            => $product->get_sku(),
			'price'          => $product->get_price(),
			'regular_price'  => $product->get_regular_price(),
			'sale_price'     => $product->get_sale_price(),
			'stock_status'   => $product->get_stock_status(),
			'manage_stock'   => $product->get_manage_stock(),
			'stock_quantity' => $product->get_stock_quantity(),
			'modified_gmt'   => get_post_modified_time( 'Y-m-d H:i:s', true, $product->get_id() ),
			'permalink'      => $product->get_permalink(),
		);
	}
}
