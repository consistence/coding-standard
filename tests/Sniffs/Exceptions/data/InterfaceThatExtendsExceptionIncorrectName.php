<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\Exceptions;

interface InterfaceThatExtendsExceptionIncorrectName extends \Consistence\Sniffs\Exceptions\InterfaceThatDoesNotExtendAnythingException
{

	public function extraString(): string;

}
