<?php
// Template part: page-user-panel
/* Template Name: User Panel */
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}
get_header();
?>
<main>
    <h2>My Orders</h2>
    <?php echo do_shortcode('[ultralightshop_orders]'); ?>
</main>
<?php
get_footer();
?>
