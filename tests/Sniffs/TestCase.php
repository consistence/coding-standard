<?php

declare(strict_types = 1);

namespace Consistence\Sniffs;

use PHP_CodeSniffer;
use PHP_CodeSniffer_File;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

	/**
	 * @param string|null $name
	 * @param mixed[] $data
	 * @param string $dataName
	 */
	public function __construct(string $name = null, array $data = [], string $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}

	protected function checkFile(string $filePath): PHP_CodeSniffer_File
	{
		$codeSniffer = new PHP_CodeSniffer();
		$codeSniffer->cli->setCommandLineValues([
			'-s', // showSources must be on, so that errors are recorded
		]);

		$codeSniffer->registerSniffs([$this->getSniffPath()], [], []);
		$codeSniffer->populateTokenListeners();

		return $codeSniffer->processFile($filePath);
	}

	protected function getSniffName(): string
	{
		return preg_replace(
			[
				'~\\\~',
				'~\.Sniffs~',
				'~Sniff$~',
			],
			[
				'.',
				'',
				'',
			],
			$this->getSniffClassName()
		);
	}

	private function getSniffPath(): string
	{
		$path = preg_replace(
			[
				'~\\\~',
				'~Consistence~',
				'~$~',
			],
			[
				'/',
				__DIR__ . '/../../Consistence',
				'.php',
			],
			$this->getSniffClassName()
		);

		return realpath($path);
	}

	protected function getSniffClassName(): string
	{
		return substr(get_class($this), 0, -strlen('Test'));
	}

	/**
	 * @param \PHP_CodeSniffer_File $resultFile
	 * @param int $line
	 * @param string $code code used inside sniff to indicate error type
	 * @param string|null $message match part of text in error message
	 */
	protected function assertSniffError(PHP_CodeSniffer_File $resultFile, int $line, string $code, string $message = null)
	{
		$errors = $resultFile->getErrors();
		$this->assertTrue(
			isset($errors[$line]),
			sprintf('Expected error on line %s, but none occurred', $line)
		);
		$expectedCode = $this->getSniffName() . '.' . $code;
		$this->assertTrue(
			$this->hasError($errors[$line], $expectedCode, $message),
			sprintf(
				'Expected code %s%s, but not found on line %s.%sErrors found on this line:%s%s%s',
				$expectedCode,
				($message !== null) ? sprintf(' with message "%s"', $message) : '',
				$line,
				PHP_EOL,
				PHP_EOL,
				$this->getFormattedErrorsOnLine($errors, $line),
				PHP_EOL
			)
		);
	}

	/**
	 * @param mixed[][][] $errorsForLine
	 * @param string $code
	 * @param string|null $message
	 * @return bool
	 */
	private function hasError(array $errorsForLine, string $code, string $message = null): bool
	{
		foreach ($errorsForLine as $errorsForPosition) {
			foreach ($errorsForPosition as $error) {
				if (
					$error['source'] === $code
					&& ($message === null || strpos($error['message'], $message) !== false)
				) {
					return true;
				}
			}
		}

		return false;
	}

	protected function assertNoSniffError(PHP_CodeSniffer_File $resultFile, int $line)
	{
		$errors = $resultFile->getErrors();
		$this->assertFalse(
			isset($errors[$line]),
			sprintf(
				'Expected no error on line %s, but errors found:%s%s%s',
				$line,
				PHP_EOL,
				$this->getFormattedErrorsOnLine($errors, $line),
				PHP_EOL
			)
		);
	}

	/**
	 * @param mixed[][][][] $errorsForFile
	 * @param int $line
	 * @return string in format <source>: <message>
	 */
	private function getFormattedErrorsOnLine(array $errorsForFile, int $line): string
	{
		if (!isset($errorsForFile[$line])) {
			return '';
		}

		return implode(PHP_EOL, array_map(function (array $errorsForPosition) {
			return implode(PHP_EOL, array_map(function (array $errorForPosition) {
				return sprintf("\t" . '%s: %s', $errorForPosition['source'], $errorForPosition['message']);
			}, $errorsForPosition));
		}, $errorsForFile[$line]));
	}

}
