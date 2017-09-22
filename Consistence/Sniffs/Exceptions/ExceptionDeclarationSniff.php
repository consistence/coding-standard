<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\Exceptions;

use PHP_CodeSniffer\Files\File as PhpCsFile;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\StringHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;

class ExceptionDeclarationSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{

	const CODE_NOT_ENDING_WITH_EXCEPTION = 'NotEndingWithException';
	const CODE_NOT_CHAINABLE = 'NotChainable';
	const CODE_INCORRECT_EXCEPTION_DIRECTORY = 'IncorrectExceptionDirectory';

	/** @var string */
	public $exceptionsDirectoryName = 'exceptions';

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

		$this->checkExceptionDirectoryName($phpcsFile, $classPointer);

		$this->checkThatExceptionIsChainable($phpcsFile, $classPointer);
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

	private function checkExceptionDirectoryName(PhpCsFile $phpcsFile, int $classPointer)
	{
		$filename = $phpcsFile->getFilename();

		// normalize path for Windows (PHP_CodeSniffer detects it with backslashes on Windows)
		$filename = str_replace('\\', '/', $filename);

		$pathInfo = pathinfo($filename);
		$pathSegments = explode('/', $pathInfo['dirname']);

		$exceptionDirectoryName = array_pop($pathSegments);

		if ($exceptionDirectoryName !== $this->exceptionsDirectoryName) {
			$phpcsFile->addError(sprintf(
				'Exception file "%s" must be placed in "%s" directory (is in "%s").',
				$pathInfo['basename'],
				$this->exceptionsDirectoryName,
				$exceptionDirectoryName
			), $classPointer, self::CODE_INCORRECT_EXCEPTION_DIRECTORY);
		}
	}

	private function checkThatExceptionIsChainable(PhpCsFile $phpcsFile, int $classPointer)
	{
		$constructorPointer = $this->findConstructorMethodPointer($phpcsFile, $classPointer);
		if ($constructorPointer === null) {
			return;
		}

		$typeHints = FunctionHelper::getParametersTypeHints($phpcsFile, $constructorPointer);
		if (count($typeHints) === 0) {
			$phpcsFile->addError(
				'Exception is not chainable. It must have optional \Throwable as last constructor argument.',
				$constructorPointer,
				self::CODE_NOT_CHAINABLE
			);
			return;
		}
		$lastArgument = array_pop($typeHints);

		if ($lastArgument === null) {
			$phpcsFile->addError(
				'Exception is not chainable. It must have optional \Throwable as last constructor argument and has none.',
				$constructorPointer,
				self::CODE_NOT_CHAINABLE
			);
			return;
		}

		if (
			$lastArgument->getTypeHint() !== '\Throwable'
			&& !StringHelper::endsWith($lastArgument->getTypeHint(), 'Exception')
		) {
			$phpcsFile->addError(sprintf(
				'Exception is not chainable. It must have optional \Throwable as last constructor argument and has "%s".',
				$lastArgument->getTypeHint()
			), $constructorPointer, self::CODE_NOT_CHAINABLE);
			return;
		}
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $classPointer
	 * @return int|null
	 */
	private function findConstructorMethodPointer(PhpCsFile $phpcsFile, int $classPointer)
	{
		$functionPointerToScan = $classPointer;
		while (($functionPointer = TokenHelper::findNext($phpcsFile, T_FUNCTION, $functionPointerToScan)) !== null) {
			$functionName = FunctionHelper::getName($phpcsFile, $functionPointer);
			if ($functionName === '__construct') {
				return $functionPointer;
			}
			$functionPointerToScan = $functionPointer + 1;
		}
		return null;
	}

}
