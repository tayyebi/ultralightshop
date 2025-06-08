<?php
// Migration: create_product_table
return function() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $product_table = $wpdb->prefix . 'ultralightshop_product';
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $sql = "CREATE TABLE $product_table (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        good_id BIGINT(20) UNSIGNED NOT NULL,
        category_id BIGINT(20) UNSIGNED NOT NULL,
        price DECIMAL(12,2) NOT NULL,
        stock_quantity INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),
        KEY good_id (good_id),
        KEY category_id (category_id)
    ) $charset_collate;";
    dbDelta($sql);
};
