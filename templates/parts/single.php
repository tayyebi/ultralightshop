<?php
$is_lazyload = isset($_SERVER['HTTP_X_LAZYLOAD_NAV']) && $_SERVER['HTTP_X_LAZYLOAD_NAV'] === '1';
if (!$is_lazyload) get_header();
?>
<main>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <header>
                <h2><?php the_title(); ?></h2>
                <p>By <?php the_author(); ?> on <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time></p>
            </header>
            <section>
                <?php the_content(); ?>
            </section>
        </article>
    <?php endwhile; endif; ?>
</main>
<?php
if (!$is_lazyload) get_footer();
?>
