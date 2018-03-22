<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\NamingConventions;

class FooClass
{

	public function fooMethod(): void
	{
		$correctVariable;
		$incorrect_variable;
		$obj->object_variable;
		MyClass::$class_variable;
		$_POST;
	}

}
