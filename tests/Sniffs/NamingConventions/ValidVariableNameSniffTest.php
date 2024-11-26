<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\NamingConventions;

use Generator;
use PHP_CodeSniffer\Files\File as PhpCsFile;

class ValidVariableNameSniffTest extends \Consistence\Sniffs\TestCase
{

	private function getFileReport(): PhpCsFile
	{
		return $this->checkFile(__DIR__ . '/data/FooClass.php');
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function validVariableNameDataProvider(): Generator
	{
		yield 'variable' => [
			'line' => 12,
		];
		yield 'variable on object' => [
			'line' => 14,
		];
		yield 'variable on class' => [
			'line' => 15,
		];
		yield 'PHP reserved variable' => [
			'line' => 16,
		];
	}

	/**
	 * @dataProvider validVariableNameDataProvider
	 *
	 * @param int $line
	 */
	public function testValidVariableName(
		int $line
	): void
	{
		$this->assertNoSniffError(
			$this->getFileReport(),
			$line
		);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function invalidVariableNameDataProvider(): Generator
	{
		yield 'not camel caps' => [
			'line' => 13,
			'code' => ValidVariableNameSniff::CODE_CAMEL_CAPS,
			'message' => 'incorrect_variable',
		];
	}

	/**
	 * @dataProvider invalidVariableNameDataProvider
	 *
	 * @param int $line
	 * @param string $code
	 * @param string $message
	 */
	public function testInvalidVariableName(
		int $line,
		string $code,
		string $message
	): void
	{
		$this->assertSniffError(
			$this->getFileReport(),
			$line,
			$code,
			$message
		);
	}

}
