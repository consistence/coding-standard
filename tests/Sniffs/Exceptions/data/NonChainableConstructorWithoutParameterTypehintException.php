<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\Exceptions;

class NonChainableConstructorWithoutParameterTypehintException extends \Exception
{

	public function __construct($foo, $bar)
	{
		parent::__construct($foo, 0, null);
	}

}
