<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\Exceptions;

use PHP_CodeSniffer\Files\File as PhpCsFile;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\StringHelper;

class ExceptionDeclarationSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{

	const CODE_NOT_ENDING_WITH_EXCEPTION = 'NotEndingWithException';

	/**
	 * @return int[]
	 */
	public function register(): array
	{
		return [
			T_CLASS,
			T_INTERFACE,
		];
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $classPointer
	 */
	public function process(PhpCsFile $phpcsFile, $classPointer)
	{
		$extendedClassName = $phpcsFile->findExtendedClassName($classPointer);
		if ($extendedClassName === false) {
			return; //does not extend anything
		}

		if (!StringHelper::endsWith($extendedClassName, 'Exception')) {
			return; // does not extend Exception, is not an exception
		}

		$this->checkExceptionName($phpcsFile, $classPointer);
	}

	private function checkExceptionName(PhpCsFile $phpcsFile, int $classPointer)
	{
		$className = ClassHelper::getName($phpcsFile, $classPointer);
		if (!StringHelper::endsWith($className, 'Exception')) {
			$phpcsFile->addError(sprintf(
				'Exception class name "%s" must end with "Exception".',
				$className
			), $classPointer, self::CODE_NOT_ENDING_WITH_EXCEPTION);
		}
	}

}
