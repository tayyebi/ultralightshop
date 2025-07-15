<?php
get_header();
?>
<main>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <header>
                <h2><?php the_title(); ?></h2>
		<p><?php echo esc_html(get_post_meta(get_the_ID(), 'tagline', true)); ?></p>
            </header>
	    <pre>
<?php var_dump(get_post_meta($post->ID)); ?>
	    </pre>
            <aside>
                <p><strong>Website: </strong><a rel="noopener nofollow" href="<?php echo esc_html(get_post_meta(get_the_ID(), 'website', true)); ?>"><?php echo esc_html(get_post_meta(get_the_ID(), 'website', true)); ?></a></p>
                <p><strong>Phone: </strong><a href="tel:<?php echo esc_html(get_post_meta(get_the_ID(), 'phone', true)); ?>"><?php echo esc_html(get_post_meta(get_the_ID(), 'phone', true)); ?></a></p>
                <p><strong>E-Mail: </strong><a href="mailto:<?php echo esc_html(get_post_meta(get_the_ID(), 'email', true)); ?>"><?php echo esc_html(get_post_meta(get_the_ID(), 'email', true)); ?></a></p>
            </aside>
            <section>
                <?php the_content(); ?>
            </section>
            <footer>
                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
            </footer>
        </article>
    <?php endwhile; endif; ?>
</main>
<?php
get_footer();
?>

