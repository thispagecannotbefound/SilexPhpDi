<?php

return array(
	'Doctrine\DBAL\Connection' => \DI\link('db'),
	'Doctrine\DBAL\Driver\Connection' => \DI\link('db'),
	'Psr\Log\LoggerInterface' => \DI\link('logger'),
	'Monolog\Logger' => \DI\link('monolog'),
	'Symfony\Component\HttpFoundation\Session\Session' => \DI\link('session'),
	'Symfony\Component\HttpFoundation\Session\SessionInterface' => \DI\link('session'),
	'Swift_Mailer' => \DI\link('mailer'),
	'Symfony\Component\Routing\Generator\UrlGenerator' => \DI\link('url_generator'),
	'Symfony\Component\Routing\Generator\UrlGeneratorInterface' => \DI\link('url_generator'),
);
