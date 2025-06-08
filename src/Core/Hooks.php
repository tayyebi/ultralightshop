<?php
namespace UltralightShop\Core;

class Hooks
{
    public function register(): void
    {
        add_action('template_redirect', [$this, 'redirectIfNotLoggedIn']);
        add_action('after_switch_theme', [$this, 'addCustomerRole']);
    }

    public function redirectIfNotLoggedIn(): void
    {
        if (is_page('user-panel') && !is_user_logged_in()) {
            wp_redirect(home_url('/login'));
            exit;
        }
    }

    public function addCustomerRole(): void
    {
        add_role('customer', __('Customer', 'ultralightshop'), [
            'read'    => true,
            'level_0' => true,
        ]);
    }
}
