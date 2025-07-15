<?php
namespace UltralightShop\Admin;

class MetaBoxes
{
    public function register(): void
    {
        add_action('add_meta_boxes', [$this, 'addProductMetaBox']);
        add_action('save_post', [$this, 'saveProductMeta']);
    }

    public function addProductMetaBox(): void
    {
        add_meta_box('product_meta', 'Product Details', [$this, 'productMetaCallback'], 'product', 'normal', 'high');
    }

    public function productMetaCallback($post): void
    {
        wp_nonce_field('product_meta', 'product_meta_nonce');
        $price = get_post_meta($post->ID, 'price', true);
        $sku = get_post_meta($post->ID, 'sku', true);
        echo '<label>Price: </label><input type="text" name="ultralight_product_price" value="'.esc_attr($price).'" /><br />';
        echo '<label>SKU: </label><input type="text" name="ultralight_product_sku" value="'.esc_attr($sku).'" />';
    }

    public function saveProductMeta($post_id): void
    {
        if (!isset($_POST['product_meta_nonce']) || !wp_verify_nonce($_POST['product_meta_nonce'], 'product_meta')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (isset($_POST['ultralight_product_price'])) {
            update_post_meta($post_id, 'price', sanitize_text_field($_POST['ultralight_product_price']));
        }
        if (isset($_POST['ultralight_product_sku'])) {
            update_post_meta($post_id, 'sku', sanitize_text_field($_POST['ultralight_product_sku']));
        }
    }
}
