<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\Exceptions;

interface InterfaceThatExtendsException extends \Consistence\Sniffs\Exceptions\InterfaceThatDoesNotExtendAnythingException
{

	public function extraString(): string;

}
