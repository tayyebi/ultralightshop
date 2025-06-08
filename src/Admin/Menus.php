namespace UltralightShop\Admin;

class Menus
{
    public function register(): void
    {
        add_action('admin_menu', [$this, 'addMenus']);
    }

    public function addMenus(): void
    {
        add_menu_page(
            'Product Categories',
            'Product Categories',
            'manage_options',
            'edit-tags.php?taxonomy=product_cat&post_type=ultralightshop_product',
            '',
            'dashicons-category',
            25
        );
        add_menu_page(
            'Product Tags',
            'Product Tags',
            'manage_options',
            'edit-tags.php?taxonomy=product_tag&post_type=ultralightshop_product',
            '',
            'dashicons-tag',
            26
        );
        add_menu_page(
            'Products',
            'Products',
            'manage_options',
            'edit.php?post_type=ultralightshop_product',
            '',
            'dashicons-products',
            27
        );
        add_menu_page(
            'Goods',
            'Goods',
            'manage_options',
            'edit.php?post_type=ultralightshop_goods',
            '',
            'dashicons-cart',
            28
        );
    }
}
