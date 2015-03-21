Manioc
===========

[![Build Status](https://travis-ci.org/mattjmattj/manioc.svg)](https://travis-ci.org/mattjmattj/manioc)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mattjmattj/manioc/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mattjmattj/manioc/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/mattjmattj/manioc/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mattjmattj/manioc/?branch=master)

An IoC container based on Maybe and Pimple. Manioc actually directly depends on Pimple 3
and extends it with [Maybe](https://github.com/mattjmattj/maybe).

# Installation

with composer

```
composer.phar require mattjmattj/manioc ~1.0
```

# Basic usage

```php
use Manioc\Container;
[...]

$container = new Container();

// A Manioc container is a Pimple 3 container
$container['feature.foo.enabled'] = false;

$container['Cache'] = function($c) {
	new Cache();
}

// ...but with Maybe! Here we use a feature switch to build an instance of Foo
// and wrap it with Maybe. If feature.foo is disabled, Maybe will provide a fake
// object
$container['Foo'] = $container->maybe('Foo',function($c) {
	if ($c['feature.foo.enabled']) {
		return new Foo();
	}
});

// we can also register factories:
$container['Foo'] = $container->maybeFactory('Foo',function($c) {
	if ($c['feature.foo.enabled']) {
		return new Foo();
	}
});

```

# License

Manioc is licensed under BSD-2-Clause license.