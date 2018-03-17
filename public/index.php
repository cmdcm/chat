<?php
require __DIR__ . '/../easyphp/base.php';

$app = \concise\Container::getInstance()->set('app','\concise\App')->get('app');

$response = $app->run(realpath(__DIR__ . '/../app'));

$response->send();