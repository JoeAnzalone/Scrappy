<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Scrappy.php';
require_once __DIR__ . '/ScrapeCommand.php';

use Symfony\Component\Console\Application;

$application = new Application();
$application->setName('Scrappy');
$application->setVersion('0.0.0.0.0.0.1');

$commands[] = new JoeAnzalone\Console\Command\ScrapeCommand();

foreach ($commands as $command) {
    $application->add($command);
}

$application->run();
