Consistence Coding Standard
===========================

General naming conventions
--------------------------

* Avoid abbreviations, use them only if long name would be less readable.
* For 2-letter shortcuts use `UPPERCASE`, for longer `PascalCase`.

```php
<?php

use Foo\IP\Bar;
use Foo\Php\Bar;
use Foo\UI\Bar;
use Foo\Xml\Bar;
```

General formatting conventions
------------------------------

* Tab indentation is used everywhere. All indentation using spaces is forbidden.
* Files end with a single blank line `\n`.
* Unix-style (LF) line endings `\n` are used.
* There is no trailing whitespace.
* If there is a list of information, where ordering has no semantic meaning, the list is sorted alphabetically.
  * Sorting concatenated words (e.g. `PascalCase`) takes into account original words:

```php
<?php

use LogAware;
use LogFactory;
use LogLevel;
use LogStandard;
use LogableTrait;
use LoggerInterface;
```

* If indentation of nested structures is needed (such as arrays or function calls), the opening of the structure should be left on the original line, followed by a nested indented content of the structure, closing of the structure should return the indentation to the original level and be on next line, followed by the rest of the code (on the same line).

```php
<?php

foo(1, 2, [
	'lorem',
	'ipsum',
], 3);

foo(1, 2, bar(
	$lorem,
	$ipsum
), 3);

foo(1, 2, function ($item) {
	// ...
}, 3);

foo(1, 2, '
	long
	text
	here
', 3);
```

PHP files
---------

* Contains only PHP code (no inline HTML etc.).
* File does not have the closing tag `?>`.
* There are no characters (including BOM) before the PHP opening tag.
* Long opening tags are used (always `<?php`, never `<?`).
* There is one empty line after the line with the open tag.
* File either declares new symbols (classes, functions, constants, etc.) and causes no other side effects, or executes logic with side effects, but should not do both.
* Uses strict typing by enabling `declare(strict_types = 1);`.
  * This declaration is placed on a separate line after the opening tag and before other content of the file. There is one empty line before and after the declaration.
  * There is one space on each side of the `=` operator.

```php
<?php

declare(strict_types = 1);

namespace Consistence;
```

Strings
-------

* Common strings are written using apostrophes (`'`). Only strings containing control characters (such as `\n`) may use double quotes (`"`).
* For concatenation of mixed strings and variables `sprintf()` is used. If in-place strings are not needed concatenation is done with only concatenation operator (`.`).
  * `.` is surrounded by one space on each side, unless it is on the beginning of the line.
  * Strings do not contain variables (`"Hello $name!"`).

```php
<?php

sprintf('%s/%s', $dir, $fileName);

// vs

$foo . $bar;

```

```sql
'SELECT `id`, `name` FROM `people`'
. 'WHERE `role` = 1'
. 'ORDER BY `name` ASC';
```

* Heredoc and nowdoc string syntax is never used.

Arrays
------

* If complete array is declared on one line, all the values are separated with a comma, followed by one space (`, `).

```php
<?php

[1, 2, 3, 'test'];
```

* If declaration is split on multiple lines, it follows general formatting rules and there is a comma after the last value.

```php
<?php

[
	'lorem',
	'ipsum',
];
```

* Declaration on multiple lines is preferred, associative arrays (with keys) are always written on multiple lines.
* Short array syntax is used (`[`, `]`) instead of the `array()` language construct.
* If possible (PHP 7.1+), short array destructuring syntax (`[$a, $b, $c] = $array;`) is used instead of the `list()` language construct.

Namespaces
----------

* Namespaces are written in `PascalCase`.
* Names are chosen based on their domain role, not based on their type (e.g. no Exception, Service, ... namespaces).
* Each file contains only one `namespace` declaration applied to the whole file.
  * Before the line with `namespace` there is one empty line.
* Types from other namespaces are imported with `use`.
  * `use` declarations are separated from `namespace` declaration with one empty line.
  * There is only one type imported per `use` declaration (one import per line).
  * `use` declarations are sorted alphabetically.
  * `use` declarations never begin with backslash (`\`).
  * Imported types should be renamed with `use ... as ...;` if the type name is too common or clashes with current domain.
  * Exceptions to this rule:
    * Exceptions are always referenced with fully qualified name (FQN).
    * Types in `extends`, `implements` and used traits are also referenced with FQN.

```php
<?php

namespace Consistence;

use Consistence\Bar;
use Consistence\Foo;

use DateTime;

use Lorem\Amet;
use Lorem\Ipsum\Dolor\Foo as DolorFoo;
use Lorem\Sit;

use ReflectionClass;
use ReflectionMethod;
```

Types
-----

* Type names are written in `PascalCase`.
* Type names are nouns.
* Types are placed in namespaces (not global space).
* General type names should be avoided to be readable when used without FQN.
* Opening brace and closing brace of type are always on a separate line.
* All parts of types are indented with one tab.
* Only one type per file is defined, name of this file has the same name as the type.
* Parts in types are declared in the following order:
  1. constants,
  2. properties,
  3. constructor,
  4. destructor,
  5. other methods
* All parts are separated by one empty line from others.
  * Empty lines are also before/after the first/last part of type.
* Multiple types referenced in `implements` are separated with comma and one space and are ordered alphabetically.
* Should the type declaration line be too long, `extends` and `implements` may be written on next lines, indented with one tab.
  * Multiple types referenced in `implements` are written on separate lines (without space after the comma).

```php
class Foo extends \Bar implements \Bax, \Baz
{

}
```

```php
<?php

class Foo
	extends \Bar
	implements \Baz
{

}
```

```php
<?php

class Lorem
	extends \Bar
	implements
		\Bax,
		\Baz
{

}
```

* Types referenced in code are always referenced using `Foo::class` syntax, never using a string.
* If the referenced type in a static access is the "current" one, `self`/`static` is used instead of type name.

### Classes

* If "static constructors" are used, they are placed after `__construct`.

```php
<?php

class Foo
{

	public function __construct()
	{
		// ...
	}

	public static function fromString(string $string)
	{
		// ...
	}

}
```

### Interfaces

* Interfaces are never prefixed with `I`.

Variables
---------

* Variable names are written in `camelCase`.
* Names are chosen based on their domain role, not based on their type, size or visibility.
* `global` keyword is never used to declare global variables.

Properties
----------

* All variables rules apply.
* All properties have explicitly declared visibility with `private`, `protected` or `public`.
* `var` is never used.
* Only one property is declared per statement.

Constants
---------

* Constant names are written in `UPPER_CASE`.
* Constants are defined only inside classes using `const`, global constants are never defined.
* If possible (PHP 7.1+), constants have explicitly declared visibility with `private`, `protected` or `public`.
* If there are more constants, that "belong together", empty lines between them may be omitted.

```php
<?php

class Foo
{

	const FOO = 'foo';

	const VISIBILITY_PRIVATE = 'private';
	const VISIBILITY_PROTECTED = 'protected';
	const VISIBILITY_PUBLIC = 'public';

}
```

Functions
---------

* Function names are written in `camelCase`.
  * Calls to built-in PHP functions are exception to this rule and are written in `snake_case`.
* There is no space between the function name and the opening parenthesis.
* Opening brace and closing brace of type are always on separate line.
  * Exception: anonymous functions.
* Global functions are never declared, they should be defined inside a (static) class.
* Named functions (not anonymous) are never declared inside other functions.

### Argument list

* There should be type hint defined whenever possible (including scalar type hints).
  * If possible (PHP 7.1+), nullable types (`?string`) are used to allow passing null to a type hinted argument. 
* There is no space after the opening parenthesis, and there is no space before the closing parenthesis.
* Arguments both in function declaration and in function call are separated with comma, followed by one space (`, `).

```php
<?php

class X
{

	public function __construct(Foo $foo, string $string)
	{
		// ...
	}

}
```

* Function declaration and call with arguments on multiple lines:
  * There is only one argument per line.

```php
<?php

class X
{

	public function __construct(
		Foo $foo,
		string $string
	)
	{
		// ...
	}

}

new X(
	$foo,
	$string
);
```

* Default argument values are used only when needed to either express optional argument (only at the end of the list) or to allow passing null to a type hinted argument.
  * If possible (PHP 7.1+), nullable types (`?string`) are used to allow passing null to a type hinted argument and therefore default arguments are used only for optional arguments.
  * For scalar arguments default arguments are used only for optional arguments, not to allow passing nulls (see detailed example below).

```php
<?php

// for PHP 7.0

class X
{

	/**
	 * @param \Foo $a required type argument
	 * @param \Foo|null $b required argument, but nullable type needed
	 * @param string $c required scalar argument
	 * @param string|null $d required argument with nullable scalar
	 * @param string $e optional nullable scalar argument
	 * @param string|null $f optional nullable scalar argument
	 */
	public function __construct(
		Foo $a,
		Foo $b = null,
		string $c,
		$d,
		string $e = '',
		string $f = null
	)
	{
		// ...
	}

}

// or with PHP 7.1+

class X
{

	/**
	 * @param \Foo $a required type argument
	 * @param \Foo|null $b required argument, but nullable type needed
	 * @param string $c required scalar argument
	 * @param string|null $d required argument with nullable scalar
	 * @param string $e optional nullable scalar argument
	 * @param string|null $f optional nullable scalar argument
	 */
	public function __construct(
		Foo $a,
		?Foo $b,
		string $c,
		?string $d,
		string $e = '',
		?string $f = null
	)
	{
		// ...
	}

}
```

* Variadic argument is written in this format: `@param \Foo ...$foo`.

### Return type

* There should be type hint defined whenever possible (including scalar type hints).
  * If possible (PHP 7.1+), nullable types (`?string`) are used to allow returning null.
* There is no space after the closing parenthesis, colon immediately follows and then there is one space between the colon and the type.

```php
<?php

class X
{

	public function getFoo(): Foo
	{
		// ...
	}

}
```

* If possible (PHP 7.1+), when there is nothing to return, `void` return type must be specified.

```php
<?php

class X
{

	public function process(Foo $foo): void
	{
		$foo->bar();
	}

}
```

### Anonymous functions

* There is a space between the `function` keyword and the opening parenthesis.
* Opening brace is NOT placed on the next line.
* There is one space before and after the `use` keyword.

```php
<?php

array_walk($foo, function (Item $item) use ($bar) {
	// ...
});
```

### Methods

* All methods have explicitly declared visibility with `private`, `protected` or `public`.
* Order of keywords in declaration:
  1. `final`/`abstract`,
  2. `private`/`protected`/`public`,
  3. `static`
* Constructor is always defined with `__construct` name, never using the old PHP behavior - with name same as class name.

```php
<?php

class X
{

	final public static function foo()
	{
		// ...
	}

	abstract public static function bar()
	{
		// ...
	}

}
```

Control structures
------------------

* Conditional statement is surrounded by parentheses.
  * There is no space after/before parentheses inside the statement.
  * There is one space before/after parentheses around the statement.
* Opening brace is placed on the same line as the conditional statement.
* `elseif` is used instead of `else if`.
* `case` statements in `switch` are indented with one tab, and their content on following lines again with another tab.
* `case` statements in `switch` end with a colon `:`.

```php
<?php

if ($foo) {
	// ...
}

if (
	$foo
	&& $bar
) {
	// ...
}

switch ($foo) {
	case 1:
	case 2:
		// ...
		break;
	default:
		// ...

}
```

* In `switch`, there must be a comment such as `// no break` when fall-through is intentional in a non-empty case body.
* Empty bodies of control structures are forbidden.
  * Exception is `catch`, but there must be a comment explaining situation.

Expressions
-----------

* After all operators, there is one space. Before operators, there is one space too, unless it is on the beginning of a line.
* Short type names are used in code (`int`, `bool`). This also applies to PHP functions which offer both variants.

```php
<?php

if (!is_int($foo)) {
	return (int) $foo;
}
```

* Logical operators `&&` and `||` are always used instead of `and` and `or`.
* All keywords are lowercase, as well as `true`, `false` and `null`.
* Strict comparisons are used by default (`===`), if there is need for `==`, usually a comment should be given explaining situation.
  * Magic PHP type conversions should be avoided - WRONG: `($foo)`, CORRECT: `($foo !== null)` - only expressions already containing boolean values should be written in `($foo)` form.
* [Yoda conditions](http://en.wikipedia.org/wiki/Yoda_conditions) should not be used.
* If expression needs to be written on multiple lines, operators belong on the beginning of the line.

```php
<?php

if (
	($lorem >= 3 && $lorem <= 5)
	|| $ipsum !== null
) {
	// ...
}
```

* There is always only one statement per line.
* One blank line may be used to separate other statements.
* There is one empty line before line with `return` statement, unless the current code block has less than four lines, then it does not have to be there.
* If there are multiple method calls in a row and it is needed to write this on multiple lines, all the method calls are indented (including the first one).

```php
<?php

$lorem
	->ipsum()
	->dolor()
	->sit()
	->amet();
```

* Decimal number notation should be used in most cases, unless needed explicitly for clarification.
* Parentheses in `new` statements should be always present, even if there are no arguments for constructor.

```php
<?php

new Foo();
```

* There is one space after type cast and no space inside the parentheses.
* For increments and decrements respective operators `++`/`--` are used instead of "manual" addition/subtraction.
* All static symbols should be accessed only with `::`, never using `$this`.
* `echo`, `print`, ... allowing both `echo('...')` and `echo ''` syntax are always used without parentheses and with one space after the keyword.

Closures and callables
----------------------

* Closure is preferred to use instead of a callable (callable might be required while implementing a third party interface though).
* Closures are invoked using `$closure()` instead of using functions.
  * `call_user_func` should never be needed.
  * `call_user_func_array` is not needed since PHP 5.6 - argument unpacking was introduced.

```php
<?php

function foo($foo, Closure $callback)
{
	// ...
	$callback($bar);
	// ...
}

foo('foo', function (Bar $bar) {
	// ...
});
```

```php
<?php

function foo($foo, Closure $callback)
{
	// ...
	$callback(...$barArray);
	// ...
}

foo('foo', function (Bar ...$bars) {
	// ...
});
```

Exceptions
----------

* Type name always ends with Exception.
* Exceptions are placed in a separate directory called `exceptions`.
* Class name describes the use-case and should be very specific.
* Inheritance is used for implementation purposes (not for creating hierarchies) - such as `Consistence\PhpException`, where `$code` argument is skipped.
* Constructor requires only arguments, which are needed, the rest of the message is composed in the constructor.
  * All exceptions should support exceptions chaining (allow optional `\Throwable` as last argument).
  * Arguments should be stored in private properties and available via public methods, so that exception handling may use this data.

```php
<?php

namespace Consistence\Foo;

class LoremException extends \Consistence\PhpException
{

	/** @var string */
	private $lorem;

	public function __construct(string $lorem, \Throwable $previous = null)
	{
		parent::__construct(sprintf('%s ipsum dolor sit amet', $lorem), $previous);
		$this->lorem = $lorem;
	}

	public function getLorem(): string
	{
		return $this->lorem;
	}

}
```

Commenting
----------
* `//` inline style comments are used, never `#`.
* Inline comment has a space between `//` and the text.
* If there is no explicit need to write something, it should be omitted - well named classes, variables, methods and arguments should be preferred.
* "Commented out" code is never present.

PHPDoc
------

Structure for types and methods:

```php
<?php

/**
 * Short description - one line (optional)
 *
 * Long description (optional)
 *
 * Documentation annotations (optional)
 *
 * Code analysis annotations (optional)
 *
 * Application annotations (optional)
 *
 * @param string $foo
 * @param int $bar
 * @return bool
 * @throws \MyException\BarException
 * @throws \MyException\FooException
 */
public function myMethod(string $foo, int $bar): bool;
```

Structure for properties and constants:

```php
<?php

/**
 * Short description - one line (optional)
 *
 * Long description (optional)
 *
 * Documentation annotations (optional)
 *
 * Code analysis annotations (optional)
 *
 * Application annotations (optional)
 *
 * @var string optional description
 */
private $foo;
```

### Annotation blocks

* Different types of annotations are grouped together (separated from other blocks by an empty line).
* See structure above.

### Short+long description

* Optional.
* If there is no explicit need to write something, it should be omitted - well named classes, variables, methods and arguments should be preferred.
* Descriptions which only rephrase method names (etc.) are never written.
* Clear code is better than long explanation.
* Short description has only one line (with no dot at the end).
* Long description may contain example usage.
* In long description formatting may be used (e.g. with HTML).

### Documentation annotations

* Optional.
* PHPDoc annotations with documentation metadata like `@author`, `@copyright`, `@see`, `@link`, ...

### Code analysis annotations

* Optional.
* Usually ignoring certain rules from code analysis tools, or special instructions for them.
* When ignoring a rule, textual explanation should be given (`@SuppressWarnings(PHPMD.UnusedFormalParameter) parameter required by interface`).

### Application annotations

* Optional.
* Annotations, that have functional significance for the application, such as Symfony, Doctrine and custom annotations.

### Multi-line annotations

* Follows general formatting rules for dealing with separating statements to multiple lines.
* Two spaces are used for indentation.

```php
/**
 * @ManyToMany(targetEntity="Phonenumber")
 * @JoinTable(
 *   name="users_phonenumbers",
 *   joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
 *   inverseJoinColumns={@JoinColumn(name="phonenumber_id", referencedColumnName="id", unique=true)}
 * )
 * @Foo
 **/
```


### Allowed types for @param, @return, @var

List of allowed types (long variants are used):

* `int`
* `bool`
* `string`
* `float`
* `double`
* `resource`
* `null`
* `object`
* `mixed`
* array/collection (see below)
* type (see below)

Multiple different types are separated with `|`.

#### Mixed

* Used when nothing is known about the type.

#### Array/collection

* Written as `<type>[]`.
* If the values are of more than one type, then `mixed[]` is used (also if there is no knowledge about the types).
* If associative array is expected (or a Map), in description, there should be description of used format, such as `string[] $names format: lastName(string) => firstName (string)`.
* If there are more nested arrays/collections, this is expressed with more `[]`, e.g. `integer[][]` means array of arrays of integers.

#### Type

* FQN with a leading backslash.
* If the referenced type is the "current" one, `self`/`static` is used instead of type name.

```php
<?php

use DateTime;
use DateTimeImmutable;

/**
 * @param \DateTimeImmutable $date calendar date
 * @param string[] $events
 * @param int|null $interval
 * @return \DateTime
 */
public function myMethod(DateTimeImmutable $date, array $events, int $interval = null): DateTime
{
	// ...
}
```

### @param

* For every method argument, there is both type and name.
* Annotations are in the same order as defined in the argument list.

```php
<?php

use DateTime;

/**
 * @param string $foo optional description
 * @param int $bar optional description
 * @param \DateTime ...$dates optional description
 */
public function myMethod(string $foo, int $bar, DateTime ...$dates)
{
	// ...
}
```

### @return

* If there are no `return` statements in the method, `@return` is not present.

### @var

* If the `@var` annotation is the only annotation and there is no long description in the PHPDoc, then one-line format is used:

```php
<?php

/** @var string optional description */
private $foo;
```

### @throws

* For implemented methods `@throws` is never used.
* `@throws` is only used in interfaces or abstract methods as part of the defining contract.
* `@throws` annotations are sorted alphabetically (according to the exception name).

### @inheritDoc

* `@inheritDoc` is never used and all types must be documented as described in the `PHPDoc` section.

### Methods without PHPDoc

* PHPDoc is omitted if there would be no additional information to what is already defined using other means.
  * If any part of the PHPDoc is needed, it must be present in its full form as defined at the beginning of the `PHPDoc` section.
  * Examples:
    * If a method has no arguments, nor return value and no description (and other annotations) it is omitted.
    * If a method has arguments type hinted with types (classes, interfaces) and no return value it is omitted.
    * If a method has an argument without typehint, PHPDoc is present.
    * If a method has an array argument, PHPDoc is present.
    * If a method has return value, PHPDoc is present.
      * This is valid only until return type hints will be introduced in PHP 7.

### Constants

* `@var` is not used for constants (type is defined by its value).
* If there is no need for other annotations or description, then PHPDoc is omitted.
