<?php
namespace UltralightShop\Admin;

use WP_Term;

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
            'edit-tags.php?taxonomy=product_cat&post_type=product',
            '',
            'dashicons-category',
            25
        );
        add_menu_page(
            'Product Tags',
            'Product Tags',
            'manage_options',
            'edit-tags.php?taxonomy=product_tag&post_type=product',
            '',
            'dashicons-tag',
            26
        );
        add_menu_page(
            'Products',
            'Products',
            'manage_options',
            'edit.php?post_type=product',
            '',
            'dashicons-products',
            27
        );
        add_menu_page(
            'Goods',
            'Goods',
            'manage_options',
            'edit.php?post_type=goods',
            '',
            'dashicons-cart',
            28
        );
        add_submenu_page(
            'edit-tags.php?taxonomy=product_cat&post_type=product',
            'Category Properties',
            'Category Properties',
            'manage_options',
            'category_properties',
            [$this, 'renderCategoryPropertiesPage']
        );
    }

    public function renderCategoryPropertiesPage(): void
    {
        $taxonomy = 'product_cat';
        $categories = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false
        ]);
        $option_name = 'category_properties';
        if ($this->isFormSubmitted()) {
            $this->saveCategoryProperties($categories, $option_name);
        }
        $properties = get_option($option_name, []);
        $this->renderCategoryPropertiesForm($categories, $properties);
    }

    private function isFormSubmitted(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['category_properties_nonce'])
            && wp_verify_nonce($_POST['category_properties_nonce'], 'save_category_properties');
    }

    private function saveCategoryProperties(array $categories, string $option_name): void
    {
        $properties = [];
        foreach ($categories as $cat) {
            $cat_id = $cat->term_id;
            $props = [];
            if (!empty($_POST['properties'][$cat_id])) {
                $names = $_POST['properties'][$cat_id]['name'] ?? [];
                $types = $_POST['properties'][$cat_id]['type'] ?? [];
                foreach ($names as $i => $name) {
                    $name = sanitize_text_field($name);
                    $type = sanitize_text_field($types[$i] ?? 'text');
                    if ($name) {
                        $props[] = [
                            'name' => $name,
                            'type' => $type
                        ];
                    }
                }
            }
            $properties[$cat_id] = $props;
        }
        update_option($option_name, $properties);
        echo '<div class="updated"><p>Saved!</p></div>';
    }

    private function renderCategoryPropertiesForm(array $categories, array $properties): void
    {
        echo '<div class="wrap"><h1>Category Properties</h1>';
        echo '<form method="post">';
        wp_nonce_field('save_category_properties', 'category_properties_nonce');
        echo '<table class="widefat"><thead><tr><th>Category</th><th>Properties</th></tr></thead><tbody>';
        foreach ($categories as $cat) {
            $this->renderCategoryRow($cat, $properties[$cat->term_id] ?? []);
        }
        echo '</tbody></table>';
        echo '<p><input type="submit" class="button button-primary" value="Save Properties"></p>';
        echo '</form>';
        $this->renderCategoryPropertiesScript();
        echo '</div>';
    }

    private function renderCategoryRow(WP_Term $cat, array $cat_props): void
    {
        $cat_id = $cat->term_id;
        $types = ['text'=>'Text','number'=>'Number','select'=>'Select','checkbox'=>'Checkbox'];
        echo '<tr><td><strong>' . esc_html($cat->name) . '</strong></td><td>';
        echo '<div class="ultralightshop-props-list">';
        foreach ($cat_props as $prop) {
            $this->renderPropertyRow($cat_id, $prop['name'], $prop['type'], $types);
        }
        $this->renderPropertyRow($cat_id, '', '', $types); // Empty row for new property
        echo '</div>';
        echo '<button type="button" class="button add-prop" data-cat="'.$cat_id.'">Add Property</button>';
        echo '</td></tr>';
    }

    private function renderPropertyRow(int $cat_id, string $name, string $type, array $types): void
    {
        echo '<div style="margin-bottom:4px;">';
        echo '<input type="text" name="properties['.$cat_id.'][name][]" value="'.esc_attr($name).'" placeholder="Property Name" style="width:180px;" /> ';
        echo '<select name="properties['.$cat_id.'][type][]">';
        foreach ($types as $type_key => $type_label) {
            $selected = ($type === $type_key) ? 'selected' : '';
            echo '<option value="'.$type_key.'" '.$selected.'>'.$type_label.'</option>';
        }
        echo '</select> ';
        echo '<button type="button" class="button remove-prop">Remove</button>';
        echo '</div>';
    }

    private function renderCategoryPropertiesScript(): void
    {
        ?>
        <script>
        document.querySelectorAll(".add-prop").forEach(function(btn){
            btn.addEventListener("click",function(e){
                e.preventDefault();
                var cat = this.getAttribute("data-cat");
                var list = this.parentNode.querySelector(".ultralightshop-props-list");
                var html = `<div style=\"margin-bottom:4px;\"><input type=\"text\" name=\"properties[${cat}][name][]\" placeholder=\"Property Name\" style=\"width:180px;\" /> <select name=\"properties[${cat}][type][]\"><option value=\"text\">Text</option><option value=\"number\">Number</option><option value=\"select\">Select</option><option value=\"checkbox\">Checkbox</option></select> <button type=\"button\" class=\"button remove-prop\">Remove</button></div>`;
                list.insertAdjacentHTML("beforeend", html);
            });
        });
        document.querySelectorAll(".ultralightshop-props-list").forEach(function(list){
            list.addEventListener("click",function(e){
                if(e.target.classList.contains("remove-prop")){
                    e.preventDefault();
                    e.target.parentNode.remove();
                }
            });
        });
        </script>
        <?php
    }
}
