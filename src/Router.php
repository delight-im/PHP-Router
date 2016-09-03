<?php

/*
 * PHP-Router (https://github.com/delight-im/PHP-Router)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

namespace Delight\Router;

require __DIR__.'/Path.php';
require __DIR__.'/Uri.php';

/** Router for PHP. Simple, lightweight and convenient. */
final class Router {

	/** Regular expression used to find named parameters in routes */
	const REGEX_PATH_PARAMS = '/(?<=\/):([^\/]+)(?=\/|$)/';
	/** Regular expression used to match a single segment of a path */
	const REGEX_PATH_SEGMENT = '([^\/]+)';
	/** Delimiter used in regular expressions */
	const REGEX_DELIMITER = '/';

	/** @var string the root path that this instance is working under */
	private $rootPath;
	/** @var string the route of the current request */
	private $route;
	/** @var string the request method of the current request */
	private $requestMethod;

	/**
	 * Constructor
	 *
	 * @param string $rootPath the base path to use for routing (optional)
	 */
	public function __construct($rootPath = '') {
		$this->rootPath = (string) (new Path($rootPath))->normalize()->removeTrailingSlashes();
		$this->route = urldecode((string) (new Uri($_SERVER['REQUEST_URI']))->removeQuery());
		$this->requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
	}

	/**
	 * Adds a new route for the HTTP request method `GET` and executes the specified callback if the route matches
	 *
	 * @param string $route the route to match, e.g. `/users/jane`
	 * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
	 * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
	 * @return bool whether the route matched the current request
	 */
	public function get($route, $callback = null, $injectArgs = null) {
		return $this->addRoute('get', $route, $callback, $injectArgs);
	}

	/**
	 * Adds a new route for the HTTP request method `POST` and executes the specified callback if the route matches
	 *
	 * @param string $route the route to match, e.g. `/users/jane`
	 * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
	 * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
	 * @return bool whether the route matched the current request
	 */
	public function post($route, $callback = null, $injectArgs = null) {
		return $this->addRoute('post', $route, $callback, $injectArgs);
	}

	/**
	 * Adds a new route for the HTTP request method `PUT` and executes the specified callback if the route matches
	 *
	 * @param string $route the route to match, e.g. `/users/jane`
	 * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
	 * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
	 * @return bool whether the route matched the current request
	 */
	public function put($route, $callback = null, $injectArgs = null) {
		return $this->addRoute('put', $route, $callback, $injectArgs);
	}

	/**
	 * Adds a new route for the HTTP request method `PATCH` and executes the specified callback if the route matches
	 *
	 * @param string $route the route to match, e.g. `/users/jane`
	 * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
	 * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
	 * @return bool whether the route matched the current request
	 */
	public function patch($route, $callback = null, $injectArgs = null) {
		return $this->addRoute('patch', $route, $callback, $injectArgs);
	}

	/**
	 * Adds a new route for the HTTP request method `DELETE` and executes the specified callback if the route matches
	 *
	 * @param string $route the route to match, e.g. `/users/jane`
	 * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
	 * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
	 * @return bool whether the route matched the current request
	 */
	public function delete($route, $callback = null, $injectArgs = null) {
		return $this->addRoute('delete', $route, $callback, $injectArgs);
	}

	/**
	 * Adds a new route for the HTTP request method `HEAD` and executes the specified callback if the route matches
	 *
	 * @param string $route the route to match, e.g. `/users/jane`
	 * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
	 * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
	 * @return bool whether the route matched the current request
	 */
	public function head($route, $callback = null, $injectArgs = null) {
		return $this->addRoute('head', $route, $callback, $injectArgs);
	}

	/**
	 * Adds a new route for the HTTP request method `TRACE` and executes the specified callback if the route matches
	 *
	 * @param string $route the route to match, e.g. `/users/jane`
	 * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
	 * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
	 * @return bool whether the route matched the current request
	 */
	public function trace($route, $callback = null, $injectArgs = null) {
		return $this->addRoute('trace', $route, $callback, $injectArgs);
	}

	/**
	 * Adds a new route for the HTTP request method `OPTIONS` and executes the specified callback if the route matches
	 *
	 * @param string $route the route to match, e.g. `/users/jane`
	 * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
	 * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
	 * @return bool whether the route matched the current request
	 */
	public function options($route, $callback = null, $injectArgs = null) {
		return $this->addRoute('options', $route, $callback, $injectArgs);
	}

	/**
	 * Adds a new route for the HTTP request method `CONNECT` and executes the specified callback if the route matches
	 *
	 * @param string $route the route to match, e.g. `/users/jane`
	 * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
	 * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
	 * @return bool whether the route matched the current request
	 */
	public function connect($route, $callback = null, $injectArgs = null) {
		return $this->addRoute('connect', $route, $callback, $injectArgs);
	}

	/**
	 * Returns the root path that this instance is working under
	 *
	 * @return string the path
	 */
	public function getRootPath() {
		return $this->rootPath;
	}

	/**
	 * Returns the route of the current request
	 *
	 * @return string the route
	 */
	public function getRoute() {
		return $this->route;
	}

	/**
	 * Returns the request method of the current request
	 *
	 * @return string the method name
	 */
	public function getRequestMethod() {
		return $this->requestMethod;
	}

	/**
	 * Attempts to match the route of the current request against the specified route
	 *
	 * @param string $expectedRoute the route to match against
	 * @return array|null the list of matched parameters or `null` if the route didn't match
	 */
	private function matchRoute($expectedRoute) {
		$params = array();

		// create the regex that matches paths against the route
		$expectedRouteRegex = $this->createRouteRegex($expectedRoute, $params);

		// if the route regex matches the current request path
		if (preg_match($expectedRouteRegex, $this->route, $matches)) {
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
			return null;
		}
	}

	/**
	 * Checks the specified route against the current request to see if it matches
	 *
	 * @param string $expectedRequestMethod the request method that must be found in order to have a match
	 * @param string $expectedRoute the route that must be found in order to have a match
	 * @param callable|null $callback (optional) the callback to execute, e.g. an anonymous function
	 * @param array|null $injectArgs (optional) any arguments that should be prepended to those matched in the route
	 * @return bool whether the route matched the current request
	 */
	private function addRoute($expectedRequestMethod, $expectedRoute, $callback = null, $injectArgs = null) {
		if ($expectedRequestMethod === $this->requestMethod) {
			$matchedArgs = $this->matchRoute($expectedRoute);

			// if the route matches the current request
			if ($matchedArgs !== null) {
				// if a callback has been set
				if (isset($callback) && is_callable($callback)) {
					// if additional arguments to be injected have been pre-defined
					if (!empty($injectArgs) && is_array($injectArgs)) {
						// prepend these arguments
						$matchedArgs = array_merge($injectArgs, $matchedArgs);
					}

					// execute the callback
					call_user_func_array($callback, $matchedArgs);
				}

				// the route matches the current request
				return true;
			}
		}

		// the route does not match the current request
		return false;
	}

	/**
	 * Creates a regular expression that can be used to match the specified route
	 *
	 * @param string $expectedRoute the route to create a regular expression for
	 * @param array $params the array that should receive the matched parameters
	 * @return string the composed regular expression
	 */
	private function createRouteRegex($expectedRoute, &$params) {
		// extract the parameters from the route (if any) and make the route a regex
		self::processUriParams($expectedRoute, $params);

		// escape the base path for regex and prepend it to the route
		return static::REGEX_DELIMITER . '^' . static::regexEscape($this->rootPath) . $expectedRoute . '$' . static::REGEX_DELIMITER;
	}

	/**
	 * Extracts parameters from a path
	 *
	 * @param string $path the path to extract the parameters from
	 * @param array $params the array that should receive the matched parameters
	 */
	private static function processUriParams(&$path, &$params) {
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

	/**
	 * Escapes the supplied string for use in a regular expression
	 *
	 * @param string $str the string to escape
	 * @return string the escaped string
	 */
	private static function regexEscape($str) {
		return preg_quote($str, static::REGEX_DELIMITER);
	}

}
