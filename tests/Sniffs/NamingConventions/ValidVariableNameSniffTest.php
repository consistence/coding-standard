<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File as PhpCsFile;

class ValidVariableNameSniffTest extends \Consistence\Sniffs\TestCase
{

	private function getFileReport(): PhpCsFile
	{
		return $this->checkFile(__DIR__ . '/data/FooClass.php');
	}

	public function testValidVariable(): void
	{
		$this->assertNoSniffError($this->getFileReport(), 12);
	}

	public function testNotCamelCaps(): void
	{
		$this->assertSniffError($this->getFileReport(), 13, ValidVariableNameSniff::CODE_CAMEL_CAPS, 'incorrect_variable');
	}

	public function testVariableOnObject(): void
	{
		$this->assertNoSniffError($this->getFileReport(), 14);
	}

	public function testVariableOnClass(): void
	{
		$this->assertNoSniffError($this->getFileReport(), 15);
	}

	public function testPhpReservedVariables(): void
	{
		$this->assertNoSniffError($this->getFileReport(), 16);
	}

}
