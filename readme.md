Consistence Coding Standard
===========================

---

**Complete standard is described in a [separate document](consistence-coding-standard.md)**.

---

This is a custom coding standard which is used by all Consistence projects. It is also suitable to be used with any other project or as a foundation for your own standard.

The main objectives of this standard are:

* Strict (and predictable) code.
* Prevent common mistakes.
* Readability over writability (character count).
* Be friendly to diffs - minimize impact of changes.
* Do not write unnecessary/unreliable information.

Automatic checks
----------------

Automatic checks of this standard are implemented as sniffs for [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) (`phpcs`), but not all the rules are checked yet. PHP_CodeSniffer contains also tool for automatic correction of certain errors `phpcbf`, but this tool is also not 100% reliable - results produced by this may not conform to this standard.

These checks also include some code analysis tools, such as detecting unreachable code etc. These are not part of the standard, but should be helpful.

Stability
---------

This package uses [SemVer](http://semver.org/) with following rules.

Coding Standard document should be considered the source of truth and main object of SemVer.
* `MAJOR` version will be incremented if new rules are added to the document.
* `MINOR` version will be incremented if new sniffs are implemented to check for existing described rules.
* `PATCH` version will be incremented for bug fixing - fixing a bug is considered everything which does not conform to the document - this may even lead to (temporarily) disabling an existing sniff - or part of it, until a better check is available or it is properly fixed.

None of the implementation of the automatic checks (both custom sniffs and the ruleset.xml file) are not subject to the SemVer and may change over time to accommodate changes in PHP_CodeSniffer and provided default sniffs, which are used also by this standard.

Recommended dependency on this package is on `MINOR` version (e.g. `~1.0.0`), which means effectively:
* No new rules will be added.
* New automatic checks may be added.
* You get fixes for existing automatic checks, or some of them may be disabled, if regressions are found.

Installation
------------

Install with composer, add require for `consistence/coding-standard`.

Then run `phpcs` with this standard:
``` bash
vendor/bin/phpcs --standard=vendor/consistence/coding-standard/Consistence/ruleset.xml --extensions=php --encoding=utf-8 -sp src
```

For further usage options see the [PHP_CodeSniffer documentation](https://github.com/squizlabs/PHP_CodeSniffer/wiki).