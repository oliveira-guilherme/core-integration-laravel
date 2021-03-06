<?php

namespace DigiSac\Base;

//use Collective\Html\HtmlServiceProvider;
use DigiSac\Base\Models\Company;
use EdsonAlvesan\DigiSac\Traits\ContactTrait;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use DigiSac\Base\Providers\EventServiceProvider;
use DigiSac\Base\Providers\RouteServiceProvider;
use DigiSac\Base\Providers\RepositoryServiceProvider;
use DigiSac\Base\Providers\DigiSacAppServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider;

class DigiSacCoreIntegrationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
         
        if (\Request::getHost() !== 'localhost') {
            \URL::forceScheme('https');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //List companies
        if(\Schema::hasTable('companies')) {
            $companies = Company::all();
            view()->share('companies', $companies);
            //Select session Company
            view()->composer('*', function ($view) {
                $view->with('SelectedCompany', \Session::get('SelectedCompany'));
            });
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(DigiSacAppServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(DataTablesServiceProvider::class);
        //$this->app->register(HtmlServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);

    }
}

