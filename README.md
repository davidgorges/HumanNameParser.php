# 🎭 HumanNameParser.php

**Parse human names like a boss.** Turn messy name strings into structured data with ease.

<p align="center">
	<a href="https://travis-ci.org/davidgorges/HumanNameParser.php"><img src="https://travis-ci.org/davidgorges/HumanNameParser.php.png" alt="Build Status"></a>
	<a href="https://packagist.org/packages/davidgorges/human-name-parser"><img src="https://poser.pugx.org/davidgorges/human-name-parser/v/stable" alt="Latest Stable Version"></a>
	<a href="https://github.com/davidgorges/HumanNameParser.php"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a>
	<img src="https://img.shields.io/badge/PHP-%3E%3D%207.1-blue.svg" alt="PHP Version">
</p>

---

## 🚀 What It Does

HumanNameParser.php takes human names of **arbitrary complexity** and **wacky formats** and intelligently breaks them down into their constituent parts. No more regex headaches or manual string splitting!

### Handles Complex Names Like:
```
✓ J. Walter Weatherman
✓ de la Cruz, Ana M.
✓ James C. ('Jimmy') O'Dell, Jr.
✓ Dr. James C. ('Jimmy') O'Dell, Jr.
✓ van der Sar, Edwin
✓ García Márquez, Gabriel José
```

### Extracts All Name Components:
- **🎯 Titles** - `Dr.`, `Prof.`, `Mr.`, `Mrs.`, etc.
- **🔤 Leading Initials** - `J.` in "J. Walter Weatherman"
- **👤 First Names** - Including single initials like 'R' in 'R. Crumb'
- **😎 Nicknames** - `Jimmy` in "James C. ('Jimmy') O'Dell"
- **📝 Middle Names** - All middle names and initials
- **👨‍👩‍👧‍👦 Last Names** - Including compound names like `van der Sar`, `Ortega y Gasset`
- **🎓 Suffixes** - `Jr.`, `Sr.`, `III`, `Esq.`, etc.

---

## 📦 Installation

Install via Composer:

```bash
composer require davidgorges/human-name-parser
```

**Requirements:** PHP >= 7.1

---

## 💡 Quick Start

### Basic Usage

```php
<?php

use HumanNameParser\Parser;

$parser = new Parser();
$name = $parser->parse("Alfonso Ribeiro");

echo "Hello " . $name->getFirstName();
// Output: Hello Alfonso
```

### Advanced Examples

```php
<?php

use HumanNameParser\Parser;

$parser = new Parser();

// Parsing a formal name
$name = $parser->parse("Dr. Martin Luther King, Jr.");
echo $name->getAcademicTitle(); // Dr
echo $name->getFirstName();     // Martin
echo $name->getMiddleName();    // Luther
echo $name->getLastName();      // King
echo $name->getSuffix();        // Jr

// Parsing reversed format (Last, First)
$name = $parser->parse("García, Gabriel José");
echo $name->getFirstName();     // Gabriel
echo $name->getMiddleName();    // José
echo $name->getLastName();      // García

// Parsing names with nicknames
$name = $parser->parse("William 'Bill' Gates III");
echo $name->getFirstName();     // William
echo $name->getNicknames();     // Bill
echo $name->getLastName();      // Gates
echo $name->getSuffix();        // III

// Parsing compound last names
$name = $parser->parse("Ludwig van Beethoven");
echo $name->getFirstName();     // Ludwig
echo $name->getLastName();      // van Beethoven

// Parsing with leading initials
$name = $parser->parse("J. Robert Oppenheimer");
echo $name->getLeadingInitial(); // J.
echo $name->getFirstName();      // Robert
echo $name->getLastName();       // Oppenheimer
```

---

## ⚙️ Configuration

Customize the parser with your own prefixes, suffixes, and titles:

```php
<?php

use HumanNameParser\Parser;

$parser = new Parser([
    'suffixes' => ['esq', 'jr', 'sr', 'ii', 'iii', 'iv', 'phd', 'md'],
    'prefixes' => ['de', 'da', 'van', 'von', 'di', 'del', 'la', 'le'],
    'academic_titles' => ['dr', 'prof', 'mr', 'mrs', 'ms', 'miss', 'sir', 'dame'],
    'mandatory_first_name' => true,  // Throw exception if first name not found
    'mandatory_last_name' => true    // Throw exception if last name not found
]);

$name = $parser->parse("Sir Arthur Conan Doyle");
```

---

## 🎯 API Reference

### Parser Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `parse($nameString)` | Parse a name string | `Name` object |
| `setSuffixes(array $suffixes)` | Set custom suffixes | `Parser` |
| `setPrefixes(array $prefixes)` | Set custom prefixes | `Parser` |
| `setAcademicTitles(array $titles)` | Set custom academic titles | `Parser` |

### Name Object Methods

| Method | Description | Example Return |
|--------|-------------|----------------|
| `getFirstName()` | Get first name | `"John"` |
| `getLastName()` | Get last name | `"Smith"` |
| `getMiddleName()` | Get middle name(s) | `"Paul"` |
| `getNicknames()` | Get nickname | `"Johnny"` |
| `getAcademicTitle()` | Get title | `"Dr"` |
| `getSuffix()` | Get suffix | `"Jr"` |
| `getLeadingInitial()` | Get leading initial | `"J."` |

---

## 🌍 Real-World Use Cases

- **📧 Email Marketing** - Personalize emails with correct name components
- **📋 Contact Management** - Structure contact data from various sources
- **🎫 Event Registration** - Parse attendee names from form submissions
- **🏢 CRM Systems** - Normalize customer name data
- **📱 User Profiles** - Extract formal and informal names
- **📊 Data Migration** - Clean up legacy name data
- **🔍 Search Indexing** - Index names by individual components

---

## 🧪 Testing

Run the test suite:

```bash
composer install
vendor/bin/phpunit
```

Run PHPStan static analysis:

```bash
vendor/bin/phpstan analyse
```

---

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. 🍴 Fork the repository
2. 🔨 Create a feature branch (`git checkout -b feature/amazing-feature`)
3. ✅ Commit your changes (`git commit -m 'Add some amazing feature'`)
4. 📤 Push to the branch (`git push origin feature/amazing-feature`)
5. 🎉 Open a Pull Request

Please ensure your code:
- Follows PSR-12 coding standards
- Includes tests for new functionality
- Passes PHPStan level checks
- Includes clear commit messages

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👏 Credits

Originally created by [Jason Priem](mailto:jason@jasonpriem.com)
Maintained and enhanced by [David Gorges](mailto:david.gorges@leaphub.de)

---

## 📝 TODO & Roadmap

### 🎯 High Priority
- [ ] Add support for parsing multiple names in a single string
- [ ] Implement name validation (check if parsed name makes sense)
- [ ] Add support for non-Western name formats (Asian, Arabic, etc.)
- [ ] Create a `getFullName()` method to reconstruct formatted names
- [ ] Add option to preserve original capitalization vs normalize
- [ ] Support for parsing organizational names vs. person names

### 🚀 Features
- [ ] Add gender detection based on first name (optional feature)
- [ ] Implement fuzzy matching for misspelled titles/suffixes
- [ ] Add support for parsing name with maiden names (née)
- [ ] Create Laravel service provider for easy integration
- [ ] Add Symfony bundle support
- [ ] Implement name comparison/similarity scoring

### 🔧 Improvements
- [ ] Improve handling of hyphenated names
- [ ] Better support for names with apostrophes (O'Brien, D'Angelo)
- [ ] Add more comprehensive list of international prefixes
- [ ] Performance optimization for batch processing
- [ ] Add caching mechanism for repeated name parsing
- [ ] Improve error messages and exception handling

### 📚 Documentation
- [ ] Add interactive examples on documentation site
- [ ] Create video tutorials for common use cases
- [ ] Add more real-world examples to README
- [ ] Document edge cases and limitations
- [ ] Create migration guide from v1.x to v2.x
- [ ] Add troubleshooting section

### 🧪 Testing
- [ ] Increase test coverage to 95%+
- [ ] Add performance benchmarking tests
- [ ] Create dataset of 10,000+ real-world names for testing
- [ ] Add mutation testing with Infection
- [ ] Test with PHP 8.0, 8.1, 8.2, 8.3 compatibility

### 🌐 Internationalization
- [ ] Add Japanese name parsing (Family-Given order)
- [ ] Add Chinese name parsing support
- [ ] Add Korean name parsing support
- [ ] Add Arabic name parsing support
- [ ] Add Spanish naming convention support (maternal/paternal surnames)
- [ ] Add Portuguese name parsing support

### 🛠️ Developer Experience
- [ ] Add PHPStan level 9 strict mode
- [ ] Implement fluent interface for parser configuration
- [ ] Add IDE auto-completion helpers
- [ ] Create PHPUnit data providers for common test cases
- [ ] Add GitHub Actions CI/CD pipeline
- [ ] Setup automatic releases with semantic versioning

---

<p align="center">Made with ❤️ by the open source community</p>
<p align="center">⭐ Star this repo if you find it useful!</p>
