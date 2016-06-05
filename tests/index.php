<?php

/*
 * Copyright (c) delight.im <info@delight.im>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

header('Content-type: text/html; charset=utf-8');

require_once(__DIR__.'/../src/Router.php');

$router = new Router('/tests');

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
