<?php
// Enqueue scripts and styles
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_style('ultralightshop-style', get_stylesheet_uri());
});

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script(
        'lazyload-nav',
        get_template_directory_uri() . '/assets/js/lazyload.js',
        ['jquery'],
        null,
        true
    );
    wp_localize_script('lazyload-nav', 'lazyloadnav_settings', [
        'fade_duration' => 300,
        'container'     => 'main',
        'debug_mode'    => false
    ]);
    wp_localize_script('lazyload-nav', 'lazyloadnav_strings', [
        'loading' => __('Loading', 'ultralightshop')
    ]);
});

add_action('wp_enqueue_scripts', function() {
    if (is_search() || is_archive()) {
        wp_enqueue_script('ultralightshop-slider', get_template_directory_uri() . '/assets/js/search-slider.js', ['jquery','jquery-ui-slider'], null, true);
    }
});
