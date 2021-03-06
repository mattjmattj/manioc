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

namespace Manioc\Tests;

class ContainerTest extends \PHPUnit_Framework_TestCase {
	
	public function testManiocContainerIsAPimpleContainer() {
		$manioc = new \Manioc\Container();
		$this->assertInstanceOf('\Pimple\Container', $manioc);
	}
	
	public function testTheMaybeMethodShouldHelpWrapACallable() {
		$manioc = new \Manioc\Container();
		
		$fn = $manioc->maybe('Manioc\Tests\Foo', function($c) {
			$this->assertInstanceOf('\Pimple\Container', $c);
			//nothing!
		});
		
		$this->assertNotEquals('Bar', $fn($manioc)->getBar());
		$this->assertInternalType('string', $fn($manioc)->getBar());
	}
	
	/** @expectedException InvalidArgumentException */
	public function testTheMaybeMethodShouldThrowWhenTheCallableIsIncompatibleWithPimple() {
		$manioc = new \Manioc\Container();
		
		$manioc->maybe('Manioc\Tests\Foo', ['foobar']);
	}
	
	public function testFactoriesCanBeWrappedByMaybe() {
		$manioc = new \Manioc\Container();
		
		$manioc['factory'] = $manioc->maybeFactory('Manioc\Tests\Foo', function($c) {
			$this->assertInstanceOf('\Pimple\Container', $c);
			return new Foo();
		});
		$this->assertEquals('Bar', $manioc['factory']->getBar());
		
		$manioc['factory'] = $manioc->maybeFactory('Manioc\Tests\Foo', function($c) {
			$this->assertInstanceOf('\Pimple\Container', $c);
			//nothing!
		});
		$this->assertNotEquals('Bar', $manioc['factory']->getBar());
		$this->assertInternalType('string', $manioc['factory']->getBar());
	}
	
}

class Foo {
	/** @return string */
	public function getBar() {
		return 'Bar';
	}
}