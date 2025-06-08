<?php
$is_lazyload = isset($_SERVER['HTTP_X_LAZYLOAD_NAV']) && $_SERVER['HTTP_X_LAZYLOAD_NAV'] === '1';
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}
if (!$is_lazyload) get_header();
?>
<main>
    <h2>My Orders</h2>
    <?php echo do_shortcode('[ultralightshop_orders]'); ?>
</main>
<?php
if (!$is_lazyload) get_footer();
?>
