<?php
get_header();
?>
<main>

    <?php
    // Query for Products (Custom Post Type "product")
    $args_products = array(
        'post_type'      => 'product', // Adjust if necessary
        'posts_per_page' => 5,         // Fetch the latest 5 products
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $products_query = new WP_Query($args_products);
    if ( $products_query->have_posts() ) : ?>
	    <?php while ( $products_query->have_posts() ) : $products_query->the_post(); ?>
	        <article <?php post_class('product-item'); ?>>
	            <header>
	                <h2>
	                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	                </h2>
	            </header>
	            <section>
	                <?php the_excerpt(); ?>
	            </section>
	        </article>
	    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php
    // Default Posts Loop (for blog posts or other post types)
    if ( have_posts() ) :
        while ( have_posts() ) : the_post(); ?>
            <article <?php post_class(); ?>>
                <header>
                    <h2>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                </header>
                <section>
                    <?php the_excerpt(); ?>
                </section>
                <footer>
                    <time datetime="<?php echo get_the_date('c'); ?>">
                        <?php echo get_the_date(); ?>
                    </time>
                </footer>
            </article>
        <?php endwhile; ?>

        <?php if ( !is_single() ) : ?>
            <nav class="pagination">
                <?php the_posts_pagination( array(
                    'prev_text' => __( 'Previous', 'ultralightshop' ),
                    'next_text' => __( 'Next', 'ultralightshop' ),
                ) ); ?>
            </nav>
        <?php endif; ?>
    <?php else : ?>
        <p><?php _e( 'No posts found.', 'ultralightshop' ); ?></p>
    <?php endif; ?>
    
</main>
<?php
get_footer();
?>

