
<p align="center">
	<a href="https://github.com/davidgorges/HumanNameParser.php/actions/workflows/tests.yml"><img src="https://github.com/davidgorges/HumanNameParser.php/actions/workflows/tests.yml/badge.svg" alt="Tests"></a>
	<a href="https://packagist.org/packages/davidgorges/human-name-parser"><img src="https://poser.pugx.org/davidgorges/human-name-parser/v/stable" alt="Latest Stable Version"></a>
	<a href="https://github.com/davidgorges/HumanNameParser.php"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a>
</p>

------ 

*Note*: Version 2.0 requires PHP >= 8.1. For PHP 7.1-8.0 support, use version 1.x.

# Description
Fork from HumanNameParser.php origninally by Jason Priem <jason@jasonpriem.com>. Takes human names of arbitrary complexity and various wacky formats like:

* J. Walter Weatherman 
* de la Cruz, Ana M. 
* James C. ('Jimmy') O'Dell, Jr.
* Dr. James C. ('Jimmy') O'Dell, Jr.

and parses out the:

- leading initial (Like "J." in "J. Walter Weatherman")
- first name (or first initial in a name like 'R. Crumb')
- nicknames (like "Jimmy" in "James C. ('Jimmy') O'Dell, Jr.")
- middle names
- last name (including compound ones like "van der Sar' and "Ortega y Gasset"), and
- suffix (like 'Jr.', 'III')
- title (like 'Dr.', 'Prof') *new*


# Requirements

- PHP 8.1, 8.2, 8.3, 8.4 or higher

# Installation

```bash
composer require davidgorges/human-name-parser
```

# How to use

```php
use HumanNameParser\Parser;

$nameparser = new Parser();
$name = $nameparser->parse("Alfonso Ribeiro");

echo "Hello " . $name->getFirstName(); // "Hello Alfonso"
```

## More Examples

```php
// Basic usage
$parser = new Parser();

// Simple name
$name = $parser->parse("John Smith");
echo $name->getFirstName();  // "John"
echo $name->getLastName();   // "Smith"

// Name with title
$name = $parser->parse("Dr. Jane Smith");
echo $name->getAcademicTitle(); // "Dr."
echo $name->getFirstName();     // "Jane"
echo $name->getLastName();      // "Smith"

// Name with middle name
$name = $parser->parse("John William Smith");
echo $name->getFirstName();   // "John"
echo $name->getMiddleName();  // "William"
echo $name->getLastName();    // "Smith"

// Name with suffix
$name = $parser->parse("John Smith Jr.");
echo $name->getFirstName();  // "John"
echo $name->getLastName();   // "Smith"
echo $name->getSuffix();     // "Jr."

// Reversed name (last, first)
$name = $parser->parse("Smith, John");
echo $name->getFirstName();  // "John"
echo $name->getLastName();   // "Smith"

// Complex name
$name = $parser->parse("Dr. John W. ('Johnny') Smith-Brown, III");
echo $name->getAcademicTitle(); // "Dr."
echo $name->getFirstName();     // "John"
echo $name->getMiddleName();    // "W."
echo $name->getNicknames();     // "Johnny"
echo $name->getLastName();      // "Smith-Brown"
echo $name->getSuffix();        // "III"
```

## Custom Configuration

```php
// Configure the parser with custom options
$parser = new Parser([
    'suffixes' => ['esq', 'jr', 'sr', 'ii', 'iii', 'iv'],
    'prefixes' => ['van', 'von', 'de', 'del', 'da', 'la'],
    'academic_titles' => ['dr', 'prof', 'mr', 'mrs', 'ms'],
    'mandatory_first_name' => true,
    'mandatory_last_name' => true
]);

// Parse name with prefix
$name = $parser->parse("Vincent van Gogh");
echo $name->getFirstName();  // "Vincent"
echo $name->getLastName();   // "van Gogh"
```

# Try It Out

The library includes an example script that you can use to test parsing various names:

```bash
# After installing the library
composer install
php examples/parse_name.php

# Or parse a specific name directly
php examples/parse_name.php "Dr. John Smith Jr."
```

# Testing

```bash
composer install
vendor/bin/phpunit
```

# Static Analysis & Coding Standards

```bash
vendor/bin/phpstan analyse
vendor/bin/phpcs --standard=PSR12 src/ tests/
```
