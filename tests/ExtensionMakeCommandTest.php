<?php namespace Elepunk\Orchestrate\Tests;

use Mockery as m;
use Elepunk\Orchestrate\Console\ExtensionMakeCommand as Command;

class ExtensionMakeCommandTest extends \PHPUnit_Framework_TestCase {

    /**
     * Teardown test environment
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test fire method
     * 
     * @test
     */
    public function testFireMethod()
    {
        $packageCreator = m::mock('Elepunk\Orchestrate\PackageCreator');
        $input  = m::mock('\Symfony\Component\Console\Input\InputInterface');
        $output = m::mock('\Symfony\Component\Console\Output\OutputInterface');

        $input->shouldReceive('bind')->once()
        ->shouldReceive('isInteractive')->once()->andReturn(true)
        ->shouldReceive('validate')->once()
        ->shouldReceive('getArgument')->once()->andReturn('foo/bar');

        $packageCreator->shouldReceive('create')->once();

        $output->shouldReceive('writeln')->once()->with('<info>Orchestra Platform extension created. Run php artisan extension:detect</info>');

        $stub = new Command($packageCreator);
        $stub->run($input, $output);
    }

}