<?php
$is_lazyload = isset($_SERVER['HTTP_X_LAZYLOAD_NAV']) && $_SERVER['HTTP_X_LAZYLOAD_NAV'] === '1';
if (!$is_lazyload) get_header();
?>
<main>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>>
                <header>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                </header>
                <section>
                    <?php if (is_single()) : ?>
                        <?php the_content(); ?>
                    <?php else : ?>
                        <?php the_excerpt(); ?>
                    <?php endif; ?>
                </section>
                <footer>
                    <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                </footer>
            </article>
        <?php endwhile; ?>
        <?php if (!is_single()) : ?>
            <nav class="pagination">
                <?php the_posts_pagination(array(
                    'prev_text' => __('Previous', 'ultralightshop'),
                    'next_text' => __('Next', 'ultralightshop'),
                )); ?>
            </nav>
        <?php endif; ?>
    <?php else: ?>
        <p><?php _e('No posts found.', 'ultralightshop'); ?></p>
    <?php endif; ?>
</main>
<?php
if (!$is_lazyload) get_footer();
?>
