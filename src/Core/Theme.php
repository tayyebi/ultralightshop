<?php
namespace UltralightShop\Core;

class Theme
{
    private static $instance;

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void
    {
        (new Setup())->register();
        (new Enqueue())->register();
        (new Hooks())->register();
        (new Admin\Settings())->register();
        (new Admin\MetaBoxes())->register();
        (new Admin\ProductTermMeta())->register();
        (new CustomPostTypes\Product())->register();
        (new DB\Tables())->register();
        (new Filters\ProductTerms())->register();
        (new Filters\Query())->register();
        (new Shortcodes\User())->register();
    }
}
