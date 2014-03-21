<?php

namespace Consistence\Sniffs\NamingConventions;

class FooClass
{

	public function fooMethod()
	{
		$correctVariable;
		$incorrect_variable;
		$obj->object_variable;
		MyClass::$class_variable;
		$_POST;
	}

}
