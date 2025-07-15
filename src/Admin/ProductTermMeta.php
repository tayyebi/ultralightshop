<?php
namespace UltralightShop\Admin;

class ProductTermMeta
{
    public function register(): void
    {
        add_action('category_edit_form_fields', [$this, 'addProductOnlyField']);
        add_action('post_tag_edit_form_fields', [$this, 'addProductOnlyField']);
        add_action('category_add_form_fields', [$this, 'addProductOnlyField']);
        add_action('post_tag_add_form_fields', [$this, 'addProductOnlyField']);
        add_action('edited_category', [$this, 'saveProductOnlyField']);
        add_action('created_category', [$this, 'saveProductOnlyField']);
        add_action('edited_post_tag', [$this, 'saveProductOnlyField']);
        add_action('created_post_tag', [$this, 'saveProductOnlyField']);
    }

    public function addProductOnlyField($term): void
    {
        $value = get_term_meta($term->term_id, 'product_only', true);
        echo '<tr class="form-field"><th scope="row"><label for="product_only">Product Only?</label></th><td><input type="checkbox" name="product_only" id="product_only" value="1" ' . checked($value, 1, false) . ' /> <span class="description">Show only for products</span></td></tr>';
    }

    public function saveProductOnlyField($term_id): void
    {
        $value = isset($_POST['product_only']) ? 1 : 0;
        update_term_meta($term_id, 'product_only', $value);
    }
}
