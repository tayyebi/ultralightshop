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
                <?php settings_fields('settings_group'); ?>
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
                <?php settings_fields('business_group'); ?>
                <?php do_settings_sections('ultralightshop-business'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function registerSettings(): void
    {
        register_setting('settings_group', 'seo');
        add_settings_section('seo_section', 'SEO Settings', '', 'ultralightshop-settings');
        add_settings_field('seo_field', 'SEO Meta', [$this, 'seoFieldCallback'], 'ultralightshop-settings', 'seo_section');
        register_setting('business_group', 'business');
        add_settings_section('business_section', 'Business Info', '', 'ultralightshop-business');
        add_settings_field('business_field', 'Business Name and Address', [$this, 'businessFieldCallback'], 'ultralightshop-business', 'business_section');
    }

    public function seoFieldCallback(): void
    {
        $seo = get_option('seo', '');
        echo '<input type="text" name="seo" value="'.esc_attr($seo).'" />';
    }

    public function businessFieldCallback(): void
    {
        $business = get_option('business', '');
        echo '<input type="text" name="business" value="'.esc_attr($business).'" />';
    }
}
