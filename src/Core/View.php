<?php
namespace UltralightShop\Core;

class View
{
    protected $is_lazyload;

    public function __construct()
    {
        $this->is_lazyload = isset($_SERVER['HTTP_X_LAZYLOAD_NAV']) && $_SERVER['HTTP_X_LAZYLOAD_NAV'] === '1';
    }

    protected function beforeRender()
    {
        if (!$this->is_lazyload) get_header();
    }

    protected function afterRender()
    {
        if (!$this->is_lazyload) get_footer();
    }

    public function render(callable $content): void
    {
        $this->beforeRender();
        $content();
        $this->afterRender();
    }

    public static function renderTemplate(string $template, array $vars = []): void
    {
        extract($vars);
        include locate_template($template);
    }
}
