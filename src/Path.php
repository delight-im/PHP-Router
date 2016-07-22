<?php

/*
 * PHP-Router (https://github.com/delight-im/PHP-Router)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

namespace Delight\Router;

/** Path component of a URI */
final class Path {

	private $str;

	/**
	 * Constructor
	 *
	 * @param string $str the path as a string
	 */
	public function __construct($str) {
		$this->str = $str;
	}

	/**
	 * Normalizes the path
	 *
	 * @return static this instance for chaining
	 */
	public function normalize() {
		// remove whitespace from the beginning
		$this->str = ltrim($this->str);

		// ensure that there is exactly one forward slash at the beginning
		$this->str = '/'.ltrim($this->str, '/');

		// remove whitespace from the end
		$this->str = rtrim($this->str);


		return $this;
	}

	/**
	 * Removes any trailing slashes
	 *
	 * @return static this instance for chaining
	 */
	public function removeTrailingSlashes() {
		// ensure that there is no forward slash at the end
		$this->str = rtrim($this->str, '/');

		return $this;
	}

	/**
	 * Whether this path is absolute
	 *
	 * @return bool whether the path is absolute
	 */
	public function isAbsolute() {
		return isset($this->str[0]) && $this->str[0] === '/';
	}

	/**
	 * Whether this path is relative
	 *
	 * @return bool whether the path is relative
	 */
	public function isRelative() {
		return !$this->isAbsolute();
	}

	public function __toString() {
		return $this->str;
	}

}
