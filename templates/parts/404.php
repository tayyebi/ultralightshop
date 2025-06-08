<?php
$is_lazyload = isset($_SERVER['HTTP_X_LAZYLOAD_NAV']) && $_SERVER['HTTP_X_LAZYLOAD_NAV'] === '1';
if (!$is_lazyload) get_header();
?>
<main>
    <section class="error-404 not-found">
        <h2><?php _e('404 - Not Found', 'ultralightshop'); ?></h2>
        <p><?php _e('Sorry, the page you are looking for does not exist.', 'ultralightshop'); ?></p>
        <a href="<?php echo home_url(); ?>"><?php _e('Return Home', 'ultralightshop'); ?></a>
    </section>
</main>
<?php
if (!$is_lazyload) get_footer();
?>
