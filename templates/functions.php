<?php
// File: functions.php


// Load Simple Importer from the theme
require_once get_template_directory() . '/inc/simple-importer.php';



function ultralightshop_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_style('ultralightshop-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'ultralightshop_enqueue_scripts');


function ultralightshop_enqueue_lazyload_script() {
    wp_enqueue_script(
        'lazyload-nav',
        get_template_directory_uri() . '/assets/js/lazyload.js',
        array('jquery'),
        null,
        true
    );
    wp_localize_script('lazyload-nav', 'lazyloadnav_settings', array(
        'fade_duration' => 300,
        'container'     => 'main',
        'debug_mode'    => false
    ));
    wp_localize_script('lazyload-nav', 'lazyloadnav_strings', array(
        'loading' => __('Loading', 'ultralightshop')
    ));
}
add_action('wp_enqueue_scripts', 'ultralightshop_enqueue_lazyload_script');


function ultralightshop_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form','comment-form','comment-list','gallery','caption'));
    register_nav_menus(array(
        'top-menu' => __('Top Menu', 'ultralightshop')
    ));
}
add_action('after_setup_theme', 'ultralightshop_setup');

function ultralightshop_register_vendor_cpt() {
    $labels = array(
        'name' => 'Vendors',
        'singular_name' => 'Vendor',
        'add_new' => 'Add New Vendor',
        'add_new_item' => 'Add New Vendor',
        'edit_item' => 'Edit Vendor',
        'new_item' => 'New Vendor',
        'view_item' => 'View Vendor',
        'search_items' => 'Search Vendors',
        'not_found' => 'No Vendors found',
        'not_found_in_trash' => 'No Vendor found in Trash'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'vendors'),
        'supports' => array('title','editor','thumbnail','excerpt'),
        'show_in_rest' => true
    );
    register_post_type('vendor', $args);
}
add_action('init', 'ultralightshop_register_vendor_cpt');

function ultralightshop_vendor_meta_box() {
    add_meta_box('vendor_meta', 'Vendor Details', 'ultralightshop_vendor_meta_callback', 'vendor', 'normal', 'high');
}
add_action('add_meta_boxes', 'ultralightshop_vendor_meta_box');

function ultralightshop_vendor_meta_callback($post) {
    wp_nonce_field('ultralightshop_vendor_meta', 'ultralightshop_vendor_meta_nonce');
    $website = get_post_meta($post->ID, 'website', true);
    $phone = get_post_meta($post->ID, 'phone', true);
    $email = get_post_meta($post->ID, 'email', true);
    echo '<label>Website: </label><input type="text" name="ultralight_vendor_website" value="'.esc_attr($website).'" /><br />';
    echo '<label>Phone: </label><input type="text" name="ultralight_vendor_phone" value="'.esc_attr($phone).'" /><br />';
    echo '<label>E-Mail: </label><input type="text" name="ultralight_vendor_email" value="'.esc_attr($email).'" />';
}

function ultralightshop_save_vendor_meta($post_id) {
    if (!isset($_POST['ultralightshop_vendor_meta_nonce']) || !wp_verify_nonce($_POST['ultralightshop_vendor_meta_nonce'], 'ultralightshop_vendor_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['ultralight_vendor_website'])) {
        update_post_meta($post_id, 'website', sanitize_text_field($_POST['ultralight_vendor_website']));
    }
    if (isset($_POST['ultralight_vendor_phone'])) {
        update_post_meta($post_id, 'phone', sanitize_text_field($_POST['ultralight_vendor_phone']));
    }
    if (isset($_POST['ultralight_vendor_email'])) {
        update_post_meta($post_id, 'email', sanitize_text_field($_POST['ultralight_vendor_email']));
    }
}
add_action('save_post', 'ultralightshop_save_vendor_meta');


function ultralightshop_register_product_cpt() {
    $labels = array(
        'name' => 'Products',
        'singular_name' => 'Product',
        'add_new' => 'Add New Product',
        'add_new_item' => 'Add New Product',
        'edit_item' => 'Edit Product',
        'new_item' => 'New Product',
        'view_item' => 'View Product',
        'search_items' => 'Search Products',
        'not_found' => 'No Products found',
        'not_found_in_trash' => 'No Products found in Trash'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'products'),
        'supports' => array('title','editor','thumbnail','excerpt'),
        'show_in_rest' => true
    );
    register_post_type('product', $args);
}
add_action('init', 'ultralightshop_register_product_cpt');

function ultralightshop_product_meta_box() {
    add_meta_box('product_meta', 'Product Details', 'ultralightshop_product_meta_callback', 'product', 'normal', 'high');
}
add_action('add_meta_boxes', 'ultralightshop_product_meta_box');

function ultralightshop_product_meta_callback($post) {
    wp_nonce_field('ultralightshop_product_meta', 'ultralightshop_product_meta_nonce');
    $price = get_post_meta($post->ID, 'price', true);
    $sku = get_post_meta($post->ID, 'sku', true);
    echo '<label>Price: </label><input type="text" name="ultralight_product_price" value="'.esc_attr($price).'" /><br />';
    echo '<label>SKU: </label><input type="text" name="ultralight_product_sku" value="'.esc_attr($sku).'" />';
}

function ultralightshop_save_product_meta($post_id) {
    if (!isset($_POST['ultralightshop_product_meta_nonce']) || !wp_verify_nonce($_POST['ultralightshop_product_meta_nonce'], 'ultralightshop_product_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['ultralight_product_price'])) {
        update_post_meta($post_id, 'price', sanitize_text_field($_POST['ultralight_product_price']));
    }
    if (isset($_POST['ultralight_product_sku'])) {
        update_post_meta($post_id, 'sku', sanitize_text_field($_POST['ultralight_product_sku']));
    }
}
add_action('save_post', 'ultralightshop_save_product_meta');


function ultralightshop_register_order_cpt() {
    $labels = array(
        'name' => 'Orders',
        'singular_name' => 'Order',
        'add_new' => 'Add New Order',
        'add_new_item' => 'Add New Order',
        'edit_item' => 'Edit Order',
        'new_item' => 'New Order',
        'view_item' => 'View Order',
        'search_items' => 'Search Orders',
        'not_found' => 'No Orders found',
        'not_found_in_trash' => 'No Orders found in Trash'
    );
    $args = array(
        'labels' => $labels,
        'public' => false,
        'has_archive' => false,
        'rewrite' => false,
        'supports' => array('title','editor'),
        'show_in_rest' => true
    );
    register_post_type('order', $args);
}
add_action('init', 'ultralightshop_register_order_cpt');

function ultralightshop_add_admin_menu() {
    add_menu_page('Theme Settings', 'Theme Settings', 'manage_options', 'ultralightshop-settings', 'ultralightshop_settings_page', 'dashicons-admin-generic');
    add_submenu_page('ultralightshop-settings', 'Business Info', 'Business Info', 'manage_options', 'ultralightshop-business', 'ultralightshop_business_page');
    add_submenu_page('ultralightshop-settings', 'Menu Settings', 'Menu Settings', 'manage_options', 'nav-menus.php', '');
}
add_action('admin_menu', 'ultralightshop_add_admin_menu');

function ultralightshop_settings_page() {
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

function ultralightshop_business_page() {
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

function ultralightshop_register_settings() {
    register_setting('ultralightshop_settings_group', 'ultralightshop_seo');
    add_settings_section('ultralightshop_seo_section', 'SEO Settings', '', 'ultralightshop-settings');
    add_settings_field('ultralightshop_seo_field', 'SEO Meta', 'ultralightshop_seo_field_callback', 'ultralightshop-settings', 'ultralightshop_seo_section');
    register_setting('ultralightshop_business_group', 'ultralightshop_business');
    add_settings_section('ultralightshop_business_section', 'Business Info', '', 'ultralightshop-business');
    add_settings_field('ultralightshop_business_field', 'Business Name and Address', 'ultralightshop_business_field_callback', 'ultralightshop-business', 'ultralightshop_business_section');
}
function ultralightshop_seo_field_callback() {
    $seo = get_option('ultralightshop_seo', '');
    echo '<input type="text" name="ultralightshop_seo" value="'.esc_attr($seo).'" />';
}
function ultralightshop_business_field_callback() {
    $business = get_option('ultralightshop_business', '');
    echo '<input type="text" name="ultralightshop_business" value="'.esc_attr($business).'" />';
}
add_action('admin_init', 'ultralightshop_register_settings');

function ultralightshop_user_orders_shortcode() {
    if (!is_user_logged_in()) return 'Login required';
    $current_user = wp_get_current_user();
    $args = array(
        'post_type' => 'order',
        'author' => $current_user->ID,
        'posts_per_page' => -1
    );
    $orders = new WP_Query($args);
    $output = '<main><h2>My Orders</h2>';
    if($orders->have_posts()){
        while($orders->have_posts()){
            $orders->the_post();
            $output .= '<article><h3>'.get_the_title().'</h3><div>'.get_the_content().'</div></article>';
        }
        wp_reset_postdata();
    } else {
        $output .= '<p>No orders found</p>';
    }
    $output .= '</main>';
    return $output;
}
add_shortcode('ultralightshop_orders', 'ultralightshop_user_orders_shortcode');

function ultralightshop_pre_get_posts($query) {
    if(!is_admin() && $query->is_main_query() && $query->is_search()){
        if(isset($_GET['price_min']) || isset($_GET['price_max'])){
            $meta_query = array();
            if(!empty($_GET['price_min'])){
                $meta_query[] = array('key'=>'price','value'=>$_GET['price_min'],'type'=>'NUMERIC','compare'=>'>=');
            }
            if(!empty($_GET['price_max'])){
                $meta_query[] = array('key'=>'price','value'=>$_GET['price_max'],'type'=>'NUMERIC','compare'=>'<=');
            }
            if($meta_query){
                $query->set('meta_query', $meta_query);
            }
        }
    }
}
add_action('pre_get_posts', 'ultralightshop_pre_get_posts');

function ultralightshop_orderby_post_type($orderby) {
    global $wpdb;
    if(is_home() || is_archive() || is_search()){
        $orderby = "FIELD({$wpdb->posts}.post_type, 'product', 'post'), {$wpdb->posts}.post_date DESC";
    }
    return $orderby;
}
add_filter('posts_orderby', 'ultralightshop_orderby_post_type');

function ultralightshop_enqueue_search_slider() {
    if (is_search() || is_archive()) {
        wp_enqueue_script('ultralightshop-slider', get_template_directory_uri() . '/assets/js/search-slider.js', array('jquery','jquery-ui-slider'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'ultralightshop_enqueue_search_slider');

// Create a custom "customer" role on theme activation
function ultralightshop_add_customer_role() {
    add_role('customer', __('Customer', 'ultralightshop'), array(
        'read'       => true,
        'level_0'    => true,
    ));
}
add_action('after_switch_theme', 'ultralightshop_add_customer_role');

// Shortcode to output a login form
function ultralightshop_login_form_shortcode() {
    if ( is_user_logged_in() ) {
        return __('You are already logged in.', 'ultralightshop');
    }
    $args = array(
        'redirect'       => home_url('/user-panel'), // redirect after login
        'form_id'        => 'loginform-custom',
        'label_username' => __('Username', 'ultralightshop'),
        'label_password' => __('Password', 'ultralightshop'),
        'label_remember' => __('Remember Me', 'ultralightshop'),
        'label_log_in'   => __('Log In', 'ultralightshop'),
        'remember'       => true,
    );
    return wp_login_form($args);
}
add_shortcode('ultralightshop_login', 'ultralightshop_login_form_shortcode');

// Shortcode to output a registration form with error handling
function ultralightshop_registration_form_shortcode() {
    if ( is_user_logged_in() ) {
        return __('You are already registered and logged in.', 'ultralightshop');
    }
    ob_start();
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ultralightshop_register_nonce'])) {
        if (!wp_verify_nonce($_POST['ultralightshop_register_nonce'], 'ultralightshop_register')) {
            echo '<p>' . __('Nonce verification failed', 'ultralightshop') . '</p>';
        } else {
            $username = sanitize_user($_POST['username']);
            $email    = sanitize_email($_POST['email']);
            $password = $_POST['password'];
            $errors   = new WP_Error();

            if (empty($username) || empty($password) || empty($email)) {
                $errors->add('field', __('Required form field is missing', 'ultralightshop'));
            }
            if (!is_email($email)) {
                $errors->add('email_invalid', __('Email is not valid', 'ultralightshop'));
            }
            if (username_exists($username)) {
                $errors->add('username_exists', __('Username already exists', 'ultralightshop'));
            }
            if (email_exists($email)) {
                $errors->add('email_exists', __('Email already registered', 'ultralightshop'));
            }

            if (empty($errors->errors)) {
                $userdata = array(
                    'user_login' => $username,
                    'user_pass'  => $password,
                    'user_email' => $email,
                    'role'       => 'customer',
                );
                $user_id = wp_insert_user($userdata);
                if (!is_wp_error($user_id)) {
                    echo '<p>' . __('Registration complete. Please log in.', 'ultralightshop') . '</p>';
                } else {
                    echo '<p>' . __('Error occurred: ', 'ultralightshop') . $user_id->get_error_message() . '</p>';
                }
            } else {
                foreach ($errors->get_error_messages() as $error) {
                    echo '<p>' . $error . '</p>';
                }
            }
        }
    }
    ?>
    <form method="post">
        <p>
            <label for="username"><?php _e('Username', 'ultralightshop'); ?></label>
            <input name="username" id="username" type="text" required>
        </p>
        <p>
            <label for="email"><?php _e('Email', 'ultralightshop'); ?></label>
            <input name="email" id="email" type="email" required>
        </p>
        <p>
            <label for="password"><?php _e('Password', 'ultralightshop'); ?></label>
            <input name="password" id="password" type="password" required>
        </p>
        <?php wp_nonce_field('ultralightshop_register', 'ultralightshop_register_nonce'); ?>
        <p>
            <input type="submit" value="<?php _e('Register', 'ultralightshop'); ?>">
        </p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('ultralightshop_register', 'ultralightshop_registration_form_shortcode');

// Redirect to the login page if a customer-only page (user panel) is accessed by a non-logged-in user
function ultralightshop_redirect_if_not_logged_in() {
    if (is_page('user-panel') && !is_user_logged_in()) { // assumes page slug is "user-panel"
        wp_redirect(home_url('/login'));
        exit;
    }
}
add_action('template_redirect', 'ultralightshop_redirect_if_not_logged_in');


