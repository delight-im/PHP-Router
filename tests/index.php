<?php

/*
 * PHP-Router (https://github.com/delight-im/PHP-Router)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

error_reporting(E_ALL);
ini_set('display_errors', 'stdout');

header('Content-type: text/html; charset=utf-8');

require __DIR__.'/../vendor/autoload.php';

$router = new \Delight\Router\Router('/PHP-Router/tests');

$router->get('/', function () {
	echo '<h1>Welcome</h1>';
	echo '<p>Hello world</p>';

	// use some values from `$_GET` here
});
$router->get('/user/:id/:name', function ($id, $name) {
	echo '<h1>Profile: '.htmlspecialchars($name).'</h1>';
	echo '<p>My user ID is '.intval($id).'</p>';

	// you may use `$_GET` in addition to the callback arguments
});
$router->post('/sign_up', function () {
	// create a new account with values from `$_POST`
});
$router->put('/articles/5', function () {
	// do something
});
