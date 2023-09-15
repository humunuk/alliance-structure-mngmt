<?php


namespace Humunuk\Seat\AllianceStructureManagement;


use Seat\Services\AbstractSeatPlugin;

class AllianceStructureManagementServiceProvider extends AbstractSeatPlugin
{

    public function getName(): string
    {
        return "Alliance Structure Management";
    }

    public function getPackageRepositoryUrl(): string
    {
        return "https://github.com/humunuk/alliance-structure-mngmt";
    }

    public function getPackagistPackageName(): string
    {
        return "alliance-structure-mngmt";
    }

    public function getPackagistVendorName(): string
    {
        return "humunuk";
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/package.alliance.menu.php', 'package.alliance.menu');
        $this->registerPermissions(__DIR__ . '/Config/Permissions/alliance.php', 'alliance');
    }

    public function boot(): void
    {
        $this->add_routes();
        $this->add_views();
        $this->add_translations();
    }

    private function add_routes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
    }

    private function add_views(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'alliance-structure-mngmt');
    }

    private function add_translations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'alliance-structure-mngmt');
    }
}