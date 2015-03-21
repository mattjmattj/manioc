<?php
/*

This file is part of Manioc

Copyright (c) 2015, Matthias Jouan
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

namespace Manioc;

use \Maybe\Maybe;

class Container extends \Pimple\Container {
	
	private $maybes = [];
	
	/**
	 * Marks a callable as being a factory service, and wraps it with Maybe.
	 *
	 * @param string $classname The expected class or interface to wrap with Maybe
	 * @param callable $callable A service definition to be used as a factory
	 *
	 * @return callable The passed callable augmented with Maybe
	 *
	 * @throws \InvalidArgumentException Service definition has to be a closure of an invokable object
	 */
	public function maybeFactory($classname, $callable) {
		
		return $this->factory($this->maybe($classname, $callable));
	}
	
	/**
	 * Helper method. Wraps a callable with Maybe
	 * 
	 * @param string $classname The expected class or interface to wrap with Maybe
	 * @param callable $callable A service definition
	 * 
	 * @return callable The passed callable augmented with Maybe
	 */ 
	public function maybe($classname, $callable) {
		// This test comes directly from Pimple
		if (!is_object($callable) || !method_exists($callable, '__invoke')) {
			throw new \InvalidArgumentException(
				'Service definition is not a Closure or invokable object.'
			);
		}
		
		return function ($c) use ($classname, $callable) {
			$maybe = $this->getOrMakeMaybe($classname);
			return $maybe->wrap(call_user_func($callable, $c));
		};
	}
	
	private function getOrMakeMaybe($classname) {
		if (!isset($this->maybes[$classname])) {
			$this->maybes[$classname] = new Maybe($classname);
		}
		return $this->maybes[$classname];
	}
}