SilexPhpDi
==========

Silex Service Provider for the [PHP-DI](http://php-di.org) Dependency Injection Container. It does not replace Silex's default DI container Pimple, but rather adds the extra functionality PHP-DI offers.


Usage
-----

Register the service provider:

	<?php

	$app->register(new ThisPageCannotBeFound\Silex\Provider\PhpDiServiceProvider());

	// full path to your DI configuration file
	$app['di.definitions'] = 'config.php';

<br>


Configuration
-------------

To configure PHP-DI's Container Builder, some options are available through the `di.options` array. The following are the defaults:

	$app['di.options'] = array(
		'cache' => null,
		'container_class' => 'DI\Container',
		'useAnnotations' => true,
		'useAutowiring' => true,
		'writeProxiesToFile' => false,
		'proxyDirectory' => null,
		'silexAliases' => true,
	);

Please refer to [the official documentation](http://php-di.org/doc/container-configuration.html) for more information on container configuration.

- `cache`: Enables the use of a cache for the definitions. Must be a `Doctrine\Common\Cache\Cache` instance.
- `container_class`: Name of the container class, used to create the DI container.
- `useAnnotations`: Enable or disable the use of annotations to guess injections.
- `useAutowiring`: Enable or disable the use of autowiring to guess injections.
- `writeProxiesToFile`: If true, write the proxies to disk to improve performances.
- `proxyDirectory`: Directory where to write the proxies.

The last option, `silexAliases`, adds aliases for some common Silex service providers, for example:

	return array (
		'Doctrine\DBAL\Connection' => \DI\link('db'),
	);

This means that when your class requests a `Doctrine\DBAL\Connection` injection, it will get the same value as when requesting `$app['db']`, which is the default defined in Silex's `DoctrineServiceProvider`.

If you need to access the Container Builder directly, you can do so through `$app['di.builder']`.

One extra feature of the default Container Builder configuration is that it adds the Silex app instance, so that it can be injected through `Silex\Application`.

<br>


Acclimate - container interoperability
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
