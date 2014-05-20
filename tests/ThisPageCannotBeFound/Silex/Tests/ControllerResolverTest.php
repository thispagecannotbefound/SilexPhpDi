<?php

namespace ThisPageCannotBeFound\Silex\Tests;

use stdClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class ControllerResolverTest extends BaseTestCase {

	protected function setUp() {
		parent::setUp();
	}

	/**
	 * @test
	 */
	public function controllerResolverShouldInjectOnServiceController() {
		$controller = new CRT_ControllerA();

		$this->app->get('/', array($controller, 'test'));
		$this->app->handle(Request::create('/'));

		$this->assertNotEmpty($controller->object);
		$this->assertInstanceOf('stdClass', $controller->object);
	}

}

class CRT_ControllerA {

	/**
	 * @inject
	 * @var stdClass
	 */
	public $object;

	public function test() {
		return __METHOD__;
	}

}

