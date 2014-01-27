<?php namespace Elepunk\Orchestrate;

use Illuminate\Support\Collection;
use Illuminate\Workbench\Package;
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

        if ( ! array_key_exists('psr-0', $stub['autoload'])) {
            $stub['autoload']['psr-0'] = array();
        }

        list($namespace, $path) = $this->appendAutoloader($package, $directory);

        $stub['autoload']['psr-0'][$namespace] =  $path;

        $this->files->put($directory.'/../../../composer.json', $this->encode($stub));
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
        return array("{$package->vendor}\\{$package->name}", 'extension/'.$package->lowerVendor.'/'.$package->lowerName.'/src');
    }

    /**
     * Encodes an array into (optionally pretty-printed) JSON
     *
     * This code is taken from composer/composer package
     * https://github.com/composer/composer/blob/master/src/Composer/Json/JsonFile.php
     * 
     * Originally licensed under MIT by Nils Adermann, Jordi Boggiano
     *
     * @param  mixed  $data    Data to encode into a formatted JSON string
     * @param  int    $options json_encode options (defaults to JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
     * @return string Encoded json
     */
    protected function encode($data, $options = 448)
    {
        if (version_compare(PHP_VERSION, '5.4', '>=')) {
            return json_encode($data, $options);
        }

        $json = json_encode($data);

        $prettyPrint = (bool) ($options & self::JSON_PRETTY_PRINT);
        $unescapeUnicode = (bool) ($options & self::JSON_UNESCAPED_UNICODE);
        $unescapeSlashes = (bool) ($options & self::JSON_UNESCAPED_SLASHES);

        if (!$prettyPrint && !$unescapeUnicode && !$unescapeSlashes) {
            return $json;
        }

        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '    ';
        $newLine = "\n";
        $outOfQuotes = true;
        $buffer = '';
        $noescape = true;

        for ($i = 0; $i < $strLen; $i++) {
            // Grab the next character in the string
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ('"' === $char && $noescape) {
                $outOfQuotes = !$outOfQuotes;
            }

            if (!$outOfQuotes) {
                $buffer .= $char;
                $noescape = '\\' === $char ? !$noescape : true;
                continue;
            } elseif ('' !== $buffer) {
                if ($unescapeSlashes) {
                    $buffer = str_replace('\\/', '/', $buffer);
                }

                if ($unescapeUnicode && function_exists('mb_convert_encoding')) {
                    // http://stackoverflow.com/questions/2934563/how-to-decode-unicode-escape-sequences-like-u00ed-to-proper-utf-8-encoded-cha
                    $buffer = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', function($match) {
                        return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
                    }, $buffer);
                }

                $result .= $buffer.$char;
                $buffer = '';
                continue;
            }

            if (':' === $char) {
                // Add a space after the : character
                $char .= ' ';
            } elseif (('}' === $char || ']' === $char)) {
                $pos--;
                $prevChar = substr($json, $i - 1, 1);

                if ('{' !== $prevChar && '[' !== $prevChar) {
                    // If this character is the end of an element,
                    // output a new line and indent the next line
                    $result .= $newLine;
                    for ($j = 0; $j < $pos; $j++) {
                        $result .= $indentStr;
                    }
                } else {
                    // Collapse empty {} and []
                    $result = rtrim($result)."\n\n".$indentStr;
                }
            }

            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line
            if (',' === $char || '{' === $char || '[' === $char) {
                $result .= $newLine;

                if ('{' === $char || '[' === $char) {
                    $pos++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
        }

        return $result;
    }
}