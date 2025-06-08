<?php
$is_lazyload = isset($_SERVER['HTTP_X_LAZYLOAD_NAV']) && $_SERVER['HTTP_X_LAZYLOAD_NAV'] === '1';
if (!$is_lazyload) get_header();
?>
<main>
    <h2>Register</h2>
    <?php echo do_shortcode('[ultralightshop_register]'); ?>
    <p><?php _e('Already registered?', 'ultralightshop'); ?> <a href="<?php echo home_url('/login'); ?>"><?php _e('Login Here', 'ultralightshop'); ?></a></p>
</main>
<?php
if (!$is_lazyload) get_footer();
?>
