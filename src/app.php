#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Limpich\Svarog\Commands\AnalyzeFileMethodsCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new AnalyzeFileMethodsCommand());

$application->run();
