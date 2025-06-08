<?php
// Add product-only field to categories and tags
$add_field = function($term) {
    $value = get_term_meta($term->term_id, 'ultralightshop_product_only', true);
    echo '<tr class="form-field"><th scope="row"><label for="ultralightshop_product_only">Product Only?</label></th><td><input type="checkbox" name="ultralightshop_product_only" id="ultralightshop_product_only" value="1" ' . checked($value, 1, false) . ' /> <span class="description">Show only for products</span></td></tr>';
};
add_action('category_edit_form_fields', $add_field);
add_action('post_tag_edit_form_fields', $add_field);
add_action('category_add_form_fields', $add_field);
add_action('post_tag_add_form_fields', $add_field);

$save_field = function($term_id) {
    $value = isset($_POST['ultralightshop_product_only']) ? 1 : 0;
    update_term_meta($term_id, 'ultralightshop_product_only', $value);
};
add_action('edited_category', $save_field);
add_action('created_category', $save_field);
add_action('edited_post_tag', $save_field);
add_action('created_post_tag', $save_field);
