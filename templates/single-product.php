<?php
get_header();
?>
<main>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <header>
                <h2><?php the_title(); ?></h2>
            </header>
            <section>
                <?php the_content(); ?>
            </section>
            <aside>
                <p><strong>Price:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'price', true)); ?></p>
                <p><strong>SKU:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'sku', true)); ?></p>
            </aside>
            <footer>
                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
            </footer>
        </article>
    <?php endwhile; endif; ?>
</main>
<?php
get_footer();
?>

