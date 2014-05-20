<?php

namespace ThisPageCannotBeFound\Silex\Provider;

use DI\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * @author Abel de Beer <abel@thispagecannotbefound.com>
 */
class PhpDiControllerResolver implements ControllerResolverInterface {

	/** @var Container */
	protected $container;

	/** @var ControllerResolverInterface */
	protected $resolver;

	function __construct(Container $container,
			ControllerResolverInterface $resolver) {
		$this->container = $container;
		$this->resolver = $resolver;
	}

	public function getArguments(Request $request, $controller) {
		return $this->resolver->getArguments($request, $controller);
	}

	public function getController(Request $request) {
		$controller = $this->resolver->getController($request);

		if (!$controller instanceof \Closure) {
			$instance = is_array($controller) ? reset($controller) : $controller;

			if (is_object($instance)) {
				$this->container->injectOn($instance);
			}
		}

		return $controller;
	}

}
