<?php namespace Elepunk\Orchestrate;

use Illuminate\Workbench\Package;
use Illuminate\Workbench\PackageCreator as Workbench;

class PackageCreator extends Workbench {

    /**
     * The building blocks of the extension.
     *
     * @param  array
     */
    protected $blocks = array(
        'SupportDirectories',
        'SupportFiles',
        'ServiceProvider',
        'ControllerDirectory',
        'ProcessorDirectory',
        'HandlerDirectory',
        'ValidatorDirectory'
    );

    /**
     * Write the support files to the extension root.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    public function writeSupportFiles(Package $package, $directory, $plain)
    {
        foreach (array('Manifest', 'Route') as $file)
        {
            $this->{"write{$file}File"}($package, $directory, $plain);
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
        foreach (array('config', 'migrations', 'views') as $support)
        {
            $this->writeSupportDirectory($package, $support, $directory);
        }
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
        $path = $directory.'/src/'.$package->vendor.'/'.$package->name;

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
        $path = $directory.'/src/'.$package->vendor.'/'.$package->name;

        $this->files->makeDirectory($path.'/Processor');

        $this->files->put($path.'/Processor/.gitkeep', '');
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
        $path = $directory.'/src/'.$package->vendor.'/'.$package->name;

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
        $path = $directory.'/src/'.$package->vendor.'/'.$package->name;

        $this->files->makeDirectory($path.'/Validator');

        $this->files->put($path.'/Validator/.gitkeep', '');
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
     * Create the view file for the extension.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @param  string  $directory
     * @return void
     */
    protected function writeHelloViewFile(Package $package, $directory)
    {   
        $stub = $this->files->get(__DIR__.'/stubs/hello.blade.php');

        $this->files->put($directory.'/views/hello.blade.php', $stub);
    }

    /**
     * Load the raw service provider file.
     *
     * @param  bool   $plain
     * @return string
     */
    protected function getProviderFile($plain)
    {
        return $this->files->get(__DIR__.'/stubs/provider.stub');
    }

}