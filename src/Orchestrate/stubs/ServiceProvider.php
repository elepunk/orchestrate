<?php namespace {{vendor}}\{{name}};

use Illuminate\Support\ServiceProvider;

class {{name}}ServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__.'/../');

        $this->package('{{lower_vendor}}/{{lower_name}}', '{{lower_vendor}}/{{lower_name}}', $path);

        include "{$path}/routes.php";
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}