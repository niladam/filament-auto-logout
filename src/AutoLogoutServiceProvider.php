<?php

namespace Niladam\FilamentAutoLogout;

use Filament\Auth\Http\Controllers\LogoutController;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AutoLogoutServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-auto-logout';

    public static string $viewNamespace = 'filament-auto-logout';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasTranslations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('niladam/filament-auto-logout');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        Route::post('auto-logout-plugin-form', LogoutController::class)->name('filament-auto-logout-plugin-form');
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-auto-logout');

        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );
    }

    protected function getAssetPackageName(): ?string
    {
        return 'niladam/filament-auto-logout';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            AlpineComponent::make('filament-auto-logout', __DIR__ . '/../resources/dist/filament-auto-logout.js'),
        ];
    }
}
