<?php
// Shortcodes for login, register, and user orders
add_shortcode('ultralightshop_login', function() {
    if ( is_user_logged_in() ) {
        return __('You are already logged in.', 'ultralightshop');
    }
    $args = [
        'redirect'       => home_url('/user-panel'),
        'form_id'        => 'loginform-custom',
        'label_username' => __('Username', 'ultralightshop'),
        'label_password' => __('Password', 'ultralightshop'),
        'label_remember' => __('Remember Me', 'ultralightshop'),
        'label_log_in'   => __('Log In', 'ultralightshop'),
        'remember'       => true,
    ];
    return wp_login_form($args);
});

add_shortcode('ultralightshop_register', function() {
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
                $userdata = [
                    'user_login' => $username,
                    'user_pass'  => $password,
                    'user_email' => $email,
                    'role'       => 'customer',
                ];
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
});

add_shortcode('ultralightshop_orders', function() {
    if (!is_user_logged_in()) return 'Login required';
    $current_user = wp_get_current_user();
    $args = [
        'post_type' => 'order',
        'author' => $current_user->ID,
        'posts_per_page' => -1
    ];
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
});
