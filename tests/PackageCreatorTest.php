<?php namespace Elepunk\Orchestrate\Tests;

use Mockery as m;
use Elepunk\Orchestrate\PackageCreator;

class PcckageCreatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Teardown test environment
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test write support files method
     * 
     * @test
     */
    public function testWriteOrchestraFilesMethod()
    {
        list($filesystem, $package) = $this->getMocks();

        $filesystem->shouldReceive('get')->twice()->andReturn('foobar')
        ->shouldReceive('put')->twice()->andReturn(1);

        $creator = new PackageCreator($filesystem);
        $creator->writeOrchestraFiles($package, __DIR__);
    }

    /**
     * Test write support directories method
     * 
     * @test
     */
    public function testWriteSupportDirectoriesMethod()
    {
        list($filesystem, $package) = $this->getMocks();

        $filesystem->shouldReceive('makeDirectory')->times(3)->andReturn(true)
        ->shouldReceive('put')->times(3)->andReturn(1);

        $creator = new PackageCreator($filesystem);
        $creator->writeSupportDirectories($package, __DIR__);
    }

    /**
     * Test write controller directory method
     * 
     * @test
     */
    public function testWriteControllerDirectoryMethod()
    {
        list($filesystem, $package) = $this->getMocks();

        $filesystem->shouldReceive('makeDirectory')->once()->andReturn(true)
        ->shouldReceive('put')->once()->andReturn(1);

        $creator = new PackageCreator($filesystem);
        $creator->writeControllerDirectory($package, __DIR__);
    }

    /**
     * Test write handler directory method
     * 
     * @test
     */
    public function testWriteHandlerDirectoryMethod()
    {
        list($filesystem, $package) = $this->getMocks();

        $filesystem->shouldReceive('makeDirectory')->once()->andReturn(true)
        ->shouldReceive('put')->once()->andReturn(1);

        $creator = new PackageCreator($filesystem);
        $creator->writeHandlerDirectory($package, __DIR__);
    }

    /**
     * Test write processor directory method
     * 
     * @test
     */
    public function testWriteProcessorDirectoryMethod()
    {
        list($filesystem, $package) = $this->getMocks();

        $filesystem->shouldReceive('makeDirectory')->once()->andReturn(true)
        ->shouldReceive('put')->once()->andReturn(1);

        $creator = new PackageCreator($filesystem);
        $creator->writeProcessorDirectory($package, __DIR__);
    }

    /**
     * Test write validator directory method
     * 
     * @test
     */
    public function testWriteValidatorDirectoryMethod()
    {
        list($filesystem, $package) = $this->getMocks();

        $filesystem->shouldReceive('makeDirectory')->once()->andReturn(true)
        ->shouldReceive('put')->once()->andReturn(1);

        $creator = new PackageCreator($filesystem);
        $creator->writeValidatorDirectory($package, __DIR__);
    }

    /**
     * Test write hello view file method
     * 
     * @test
     */
    public function testWriteHelloViewFileMethod()
    {
        list($filesystem, $package) = $this->getMocks();

        $filesystem->shouldReceive('copy')->once()->andReturn(true);

        $creator = new PackageCreator($filesystem);
        $creator->writeHelloViewFile($package, __DIR__);
    }

    /**
     * Test write service provider method
     * 
     * @test
     */
    public function testWriteServiceProviderMethod()
    {
        list($filesystem, $package) = $this->getMocks();

        $filesystem->shouldReceive('get')->once()->andReturn('foobar')
        ->shouldReceive('isDirectory')->once()->andReturn(true)
        ->shouldReceive('put')->once()->andReturn(1);

        $creator = new PackageCreator($filesystem);
        $creator->writeServiceProvider($package, __DIR__, false);
    }

    /**
     * Test update composer.json file
     * 
     * @test
     */
    public function testWriteComposerFileMethod()
    {
        $composer = array(
            'autoload' => array()
        );

        list($filesystem, $package) = $this->getMocks();

        $filesystem->shouldReceive('get')->once()->andReturn(json_encode($composer))
        ->shouldReceive('put')->once()->andReturn(1);

        $creator = new PackageCreator($filesystem);
        $creator->writeAutoloader($package, __DIR__);
    }

    /**
     * Test json encode method
     * 
     * @test
     */
    public function testEncodeMethod()
    {
        $composer = array(
            'autoload' => array()
        );

        list($filesystem, $package) = $this->getMocks();

        $creator = new PackageCreator($filesystem);

        $reflection = new \ReflectionClass(get_class($creator));
        $method = $reflection->getMethod('encode');
        $method->setAccessible(true);

        $method->invokeArgs($creator, $composer);
    }

    /**
     * Get mocks classes
     *
     * @return array
     */
    protected function getMocks()
    {
        return array(m::mock('Illuminate\Filesystem\Filesystem'), m::mock('Illuminate\Workbench\Package'));
    }

}