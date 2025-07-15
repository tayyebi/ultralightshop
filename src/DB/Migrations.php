<?php
namespace UltralightShop\DB;

class Migrations
{
    public static function createTables(): void
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $goods_table = $wpdb->prefix . 'goods';
        $product_table = $wpdb->prefix . 'product';
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
    }

    public static function runAll(): void
    {
        $migrationsDir = __DIR__ . '/migrations';
        $files = glob($migrationsDir . '/*.php');
        sort($files);
        foreach ($files as $file) {
            $migration = include $file;
            if (is_callable($migration)) {
                $migration();
            }
        }
    }

    public static function runPending(): void
    {
        $migrationsDir = __DIR__ . '/migrations';
        $files = glob($migrationsDir . '/*.php');
        sort($files);
        $applied = get_option('migrations', []);
        if (!is_array($applied)) $applied = [];
        foreach ($files as $file) {
            $migrationName = basename($file);
            if (!in_array($migrationName, $applied, true)) {
                $migration = include $file;
                if (is_callable($migration)) {
                    $migration();
                    $applied[] = $migrationName;
                }
            }
        }
        update_option('migrations', $applied);
    }
}
