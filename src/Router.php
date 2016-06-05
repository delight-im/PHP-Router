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

namespace Delight\Router;

/** Router for PHP. Simple, lightweight and convenient. */
class Router {

	const REGEX_PATH_PARAMS = '/(?<=\/):([^\/]+)(?=\/|$)/';
	const REGEX_PATH_SEGMENT = '([^\/]+)';
	const REGEX_DELIMITER = '/';

	protected $basePath;
	protected $requestUri;
	protected $routes;

	/**
	 * Constructor
	 *
	 * @param string $basePath the base path to use for routing (optional)
	 */
	public function __construct($basePath = '') {
		$this->basePath = static::validateBasePath($basePath);
		$this->requestUri = static::parseRequestUri();
		$this->routes = array();
	}

	/**
	 * Adds a new route for the HTTP method GET
	 *
	 * @param string $path the path to map, e.g. `/users/jane`
	 * @param callable|null $callback the callback to execute, e.g. an anonymous function
	 */
	public function get($path = '/', $callback = null) {
		$this->addRoute('get', $path, $callback);
	}

	/**
	 * Adds a new route for the HTTP method POST
	 *
	 * @param string $path the path to map, e.g. `/users/jane`
	 * @param callable|null $callback the callback to execute, e.g. an anonymous function
	 */
	public function post($path = '/', $callback = null) {
		$this->addRoute('post', $path, $callback);
	}

	/**
	 * Adds a new route for the HTTP method PUT
	 *
	 * @param string $path the path to map, e.g. `/users/jane`
	 * @param callable|null $callback the callback to execute, e.g. an anonymous function
	 */
	public function put($path = '/', $callback = null) {
		$this->addRoute('put', $path, $callback);
	}

	/**
	 * Adds a new route for the HTTP method PATCH
	 *
	 * @param string $path the path to map, e.g. `/users/jane`
	 * @param callable|null $callback the callback to execute, e.g. an anonymous function
	 */
	public function patch($path = '/', $callback = null) {
		$this->addRoute('patch', $path, $callback);
	}

	/**
	 * Adds a new route for the HTTP method DELETE
	 *
	 * @param string $path the path to map, e.g. `/users/jane`
	 * @param callable|null $callback the callback to execute, e.g. an anonymous function
	 */
	public function delete($path = '/', $callback = null) {
		$this->addRoute('delete', $path, $callback);
	}

	/**
	 * Adds a new route for the HTTP method HEAD
	 *
	 * @param string $path the path to map, e.g. `/users/jane`
	 * @param callable|null $callback the callback to execute, e.g. an anonymous function
	 */
	public function head($path = '/', $callback = null) {
		$this->addRoute('head', $path, $callback);
	}

	/**
	 * Adds a new route for the HTTP method TRACE
	 *
	 * @param string $path the path to map, e.g. `/users/jane`
	 * @param callable|null $callback the callback to execute, e.g. an anonymous function
	 */
	public function trace($path = '/', $callback = null) {
		$this->addRoute('trace', $path, $callback);
	}

	/**
	 * Adds a new route for the HTTP method OPTIONS
	 *
	 * @param string $path the path to map, e.g. `/users/jane`
	 * @param callable|null $callback the callback to execute, e.g. an anonymous function
	 */
	public function options($path = '/', $callback = null) {
		$this->addRoute('options', $path, $callback);
	}

	/**
	 * Adds a new route for the HTTP method CONNECT
	 *
	 * @param string $path the path to map, e.g. `/users/jane`
	 * @param callable|null $callback the callback to execute, e.g. an anonymous function
	 */
	public function connect($path = '/', $callback = null) {
		$this->addRoute('connect', $path, $callback);
	}

	/**
	 * Matches the request path against the specified routes and executes the callback (if found)
	 *
	 * This method must *always* be called at the end of the routing definitions
	 */
	public function run() {
		$requestMethod = strtolower($_SERVER['REQUEST_METHOD']);

		// if there are no routes for the current request method
		if (!isset($this->routes[$requestMethod])) {
			// no route could be matched and executed
			return false;
		}

		// iterate over all routes for the current request method
		foreach ($this->routes[$requestMethod] as $path => $callback) {
			$routeArgs = $this->getRouteArgs($path);
			if ($routeArgs !== false) {
				if (isset($callback) && is_callable($callback)) {
					call_user_func_array($callback, $routeArgs);
				}

				// a route has been matched and executed
				return true;
			}
		}

		// no route could be matched and executed
		return false;
	}

	protected function getRouteArgs($path) {
		// get the route parameters (if any) and the regex to match URIs
		$params = array();
		$routeRegex = $this->createRouteRegex($path, $params);

		// if the route regex matches the current request URI
		if (preg_match($routeRegex, $this->requestUri, $matches)) {
			if (count($matches) > 1) {
				// remove the first match (which is the full route match)
				array_shift($matches);

				// use the extracted parameters as the arguments' keys and the matches as the arguments' values
				return array_combine($params, $matches);
			}
			else {
				return array();
			}
		}
		// if the route does not match the current request URI
		else {
			return false;
		}
	}

	protected function addRoute($method, $path, $callback) {
		if (!isset($this->routes[$method])) {
			$this->routes[$method] = array();
		}

		$this->routes[$method][$path] = $callback;
	}

	protected function createRouteRegex($path, &$params) {
		// extract the parameters from the route (if any) and make the route a regex
		self::processUriParams($path, $params);

		// escape the base path for regex and prepend it to the route
		return static::REGEX_DELIMITER . '^' . static::regexEscape($this->basePath) . $path . '$' . static::REGEX_DELIMITER;
	}

	protected static function processUriParams(&$path, &$params) {
		// if the route path contains parameters like `:key`
		if (preg_match_all(static::REGEX_PATH_PARAMS, $path, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
			$previousMatchEnd = 0;
			$regexParts = array();

			// extract all parameter names and create a regex that matches URIs and captures the parameters' values
			foreach ($matches as $match) {
				// remember the boundaries of the full match (e.g. `:key`) in the subject
				$matchStart = $match[0][1];
				$matchEnd = $matchStart + strlen($match[0][0]);

				// keep the part between this one and the previous match and escape it for regex
				$regexParts[] = static::regexEscape(substr($path, $previousMatchEnd, $matchStart - $previousMatchEnd));

				// save the current parameter's name
				$params[] = $match[1][0];

				// insert an expression that will match the parameter's value
				$regexParts[] = static::REGEX_PATH_SEGMENT;

				// remember the end index of the current match
				$previousMatchEnd = $matchEnd;
			}

			// keep the part after the last match and escape it for regex
			$regexParts[] = static::regexEscape(substr($path, $previousMatchEnd));

			// replace the parameterized URI with a regex that matches the parameters' values
			$path = implode('', $regexParts);
		}
		// if the route path is not parameterized
		else {
			// just escape the path for literal usage in regex
			$path = static::regexEscape($path);
		}
	}

	protected static function validateBasePath($basePath) {
		// if the base path does not start with a slash
		if (substr($basePath, 0, 1) !== '/') {
			// prepend a slash
			$basePath = '/'.$basePath;
		}

		// if the base path ends with a slash
		if (substr($basePath, -1) === '/') {
			// cut off the trailing slash
			return substr($basePath, 0, -1);
		}

		return $basePath;
	}

	protected static function parseRequestUri() {
		$uri = $_SERVER['REQUEST_URI'];

		// get the position of the query string
		$queryStringStart = strpos($uri, '?');

		// if the URI contains a query string
		if ($queryStringStart !== false) {
			// cut off the query string
			$uri = substr($uri, 0, $queryStringStart);
		}

		return $uri;
	}

	protected static function regexEscape($str) {
		return preg_quote($str, static::REGEX_DELIMITER);
	}

}
