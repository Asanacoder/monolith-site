<?php
/**
 * Authenticated WordPress/WooCommerce REST administration for MONOLITH.
 *
 * Never expose arbitrary PHP execution, filesystem reads, database SQL, or
 * credentials. The bridge is installed on one WordPress site only and its
 * dedicated MCP server authenticates each request before abilities execute.
 */
defined( 'ABSPATH' ) || exit;

final class Monolith_Bridge_Admin {
	const AUDIT_OPTION = 'monolith_bridge_audit_log';
	const MAX_MEDIA_BYTES = 16777216;

	public static function register(): void {
		$common = array(
			'category'            => Monolith_Bridge::CATEGORY,
			'permission_callback' => array( __CLASS__, 'can_manage' ),
			'output_schema'       => array( 'type' => 'object', 'additionalProperties' => true ),
		);
		$read_meta = self::meta( true );
		$write_meta = self::meta( false );

		wp_register_ability( 'monolith-bridge/rest-request', array_merge( $common, array(
			'label'            => 'MONOLITH WordPress/WooCommerce REST request',
			'description'      => 'Authenticated administrator-level GET, POST, PUT, PATCH or DELETE against built-in wp/v2 and WooCommerce wc/v3 routes on THIS WordPress site only. Use GET to inspect before editing. Preserves WordPress and WooCommerce REST capability checks. Do not send secrets in request bodies. Supports pages, posts, products, variations, attributes, images, shop settings, plugins and other supported REST routes. Mutations are audit-logged.',
			'input_schema'     => array(
				'type'       => 'object',
				'properties' => array(
					'method' => array( 'type' => 'string', 'enum' => array( 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' ) ),
					'path'   => array( 'type' => 'string', 'description' => 'Path beginning /wp/v2/ or /wc/v3/; no host, URL, query string or fragment.' ),
					'params' => array( 'type' => 'object', 'additionalProperties' => true, 'description' => 'Query parameters on GET/DELETE; request body fields on POST/PUT/PATCH.' ),
				),
				'required' => array( 'method', 'path' ),
			),
			'execute_callback' => array( __CLASS__, 'rest_request' ),
			'meta'             => $write_meta,
		) ) );

		wp_register_ability( 'monolith-bridge/upload-media-url', array_merge( $common, array(
			'label'            => 'Upload image from HTTPS URL',
			'description'      => 'Sideload a publicly accessible HTTPS image into this WordPress Media Library. Images must be <= 16 MiB and valid supported image types. Returns attachment ID and URL. Private Google Drive viewing links are not direct downloadable image URLs.',
			'input_schema'     => self::media_schema( 'url' ),
			'execute_callback' => array( __CLASS__, 'upload_media_url' ),
			'meta'             => $write_meta,
		) ) );

		wp_register_ability( 'monolith-bridge/upload-media-base64', array_merge( $common, array(
			'label'            => 'Upload image bytes to Media Library',
			'description'      => 'Upload a base64 encoded PNG, JPEG, WebP, GIF, or AVIF image (maximum decoded size 16 MiB). Returns attachment ID and URL. Do not pass data: URL prefixes.',
			'input_schema'     => self::media_schema( 'base64' ),
			'execute_callback' => array( __CLASS__, 'upload_media_base64' ),
			'meta'             => $write_meta,
		) ) );

		wp_register_ability( 'monolith-bridge/audit-log', array_merge( $common, array(
			'label'            => 'Review MONOLITH write audit log',
			'description'      => 'Shows recent recorded changes from this MONOLITH Bridge. Does not expose request payloads or secrets.',
			'input_schema'     => array(
				'type'       => 'object',
				'properties' => array(
					'limit' => array( 'type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 30 ),
				),
			),
			'execute_callback' => array( __CLASS__, 'audit_log' ),
			'meta'             => $read_meta,
		) ) );
	}

	private static function meta( bool $readonly ): array {
		return array(
			'mcp' => array( 'public' => true, 'type' => 'tool' ),
			'annotations' => array(
				'readonly'       => $readonly,
				'destructive'    => ! $readonly,
				'idempotent'     => $readonly,
				'readOnlyHint'   => $readonly,
				'destructiveHint'=> ! $readonly,
			),
			'show_in_rest' => false,
		);
	}

	private static function media_schema( string $source ): array {
		$properties = array(
			'filename' => array( 'type' => 'string', 'description' => 'Filename with correct image extension.' ),
			'title'    => array( 'type' => 'string' ),
			'alt_text' => array( 'type' => 'string' ),
			'parent_id'=> array( 'type' => 'integer', 'minimum' => 0 ),
		);
		$properties[ $source ] = array( 'type' => 'string' );
		return array(
			'type'       => 'object',
			'properties' => $properties,
			'required'   => array( $source, 'filename' ),
		);
	}

	public static function can_manage( $input = null ) {
		return current_user_can( 'manage_options' )
			? true
			: new WP_Error( 'monolith_bridge_forbidden', 'MONOLITH administrator permission required.', array( 'status' => 403 ) );
	}

	/**
	 * Proxies narrowly scoped WordPress REST namespaces; the WP REST permission
	 * callback for the target route still runs under the connector operator.
	 */
	public static function rest_request( $input ) {
		$input = is_array( $input ) ? $input : array();
		$method = strtoupper( (string) ( $input['method'] ?? '' ) );
		$path = (string) ( $input['path'] ?? '' );
		if ( ! in_array( $method, array( 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' ), true ) ) {
			return new WP_Error( 'monolith_method_invalid', 'Unsupported REST method.', array( 'status' => 400 ) );
		}
		$decoded_path = rawurldecode( $path );
		if ( ! preg_match( '#^/(?:wp/v2|wc/v3)(?:/[A-Za-z0-9._~/%-]+)?$#D', $path )
			|| preg_match( '/%(?![0-9A-Fa-f]{2})/', $path )
			|| ! preg_match( '#^/(?:wp/v2|wc/v3)(?:/[A-Za-z0-9._~/-]+)?$#D', $decoded_path )
			|| strpos( $decoded_path, '..' ) !== false || strpos( $decoded_path, '//' ) !== false ) {
			return new WP_Error( 'monolith_path_invalid', 'Only simple wp/v2 and wc/v3 REST paths on the connected site are allowed.', array( 'status' => 400 ) );
		}
		$params = $input['params'] ?? array();
		if ( ! is_array( $params ) || count( $params ) > 100 ) {
			return new WP_Error( 'monolith_params_invalid', 'Expected an object with at most 100 top-level request fields.', array( 'status' => 400 ) );
		}

		$request = new WP_REST_Request( $method, $path );
		if ( 'GET' === $method || 'DELETE' === $method ) {
			$request->set_query_params( $params );
		} else {
			$request->set_header( 'Content-Type', 'application/json' );
			$body = wp_json_encode( $params );
			if ( false === $body || strlen( $body ) > 1048576 ) {
				return new WP_Error( 'monolith_body_too_large', 'REST request body must be valid JSON under 1 MiB. Use dedicated media upload for images.', array( 'status' => 413 ) );
			}
			$request->set_body( $body );
		}

		$response = rest_do_request( $request );
		if ( is_wp_error( $response ) ) {
			$error_data = $response->get_error_data();
			$status = is_array( $error_data ) ? (int) ( $error_data['status'] ?? 500 ) : 500;
			self::audit( $method, $path, $status );
			return $response;
		}
		$response = rest_ensure_response( $response );
		$status = (int) $response->get_status();
		$headers = $response->get_headers();
		$result = array(
			'site'      => home_url( '/' ),
			'method'    => $method,
			'path'      => $path,
			'status'    => $status,
			'success'   => $status >= 200 && $status < 400,
			'data'      => $response->get_data(),
			'pagination'=> array(
				'total'       => isset( $headers['X-WP-Total'] ) ? (int) $headers['X-WP-Total'] : null,
				'total_pages' => isset( $headers['X-WP-TotalPages'] ) ? (int) $headers['X-WP-TotalPages'] : null,
			),
		);
		if ( 'GET' !== $method ) {
			$result['audit_id'] = self::audit( $method, $path, $status );
		}
		return $result;
	}

	public static function upload_media_url( $input ) {
		$input = is_array( $input ) ? $input : array();
		$url = (string) ( $input['url'] ?? '' );
		if ( ! wp_http_validate_url( $url ) || 'https' !== strtolower( (string) wp_parse_url( $url, PHP_URL_SCHEME ) ) ) {
			return new WP_Error( 'monolith_image_url_invalid', 'A safe, directly downloadable HTTPS image URL is required.', array( 'status' => 400 ) );
		}
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$temp = download_url( $url, 30 );
		if ( is_wp_error( $temp ) ) {
			return $temp;
		}
		if ( filesize( $temp ) > self::MAX_MEDIA_BYTES ) {
			@unlink( $temp );
			return new WP_Error( 'monolith_image_too_large', 'Image exceeds 16 MiB.', array( 'status' => 413 ) );
		}
		return self::finish_image_upload( $temp, $input );
	}

	public static function upload_media_base64( $input ) {
		$input = is_array( $input ) ? $input : array();
		$b64 = (string) ( $input['base64'] ?? '' );
		if ( strlen( $b64 ) > (int) ceil( self::MAX_MEDIA_BYTES * 4 / 3 ) + 4 ) {
			return new WP_Error( 'monolith_image_too_large', 'Image exceeds 16 MiB.', array( 'status' => 413 ) );
		}
		$bytes = base64_decode( $b64, true );
		if ( false === $bytes || strlen( $bytes ) > self::MAX_MEDIA_BYTES ) {
			return new WP_Error( 'monolith_image_data_invalid', 'Invalid image base64 or file too large.', array( 'status' => 400 ) );
		}
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$temp = wp_tempnam( sanitize_file_name( (string) ( $input['filename'] ?? 'image.png' ) ) );
		if ( ! $temp || false === file_put_contents( $temp, $bytes ) ) {
			return new WP_Error( 'monolith_image_temp_failed', 'Could not save temporary upload.', array( 'status' => 500 ) );
		}
		return self::finish_image_upload( $temp, $input );
	}

	private static function finish_image_upload( string $temp, array $input ) {
		$filename = sanitize_file_name( (string) ( $input['filename'] ?? 'image.png' ) );
		$allowed = array( 'png', 'jpg', 'jpeg', 'webp', 'gif', 'avif' );
		$extension = strtolower( (string) pathinfo( $filename, PATHINFO_EXTENSION ) );
		$size = is_file( $temp ) ? filesize( $temp ) : false;
		$info = is_file( $temp ) ? @getimagesize( $temp ) : false;
		if ( ! in_array( $extension, $allowed, true ) || false === $size || $size > self::MAX_MEDIA_BYTES || ! $info || strpos( (string) $info['mime'], 'image/' ) !== 0 ) {
			@unlink( $temp );
			return new WP_Error( 'monolith_media_type_invalid', 'Only valid image files PNG/JPEG/WebP/GIF/AVIF up to 16 MiB are accepted.', array( 'status' => 400 ) );
		}
		$file = array( 'name' => $filename, 'tmp_name' => $temp );
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$id = media_handle_sideload( $file, max( 0, (int) ( $input['parent_id'] ?? 0 ) ), sanitize_text_field( (string) ( $input['title'] ?? '' ) ) );
		if ( is_wp_error( $id ) ) {
			@unlink( $temp );
			return $id;
		}
		if ( ! empty( $input['title'] ) ) {
			wp_update_post( array(
				'ID'         => $id,
				'post_title' => sanitize_text_field( (string) $input['title'] ),
			) );
		}
		if ( ! empty( $input['alt_text'] ) ) {
			update_post_meta( $id, '_wp_attachment_image_alt', sanitize_text_field( (string) $input['alt_text'] ) );
		}
		$audit_id = self::audit( 'UPLOAD', '/wp/v2/media/' . $id, 201 );
		return array(
			'site'          => home_url( '/' ),
			'attachment_id' => (int) $id,
			'url'           => wp_get_attachment_url( $id ),
			'filename'      => $filename,
			'audit_id'      => $audit_id,
		);
	}

	private static function audit( string $method, string $path, int $status ): string {
		$uuid = wp_generate_uuid4();
		$items = get_option( self::AUDIT_OPTION, array() );
		$items = is_array( $items ) ? $items : array();
		array_unshift( $items, array(
			'id'         => $uuid,
			'time_utc'   => gmdate( 'c' ),
			'operator'   => get_current_user_id(),
			'method'     => $method,
			'path'       => $path,
			'status'     => $status,
		) );
		update_option( self::AUDIT_OPTION, array_slice( $items, 0, 200 ), false );
		return $uuid;
	}

	public static function audit_log( $input = array() ): array {
		$items = get_option( self::AUDIT_OPTION, array() );
		$limit = min( 100, max( 1, (int) ( is_array( $input ) ? ( $input['limit'] ?? 30 ) : 30 ) ) );
		return array( 'items' => array_slice( is_array( $items ) ? $items : array(), 0, $limit ) );
	}
}
