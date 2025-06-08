<?php
// Admin settings pages for SEO and business info
add_action('admin_menu', function() {
    add_menu_page('Theme Settings', 'Theme Settings', 'manage_options', 'ultralightshop-settings', function() {
        ?>
        <div class="wrap">
            <h1>Theme SEO and Business Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('ultralightshop_settings_group'); ?>
                <?php do_settings_sections('ultralightshop-settings'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }, 'dashicons-admin-generic');
    add_submenu_page('ultralightshop-settings', 'Business Info', 'Business Info', 'manage_options', 'ultralightshop-business', function() {
        ?>
        <div class="wrap">
            <h1>Business Information</h1>
            <form method="post" action="options.php">
                <?php settings_fields('ultralightshop_business_group'); ?>
                <?php do_settings_sections('ultralightshop-business'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    });
    add_submenu_page('ultralightshop-settings', 'Menu Settings', 'Menu Settings', 'manage_options', 'nav-menus.php', '');
});

add_action('admin_init', function() {
    register_setting('ultralightshop_settings_group', 'ultralightshop_seo');
    add_settings_section('ultralightshop_seo_section', 'SEO Settings', '', 'ultralightshop-settings');
    add_settings_field('ultralightshop_seo_field', 'SEO Meta', function() {
        $seo = get_option('ultralightshop_seo', '');
        echo '<input type="text" name="ultralightshop_seo" value="'.esc_attr($seo).'" />';
    }, 'ultralightshop-settings', 'ultralightshop_seo_section');
    register_setting('ultralightshop_business_group', 'ultralightshop_business');
    add_settings_section('ultralightshop_business_section', 'Business Info', '', 'ultralightshop-business');
    add_settings_field('ultralightshop_business_field', 'Business Name and Address', function() {
        $business = get_option('ultralightshop_business', '');
        echo '<input type="text" name="ultralightshop_business" value="'.esc_attr($business).'" />';
    }, 'ultralightshop-business', 'ultralightshop_business_section');
});
