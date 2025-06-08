<?php
// Custom DB tables for goods and product
add_action('after_switch_theme', function() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $goods_table = $wpdb->prefix . 'ultralightshop_goods';
    $product_table = $wpdb->prefix . 'ultralightshop_product';
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $sql1 = "CREATE TABLE $goods_table (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        tags TEXT,
        PRIMARY KEY (id)
    ) $charset_collate;";
    $sql2 = "CREATE TABLE $product_table (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        good_id BIGINT(20) UNSIGNED NOT NULL,
        category_id BIGINT(20) UNSIGNED NOT NULL,
        price DECIMAL(12,2) NOT NULL,
        stock_quantity INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),
        KEY good_id (good_id),
        KEY category_id (category_id)
    ) $charset_collate;";
    dbDelta($sql1);
    dbDelta($sql2);
});
