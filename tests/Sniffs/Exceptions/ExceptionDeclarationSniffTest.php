<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\Exceptions;

class ExceptionDeclarationSniffTest extends \Consistence\Sniffs\TestCase
{

	public function testInvalidExceptionName()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/InvalidExceptionName.php');

		$this->assertSniffError(
			$resultFile,
			7,
			ExceptionDeclarationSniff::CODE_NOT_ENDING_WITH_EXCEPTION,
			'Exception class name "InvalidExceptionName" must end with "Exception".'
		);
	}

	public function testValidClassName()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ValidNameException.php');

		$this->assertNoSniffError($resultFile, 7);
	}

	public function testValidClassNameThatExtendsCustomException()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ValidClassNameThatExtendsCustomException.php');

		$this->assertNoSniffError($resultFile, 7);
	}

	public function testAbstractExceptionWithValidNameException()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/AbstractExceptionWithValidNameException.php');

		$this->assertNoSniffError($resultFile, 7);
	}

	public function testAbstractClassWithInvalidExceptionName()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/AbstractExceptionWithInvalidName.php');

		$this->assertSniffError(
			$resultFile,
			7,
			ExceptionDeclarationSniff::CODE_NOT_ENDING_WITH_EXCEPTION,
			'Exception class name "AbstractExceptionWithInvalidName" must end with "Exception".'
		);
	}

	public function testClassThatDoesNotExtendAnything()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ClassThatDoesNotExtendAnything.php');

		$this->assertNoSniffError($resultFile, 7);
	}

	public function testClassThatExtendsRegularClass()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ClassThatDoesNotExtendException.php');

		$this->assertNoSniffError($resultFile, 7);
	}

	public function testInterfaceThatDoesNotExtendAnything()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/InterfaceThatDoesNotExtendAnything.php');

		$this->assertNoSniffError($resultFile, 7);
	}

	public function testInterfaceThatDoesNotExtendAnythingException()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/InterfaceThatDoesNotExtendAnythingException.php');

		$this->assertNoSniffError($resultFile, 7);
	}

	public function testInterfaceThatExtendsException()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/InterfaceThatExtendsException.php');

		$this->assertNoSniffError($resultFile, 7);
	}

	public function testInterfaceThatExtendsExceptionIncorrectName()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/InterfaceThatExtendsExceptionIncorrectName.php');

		$this->assertSniffError(
			$resultFile,
			7,
			ExceptionDeclarationSniff::CODE_NOT_ENDING_WITH_EXCEPTION,
			'Exception class name "InterfaceThatExtendsExceptionIncorrectName" must end with "Exception".'
		);
	}

	public function testExceptionWithConstructorWithoutParametersIsNotChainable()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ConstructWithoutParametersException.php');

		$this->assertSniffError(
			$resultFile,
			10,
			ExceptionDeclarationSniff::CODE_NOT_CHAINABLE,
			'Exception is not chainable. It must have optional \Throwable as last constructor argument.'
		);
	}

	public function testExceptionWithChainableConstructorIsChainable()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/ChainableConstructorException.php');

		$this->assertNoSniffError($resultFile, 10);
	}

	public function testExceptionWithNonchainableConstructorIsNotChainable()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/NonChainableConstructorException.php');

		$this->assertSniffError(
			$resultFile,
			10,
			ExceptionDeclarationSniff::CODE_NOT_CHAINABLE,
			'Exception is not chainable. It must have optional \Throwable as last constructor argument and has "string".'
		);
	}

	public function testExceptionWithConstructorWithoutParameterTypeHintIsNotChainable()
	{
		$resultFile = $this->checkFile(__DIR__ . '/data/NonChainableConstructorWithoutParameterTypehintException.php');

		$this->assertSniffError(
			$resultFile,
			10,
			ExceptionDeclarationSniff::CODE_NOT_CHAINABLE,
			'Exception is not chainable. It must have optional \Throwable as last constructor argument and has none.'
		);
	}

}
