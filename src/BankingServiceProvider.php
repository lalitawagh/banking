<?php

namespace Kanexy\Banking;

use Kanexy\Cms\Setting\Models\Setting;
use Kanexy\Cms\Traits\InteractsWithMigrations;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BankingServiceProvider extends PackageServiceProvider
{

    use InteractsWithMigrations;

    /**
     * The date and time for these migrations will be preserved when
     * published.
     *
     * @var array|string[]
     */

    protected array $migrationsWithPresetDateTime = [];

    /**
     * A new date and time for these migrations will be appended in the
     * files when published.
     *
     * @var array|string[]
     */
    protected array $migrationsWithoutPresetDateTime = [];

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
         $package
            ->name('banking')
            ->hasViews()
            ->hasRoute('web')
            ->hasRoute('api')
            ->hasMigrations($this->migrationsWithoutPresetDateTime);

        $this->publishMigrationsWithPresetDateTime($this->migrationsWithPresetDateTime);
    }

    public function packageRegistered()
    {
    }

    public function packageBooted()
    {
        parent::packageBooted();

        Banking::registerDefaultPolicies();
        Banking::registerDefaultLivewireComponents();
        Banking::registerDefaultMenuComponents();
        Banking::registerDefaultRegistrationFlowComponents();
        Banking::registerDefaultDashboardComponents();
        Banking::registerDefaultTopbarComponents();
        Banking::registerDefaultFormComponents();
        Banking::registerDefaultRedirectRouteComponents();

        $this->app->singleton(\Kanexy\PartnerFoundation\Core\Services\WrappexService::class, function () {
            /**
             * We will give preference to the prefixes set in the configuration
             * in case the management wants to overwrite the prefixes for the
             * temporary usage. So, all we will have to do is to set it in the
             * .env file and it will automatically reflect in the system wide.
             */
            $environment = config('partner-foundation.services.wrappex.environment');

            if (empty($environment)) {
                $environment = Setting::getValue('wrappex_environment');
            }

            return new \Kanexy\PartnerFoundation\Core\Services\WrappexService();
        });

    }
}
