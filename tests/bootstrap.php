<?php

// Include helper functions
require_once __DIR__ . '/helpers.php';

// Set up autoloading
if (
    (!$loader = includeIfExists(__DIR__ . '/../vendor/autoload.php')) &&
    (!$loader = includeIfExists(__DIR__ . '/../../../../../autoload.php'))
) {
    die('You must set up the project dependencies, run the following commands:' . PHP_EOL .
        'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL);
}
$loader->add('HumanNameParser\\', __DIR__);
