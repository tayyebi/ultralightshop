<?php
// Template part: page-login
/* Template Name: Login Page */
get_header();
?>
<main>
    <h2>Login</h2>
    <?php echo do_shortcode('[ultralightshop_login]'); ?>
    <p><?php _e('Not registered yet?', 'ultralightshop'); ?> <a href="<?php echo home_url('/register'); ?>"><?php _e('Register Here', 'ultralightshop'); ?></a></p>
</main>
<?php
get_footer();
?>
