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

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/package.alliance.menu.php', 'package.alliance.menu');
    }
}