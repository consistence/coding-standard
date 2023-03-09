<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\Exceptions;

use Generator;

class ExceptionDeclarationSniffTest extends \Consistence\Sniffs\TestCase
{

	/**
	 * @return mixed[][]|\Generator
	 */
	public function validExceptionDeclarationDataProvider(): Generator
	{
		yield 'valid class name' => [
			'filePath' => __DIR__ . '/data/ValidNameException.php',
		];
		yield 'valid class name that extends custom exception' => [
			'filePath' => __DIR__ . '/data/ValidClassNameThatExtendsCustomException.php',
		];
		yield 'abstract exception with valid name' => [
			'filePath' => __DIR__ . '/data/AbstractExceptionWithValidNameException.php',
		];
		yield 'class that does not extend anything' => [
			'filePath' => __DIR__ . '/data/ClassThatDoesNotExtendAnything.php',
		];
		yield 'class that extends regular class' => [
			'filePath' => __DIR__ . '/data/ClassThatDoesNotExtendException.php',
		];
		yield 'interface that does not extend anything' => [
			'filePath' => __DIR__ . '/data/InterfaceThatDoesNotExtendAnything.php',
		];
		yield 'interface that does not extend anything exception' => [
			'filePath' => __DIR__ . '/data/InterfaceThatDoesNotExtendAnythingException.php',
		];
		yield 'interface that extends exception' => [
			'filePath' => __DIR__ . '/data/InterfaceThatExtendsException.php',
		];
		yield 'exception with chainable constructor is chainable' => [
			'filePath' => __DIR__ . '/data/ChainableConstructorException.php',
		];
		yield 'exception with custom exception argument is chainable' => [
			'filePath' => __DIR__ . '/data/CustomExceptionArgumentChainableConstructorException.php',
		];
		yield 'exception with error argument is chainable' => [
			'filePath' => __DIR__ . '/data/ErrorArgumentChainableConstructorException.php',
		];
		yield 'exception is placed in correct directory' => [
			'filePath' => __DIR__ . '/data/ValidNameException.php',
		];
	}

	/**
	 * @dataProvider validExceptionDeclarationDataProvider
	 *
	 * @param string $filePath
	 */
	public function testValidExceptionDeclaration(string $filePath): void
	{
		$resultFile = $this->checkFile($filePath, [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function invalidExceptionDeclarationDataProvider(): Generator
	{
		yield 'invalid exception name' => [
			'filePath' => __DIR__ . '/data/InvalidExceptionName.php',
			'exceptionsDirectoryName' => 'data',
			'line' => 7,
			'code' => ExceptionDeclarationSniff::CODE_NOT_ENDING_WITH_EXCEPTION,
			'message' => 'Exception class name "InvalidExceptionName" must end with "Exception".',
		];
		yield 'abstract class with invalid exception name' => [
			'filePath' => __DIR__ . '/data/AbstractExceptionWithInvalidName.php',
			'exceptionsDirectoryName' => 'data',
			'line' => 7,
			'code' => ExceptionDeclarationSniff::CODE_NOT_ENDING_WITH_EXCEPTION,
			'message' => 'Exception class name "AbstractExceptionWithInvalidName" must end with "Exception".',
		];
		yield 'interface that extends exception incorrect name' => [
			'filePath' => __DIR__ . '/data/InterfaceThatExtendsExceptionIncorrectName.php',
			'exceptionsDirectoryName' => 'data',
			'line' => 7,
			'code' => ExceptionDeclarationSniff::CODE_NOT_ENDING_WITH_EXCEPTION,
			'message' => 'Exception class name "InterfaceThatExtendsExceptionIncorrectName" must end with "Exception".',
		];
		yield 'exception with constructor without parameters is not chainable' => [
			'filePath' => __DIR__ . '/data/ConstructWithoutParametersException.php',
			'exceptionsDirectoryName' => 'data',
			'line' => 10,
			'code' => ExceptionDeclarationSniff::CODE_NOT_CHAINABLE,
			'message' => 'Exception is not chainable. It must have optional \Throwable as last constructor argument.',
		];
		yield 'exception with non-chainable constructor is not chainable' => [
			'filePath' => __DIR__ . '/data/NonChainableConstructorException.php',
			'exceptionsDirectoryName' => 'data',
			'line' => 10,
			'code' => ExceptionDeclarationSniff::CODE_NOT_CHAINABLE,
			'message' => 'Exception is not chainable. It must have optional \Throwable as last constructor argument and has "string".',
		];
		yield 'exception with constructor without parameter type hint is not chainable' => [
			'filePath' => __DIR__ . '/data/NonChainableConstructorWithoutParameterTypehintException.php',
			'exceptionsDirectoryName' => 'data',
			'line' => 10,
			'code' => ExceptionDeclarationSniff::CODE_NOT_CHAINABLE,
			'message' => 'Exception is not chainable. It must have optional \Throwable as last constructor argument and has none.',
		];
		yield 'exception is placed in incorrect directory' => [
			'filePath' => __DIR__ . '/data/ValidNameException.php',
			'exceptionsDirectoryName' => 'exceptions',
			'line' => 7,
			'code' => ExceptionDeclarationSniff::CODE_INCORRECT_EXCEPTION_DIRECTORY,
			'message' => 'Exception file "ValidNameException.php" must be placed in "exceptions" directory (is in "data").',
		];
		yield 'exception is placed in incorrect directory case sensitively' => [
			'filePath' => __DIR__ . '/data/ValidNameException.php',
			'exceptionsDirectoryName' => 'Data',
			'line' => 7,
			'code' => ExceptionDeclarationSniff::CODE_INCORRECT_EXCEPTION_DIRECTORY,
			'message' => 'Exception file "ValidNameException.php" must be placed in "Data" directory (is in "data").',
		];
	}

	/**
	 * @dataProvider invalidExceptionDeclarationDataProvider
	 *
	 * @param string $filePath
	 * @param string $exceptionsDirectoryName
	 * @param int $line
	 * @param string $code
	 * @param string $message
	 */
	public function testInvalidExceptionDeclaration(
		string $filePath,
		string $exceptionsDirectoryName,
		int $line,
		string $code,
		string $message
	): void
	{
		$resultFile = $this->checkFile($filePath, [
			'exceptionsDirectoryName' => $exceptionsDirectoryName,
		]);

		$this->assertSniffError(
			$resultFile,
			$line,
			$code,
			$message
		);
	}

	/**
	 * @requires OS WIN
	 */
	public function testExceptionIsPlacedInCorrectDirectoryOnWindows(): void
	{
		// PHP_CodeSniffer detects the path with backslashes on Windows
		$resultFile = $this->checkFile(__DIR__ . '\data\ValidNameException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

}
