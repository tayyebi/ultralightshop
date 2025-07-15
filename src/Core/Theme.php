<?php
namespace UltralightShop\Core;

use UltralightShop\Core\Setup;
use UltralightShop\Core\Enqueue;
use UltralightShop\Core\Hooks;
use UltralightShop\Admin\Settings;
use UltralightShop\Admin\MetaBoxes;
use UltralightShop\Admin\ProductTermMeta;
use UltralightShop\CustomPostTypes\Product;
use UltralightShop\DB\Tables;
use UltralightShop\Filters\ProductTerms;
use UltralightShop\Filters\Query;
use UltralightShop\Shortcodes\User;

class Theme
{
    private static $instance;

    public static function getInstance(): self
    {
        if (! self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void
    {
        (new Setup())->register();
        (new Enqueue())->register();
        (new Hooks())->register();
        (new Settings())->register();
        (new MetaBoxes())->register();
        (new ProductTermMeta())->register();
        (new Product())->register();
        (new Tables())->register();
        (new ProductTerms())->register();
        (new Query())->register();
        (new User())->register();
    }
}

