<?php
// Template part: header
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo is_home() || is_front_page() ? get_bloginfo('name') : wp_title('', false); ?></title>
  <meta name="description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
  <meta name="keywords" content="shop, e-commerce, products, <?php echo esc_attr(get_bloginfo('name')); ?>">
  <meta property="og:title" content="<?php echo is_home() || is_front_page() ? get_bloginfo('name') : wp_title('', false); ?>">
  <meta property="og:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
  <meta property="og:url" content="<?php echo esc_url(home_url()); ?>">
  <meta property="og:type" content="website">
  <meta property="og:image" content="<?php echo esc_url(get_template_directory_uri() . '/logo.png'); ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?php echo esc_url(home_url()); ?>">
  <link rel="preload" href="<?php echo esc_url(get_template_directory_uri() . '/fonts/sahel/Sahel-Regular.ttf'); ?>" as="font" type="font/ttf" crossorigin="anonymous">
  <link rel="preload" href="<?php echo esc_url(get_template_directory_uri() . '/fonts/lalezar/Lalezar-Regular.ttf'); ?>" as="font" type="font/ttf" crossorigin="anonymous">
  <style>
    @font-face {
      font-family: 'Sahel';
      src: url('<?php echo esc_url(get_template_directory_uri() . '/fonts/sahel/Sahel-Regular.ttf'); ?>') format('truetype');
      font-weight: 400;
      font-style: normal;
    }
    @font-face {
      font-family: 'Lalezar';
      src: url('<?php echo esc_url(get_template_directory_uri() . '/fonts/lalezar/Lalezar-Regular.ttf'); ?>') format('truetype');
      font-weight: 400;
      font-style: normal;
    }
  </style>
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/style.css'); ?>">
  <script>
    window.lazyloadnav_settings = { 
      fade_duration: 300, 
      container: 'main', 
      debug_mode: false
    };
  </script>
  <script src="<?php echo esc_url(get_template_directory_uri() . '/assets/js/lazyload.js'); ?>" defer></script>
  <?php if ( is_single() && get_post_type() == 'product' ) : 
    $product_price = get_post_meta(get_the_ID(), 'price', true);
    $product_sku   = get_post_meta(get_the_ID(), 'sku', true);
    $product_currency = "USD";
  ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "<?php the_title(); ?>",
    "description": "<?php echo esc_js(get_the_excerpt()); ?>",
    "sku": "<?php echo esc_js($product_sku); ?>",
    "offers": {
      "@type": "Offer",
      "priceCurrency": "<?php echo $product_currency; ?>",
      "price": "<?php echo esc_js($product_price); ?>",
      "availability": "https://schema.org/InStock"
    }
  }
  </script>
  <?php elseif ( is_single() && get_post_type() == 'post' ) : ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "<?php the_title(); ?>",
    "datePublished": "<?php echo get_the_date('c'); ?>",
    "author": {
      "@type": "Person",
      "name": "<?php the_author(); ?>"
    },
    "articleBody": "<?php echo esc_js(wp_strip_all_tags(get_the_content())); ?>"
  }
  </script>
  <?php elseif ( is_home() || is_archive() || is_search() ) : ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
    "url": "<?php echo esc_url(home_url()); ?>",
    "logo": "<?php echo esc_url(get_template_directory_uri() . '/logo.png'); ?>",
    "description": "<?php echo esc_js(get_bloginfo('description')); ?>"
  }
  </script>
  <?php endif; ?>
  <?php if ( is_rtl() ) : ?>
  <style>
      body {
          direction: rtl;
      }
  </style>
  <?php endif; ?>
</head>
<body <?php body_class(); ?>>
<header>
  <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
  <nav>
    <?php wp_nav_menu(array('theme_location' => 'top-menu')); ?>
    <ul class="top-bar-links">
      <li><a href="<?php echo home_url(); ?>">Home</a></li>
      <li><a href="<?php echo home_url('/shop'); ?>">Shop</a></li>
      <li><a href="<?php echo home_url('/blog'); ?>">Blog</a></li>
      <?php if ( is_user_logged_in() ) : ?>
        <li><a href="<?php echo home_url('/user-panel'); ?>">My Account</a></li>
        <li><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Logout</a></li>
      <?php else : ?>
        <li><a href="<?php echo home_url('/login'); ?>">Login</a></li>
        <li><a href="<?php echo home_url('/register'); ?>">Register</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
<div id="loading" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%, -50%);background-color:#fff;padding:10px;border:1px solid #ccc;z-index:1000;">
  <?php _e('Loading', 'ultralightshop'); ?> ...
</div>
