<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\NamingConventions;

use PHP_CodeSniffer;
use PHP_CodeSniffer_File;

class ValidVariableNameSniff extends \PHP_CodeSniffer_Standards_AbstractVariableSniff
{

	const CODE_CAMEL_CAPS = 'NotCamelCaps';

	/** @var string[] */
	private static $phpReservedVars = [
		'_SERVER',
		'_GET',
		'_POST',
		'_REQUEST',
		'_SESSION',
		'_ENV',
		'_COOKIE',
		'_FILES',
		'GLOBALS',
	];

	/**
	 * @param \PHP_CodeSniffer_File $file
	 * @param int $stackPointer position of the double quoted string
	 */
	protected function processVariable(PHP_CodeSniffer_File $file, $stackPointer)
	{
		$tokens = $file->getTokens();
		$varName = ltrim($tokens[$stackPointer]['content'], '$');

		if (in_array($varName, self::$phpReservedVars, true)) {
			return; // skip PHP reserved vars
		}

		$objOperator = $file->findPrevious([T_WHITESPACE], ($stackPointer - 1), null, true);
		if ($tokens[$objOperator]['code'] === T_DOUBLE_COLON) {
			return; // skip MyClass::$variable, there might be no control over the declaration
		}

		if (!PHP_CodeSniffer::isCamelCaps($varName, false, true, false)) {
			$error = 'Variable "%s" is not in valid camel caps format';
			$data = [$varName];
			$file->addError($error, $stackPointer, self::CODE_CAMEL_CAPS, $data);
		}
	}

	/**
	 * @codeCoverageIgnore
	 *
	 * @param \PHP_CodeSniffer_File $file
	 * @param int $stackPointer position of the double quoted string
	 */
	protected function processMemberVar(PHP_CodeSniffer_File $file, $stackPointer)
	{
		// handled by PSR2.Classes.PropertyDeclaration
	}

	/**
	 * @codeCoverageIgnore
	 *
	 * @param \PHP_CodeSniffer_File $file
	 * @param int $stackPointer position of the double quoted string
	 */
	protected function processVariableInString(PHP_CodeSniffer_File $file, $stackPointer)
	{
		// Consistence standard does not allow variables in strings, handled by Squiz.Strings.DoubleQuoteUsage
	}

}
