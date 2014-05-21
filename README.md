SilexPhpDi
==========

Silex Service Provider for the [PHP-DI](http://php-di.org) Dependency Injection Container. It does not replace Silex's default DI container Pimple, but rather adds the extra functionality PHP-DI offers.

<br>


Installation
------------

This library is available on [Packagist](https://packagist.org/packages/thispagecannotbefound/silex-php-di). To include it using Composer, add the following to your `composer.json`:

	"require": {
		"thispagecannotbefound/silex-php-di": "*"
	}

<br>


Registering
-----------

Assuming `$app` is a Silex Application instance:

	$app->register(new ThisPageCannotBeFound\Silex\Provider\PhpDiServiceProvider(), array(
		'di.definitions' => '/path/to/config.php',
	));

<br>


Parameters
----------

The following parameters are available. Non are required.

- `di.definitions`: (array of) path(s) of your PHP-DI injection definitions.
- `di.options`: array of options:
	- `cache`: Enables the use of a cache for the definitions. Must be a `Doctrine\Common\Cache\Cache` instance. Defaults to `null`.
	- `container_class`: Name of the container class, used to create the DI container. Defaults to `DI\Container`.
	- `useAnnotations`: Enable or disable the use of annotations to guess injections. Defaults to `true`.
	- `useAutowiring`: Enable or disable the use of autowiring to guess injections. Defaults to `true`.
	- `writeProxiesToFile`: If true, write the proxies to disk to improve performances. Defaults to `false`.
	- `proxyDirectory`: Directory where to write the proxies. Defaults to `null`.
	- `silexAliases`: Add aliases for common Silex services. Defaults to `true`. (see below)
	- `injectOnControllers`: Fulfill controller dependencies after it has been resolved. Defaults to `true`. (see below)

Please refer to [the official documentation](http://php-di.org/doc/container-configuration.html) for more information on container configuration.

### `silexAliases`

This adds aliases for some common Silex service providers, for example:

	return array (
		'Doctrine\DBAL\Connection' => \DI\link('db'),
	);

This means that when your class requests a `Doctrine\DBAL\Connection` injection, it will get the same value as when requesting `$app['db']`, which is the default defined in Silex's `DoctrineServiceProvider`.

### `injectOnControllers`

If you organize your controllers in classes, enabling this option will fulfill the dependencies of your controller instance. This is achieved by replacing the default Silex controller resolver with this provider's `PhpDiControllerResolver`.

<br>


Services
--------

The provider exposes the following services:

- `di`: The dependency injection container, instance of Acclimate's `CompositeContainer`. (see "Acclimate" section below)
- `di.raw`: Instance of the wrapped `DI\Container` instance. (see "Acclimate" section below)
- `di.builder`: The `DI\ContainerBuilder` instance, allowing you to further configure or replace the builder.

<br>


Acclimate (container interoperability)
--------------------------------------

This Service Provider uses [Acclimate](https://github.com/jeremeamia/acclimate-container), as suggested by [the PHP-DI docs](http://php-di.org/doc/container-configuration.html), to allow for PHP-DI and Silex's Pimple to work together. This is manifested through Acclimate's `CompositeContainer`. The way it is configured will make values defined by PHP-DI have a higher priority. Consider the following:

	<?php

	// contents of definitions file, e.g. config.php

	return array(
		'foo' => 'bar'
	);

	// example Silex app, e.g. app.php (continuing from registration as demonstrated above)

	$app['foo'] = 'baz'; // set Pimple value

	$value = $app['di']->get('foo'); // the returned value will be "bar"

If the definitions file (`config.php`) would not have had an entry for "foo", the result would have been "baz".

Also, this means that `$app['di']` actually returns the generated `CompositeContainer` instance. If, for some reason, you need to access the PHP-DI container, you can do so through `$app['di.raw']`.
