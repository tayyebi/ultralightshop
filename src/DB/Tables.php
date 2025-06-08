<?php
namespace UltralightShop\DB;

class Tables
{
    public function register(): void
    {
        add_action('init', [$this, 'runMigrations']);
    }

    public function runMigrations(): void
    {
        \UltralightShop\DB\Migrations::runPending();
    }
}
