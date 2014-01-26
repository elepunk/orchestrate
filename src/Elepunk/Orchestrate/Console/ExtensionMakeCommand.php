<?php namespace Elepunk\Orchestrate\Console;

use Illuminate\Console\Command;
use Illuminate\Workbench\Package;
use Elepunk\Orchestrate\PackageCreator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ExtensionMakeCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'orchestrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new orchestra platform extension';

    /**
     * The package creator instance.
     *
     * @var \Illuminate\Workbench\PackageCreator
     */
    protected $creator;

    /**
     * Create a new make workbench command instance.
     *
     * @param  \Illuminate\Workbench\PackageCreator  $creator
     * @return void
     */
    public function __construct(PackageCreator $creator)
    {
        parent::__construct();

        $this->creator = $creator;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->runCreator($this->buildPackage());

        $this->info('Orchestra Platform extension created. Run php artisan extension:detect');
    }

    /**
     * Run the package creator class for a given Package.
     *
     * @param  \Illuminate\Workbench\Package  $package
     * @return string
     */
    protected function runCreator($package)
    {
        $path = $this->laravel['path.base'].'/extension';

        return $this->creator->create($package, $path, false);
    }

    /**
     * Build the package details from user input.
     *
     * @return \Illuminate\Workbench\Package
     */
    protected function buildPackage()
    {
        list($vendor, $name) = $this->getPackageSegments();

        $config = $this->laravel['config']['workbench'];

        return new Package($vendor, $name, $config['name'], $config['email']);
    }

    /**
     * Get the package vendor and name segments from the input.
     *
     * @return array
     */
    protected function getPackageSegments()
    {
        $package = (string) $this->argument('package');

        return array_map('studly_case', explode('/', $package, 2));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('package', InputArgument::REQUIRED, 'The name (vendor/name) of the extension.'),
        );
    }
}
