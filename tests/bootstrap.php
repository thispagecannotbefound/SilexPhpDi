<?php

ini_set('display_errors', 1);
ini_set('error_reporting', -1);

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('ThisPageCannotBeFound\Silex\Tests', __DIR__);
$loader->add('Silex\Tests', __DIR__ . '/../vendor/silex/silex/tests');
