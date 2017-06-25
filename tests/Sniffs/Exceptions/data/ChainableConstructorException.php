<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\Exceptions;

class ChainableConstructorException extends \Exception
{

	public function __construct(string $foo, \Throwable $e)
	{
		parent::__construct($foo, 0, $e);
	}

}
