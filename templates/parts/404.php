<?php
// Template part: 404
get_header();
?>
<main>
    <section class="error-404 not-found">
        <h2><?php _e('404 - Not Found', 'ultralightshop'); ?></h2>
        <p><?php _e('Sorry, the page you are looking for does not exist.', 'ultralightshop'); ?></p>
        <a href="<?php echo home_url(); ?>"><?php _e('Return Home', 'ultralightshop'); ?></a>
    </section>
</main>
<?php
get_footer();
?>
