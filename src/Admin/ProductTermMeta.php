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
        $value = get_term_meta($term->term_id, 'ultralightshop_product_only', true);
        echo '<tr class="form-field"><th scope="row"><label for="ultralightshop_product_only">Product Only?</label></th><td><input type="checkbox" name="ultralightshop_product_only" id="ultralightshop_product_only" value="1" ' . checked($value, 1, false) . ' /> <span class="description">Show only for products</span></td></tr>';
    }

    public function saveProductOnlyField($term_id): void
    {
        $value = isset($_POST['ultralightshop_product_only']) ? 1 : 0;
        update_term_meta($term_id, 'ultralightshop_product_only', $value);
    }
}
