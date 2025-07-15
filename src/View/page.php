<?php
get_header();
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
get_footer();
?>

