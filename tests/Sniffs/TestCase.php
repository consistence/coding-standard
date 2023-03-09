<?php

declare(strict_types = 1);

namespace Consistence\Sniffs;

use PHPUnit\Framework\Assert;
use PHP_CodeSniffer\Config as PhpCsConfig;
use PHP_CodeSniffer\Files\File as PhpCsFile;
use PHP_CodeSniffer\Files\LocalFile as PhpCsLocalFile;
use PHP_CodeSniffer\Runner as PhpCsRunner;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

	/**
	 * @param string|null $name
	 * @param mixed[] $data
	 * @param string $dataName
	 */
	public function __construct(?string $name = null, array $data = [], string $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}

	/**
	 * @param string $filePath
	 * @param mixed[] $sniffProperties
	 * @return \PHP_CodeSniffer\Files\File
	 */
	protected function checkFile(
		string $filePath,
		array $sniffProperties = []
	): PhpCsFile
	{
		if (!is_readable($filePath)) {
			throw new \Exception(sprintf(
				'File "%s" is not readable',
				$filePath
			));
		}

		$codeSniffer = new PhpCsRunner();
		$codeSniffer->config = new PhpCsConfig([
			'-s', // showSources must be on, so that errors are recorded
		]);

		$codeSniffer->init();

		if (count($sniffProperties) > 0) {
			$codeSniffer->ruleset->ruleset[$this->getSniffName()]['properties'] = $sniffProperties;
		}

		$codeSniffer->ruleset->sniffs = [$this->getSniffClassName() => $this->getSniffClassName()];
		$codeSniffer->ruleset->populateTokenListeners();

		$file = new PhpCsLocalFile($filePath, $codeSniffer->ruleset, $codeSniffer->config);
		$file->process();

		return $file;
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

	protected function getSniffClassName(): string
	{
		return substr(get_class($this), 0, -strlen('Test'));
	}

	protected function assertSniffError(PhpCsFile $resultFile, int $line, string $code, ?string $message = null): void
	{
		$errors = $resultFile->getErrors();
		Assert::assertTrue(
			isset($errors[$line]),
			sprintf('Expected error on line %s, but none occurred', $line)
		);
		$expectedCode = $this->getSniffName() . '.' . $code;
		Assert::assertTrue(
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
	private function hasError(iterable $errorsForLine, string $code, ?string $message = null): bool
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

	protected function assertNoSniffError(PhpCsFile $resultFile, int $line): void
	{
		$errors = $resultFile->getErrors();
		Assert::assertFalse(
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

	protected function assertNoSniffErrorInFile(PhpCsFile $file): void
	{
		$errorsForFile = $file->getErrors();

		Assert::assertEmpty($errorsForFile, sprintf(
			'No errors expected, but %d errors found: %s%s%s%s',
			count($errorsForFile),
			PHP_EOL,
			PHP_EOL,
			$this->getFormattedErrorsForFile($errorsForFile),
			PHP_EOL
		));
	}

	/**
	 * @param mixed[][][][] $errorsForFile
	 * @return string
	 */
	private function getFormattedErrorsForFile(array $errorsForFile): string
	{
		$message = '';
		foreach ($errorsForFile as $line => $errorsForPossition) {
			$message .= sprintf(
				'%d:%s%s%s',
				$line,
				PHP_EOL,
				$this->getFormattedErrorsOnLine($errorsForFile, $line),
				PHP_EOL
			);
		}

		return $message;
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
