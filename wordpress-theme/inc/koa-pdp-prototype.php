<?php
defined( 'ABSPATH' ) || exit;

/**
 * Koa PDP: first live implementation of the structured MONOLITH product experience.
 *
 * Demo 001 remains the visual proving ground, but factual product labels now render
 * from WooCommerce global attributes / MONOLITH discovery taxonomies rather than
 * being duplicated in the template.
 */

function monolith_is_koa_pdp_prototype() {
    return function_exists( 'is_product' ) && is_product() && 82 === (int) get_queried_object_id();
}

function monolith_koa_asset_url( $attachment_id ) {
    $url = wp_get_attachment_image_url( (int) $attachment_id, 'full' );
    return $url ? $url : '';
}

function monolith_koa_pdp_data() {
    return array(
        'eyebrow'  => monolith_studio_copy('eyebrow','THE COLLECTION / 001',82),
        'title'    => monolith_studio_copy('product_title','The Koa',82),
        'intro'    => monolith_studio_copy('intro','Warmth without ornament. A quiet statement, made to be worn.',82),
        'hero'     => monolith_studio_image('object_image',137,82),
        'life'     => monolith_studio_image('life_image',138,82),
        'box'      => monolith_studio_image('brand_image',134,82),
        'scale'    => monolith_studio_image('fit_image',139,82),
        'final'    => monolith_studio_image('final_image',136,82),
    );
}

function monolith_koa_pdp_assets() {
    if ( ! monolith_is_koa_pdp_prototype() ) return;

    wp_enqueue_style(
        'monolith-product-cinematic',
        get_theme_file_uri( '/assets/css/product-cinematic.css' ),
        array( 'monolith-woo' ),
        MONOLITH_THEME_VERSION
    );

    wp_enqueue_script(
        'monolith-product-cinematic',
        get_theme_file_uri( '/assets/js/product-cinematic.js' ),
        array(),
        MONOLITH_THEME_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'monolith_koa_pdp_assets', 30 );

function monolith_koa_pdp_body_class( $classes ) {
    if ( monolith_is_koa_pdp_prototype() ) {
        $classes[] = 'monolith-cinematic-pdp';
        $classes[] = 'monolith-koa-prototype';
    }
    return $classes;
}
add_filter( 'body_class', 'monolith_koa_pdp_body_class' );

function monolith_koa_prepare_hooks() {
    if ( ! monolith_is_koa_pdp_prototype() ) return;

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

    add_action( 'woocommerce_single_product_summary', 'monolith_koa_purchase_heading', 5 );
    add_action( 'woocommerce_single_product_summary', 'monolith_koa_purchase_intro', 20 );
    add_action( 'woocommerce_single_product_summary', 'monolith_koa_purchase_notes', 35 );

    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

    add_action( 'woocommerce_after_single_product_summary', 'monolith_koa_cinematic_story', 8 );
}
add_action( 'wp', 'monolith_koa_prepare_hooks', 20 );

function monolith_koa_value( $taxonomy, $fallback = '' ) {
    $value = monolith_product_term_text( 82, $taxonomy );
    return $value ? $value : $fallback;
}

function monolith_koa_purchase_heading() {
    $data = monolith_koa_pdp_data();
    ?>
    <div class="mono-pdp-kicker"><?php echo esc_html( $data['eyebrow'] ); ?></div>
    <h1 class="product_title entry-title mono-pdp-title"><?php echo esc_html( $data['title'] ); ?></h1>
    <?php
}

function monolith_koa_purchase_intro() {
    $data = monolith_koa_pdp_data();
    $base = monolith_koa_value( 'pa_base-material' );
    $inlay = monolith_koa_value( 'pa_inlay-material' );
    ?>
    <div class="woocommerce-product-details__short-description mono-pdp-intro"><p><?php echo esc_html( $data['intro'] ); ?></p></div>
    <?php if ( $base || $inlay ) : ?>
        <p class="mono-pdp-material-line"><?php echo esc_html( implode( ' · ', array_filter( array( $base, $inlay ) ) ) ); ?></p>
    <?php endif; ?>
    <?php
}

function monolith_koa_purchase_notes() {
    ?>
    <div class="mono-pdp-purchase-notes" aria-label="Fit guidance">
        <span><?php echo monolith_studio_text('purchase_note',"Comfort fit · US 7–13 in half sizes",82); ?></span>
        <a href="<?php echo esc_url( monolith_support_url() ); ?>"><?php echo monolith_studio_text('purchase_link',"Find your fit",82); ?> <span aria-hidden="true">↗</span></a>
    </div>
    <?php
}

function monolith_koa_image_section( $url, $class, $alt ) {
    if ( ! $url ) return;
    ?>
    <div class="<?php echo esc_attr( $class ); ?>">
        <img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" decoding="async">
    </div>
    <?php
}

function monolith_koa_specifications_section( $product_id ) {
    $all_specs = monolith_product_specification_groups( $product_id );
    $fit_keys  = array( 'pa_fit', 'pa_edge-style', 'pa_interior-material', 'pa_band-width', 'pa_profile' );
    $specs = array_values( array_filter( $all_specs, static function( $spec ) use ( $fit_keys ) {
        return in_array( $spec['taxonomy'], $fit_keys, true );
    } ) );
    if ( ! $specs ) return;
    ?>
    <section class="mono-product-architecture" aria-labelledby="mono-spec-title">
        <div class="mono-product-architecture__head">
            <div><div class="mono-story-eyebrow"><?php echo monolith_studio_text('details_eyebrow',"DETAILS & FIT",82); ?></div><h2 id="mono-spec-title"><?php echo monolith_studio_heading('details_title',"Considered in\nevery detail.",82); ?></h2></div>
            <p><?php echo monolith_studio_text('details_body',"The fit and finishing details, at a glance. Tungsten bands cannot be resized, so confirm your ring size before ordering.",82); ?></p>
        </div>
        <dl class="mono-spec-grid">
            <?php foreach ( $specs as $spec ) : ?>
                <div class="mono-spec"><dt><?php echo esc_html( $spec['label'] ); ?></dt><dd><?php echo esc_html( $spec['value'] ); ?></dd></div>
            <?php endforeach; ?>
        </dl>
        <a class="mono-spec-link" href="<?php echo esc_url( monolith_support_url() ); ?>"><?php echo monolith_studio_text('details_link',"Sizing and fit guidance",82); ?> <span aria-hidden="true">↗</span></a>
    </section>
    <?php
}

function monolith_koa_material_study_section( $product_id ) {
    $specimens = array_slice( monolith_product_specimens( $product_id ), 0, 4 );
    $character = monolith_product_discovery_groups( $product_id );

    if ( ! $specimens && ! $character ) return;
    ?>
    <section class="mono-material-study" aria-labelledby="mono-material-study-title">
        <?php if ( $specimens ) : ?>
            <div class="mono-material-study__head">
                <div>
                    <div class="mono-story-eyebrow"><?php echo monolith_studio_text('material_eyebrow',"The details",82); ?></div>
                    <h2 id="mono-material-study-title"><?php echo monolith_studio_text('material_title',"Material Study",82); ?></h2>
                </div>
                <p><?php echo monolith_studio_text('material_description',"Four details. One distinct point of view.",82); ?></p>
            </div>

            <div class="mono-material-study__grid">
                <?php foreach ( $specimens as $index => $specimen ) : ?>
                    <article class="mono-material-card<?php echo ! empty( $specimen['image_url'] ) ? ' has-media' : ''; ?>">
                        <?php if ( ! empty( $specimen['image_url'] ) ) : ?>
                            <div class="mono-material-card__media">
                                <img
                                    src="<?php echo esc_url( $specimen['image_url'] ); ?>"
                                    alt="<?php echo esc_attr( $specimen['name'] ); ?>"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </div>
                        <?php endif; ?>

                        <div class="mono-material-card__number"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
                        <h3><?php echo esc_html( $specimen['name'] ); ?></h3>

                        <?php if ( ! empty( $specimen['description'] ) ) : ?>
                            <p><?php echo esc_html( $specimen['description'] ); ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ( $character ) : ?>
            <div class="mono-material-character" aria-labelledby="mono-material-character-title">
                <div class="mono-material-character__head">
                    <div>
                        <div class="mono-story-eyebrow"><?php echo monolith_studio_text('character_eyebrow',"The character",82); ?></div>
                        <h2 id="mono-material-character-title"><?php echo monolith_studio_text('character_title',"Character",82); ?></h2>
                    </div>
                    <p><?php echo monolith_studio_text('character_description',"For those drawn to quiet strength.",82); ?></p>
                </div>

                <div class="mono-material-character__grid">
                    <?php foreach ( $character as $group ) : ?>
                        <article class="mono-character-card<?php echo ! empty( $group['image_url'] ) ? ' has-media' : ''; ?>">
                            <?php if ( ! empty( $group['image_url'] ) ) : ?>
                                <div class="mono-character-card__media">
                                    <img
                                        class="mono-character-card__background"
                                        src="<?php echo esc_url( $group['image_url'] ); ?>"
                                        alt=""
                                        aria-hidden="true"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                    <span class="mono-character-card__veil" aria-hidden="true"></span>
                                    <?php if ( ! empty( $group['icon_url'] ) ) : ?>
                                        <img
                                            class="mono-character-card__icon"
                                            src="<?php echo esc_url( $group['icon_url'] ); ?>"
                                            alt=""
                                            aria-hidden="true"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <span class="mono-character-card__label"><?php echo esc_html( $group['label'] ); ?></span>
                            <h3><?php echo esc_html( $group['value'] ); ?></h3>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
    <?php
}


function monolith_koa_cinematic_story() {
    $product_id = 82;
    $data = monolith_koa_pdp_data();
    $mono_parts = array();
    ob_start();
    monolith_koa_material_study_section( $product_id );
    $mono_parts['material'] = ob_get_clean();
    ob_start();
    ?>

        <section class="mono-story-hero" style="--mono-story-image:url('<?php echo esc_url( $data['hero'] ); ?>')">
            <div class="mono-story-shade"></div>
            <div class="mono-story-shell"><div class="mono-story-copy">
                <div class="mono-story-eyebrow"><?php echo monolith_studio_text('object_eyebrow',"THE OBJECT",82); ?></div>
                <h2><?php echo monolith_studio_heading('object_title',"Warmth held\nin structure.",82); ?></h2>
                <p><?php echo monolith_studio_text('object_body',"Warm grain against dark metal. A quiet contrast that gives The Koa its presence.",82); ?></p>
            </div></div>
        </section>
        <?php $mono_parts['object'] = ob_get_clean(); ob_start(); ?>
        <section class="mono-story-split mono-story-presentation" aria-labelledby="mono-brand-title">
            <div class="mono-story-panel mono-story-panel--dark">
                <div class="mono-story-eyebrow"><?php echo monolith_studio_text('brand_eyebrow',"THE MONOLITH STANDARD",82); ?></div>
                <h2 id="mono-brand-title"><?php echo monolith_studio_heading('brand_title',"Promises\nhave weight.",82); ?></h2>
                <p><?php echo monolith_studio_text('brand_body',"Objects of permanence. Defined by intention, restraint, and a sense of purpose that outlasts the moment. This is MONOLITH.",82); ?></p>
                <a class="mono-brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo monolith_studio_text('brand_link',"The world of MONOLITH",82); ?> <span aria-hidden="true">↗</span></a>
            </div>
            <?php monolith_koa_image_section( $data['box'], 'mono-story-media', 'MONOLITH ring beside its architectural presentation case' ); ?>
        </section>
        <?php $mono_parts['brand'] = ob_get_clean(); ob_start(); ?>
        <section class="mono-story-life">
            <?php monolith_koa_image_section( $data['life'], 'mono-story-life__image', 'Man wearing The Koa in a mountain setting' ); ?>
            <div class="mono-story-life__copy">
                <div class="mono-story-eyebrow"><?php echo monolith_studio_text('life_eyebrow',"IN THE WORLD",82); ?></div>
                <h2><?php echo monolith_studio_heading('life_title',"Made to belong\nto a life.",82); ?></h2>
                <p><?php echo monolith_studio_text('life_body',"Not just for the occasion. For everything that follows it. A daily reminder of what matters.",82); ?></p>
            </div>
        </section>
        <?php
            $mono_parts['life'] = ob_get_clean();
            ob_start();
            monolith_koa_specifications_section( $product_id );
            $mono_parts['details'] = ob_get_clean();
            ob_start();
        ?>
        <section class="mono-story-scale">
            <div class="mono-story-scale__copy">
                <div class="mono-story-eyebrow"><?php echo monolith_studio_text('fit_eyebrow',"THE FIT",82); ?></div>
                <h2><?php echo monolith_studio_heading('fit_title',"Substantial up close.\nRestrained on hand.",82); ?></h2>
                <p><?php echo monolith_studio_text('fit_body',"The right fit feels natural from the first moment. Take the time to choose a size that stays comfortable every day.",82); ?></p>
                <a class="btn" href="<?php echo esc_url( monolith_support_url() ); ?>"><?php echo monolith_studio_text('fit_link',"Sizing & fit guidance",82); ?> <span aria-hidden="true">↗</span></a>
            </div>
            <?php monolith_koa_image_section( $data['scale'], 'mono-story-scale__image', 'Koa ring held between fingertips to show visual scale' ); ?>
        </section>
        <?php $mono_parts['fit'] = ob_get_clean(); ob_start(); ?>
        <section class="mono-story-final" style="--mono-story-image:url('<?php echo esc_url( $data['final'] ); ?>')">
            <div class="mono-story-shade mono-story-shade--final"></div>
            <div class="mono-story-shell mono-story-final__inner"><div class="mono-story-copy">
                <div class="mono-story-eyebrow"><?php echo monolith_studio_text('final_eyebrow',"THE KOA / MONOLITH",82); ?></div>
                <h2><?php echo monolith_studio_heading('final_title',"Choose the object\nthat stays.",82); ?></h2>
                <p><?php echo monolith_studio_text('final_body',"A considered band, for a promise made to last.",82); ?></p>
                <a class="mono-final-cta" href="#product-82"><?php echo monolith_studio_text('final_button',"Make it yours",82); ?> <span aria-hidden="true">↗</span></a>
            </div></div>
        </section>
    <?php
    $mono_parts['final'] = ob_get_clean();
    echo '<div class="monolith-pdp-story">';
    foreach (monolith_studio_section_order() as $slug) {
        if (isset($mono_parts[$slug])) { echo $mono_parts[$slug]; }
    }
    echo '</div>';
}
