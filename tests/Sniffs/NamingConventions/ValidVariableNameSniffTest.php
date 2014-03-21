<?php

namespace Consistence\Sniffs\NamingConventions;

class ValidVariableNameSniffTest extends \Consistence\Sniffs\TestCase
{

	private function getFileReport()
	{
		return $this->checkFile(__DIR__ . '/data/FooClass.php');
	}

	public function testValidVariable()
	{
		$this->assertNoSniffError($this->getFileReport(), 10);
	}

	public function testNotCamelCaps()
	{
		$this->assertSniffError($this->getFileReport(), 11, ValidVariableNameSniff::CODE_CAMEL_CAPS, 'incorrect_variable');
	}

	public function testVariableOnObject()
	{
		$this->assertNoSniffError($this->getFileReport(), 12);
	}

	public function testVariableOnClass()
	{
		$this->assertNoSniffError($this->getFileReport(), 13);
	}

	public function testPhpReservedVariables()
	{
		$this->assertNoSniffError($this->getFileReport(), 14);
	}

}
