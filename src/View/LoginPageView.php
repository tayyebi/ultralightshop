<?php
namespace UltralightShop\View;

use UltralightShop\Core\View;
use UltralightShop\Shortcodes\User;

class LoginPageView extends View
{
    private $shortcodes;

    public function __construct($shortcodes = null)
    {
        if ($shortcodes === null) {
            $shortcodes = new \UltralightShop\Shortcodes\User();
        }
        $this->shortcodes = $shortcodes;
        parent::__construct();
    }

    public function render(): void
    {
        parent::render(function () {
            echo '<main>';
            echo '<h2>Login</h2>';
            echo $this->shortcodes->renderLoginForm();
            echo '<p>' . __( 'Not registered yet?', 'ultralightshop' ) . ' <a href="' . home_url('/register') . '">' . __( 'Register Here', 'ultralightshop' ) . '</a></p>';
            echo '</main>';
        });
    }
}
