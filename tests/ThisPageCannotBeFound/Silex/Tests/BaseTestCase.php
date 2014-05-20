<?php

namespace ThisPageCannotBeFound\Silex\Tests;

use PHPUnit_Framework_TestCase;
use Silex\Application;
use ThisPageCannotBeFound\Silex\Provider\PhpDiServiceProvider;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class BaseTestCase extends PHPUnit_Framework_TestCase {

	/** @var Application */
	protected $app;

	protected function setUp() {
		$this->app = new Application();

		$this->app->register(new PhpDiServiceProvider());

		$this->app['debug'] = true;
		$this->app['exception_handler']->disable();
	}

}
