<?php

require_once 'vendor/autoload.php';

$filePath = PHP_SAPI === 'cli' ? $argv[1] : 'logs/test.log';

$parser = new \App\Parser(new \App\Adapters\AccessLogAdapter($filePath));
$handler = new \App\Handlers\RoistatLogHandler();
$parser->setHandler($handler)->parse();

