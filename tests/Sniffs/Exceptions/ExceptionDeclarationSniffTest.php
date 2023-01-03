<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\Exceptions;

class ExceptionDeclarationSniffTest extends \Consistence\Sniffs\TestCase
{

	public function testInvalidExceptionName(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/InvalidExceptionName.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertSniffError(
			$resultFile,
			7,
			ExceptionDeclarationSniff::CODE_NOT_ENDING_WITH_EXCEPTION,
			'Exception class name "InvalidExceptionName" must end with "Exception".'
		);
	}

	public function testValidClassName(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ValidNameException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testValidClassNameThatExtendsCustomException(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ValidClassNameThatExtendsCustomException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testAbstractExceptionWithValidNameException(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/AbstractExceptionWithValidNameException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testAbstractClassWithInvalidExceptionName(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/AbstractExceptionWithInvalidName.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertSniffError(
			$resultFile,
			7,
			ExceptionDeclarationSniff::CODE_NOT_ENDING_WITH_EXCEPTION,
			'Exception class name "AbstractExceptionWithInvalidName" must end with "Exception".'
		);
	}

	public function testClassThatDoesNotExtendAnything(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ClassThatDoesNotExtendAnything.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testClassThatExtendsRegularClass(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ClassThatDoesNotExtendException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testInterfaceThatDoesNotExtendAnything(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/InterfaceThatDoesNotExtendAnything.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testInterfaceThatDoesNotExtendAnythingException(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/InterfaceThatDoesNotExtendAnythingException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testInterfaceThatExtendsException(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/InterfaceThatExtendsException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testInterfaceThatExtendsExceptionIncorrectName(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/InterfaceThatExtendsExceptionIncorrectName.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertSniffError(
			$resultFile,
			7,
			ExceptionDeclarationSniff::CODE_NOT_ENDING_WITH_EXCEPTION,
			'Exception class name "InterfaceThatExtendsExceptionIncorrectName" must end with "Exception".'
		);
	}

	public function testExceptionWithConstructorWithoutParametersIsNotChainable(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ConstructWithoutParametersException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertSniffError(
			$resultFile,
			10,
			ExceptionDeclarationSniff::CODE_NOT_CHAINABLE,
			'Exception is not chainable. It must have optional \Throwable as last constructor argument.'
		);
	}

	public function testExceptionWithChainableConstructorIsChainable(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ChainableConstructorException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testExceptionWithCustomExceptionArgumentIsChainable(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/CustomExceptionArgumentChainableConstructorException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testExceptionWithErrorArgumentIsChainable(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ErrorArgumentChainableConstructorException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testExceptionWithNonchainableConstructorIsNotChainable(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/NonChainableConstructorException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertSniffError(
			$resultFile,
			10,
			ExceptionDeclarationSniff::CODE_NOT_CHAINABLE,
			'Exception is not chainable. It must have optional \Throwable as last constructor argument and has "string".'
		);
	}

	public function testExceptionWithConstructorWithoutParameterTypeHintIsNotChainable(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/NonChainableConstructorWithoutParameterTypehintException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertSniffError(
			$resultFile,
			10,
			ExceptionDeclarationSniff::CODE_NOT_CHAINABLE,
			'Exception is not chainable. It must have optional \Throwable as last constructor argument and has none.'
		);
	}

	public function testExceptionIsPlacedInCorrectDirectory(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ValidNameException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	/**
	 * @requires OS WIN
	 */
	public function testExceptionIsPlacedInCorrectDirectoryOnWindows(): void
	{
		// PHP_CodeSniffer detects the path with backslashes on Windows
		$resultFile = $this->checkFile(__DIR__ . '/data/ValidNameException.php', [
			'exceptionsDirectoryName' => 'data',
		]);

		$this->assertNoSniffErrorInFile($resultFile);
	}

	public function testExceptionIsPlacedInIncorrectDirectory(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ValidNameException.php', [
			'exceptionsDirectoryName' => 'exceptions',
		]);

		$this->assertSniffError(
			$resultFile,
			7,
			ExceptionDeclarationSniff::CODE_INCORRECT_EXCEPTION_DIRECTORY,
			'Exception file "ValidNameException.php" must be placed in "exceptions" directory (is in "data").'
		);
	}

	public function testExceptionIsPlacedInIncorrectDirectoryCaseSensitively(): void
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ValidNameException.php', [
			'exceptionsDirectoryName' => 'Data',
		]);

		$this->assertSniffError(
			$resultFile,
			7,
			ExceptionDeclarationSniff::CODE_INCORRECT_EXCEPTION_DIRECTORY,
			'Exception file "ValidNameException.php" must be placed in "Data" directory (is in "data").'
		);
	}

}
