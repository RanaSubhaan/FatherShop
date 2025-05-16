<?php

namespace FatherShop\EmailCampaigns\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-campaigns:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the FatherShop Email Campaigns package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Installing FatherShop Email Campaigns Package...');

        $this->info('Publishing configuration...');
        $this->publishConfiguration();

        $this->info('Publishing migrations...');
        $this->publishMigrations();

        $this->info('Running migrations...');
        $this->runMigrations();

        $this->info('FatherShop Email Campaigns Package installed successfully.');

        return Command::SUCCESS;
    }

    /**
     * Publish package configuration.
     *
     * @return void
     */
    private function publishConfiguration()
    {
        $this->callSilent('vendor:publish', [
            '--provider' => 'FatherShop\EmailCampaigns\Providers\EmailCampaignsServiceProvider',
            '--tag' => 'config'
        ]);

        $this->info('Configuration published successfully.');
    }

    /**
     * Publish package migrations.
     *
     * @return void
     */
    private function publishMigrations()
    {
        $this->callSilent('vendor:publish', [
            '--provider' => 'FatherShop\EmailCampaigns\Providers\EmailCampaignsServiceProvider',
            '--tag' => 'migrations'
        ]);

        $this->info('Migrations published successfully.');
    }

    /**
     * Run database migrations.
     *
     * @return void
     */
    private function runMigrations()
    {
        $this->callSilent('migrate');
        $this->info('Migrations completed successfully.');
    }
}