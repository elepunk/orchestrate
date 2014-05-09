<?php namespace Elepunk\Orchestrate;

use Illuminate\Support\ServiceProvider;
use Elepunk\Orchestrate\Console\ExtensionMakeCommand;

class OrchestrateServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('package.creator', function ($app) {
            return new PackageCreator($app['files']);
        });

        $this->app->bindShared('command.orchestrate', function ($app) {
            return new ExtensionMakeCommand($app['package.creator']);
        });

        $this->commands('command.orchestrate');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__.'/../');

        $this->package('elepunk/orchestrate', 'elepunk/orchestrate', $path);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('package.creator', 'comand.orchestrate');
    }
}