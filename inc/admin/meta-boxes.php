<?php
// Add product meta box
add_action('add_meta_boxes', function() {
    add_meta_box('product_meta', 'Product Details', function($post) {
        wp_nonce_field('ultralightshop_product_meta', 'ultralightshop_product_meta_nonce');
        $price = get_post_meta($post->ID, 'price', true);
        $sku = get_post_meta($post->ID, 'sku', true);
        echo '<label>Price: </label><input type="text" name="ultralight_product_price" value="'.esc_attr($price).'" /><br />';
        echo '<label>SKU: </label><input type="text" name="ultralight_product_sku" value="'.esc_attr($sku).'" />';
    }, 'product', 'normal', 'high');
});

add_action('save_post', function($post_id) {
    if (!isset($_POST['ultralightshop_product_meta_nonce']) || !wp_verify_nonce($_POST['ultralightshop_product_meta_nonce'], 'ultralightshop_product_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['ultralight_product_price'])) {
        update_post_meta($post_id, 'price', sanitize_text_field($_POST['ultralight_product_price']));
    }
    if (isset($_POST['ultralight_product_sku'])) {
        update_post_meta($post_id, 'sku', sanitize_text_field($_POST['ultralight_product_sku']));
    }
});
