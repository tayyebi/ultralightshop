<?php
// Product custom post type
add_action('init', function() {
    $labels = [
        'name' => 'Products',
        'singular_name' => 'Product',
        'add_new' => 'Add New Product',
        'add_new_item' => 'Add New Product',
        'edit_item' => 'Edit Product',
        'new_item' => 'New Product',
        'view_item' => 'View Product',
        'search_items' => 'Search Products',
        'not_found' => 'No Products found',
        'not_found_in_trash' => 'No Products found in Trash'
    ];
    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'products'],
        'supports' => ['title','editor','thumbnail','excerpt'],
        'show_in_rest' => true
    ];
    register_post_type('product', $args);
});

// Order custom post type
add_action('init', function() {
    $labels = [
        'name' => 'Orders',
        'singular_name' => 'Order',
        'add_new' => 'Add New Order',
        'add_new_item' => 'Add New Order',
        'edit_item' => 'Edit Order',
        'new_item' => 'New Order',
        'view_item' => 'View Order',
        'search_items' => 'Search Orders',
        'not_found' => 'No Orders found',
        'not_found_in_trash' => 'No Orders found in Trash'
    ];
    $args = [
        'labels' => $labels,
        'public' => false,
        'has_archive' => false,
        'rewrite' => false,
        'supports' => ['title','editor'],
        'show_in_rest' => true
    ];
    register_post_type('order', $args);
});
