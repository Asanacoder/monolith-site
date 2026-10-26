<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MONOLITH_THEME_VERSION', '0.7.2' );
require_once get_template_directory() . '/inc/demo-importer.php';
require_once get_template_directory() . '/inc/product-discovery.php';
require_once get_template_directory() . '/inc/monolith-studio.php';
require_once get_template_directory() . '/inc/koa-pdp-prototype.php';


function monolith_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 600, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'html5', array( 'search-form','comment-form','comment-list','gallery','caption','style','script' ) );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    register_nav_menus( array(
        'primary' => __( 'Primary Navigation', 'monolith' ),
        'footer_shop' => __( 'Footer — Shop', 'monolith' ),
        'footer_support' => __( 'Footer — Support', 'monolith' ),
        'footer_brand' => __( 'Footer — MONOLITH', 'monolith' ),
    ) );
}
add_action( 'after_setup_theme', 'monolith_setup' );

function monolith_assets() {
    wp_enqueue_style( 'monolith-fonts', 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=Playfair+Display:wght@400;500&display=swap', array(), null );
    wp_enqueue_style( 'monolith-base', get_theme_file_uri( '/assets/css/base.css' ), array(), MONOLITH_THEME_VERSION );
    if ( is_front_page() ) wp_enqueue_style( 'monolith-home', get_theme_file_uri( '/assets/css/home.css' ), array( 'monolith-base' ), MONOLITH_THEME_VERSION );
    if ( is_page( array( 'support','contact' ) ) ) wp_enqueue_style( 'monolith-support', get_theme_file_uri( '/assets/css/support.css' ), array( 'monolith-base' ), MONOLITH_THEME_VERSION );
    if ( function_exists('is_cart') && ( is_cart() || is_checkout() ) ) wp_enqueue_style( 'monolith-transactional', get_theme_file_uri( '/assets/css/transactional.css' ), array( 'monolith-base' ), MONOLITH_THEME_VERSION );
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 'monolith-woo', get_theme_file_uri( '/assets/css/woo.css' ), array( 'monolith-base' ), MONOLITH_THEME_VERSION );
    }
    wp_enqueue_script( 'monolith-theme', get_theme_file_uri( '/assets/js/theme.js' ), array(), MONOLITH_THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'monolith_assets' );

function monolith_body_classes( $classes ) {
    if ( is_front_page() ) $classes[] = 'monolith-front';
    return $classes;
}
add_filter( 'body_class', 'monolith_body_classes' );

function monolith_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'monolith_home', array(
        'title' => __( 'MONOLITH Homepage', 'monolith' ),
        'priority' => 30,
    ) );

    $settings = array(
        'monolith_hero_image' => 'Hero background image',
        'monolith_material_image' => 'Materials section image',
        'monolith_ceremony_image' => 'Ceremony background image',
        'monolith_ownership_image' => 'Ownership section image',
    );
    foreach ( $settings as $id => $label ) {
        $wp_customize->add_setting( $id, array( 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, array(
            'label' => __( $label, 'monolith' ),
            'section' => 'monolith_home',
            'settings' => $id,
        ) ) );
    }
}
add_action( 'customize_register', 'monolith_customize_register' );

function monolith_customizer_css() {
    $map = array(
        '--monolith-hero-image' => get_theme_mod( 'monolith_hero_image' ),
        '--monolith-material-image' => get_theme_mod( 'monolith_material_image' ),
        '--monolith-ceremony-image' => get_theme_mod( 'monolith_ceremony_image' ),
        '--monolith-ownership-image' => get_theme_mod( 'monolith_ownership_image' ),
    );
    echo '<style id="monolith-customizer-vars">:root{';
    foreach ( $map as $var => $url ) {
        if ( $url ) echo esc_html( $var ) . ':url("' . esc_url( $url ) . '");';
    }
    echo '}</style>';
}
add_action( 'wp_head', 'monolith_customizer_css', 40 );

function monolith_primary_fallback() {
    echo '<ul class="monolith-menu">';
    echo '<li><a href="' . esc_url( home_url( '/#bands' ) ) . '">Bands</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/#materials' ) ) . '">Materials</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/#ceremony' ) ) . '">The Ceremony</a></li>';
    echo '<li><a href="' . esc_url( monolith_support_url() ) . '">Support</a></li>';
    echo '</ul>';
}

function monolith_cart_count() {
    return function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

function monolith_cart_fragment( $fragments ) {
    ob_start();
    ?><span class="monolith-bag-count"><?php echo esc_html( monolith_cart_count() ); ?></span><?php
    $fragments['.monolith-bag-count'] = ob_get_clean();
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'monolith_cart_fragment' );

function monolith_shop_url() {
    return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
}


function monolith_support_url() {
    $page = get_page_by_path( 'support', OBJECT, 'page' );
    if ( $page && 'trash' !== get_post_status( $page ) ) {
        return get_permalink( $page );
    }
    return home_url( '/support/' );
}

/**
 * Keep the theme's required presentation pages self-healing during design development.
 * Support is a real WordPress page so normal routing, menus and canonical URLs work.
 * Contact remains only as a backwards-compatible alias and redirects to Support.
 */
function monolith_ensure_support_pages() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) return;

    $support = get_page_by_path( 'support', OBJECT, 'page' );
    if ( ! $support || 'trash' === get_post_status( $support ) ) {
        $support_id = wp_insert_post( array(
            'post_title'   => 'Support',
            'post_name'    => 'support',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ), true );
        if ( ! is_wp_error( $support_id ) ) {
            update_post_meta( $support_id, '_monolith_theme_page', 'support' );
        }
    }

    $contact = get_page_by_path( 'contact', OBJECT, 'page' );
    if ( ! $contact || 'trash' === get_post_status( $contact ) ) {
        $contact_id = wp_insert_post( array(
            'post_title'   => 'Contact',
            'post_name'    => 'contact',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ), true );
        if ( ! is_wp_error( $contact_id ) ) {
            update_post_meta( $contact_id, '_monolith_theme_page', 'support-alias' );
        }
    }
}
add_action( 'admin_init', 'monolith_ensure_support_pages', 25 );

/** Koa pilot: refined purchase wording while preserving the native WooCommerce cart. */
function monolith_koa_cart_button_text( $text ) {
    return monolith_is_koa_pdp_prototype() ? 'Add to Bag' : $text;
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'monolith_koa_cart_button_text', 20 );


/**
 * Render the single-product purchase quantity as a hidden field (value 1).
 * Customers can still change cart quantities after adding. This is strictly
 * presentational and does not change stock or purchasing restrictions.
 */
add_filter( 'woocommerce_quantity_input_type', function( $type ) {
    if ( function_exists( 'monolith_is_koa_pdp_prototype' ) && monolith_is_koa_pdp_prototype() ) {
        return 'hidden';
    }
    return $type;
}, 20 );


/**
 * Critical purchase contrast rules are emitted in the document head as well
 * as in the versioned cinematic stylesheet. This guards against an upstream
 * asset cache serving an older stylesheet, observed on the Koa pilot.
 */
add_action( 'wp_head', function() {
    if ( ! function_exists( 'monolith_is_koa_pdp_prototype' ) || ! monolith_is_koa_pdp_prototype() ) {
        return;
    }
    ?>
    <style id="monolith-koa-purchase-contrast">
    body.monolith-cinematic-pdp .woocommerce div.product div.summary .price,
    body.monolith-cinematic-pdp .woocommerce div.product div.summary .price .amount,
    body.monolith-cinematic-pdp .woocommerce div.product div.summary .price bdi,
    body.monolith-cinematic-pdp .woocommerce div.product div.summary .price .woocommerce-Price-currencySymbol {
        color:#f8f5ee!important;opacity:1!important;
    }
    body.monolith-cinematic-pdp .woocommerce div.product form.cart .single_add_to_cart_button.button:disabled,
    body.monolith-cinematic-pdp .woocommerce div.product form.cart .single_add_to_cart_button.button.disabled,
    body.monolith-cinematic-pdp .woocommerce div.product form.cart .single_add_to_cart_button.button.wc-variation-selection-needed {
        background:#24211e!important;background-image:none!important;
        color:#f1e9de!important;border:1px solid #8e806e!important;
        box-shadow:inset 0 1px 0 rgba(255,255,255,.055)!important;
        opacity:1!important;
    }
    body.monolith-cinematic-pdp .woocommerce div.product form.cart .single_add_to_cart_button.button:not(.disabled):not(:disabled) {
        background:#eee8df!important;background-image:none!important;
        color:#161513!important;border-color:#eee8df!important;opacity:1!important;
    }
    </style>
    <?php
}, 110 );
