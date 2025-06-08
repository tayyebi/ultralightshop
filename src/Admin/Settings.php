<?php
namespace UltralightShop\Admin;

class Settings
{
    public function register(): void
    {
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addAdminMenu(): void
    {
        add_menu_page('Theme Settings', 'Theme Settings', 'manage_options', 'ultralightshop-settings', [$this, 'settingsPage'], 'dashicons-admin-generic');
        add_submenu_page('ultralightshop-settings', 'Business Info', 'Business Info', 'manage_options', 'ultralightshop-business', [$this, 'businessPage']);
        add_submenu_page('ultralightshop-settings', 'Menu Settings', 'Menu Settings', 'manage_options', 'nav-menus.php', '');
    }

    public function settingsPage(): void
    {
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
    }

    public function businessPage(): void
    {
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
    }

    public function registerSettings(): void
    {
        register_setting('ultralightshop_settings_group', 'ultralightshop_seo');
        add_settings_section('ultralightshop_seo_section', 'SEO Settings', '', 'ultralightshop-settings');
        add_settings_field('ultralightshop_seo_field', 'SEO Meta', [$this, 'seoFieldCallback'], 'ultralightshop-settings', 'ultralightshop_seo_section');
        register_setting('ultralightshop_business_group', 'ultralightshop_business');
        add_settings_section('ultralightshop_business_section', 'Business Info', '', 'ultralightshop-business');
        add_settings_field('ultralightshop_business_field', 'Business Name and Address', [$this, 'businessFieldCallback'], 'ultralightshop-business', 'ultralightshop_business_section');
    }

    public function seoFieldCallback(): void
    {
        $seo = get_option('ultralightshop_seo', '');
        echo '<input type="text" name="ultralightshop_seo" value="'.esc_attr($seo).'" />';
    }

    public function businessFieldCallback(): void
    {
        $business = get_option('ultralightshop_business', '');
        echo '<input type="text" name="ultralightshop_business" value="'.esc_attr($business).'" />';
    }
}
