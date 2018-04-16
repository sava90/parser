<?php
require_once __DIR__ . '/vendor/autoload.php';

use ParserApplication\Command\ParserCommand;
use ParserApplication\Manager\HtmlManager;

$params = $argv;
array_shift($params);

$parserCommand = new ParserCommand();
$htmlManager = new HtmlManager();
$parserCommand->execute($params);
