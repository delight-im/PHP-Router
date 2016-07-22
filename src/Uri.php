<?php

/*
 * PHP-Router (https://github.com/delight-im/PHP-Router)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

namespace Delight\Router;

/** Uniform Resource Identifier (URI) */
final class Uri {

	private $str;

	/**
	 * Constructor
	 *
	 * @param string $str the URI as a string
	 */
	public function __construct($str) {
		$this->str = $str;
	}

	/**
	 * Removes the query component from this string
	 *
	 * @return static this instance for chaining
	 */
	public function removeQuery() {
		$this->str = strtok($this->str, '?');

		return $this;
	}

	public function __toString() {
		return $this->str;
	}

}
