<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\Exceptions;

class CustomExceptionArgumentChainableConstructorException extends \Exception
{

	public function __construct(string $foo, \Consistence\Sniffs\Exceptions\ChainableConstructorException $e)
	{
		parent::__construct($foo, 0, $e);
	}

}
