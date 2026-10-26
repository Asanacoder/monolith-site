<?php
/**
 * MONOLITH Studio v0.1 — editable content and template section registry.
 * This implementation is theme-integrated for the initial rollout; its
 * field contract and option/meta storage can move to MONOLITH Core later.
 */
defined('ABSPATH') || exit;

function monolith_studio_sections_catalog() {
    return array(
        'material' => 'Material Study + Character',
        'object'   => 'The Object — Full-Width Hero',
        'brand'    => 'The MONOLITH Standard',
        'life'     => 'In the World — Lifestyle',
        'details'  => 'Details & Fit — Specifications',
        'fit'      => 'The Fit — Hand Scale',
        'final'    => 'Final Call to Action',
    );
}
function monolith_studio_fields() {
    return array(
        'purchase' => array('label'=>'Purchase Area','fields'=>array(
            'eyebrow'=>array('label'=>'Eyebrow','default'=>'THE COLLECTION / 001'),
            'product_title'=>array('label'=>'Display title','default'=>'The Koa'),
            'intro'=>array('label'=>'Short introduction','default'=>'Warmth without ornament. A quiet statement, made to be worn.','type'=>'textarea'),
            'purchase_note'=>array('label'=>'Fit note','default'=>'Comfort fit · US 7–13 in half sizes'),
            'purchase_link'=>array('label'=>'Fit help link label','default'=>'Find your fit'),
        )),
        'material'=>array('label'=>'Material Study','fields'=>array(
            'material_eyebrow'=>array('label'=>'Eyebrow','default'=>'The details'),
            'material_title'=>array('label'=>'Title','default'=>'Material Study'),
            'material_description'=>array('label'=>'Introduction','default'=>'Four details. One distinct point of view.','type'=>'textarea'),
            'character_eyebrow'=>array('label'=>'Character eyebrow','default'=>'The character'),
            'character_title'=>array('label'=>'Character title','default'=>'Character'),
            'character_description'=>array('label'=>'Character introduction','default'=>'For those drawn to quiet strength.','type'=>'textarea'),
        )),
        'object'=>array('label'=>'The Object','fields'=>array(
            'object_eyebrow'=>array('label'=>'Eyebrow','default'=>'THE OBJECT'),
            'object_title'=>array('label'=>'Headline','default'=>"Warmth held\nin structure.",'type'=>'textarea'),
            'object_body'=>array('label'=>'Paragraph','default'=>'Warm grain against dark metal. A quiet contrast that gives The Koa its presence.','type'=>'textarea'),
            'object_image'=>array('label'=>'Background image','default'=>'','type'=>'image','fallback_id'=>137),
        )),
        'brand'=>array('label'=>'The MONOLITH Standard','fields'=>array(
            'brand_eyebrow'=>array('label'=>'Eyebrow','default'=>'THE MONOLITH STANDARD'),
            'brand_title'=>array('label'=>'Headline','default'=>"Promises\nhave weight.",'type'=>'textarea'),
            'brand_body'=>array('label'=>'Paragraph','default'=>'Objects of permanence. Defined by intention, restraint, and a sense of purpose that outlasts the moment. This is MONOLITH.','type'=>'textarea'),
            'brand_link'=>array('label'=>'Button label','default'=>'The world of MONOLITH'),
            'brand_image'=>array('label'=>'Editorial image','default'=>'','type'=>'image','fallback_id'=>134),
        )),
        'life'=>array('label'=>'In the World','fields'=>array(
            'life_eyebrow'=>array('label'=>'Eyebrow','default'=>'IN THE WORLD'),
            'life_title'=>array('label'=>'Headline','default'=>"Made to belong\nto a life.",'type'=>'textarea'),
            'life_body'=>array('label'=>'Paragraph','default'=>'Not just for the occasion. For everything that follows it. A daily reminder of what matters.','type'=>'textarea'),
            'life_image'=>array('label'=>'Editorial image','default'=>'','type'=>'image','fallback_id'=>138),
        )),
        'details'=>array('label'=>'Details & Fit','fields'=>array(
            'details_eyebrow'=>array('label'=>'Eyebrow','default'=>'DETAILS & FIT'),
            'details_title'=>array('label'=>'Headline','default'=>"Considered in\nevery detail.",'type'=>'textarea'),
            'details_body'=>array('label'=>'Paragraph','default'=>'The fit and finishing details, at a glance. Tungsten bands cannot be resized, so confirm your ring size before ordering.','type'=>'textarea'),
            'details_link'=>array('label'=>'Link label','default'=>'Sizing and fit guidance'),
        )),
        'fit'=>array('label'=>'The Fit','fields'=>array(
            'fit_eyebrow'=>array('label'=>'Eyebrow','default'=>'THE FIT'),
            'fit_title'=>array('label'=>'Headline','default'=>"Substantial up close.\nRestrained on hand.",'type'=>'textarea'),
            'fit_body'=>array('label'=>'Paragraph','default'=>'The right fit feels natural from the first moment. Take the time to choose a size that stays comfortable every day.','type'=>'textarea'),
            'fit_link'=>array('label'=>'Button label','default'=>'Sizing & fit guidance'),
            'fit_image'=>array('label'=>'Hand-scale image','default'=>'','type'=>'image','fallback_id'=>139),
        )),
        'final'=>array('label'=>'Final Call to Action','fields'=>array(
            'final_eyebrow'=>array('label'=>'Eyebrow','default'=>'THE KOA / MONOLITH'),
            'final_title'=>array('label'=>'Headline','default'=>"Choose the object\nthat stays.",'type'=>'textarea'),
            'final_body'=>array('label'=>'Paragraph','default'=>'A considered band, for a promise made to last.','type'=>'textarea'),
            'final_button'=>array('label'=>'Button label','default'=>'Make it yours'),
            'final_image'=>array('label'=>'Background image','default'=>'','type'=>'image','fallback_id'=>136),
        )),
    );
}
function monolith_studio_flat_fields() {
    $flat = array();
    foreach (monolith_studio_fields() as $group) {
        foreach ($group['fields'] as $key => $field) { $flat[$key] = $field; }
    }
    return $flat;
}
function monolith_studio_copy($key, $fallback = '', $product_id = 0) {
    $pid = $product_id ? absint($product_id) : (is_singular('product') ? get_queried_object_id() : 0);
    if ($pid) {
        $specific = get_post_meta($pid, '_monolith_studio_copy', true);
        if (is_array($specific) && array_key_exists($key, $specific) && $specific[$key] !== '') {
            return (string) $specific[$key];
        }
    }
    $global = get_option('monolith_studio_global_copy', array());
    if (is_array($global) && array_key_exists($key, $global) && $global[$key] !== '') {
        return (string) $global[$key];
    }
    return (string) $fallback;
}
function monolith_studio_text($key, $fallback = '', $product_id = 0) {
    return esc_html(monolith_studio_copy($key, $fallback, $product_id));
}
function monolith_studio_heading($key, $fallback = '', $product_id = 0) {
    return nl2br(esc_html(monolith_studio_copy($key, $fallback, $product_id)));
}
function monolith_studio_image($key, $fallback_id, $product_id = 0) {
    $id = absint(monolith_studio_copy($key, '', $product_id));
    if (!$id) { $id = absint($fallback_id); }
    $url = $id ? wp_get_attachment_image_url($id, 'full') : false;
    return $url ? $url : '';
}
function monolith_studio_section_order() {
    $catalog = monolith_studio_sections_catalog();
    $saved = get_option('monolith_studio_section_order', array());
    $saved = is_array($saved) ? $saved : array();
    $ordered = array();
    foreach ($saved as $slug) {
        if (isset($catalog[$slug]) && !in_array($slug, $ordered, true)) { $ordered[] = $slug; }
    }
    foreach (array_keys($catalog) as $slug) {
        if (!in_array($slug, $ordered, true)) { $ordered[] = $slug; }
    }
    $disabled = get_option('monolith_studio_disabled_sections', array());
    $disabled = is_array($disabled) ? $disabled : array();
    return array_values(array_diff($ordered, $disabled));
}

add_action('admin_menu', function () {
    add_menu_page('MONOLITH Studio', 'MONOLITH Studio', 'manage_options', 'monolith-studio',
        'monolith_studio_admin_page', 'dashicons-layout', 24);
});
function monolith_studio_media_control($key, $value, $default_id = 0) {
    $id = absint($value);
    $shown = $id ?: absint($default_id);
    $url = $shown ? wp_get_attachment_image_url($shown, 'thumbnail') : false;
    ?>
    <div class="mono-studio-image">
        <input type="hidden" name="mono_fields[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($id); ?>">
        <div class="mono-studio-image-preview"><?php if ($url) : ?><img src="<?php echo esc_url($url); ?>" alt=""><?php endif; ?></div>
        <button type="button" class="button mono-studio-choose">Choose image</button>
        <button type="button" class="button-link mono-studio-reset">Use default</button>
    </div>
    <?php
}
function monolith_studio_field_row($key, $field, $value, $is_product = false) {
    $type = isset($field['type']) ? $field['type'] : 'text';
    ?>
    <div class="mono-studio-field">
        <label for="mono-<?php echo esc_attr($key); ?>"><?php echo esc_html($field['label']); ?></label>
        <?php if ($type === 'image') : ?>
            <?php monolith_studio_media_control($key, $value, isset($field['fallback_id']) ? $field['fallback_id'] : 0); ?>
        <?php elseif ($type === 'textarea') : ?>
            <textarea id="mono-<?php echo esc_attr($key); ?>" name="mono_fields[<?php echo esc_attr($key); ?>]" rows="3" placeholder="<?php echo esc_attr($field['default']); ?>"><?php echo esc_textarea($value); ?></textarea>
        <?php else : ?>
            <input id="mono-<?php echo esc_attr($key); ?>" type="text" name="mono_fields[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($value); ?>" placeholder="<?php echo esc_attr($field['default']); ?>">
        <?php endif; ?>
        <?php if ($is_product) : ?><small>Leave empty to inherit the global/default value.</small><?php endif; ?>
    </div>
    <?php
}
function monolith_studio_admin_page() {
    if (!current_user_can('manage_options')) { return; }
    $catalog = monolith_studio_sections_catalog();
    $order = get_option('monolith_studio_section_order', array_keys($catalog));
    if (!is_array($order)) { $order = array_keys($catalog); }
    $order = array_values(array_unique(array_merge(array_intersect($order, array_keys($catalog)), array_keys($catalog))));
    $disabled = get_option('monolith_studio_disabled_sections', array());
    $disabled = is_array($disabled) ? $disabled : array();
    $global = get_option('monolith_studio_global_copy', array());
    $global = is_array($global) ? $global : array();
    ?>
    <div class="wrap mono-studio">
        <div class="mono-studio-header"><span class="mono-studio-label">MONOLITH / CONTENT SYSTEM</span><h1>Studio</h1>
        <p>Control the editorial experience without touching theme code. WooCommerce continues to own product pricing, stock, sizes and checkout.</p></div>
        <?php if (!empty($_GET['saved'])) : ?><div class="notice notice-success is-dismissible"><p>Studio settings saved.</p></div><?php endif; ?>
        <nav class="mono-studio-tabs"><a class="mono-studio-tab active" href="#mono-layout">Product template</a><a class="mono-studio-tab" href="#mono-global">Global copy</a><a class="mono-studio-tab" href="<?php echo esc_url(admin_url('post.php?post=82&action=edit#monolith-studio-product')); ?>">Edit Koa content ↗</a></nav>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <?php wp_nonce_field('monolith_studio_save_global'); ?>
            <input type="hidden" name="action" value="monolith_studio_save_global">
            <section id="mono-layout" class="mono-studio-panel">
                <div class="mono-studio-panel-heading"><div><h2>Product page sections</h2><p>Drag sections to reorder. Untick “Show” to hide a section on all products using this template.</p></div><span class="mono-studio-chip">Default template</span></div>
                <input type="hidden" name="mono_order" id="mono-studio-order" value="<?php echo esc_attr(implode(',', $order)); ?>">
                <div class="mono-studio-sort" id="mono-studio-sort">
                    <?php foreach ($order as $slug) : ?>
                        <div class="mono-studio-sort-row" draggable="true" data-slug="<?php echo esc_attr($slug); ?>">
                            <span class="mono-studio-handle" title="Drag to move">☷</span><span class="mono-studio-row-name"><?php echo esc_html($catalog[$slug]); ?></span>
                            <button class="button-link mono-studio-up" type="button" aria-label="Move up">↑</button><button class="button-link mono-studio-down" type="button" aria-label="Move down">↓</button>
                            <label class="mono-studio-switch"><input name="mono_enabled[<?php echo esc_attr($slug); ?>]" type="checkbox" value="1" <?php checked(!in_array($slug,$disabled,true)); ?>> Show</label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mono-studio-note">The purchase gallery, variations and Add to Cart stay in WooCommerce and are not draggable.</div>
            </section>
            <section id="mono-global" class="mono-studio-panel">
                <div class="mono-studio-panel-heading"><div><h2>Global editorial copy</h2><p>These values provide shared defaults. Individual product content can override them.</p></div></div>
                <div class="mono-studio-accordion">
                <?php foreach (monolith_studio_fields() as $slug => $group) : ?>
                    <details <?php if ($slug === 'purchase') echo 'open'; ?>><summary><?php echo esc_html($group['label']); ?><span>✎</span></summary>
                        <div class="mono-studio-grid">
                            <?php foreach ($group['fields'] as $key => $field) {
                                monolith_studio_field_row($key, $field, isset($global[$key]) ? $global[$key] : '');
                            } ?>
                        </div>
                    </details>
                <?php endforeach; ?>
                </div>
            </section>
            <div class="mono-studio-save"><button class="button button-primary button-hero" type="submit">Save Studio settings</button><span>Changes apply when saved. Existing product-specific overrides remain intact.</span></div>
        </form>
    </div>
    <?php
}
add_action('admin_post_monolith_studio_save_global', function () {
    if (!current_user_can('manage_options')) { wp_die('Insufficient permissions'); }
    check_admin_referer('monolith_studio_save_global');
    $catalog = monolith_studio_sections_catalog();
    $parts = isset($_POST['mono_order']) ? explode(',', sanitize_text_field(wp_unslash($_POST['mono_order']))) : array();
    $order = array_values(array_unique(array_merge(array_intersect($parts,array_keys($catalog)),array_keys($catalog))));
    update_option('monolith_studio_section_order', $order, false);
    $enabled = isset($_POST['mono_enabled']) && is_array($_POST['mono_enabled']) ? array_keys($_POST['mono_enabled']) : array();
    update_option('monolith_studio_disabled_sections', array_values(array_diff(array_keys($catalog),$enabled)), false);
    $input = isset($_POST['mono_fields']) && is_array($_POST['mono_fields']) ? wp_unslash($_POST['mono_fields']) : array();
    update_option('monolith_studio_global_copy', monolith_studio_sanitize_copy($input), false);
    wp_safe_redirect(admin_url('admin.php?page=monolith-studio&saved=1')); exit;
});
function monolith_studio_sanitize_copy($input) {
    $clean = array();
    foreach (monolith_studio_flat_fields() as $key=>$field) {
        if (!isset($input[$key]) || !is_scalar($input[$key])) { continue; }
        $value = $field['type'] ?? 'text';
        $raw = (string) $input[$key];
        $sanitized = $value === 'image' ? absint($raw) : ($value === 'textarea' ? sanitize_textarea_field($raw) : sanitize_text_field($raw));
        if ($sanitized !== '' && $sanitized !== 0) { $clean[$key] = $sanitized; }
    }
    return $clean;
}
add_action('add_meta_boxes_product', function () {
    add_meta_box('monolith-studio-product','MONOLITH Studio — Product Content',
        'monolith_studio_product_metabox','product','normal','high');
});
function monolith_studio_product_metabox($post) {
    wp_nonce_field('monolith_studio_save_product', 'monolith_studio_nonce');
    $values = get_post_meta($post->ID,'_monolith_studio_copy',true);
    $values = is_array($values) ? $values : array();
    ?>
    <div class="mono-studio-product">
        <p>Set editorial copy and images for this product. Empty values inherit Studio global defaults. Product specifications and materials remain linked to WooCommerce attributes and taxonomies.</p>
        <?php foreach (monolith_studio_fields() as $group) : ?>
            <details><summary><?php echo esc_html($group['label']); ?> <span>✎</span></summary>
                <div class="mono-studio-grid"><?php foreach ($group['fields'] as $key=>$field) {
                    monolith_studio_field_row($key,$field,$values[$key]??'',true);
                } ?></div>
            </details>
        <?php endforeach; ?>
    </div>
    <?php
}
add_action('save_post_product', function($post_id) {
    if (!isset($_POST['monolith_studio_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['monolith_studio_nonce'])),'monolith_studio_save_product')) { return; }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (!current_user_can('edit_post',$post_id)) { return; }
    $input = isset($_POST['mono_fields']) && is_array($_POST['mono_fields']) ? wp_unslash($_POST['mono_fields']) : array();
    update_post_meta($post_id,'_monolith_studio_copy',monolith_studio_sanitize_copy($input));
}, 10, 1);

add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'toplevel_page_monolith-studio' && !in_array($hook,array('post.php','post-new.php'),true)) { return; }
    $screen = get_current_screen();
    if ($hook !== 'toplevel_page_monolith-studio' && (!$screen || $screen->post_type !== 'product')) { return; }
    wp_enqueue_media();
    wp_register_style('monolith-studio-admin', false, array(), '0.1.0');
    wp_enqueue_style('monolith-studio-admin');
    wp_add_inline_style('monolith-studio-admin', monolith_studio_admin_css());
    wp_register_script('monolith-studio-admin', false, array('jquery','media-views'), '0.1.0', true);
    wp_enqueue_script('monolith-studio-admin');
    wp_add_inline_script('monolith-studio-admin', monolith_studio_admin_js());
});
function monolith_studio_admin_css() {
    return <<<'CSS'
.mono-studio{max-width:1120px}.mono-studio-header{background:#141414;color:#f3eee6;padding:34px 42px;border-radius:12px;margin:20px 0 0}.mono-studio-label{font-size:10px;letter-spacing:.25em;color:#bd916a}.mono-studio-header h1{color:#f3eee6;font:400 48px Georgia,serif;margin:12px 0}.mono-studio-header p{color:#b3aca2;max-width:680px;line-height:1.7}.mono-studio-tabs{display:flex;gap:22px;border-bottom:1px solid #d8d3cc;margin:22px 0}.mono-studio-tab{text-decoration:none!important;color:#555;padding:12px 0;font-weight:600}.mono-studio-tab.active{border-bottom:2px solid #151515;color:#141414}.mono-studio-panel{border:1px solid #dedad4;background:#fff;border-radius:10px;margin:20px 0;overflow:hidden}.mono-studio-panel-heading{padding:24px 28px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #e8e5e1}.mono-studio-panel-heading h2{font:400 28px Georgia,serif;margin:0 0 6px}.mono-studio-panel-heading p{margin:0;color:#666}.mono-studio-chip{font-size:11px;text-transform:uppercase;letter-spacing:.1em;background:#efebe6;padding:8px 12px;border-radius:20px;white-space:nowrap}.mono-studio-sort{padding:15px 28px 22px}.mono-studio-sort-row{display:flex;gap:16px;align-items:center;padding:16px 20px;border:1px solid #e7e3dc;background:#faf9f7;border-radius:6px;margin-top:8px}.mono-studio-sort-row.mono-dragging{opacity:.45}.mono-studio-handle{cursor:grab;color:#8c8178;font-size:26px;line-height:18px}.mono-studio-row-name{font-weight:600;flex:1}.mono-studio-up,.mono-studio-down{font-size:20px!important;line-height:1!important}.mono-studio-switch{white-space:nowrap}.mono-studio-note{background:#f6f4f0;padding:14px 28px;color:#665f56;font-size:12px}.mono-studio-accordion{padding:15px 28px 28px}.mono-studio-accordion details,.mono-studio-product details{border-bottom:1px solid #e5e1da}.mono-studio-accordion summary,.mono-studio-product summary{display:flex;justify-content:space-between;cursor:pointer;padding:20px 8px;font-weight:600;font-size:14px}.mono-studio-grid{padding:15px 8px 28px;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:22px}.mono-studio-field label{display:block;font-weight:600;margin:0 0 7px}.mono-studio-field input[type=text],.mono-studio-field textarea{width:100%;border:1px solid #d6d1c9;border-radius:4px;min-height:40px}.mono-studio-field small{display:block;color:#777;margin-top:5px}.mono-studio-image-preview img{width:92px;height:92px;object-fit:cover;border:1px solid #ddd;margin-bottom:8px}.mono-studio-image .button{margin-right:12px}.mono-studio-save{display:flex;align-items:center;gap:20px;padding:14px 0 40px;color:#666}.mono-studio-save .button-primary{background:#23201f;border-color:#23201f}.mono-studio-product{padding:12px 22px}.mono-studio-product>p{color:#666}.mono-studio-product .mono-studio-grid{padding:18px 8px 24px}@media(max-width:760px){.mono-studio-grid{grid-template-columns:1fr}.mono-studio-panel-heading{display:block}.mono-studio-header{padding:25px}.mono-studio-sort-row{gap:8px;padding:12px}.mono-studio-tabs{flex-wrap:wrap}}
CSS;
}
function monolith_studio_admin_js() {
    return <<<'JS'
document.addEventListener('DOMContentLoaded',function(){
 var list=document.getElementById('mono-studio-sort'), order=document.getElementById('mono-studio-order'),drag=null;
 function sync(){if(order&&list)order.value=Array.from(list.children).map(function(x){return x.dataset.slug}).join(',')}
 if(list){list.addEventListener('dragstart',function(e){drag=e.target.closest('.mono-studio-sort-row');if(drag){drag.classList.add('mono-dragging');e.dataTransfer.effectAllowed='move'}});
 list.addEventListener('dragover',function(e){e.preventDefault();var target=e.target.closest('.mono-studio-sort-row');if(!target||!drag||target===drag)return;var r=target.getBoundingClientRect();list.insertBefore(drag,e.clientY<r.top+r.height/2?target:target.nextSibling)});
 list.addEventListener('dragend',function(){if(drag)drag.classList.remove('mono-dragging');drag=null;sync()});
 list.addEventListener('click',function(e){var b=e.target.closest('.mono-studio-up,.mono-studio-down');if(!b)return;var row=b.closest('.mono-studio-sort-row');if(b.classList.contains('mono-studio-up')&&row.previousElementSibling)list.insertBefore(row,row.previousElementSibling);if(b.classList.contains('mono-studio-down')&&row.nextElementSibling)list.insertBefore(row.nextElementSibling,row);sync()})}
 document.addEventListener('click',function(e){var choose=e.target.closest('.mono-studio-choose');if(choose){e.preventDefault();var wrap=choose.closest('.mono-studio-image');var frame=wp.media({title:'Choose MONOLITH image',button:{text:'Use image'},multiple:false});frame.on('select',function(){var a=frame.state().get('selection').first().toJSON();wrap.querySelector('input').value=a.id;wrap.querySelector('.mono-studio-image-preview').innerHTML='<img src="'+(a.sizes&&a.sizes.thumbnail?a.sizes.thumbnail.url:a.url)+'" alt="">'});frame.open()}
 var reset=e.target.closest('.mono-studio-reset');if(reset){e.preventDefault();var wrap=reset.closest('.mono-studio-image');wrap.querySelector('input').value='';wrap.querySelector('.mono-studio-image-preview').innerHTML=''}}
 );
 document.querySelectorAll('.mono-studio-tabs a[href^="#"]').forEach(function(a){a.addEventListener('click',function(){document.querySelectorAll('.mono-studio-tabs a').forEach(function(x){x.classList.remove('active')});a.classList.add('active')})});
});
JS;
}
