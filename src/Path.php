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
