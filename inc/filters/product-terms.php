<?php
// Filter categories/tags for products to only show product-only terms
add_filter('get_terms', function($terms, $taxonomies, $args, $term_query) {
    if (in_array('category', $taxonomies) || in_array('post_tag', $taxonomies)) {
        $terms = array_filter($terms, function($term) {
            return get_term_meta($term->term_id, 'ultralightshop_product_only', true);
        });
    }
    return $terms;
}, 10, 4);
