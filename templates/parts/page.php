<?php
$is_lazyload = isset($_SERVER['HTTP_X_LAZYLOAD_NAV']) && $_SERVER['HTTP_X_LAZYLOAD_NAV'] === '1';
if (!$is_lazyload) get_header();
?>
<main>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <header>
                <h1><?php the_title(); ?></h1>
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
