<?php
// Theme setup, hooks, helpers
add_action('after_setup_theme', function() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption']);
    register_nav_menus([
        'top-menu' => __('Top Menu', 'ultralightshop')
    ]);
});
