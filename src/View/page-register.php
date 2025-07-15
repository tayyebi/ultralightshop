<?php
/* Template Name: Register Page */
get_header();
?>
<main>
    <h2>Register</h2>
    <?php echo do_shortcode('[ultralightshop_register]'); ?>
    <p><?php _e('Already registered?', 'ultralightshop'); ?> <a href="<?php echo home_url('/login'); ?>"><?php _e('Login Here', 'ultralightshop'); ?></a></p>
</main>
<?php
get_footer();
?>

