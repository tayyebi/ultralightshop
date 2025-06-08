<?php
// Redirect to login if user-panel is accessed by non-logged-in user
add_action('template_redirect', function() {
    if (is_page('user-panel') && !is_user_logged_in()) {
        wp_redirect(home_url('/login'));
        exit;
    }
});

// Create a custom "customer" role on theme activation
add_action('after_switch_theme', function() {
    add_role('customer', __('Customer', 'ultralightshop'), [
        'read'    => true,
        'level_0' => true,
    ]);
});
