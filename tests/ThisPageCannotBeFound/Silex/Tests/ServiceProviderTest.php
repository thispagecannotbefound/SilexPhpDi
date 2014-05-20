<?php

namespace ThisPageCannotBeFound\Silex\Tests;

use Silex\Provider\SessionServiceProvider;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class ServiceProviderTest extends BaseTestCase {

	/**
	 * @test
	 */
	public function compositeContainerShouldUsePimpleAsFallback() {
		$name = __METHOD__;
		$value = __LINE__;

		$this->app[$name] = $value;

		$result = $this->app['di']->get($name);

		$this->assertEquals($value, $result);
	}

	/**
	 * @test
	 */
	public function compositeContainerShouldFavorPhpDiOverPimple() {
		$name = __METHOD__;
		$pimpleValue = __LINE__;
		$phpdiValue = __LINE__;

		$this->app['di.raw']->set($name, $phpdiValue);
		$this->app[$name] = $pimpleValue;

		$result = $this->app['di']->get($name);

		$this->assertEquals($phpdiValue, $result);
	}

	/**
	 * @test
	 */
	public function getSilexClassShouldReturnApp() {
		$result = $this->app['di']->get('Silex\Application');

		$this->assertSame($this->app, $result);
	}

	/**
	 * @test
	 */
	public function aliasesShouldGetSilexServices() {
		$interface = 'Symfony\Component\HttpFoundation\Session\SessionInterface';

		$this->app->register(new SessionServiceProvider());
		$this->app['session.test'] = true;

		$result = $this->app['di']->get($interface);

		$this->assertSame($this->app['session'], $result);
		$this->assertInstanceOf($interface, $result);
	}

}
