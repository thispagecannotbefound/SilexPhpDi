<?php

namespace ThisPageCannotBeFound\Silex\Provider;

use Acclimate\Container\CompositeContainer;
use Acclimate\Container\ContainerAcclimator;
use DI\ContainerBuilder;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class PhpDiServiceProvider implements ServiceProviderInterface {

	public function boot(Application $app) {
		// noop
	}

	public function register(Application $app) {
		$app['di'] = $app->share(function() use($app) {
					$acclimator = new ContainerAcclimator();
					$container = new CompositeContainer();

					$builder = $app['di.builder'];
					$builder->wrapContainer($container);

					$phpdi = $builder->build();
					$phpdi->set('Silex\Application', $app);

					$app['di.raw'] = $phpdi;

					$container->addContainer($acclimator->acclimate($phpdi));
					$container->addContainer($acclimator->acclimate($app));

					return $container;
				});

		$app['di.builder'] = $app->share(function() use($app) {
					$options = array_merge($app['di.default_options'], $app['di.options']);

					$builder = new ContainerBuilder($options['container_class']);
					$builder->useAnnotations((bool) $options['useAnnotations']);
					$builder->useAutowiring((bool) $options['useAutowiring']);
					$builder->writeProxiesToFile($options['writeProxiesToFile'],
							$options['proxyDirectory']);

					if ($options['cache']) {
						$builder->setDefinitionCache($options['cache']);
					}

					$definitions = (array) $app['di.definitions'];

					if ($options['silexAliases']) {
						$definitions[] = __DIR__ . '/config.php';
					}

					foreach ($definitions as $file) {
						$builder->addDefinitions($file);
					}

					return $builder;
				});

		$app['di.definitions'] = array();

		$app['di.raw'] = null;

		$app['di.options'] = array();

		$app['di.default_options'] = array(
			'cache' => null,
			'container_class' => 'DI\Container',
			'useAnnotations' => true,
			'useAutowiring' => true,
			'writeProxiesToFile' => false,
			'proxyDirectory' => null,
			'silexAliases' => true,
		);
	}

}
