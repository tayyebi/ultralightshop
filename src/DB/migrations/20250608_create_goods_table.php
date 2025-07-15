<?php
// Migration: create_goods_table
return function() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $goods_table = $wpdb->prefix . 'goods';
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $sql = "CREATE TABLE $goods_table (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        tags TEXT,
        PRIMARY KEY (id)
    ) $charset_collate;";
    dbDelta($sql);
};
