<?php
require __DIR__ . '/easyphp/base.php';

try {
	$app = \concise\Container::getInstance()->set('app','\concise\App')->get('app');
	$response = $app->run(realpath(__DIR__ . '/app'),['module' => 'chat','controller' => 'Server','method' => 'run']);
	$response->send();
} catch (\Exception $e) {
	print( $e->getMessage() );
}