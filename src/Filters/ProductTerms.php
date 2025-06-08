<?php
namespace UltralightShop\Filters;

class ProductTerms
{
    public function register(): void
    {
        add_filter('get_terms', [$this, 'filterTermsForProducts'], 10, 4);
    }

    public function filterTermsForProducts($terms, $taxonomies, $args, $term_query)
    {
        if (in_array('category', $taxonomies) || in_array('post_tag', $taxonomies)) {
            $terms = array_filter($terms, function($term) {
                return get_term_meta($term->term_id, 'ultralightshop_product_only', true);
            });
        }
        return $terms;
    }
}
