<?php

namespace FatherShop\EmailCampaigns\Providers;

use FatherShop\EmailCampaigns\Console\Commands\InstallCommand;
use FatherShop\EmailCampaigns\Contracts\CampaignRepositoryInterface;
use FatherShop\EmailCampaigns\Contracts\CustomerRepositoryInterface;
use FatherShop\EmailCampaigns\Repositories\CampaignRepository;
use FatherShop\EmailCampaigns\Repositories\CustomerRepository;
use Illuminate\Support\ServiceProvider;

class EmailCampaignsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/email-campaigns.php', 'email-campaigns'
        );

        // Register repositories
        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/email-campaigns.php' => config_path('email-campaigns.php'),
        ], 'config');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}