## Orchestrate

[Orchestra/Platform](http://orchestraplatform.com/) extension skeleton generator.  This will provide you with a basic directory structures needed to kickstart the development process.

### Installation & Usage

Add the package into your composer.json file.

```javascript
"require": {
    "elepunk/orchestrate": "0.1.*"
 },
 ```

Update ```app/start/global.php``` so that Orchestra\Platform is able to detect the extensions.

```php
App::make('orchestra.extension.finder')->addPath(base_path().'/extension/*/*/');
```

Add the service provider in ```app/config/app.php```.

```php
'providers' => array(
    'Elepunk\Orchestrate\OrchestrateServiceProvider'
),
```

Run ```php artisan orchestrate foo/bar``` and the run ```php artisan extension:detect``` and you will see your newly created extension. All extensions will be created under ```extension``` directory.

Don't forget to autoload your extension namespace inside ```composer.json``` file.

### TODO

- Add support for PSR-4 autoloader
- Autoload namespace into composer.json file
- Add extra options