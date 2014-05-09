## Orchestrate

[Orchestra/Platform](http://orchestraplatform.com/) extension skeleton generator.  This will provide you with a basic directory structures needed to kickstart the development process.

[![Build Status](https://travis-ci.org/elepunk/orchestrate.png?branch=master)](https://travis-ci.org/elepunk/orchestrate)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/elepunk/orchestrate/badges/quality-score.png?s=ed3dc42b70b401103d1e4a919df828499a9c3182)](https://scrutinizer-ci.com/g/elepunk/orchestrate/)
[![Code Coverage](https://scrutinizer-ci.com/g/elepunk/orchestrate/badges/coverage.png?s=b3518671ab38232ce352aeeb4458ca4fa7ae24ca)](https://scrutinizer-ci.com/g/elepunk/orchestrate/)

### Installation & Usage

Add the package into your composer.json file.

```javascript
"require": {
    "elepunk/orchestrate": "0.2.*"
 },
 ```

Update ```app/start/global.php``` so that Orchestra\Platform is able to detect the extensions.

```php
App::make('orchestra.extension.finder')->addPath(base_path().'/extensions/*/*/');
```

Add the service provider in ```app/config/app.php```.

```php
'providers' => array(
    'Elepunk\Orchestrate\OrchestrateServiceProvider'
),
```

Run ```php artisan orchestrate foo/bar```to generate the extension skeleton.

Run ```php artisan dump-autoload``` to reload the autoloader.

Run ```php artisan extension:detect``` and you will see your newly created extension. All extensions will be created under ```extension``` directory.

If you want to change the default directory just publish the configuration file ```php artisan config:publish elepunk/orchestrate```.