<?php namespace Elepunk\Orchestrate;

use Illuminate\Support\Collection;
use Illuminate\Workbench\Package;
use Illuminate\Support\Facades\Config;
use Illuminate\Workbench\PackageCreator as Workbench;

class PackageCreator extends Workbench
{

    /**
     * The building blocks of the extension.
     *
     * @param  array
     */
    protected $blocks = array(
        'SupportDirectories',
        'OrchestraFiles',
        'ServiceProvider',
        'ControllerDirectory',
        'ProcessorDirectory',
        'PresenterDirectory',
        'HandlerDirectory',
        'ValidatorDirectory',
        'HelloViewFile',
        'Autoloader'
    );

    /**
     * Write the support files to the extension root.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writeOrchestraFiles(Package $package, $directory)
    {
        foreach (array('Manifest', 'Route') as $file) {
            $this->{"write{$file}File"}($package, $directory);
        }
    }

    /**
     * Create the support directories for the extension.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writeSupportDirectories(Package $package, $directory)
    {
        foreach (array('config', 'migrations', 'views') as $support) {
            $this->writeSupportDirectory($package, $support, $directory);
        }
    }

    /**
     * Write the stub ServiceProvider for the package.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writeServiceProvider(Package $package, $directory, $plain)
    {
        $stub = $this->files->get(__DIR__.'/stubs/ServiceProvider.php');

        $stub = $this->formatPackageStub($package, $stub);

        $this->writeProviderStub($package, $directory, $stub);
    }

    /**
     * Create the controller directory for the extension.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writeControllerDirectory(Package $package, $directory)
    {
        $path = $directory.'/src/'.$package->name;

        $this->files->makeDirectory($path.'/Controller');

        $this->files->put($path.'/Controller/.gitkeep', '');
    }

    /**
     * Create the processor directory for the extension.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writeProcessorDirectory(Package $package, $directory)
    {
        $path = $directory.'/src/'.$package->name;

        $this->files->makeDirectory($path.'/Processor');

        $this->files->put($path.'/Processor/.gitkeep', '');
    }

    /**
     * Create the presenter directory for the extension.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writePresenterDirectory(Package $package, $directory)
    {
        $path = $directory.'/src/'.$package->name;

        $this->files->makeDirectory($path.'/Presenter');

        $this->files->put($path.'/Presenter/.gitkeep', '');
    }

    /**
     * Create the handler directory for the extension.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writeHandlerDirectory(Package $package, $directory)
    {
        $path = $directory.'/src/'.$package->name;

        $this->files->makeDirectory($path.'/Handler');

        $this->files->put($path.'/Handler/.gitkeep', '');
    }

    /**
     * Create the validator directory for the extension.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writeValidatorDirectory(Package $package, $directory)
    {
        $path = $directory.'/src/'.$package->name;

        $this->files->makeDirectory($path.'/Validator');

        $this->files->put($path.'/Validator/.gitkeep', '');
    }

    /**
     * Create the view file for the extension.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writeHelloViewFile(Package $package, $directory)
    {   
        $stub = __DIR__.'/stubs/hello.blade.php';

        $this->files->copy($stub, $directory.'/src/views/hello.blade.php');
    }

    /**
     * Autoload extension namespace
     * 
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writeAutoloader(Package $package, $directory)
    {
        $stub = $this->files->get($directory.'/../../../composer.json');

        $stub = json_decode($stub, true);

        if ( ! array_key_exists('psr-4', $stub['autoload'])) {
            $stub['autoload']['psr-4'] = array();
        }

        list($namespace, $path) = $this->appendAutoloader($package, $directory);

        $stub['autoload']['psr-4'][$namespace] =  $path;

        $this->files->put($directory.'/../../../composer.json', json_encode($stub, 448));
    }

    /**
     * Create the manifest file for the extension.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    protected function writeManifestFile(Package $package, $directory)
    {
        $stub = $this->files->get(__DIR__.'/stubs/orchestra.json');

        $stub = $this->formatPackageStub($package, $stub);

        $this->files->put($directory.'/orchestra.json', $stub);
    }

    /**
     * Create the route file for the extension.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    protected function writeRouteFile(Package $package, $directory)
    {
        $stub = $this->files->get(__DIR__.'/stubs/routes.php');

        $stub = $this->formatPackageStub($package, $stub);

        $this->files->put($directory.'/src/routes.php', $stub);
    }

    /**
     * [appendAutoloader description]
     * @param  [type] $package   [description]
     * @param  [type] $directory [description]
     * @return [type]            [description]
     */
    protected function appendAutoloader($package, $directory)
    {
        return array("{$package->vendor}\\{$package->name}\\", Config::get('elepunk/orchestrate::extension_directory').'/'.$package->lowerVendor.'/'.$package->lowerName.'/src/'.$package->name);
    }

    /**
     * Create the main source directory for the package.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return string
     */
    protected function createClassDirectory(Package $package, $directory)
    {
        $path = $directory.'/src/'.$package->name;

        if ( ! $this->files->isDirectory($path))
        {
            $this->files->makeDirectory($path, 0777, true);
        }

        return $path;
    }
    
}