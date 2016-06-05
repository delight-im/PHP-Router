<?php

header('Content-type: text/html; charset=utf-8');

require_once(__DIR__.'/../src/Router.php');

$router = new Router('/example');

$router->get('/', function () {
	echo '<h1>Welcome</h1>';
	echo '<p>Lorem ipsum</p>';
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

$router->run();
