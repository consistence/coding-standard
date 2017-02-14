<?php

declare(strict_types = 1);

namespace Consistence\Sniffs\NamingConventions;

use PHP_CodeSniffer_File;

class ValidVariableNameSniffTest extends \Consistence\Sniffs\TestCase
{

	private function getFileReport(): PHP_CodeSniffer_File
	{
		return $this->checkFile(__DIR__ . '/data/FooClass.php');
	}

	public function testValidVariable()
	{
		$this->assertNoSniffError($this->getFileReport(), 12);
	}

	public function testNotCamelCaps()
	{
		$this->assertSniffError($this->getFileReport(), 13, ValidVariableNameSniff::CODE_CAMEL_CAPS, 'incorrect_variable');
	}

	public function testVariableOnObject()
	{
		$this->assertNoSniffError($this->getFileReport(), 14);
	}

	public function testVariableOnClass()
	{
		$this->assertNoSniffError($this->getFileReport(), 15);
	}

	public function testPhpReservedVariables()
	{
		$this->assertNoSniffError($this->getFileReport(), 16);
	}

}
