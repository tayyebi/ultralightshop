<?php
namespace UltralightShop;

spl_autoload_register(function (string $class_name): void {
    $namespace = 'UltralightShop';
    if (strpos($class_name, $namespace) === 0) {
        $class_file = __DIR__ . '/src';
        $class_file .=
            str_replace(
                [$namespace, '\\'],
                ['', DIRECTORY_SEPARATOR],
                $class_name)
                . '.php';
        if (file_exists($class_file)) {
            require_once $class_file;
            return;
        }
    }
});

// Bootstrap the theme
use UltralightShop\Core\Theme;
Theme::getInstance()->init();

use UltralightShop\Core\Router;
use UltralightShop\View\IndexPageView;
use UltralightShop\View\LoginPageView;
use UltralightShop\Admin\Menus;

$router = new Router();
$router->add('', function () {
    $view = new IndexPageView();
    $view->render();
});
$router->add('login', function () {
    $view = new LoginPageView();
    $view->render();
});

// Register the admin menus
(new Menus())->register();

$router->dispatch('');


