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

require __DIR__.'/Path.php';
require __DIR__.'/Uri.php';

/** Router for PHP. Simple, lightweight and convenient. */
class Router {

	const REGEX_PATH_PARAMS = '/(?<=\/):([^\/]+)(?=\/|$)/';
	const REGEX_PATH_SEGMENT = '([^\/]+)';
	const REGEX_DELIMITER = '/';

	protected $rootPath;
	protected $requestPath;
	protected $requestMethod;

	/**
	 * Constructor
	 *
	 * @param string $rootPath the base path to use for routing (optional)
	 */
	public function __construct($rootPath = '') {
		$this->rootPath = (string) (new Path($rootPath))->normalize()->removeTrailingSlashes();
		$this->requestPath = urldecode((string) (new Uri($_SERVER['REQUEST_URI']))->removeQuery());
		$this->requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
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

	private function matchRoute($route) {
		$params = array();

		// create the regex that matches paths against the route
		$routeRegex = $this->createRouteRegex($route, $params);

		// if the route regex matches the current request path
		if (preg_match($routeRegex, $this->requestPath, $matches)) {
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
		// if the route regex does not match the current request path
		else {
			return false;
		}
	}

	private function addRoute($expectedRequestMethod, $expectedRoute, $callback) {
		if ($expectedRequestMethod === $this->requestMethod) {
			$routeArgs = $this->matchRoute($expectedRoute);

			// if the route matches the current request
			if ($routeArgs !== false) {
				// if a callback has been set
				if (isset($callback) && is_callable($callback)) {
					// execute the callback
					call_user_func_array($callback, $routeArgs);
				}

				// the route matches the current request
				return true;
			}
		}

		// the route does not match the current request
		return false;
	}

	private function createRouteRegex($route, &$params) {
		// extract the parameters from the route (if any) and make the route a regex
		self::processUriParams($route, $params);

		// escape the base path for regex and prepend it to the route
		return static::REGEX_DELIMITER . '^' . static::regexEscape($this->rootPath) . $route . '$' . static::REGEX_DELIMITER;
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

	protected static function regexEscape($str) {
		return preg_quote($str, static::REGEX_DELIMITER);
	}

}
