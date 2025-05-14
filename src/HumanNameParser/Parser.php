<?php

/**
 * Split a single name string into it's name parts (first name, last name, titles, middle names)
 */

namespace HumanNameParser;

use HumanNameParser\Name;
use HumanNameParser\Exception\FirstNameNotFoundException;
use HumanNameParser\Exception\LastNameNotFoundException;
use HumanNameParser\Exception\NameParsingException;

class Parser
{
    // The regex use is a bit tricky.  *Everything* matched by the regex will be replaced,
    //    but you can select a particular parenthesized submatch to be returned.
    //    Also, note that each regex requires that the preceding ones have been run, and matches chopped out.
    // Names that starts or end with an apostrophe break this
    private const REGEX_NICKNAMES = "/ ('|\"|\(\"*'*)(.+?)('|\"|\"*'*\)) /i";
    private const REGEX_TITLES = "/^(%s)\.*/i";
    // Using a different approach instead of this regex
    // private const REGEX_SUFFIX = "/(\*,) *(%s)$/i";
    private const REGEX_LAST_NAME = "/(?!^)\b([^ ]+ y |%s)*[^ ]+$/i";
    // Note the lookahead, which isn't returned or replaced
    private const REGEX_LEADING_INITIAL = "/^(.\.*)(?= \p{L}{2})/i";
    private const REGEX_FIRST_NAME = "/^[^ ]+/i"; //

    /**
     * @var array<string>
     */
    private $suffixes = [];

    /**
     * @var array<string>
     */
    private $prefixes = [];

    /**
     * @var array<string>
     */
    private $academicTitles = [];

    /**
     * @var string|null
     */
    private $nameToken = null;

    /**
     * @var bool
     */
    private $mandatoryFirstName = true;

    /**
     * @var bool
     */
    private $mandatoryLastName = true;

    /**
     * @var Name
     */
    private $name;

    /**
     * Constructor
     *
     * Options:
     * - 'suffixes' - Array of name suffixes
     * - 'prefixes' - Array of name prefixes
     * - 'academic_titles' - Array of academic titles
     * - 'mandatory_first_name' - If true, requires first name (default: true)
     * - 'mandatory_last_name' - If true, requires last name (default: true)
     */
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        if (!isset($options['suffixes'])) {
            $options['suffixes'] = ['esq', 'esquire', 'jr', 'sr', '2', 'ii', 'iii', 'iv'];
        }
        if (!isset($options['prefixes'])) {
            $options['prefixes'] = ['bar', 'ben', 'bin', 'da', 'dal', 'de la', 'de', 'del', 'der', 'di',
                'ibn', 'la', 'le', 'san', 'st', 'ste', 'van', 'van der', 'van den', 'vel', 'von'];
        }
        if (!isset($options['academic_titles'])) {
            $options['academic_titles'] = ['ms', 'miss', 'mrs', 'mr', 'prof', 'dr'];
        }
        if (isset($options['mandatory_first_name'])) {
            $this->mandatoryFirstName = (bool)$options['mandatory_first_name'];
        }
        if (isset($options['mandatory_last_name'])) {
            $this->mandatoryLastName = (bool)$options['mandatory_last_name'];
        }

        $this->name = new Name();
        $this->setSuffixes($options['suffixes']);
        $this->setPrefixes($options['prefixes']);
        $this->setAcademicTitles($options['academic_titles']);
    }


    /**
     * Parse the name into its constituent parts.
     *
     *
     * @return Name the parsed name
     */
    public function parse(string $name): Name
    {
        $suffixes = implode("\.*|", $this->suffixes) . "\.*"; // each suffix gets a "\.*" behind it.
        $prefixes = implode(" |", $this->prefixes) . " "; // each prefix gets a " " behind it.
        $academicTitles = implode("\.*|", $this->academicTitles) . "\.*"; // each suffix gets a "\.*" behind it.

        $this->nameToken = $name;
        $this->name = new Name();

        // Flip on slashes before any other transformations
        $this->flipNameToken("\/");

        $this->findAcademicTitle($academicTitles);
        $this->findNicknames();

        $this->findSuffix($suffixes);

        // Flip on commas
        $this->flipNameToken(",");

        $this->findLastName($prefixes);
        $this->findLeadingInitial();
        $this->findFirstName();
        $this->findMiddleName();

        return $this->name;
    }

    /**
     * @param  string $academicTitles
     *
     * @return Parser
     */
    private function findAcademicTitle(string $academicTitles): self
    {
        $regex = sprintf(self::REGEX_TITLES, $academicTitles);
        $title = $this->findWithRegex($regex, 1);
        if ($title) {
            $this->name->setAcademicTitle($title);
            if ($this->nameToken !== null) {
                $this->nameToken = str_ireplace($title, "", $this->nameToken);
            }
        }

        return $this;
    }


    /**
     * @return Parser
     */
    private function findNicknames(): self
    {
        $nicknames = $this->findWithRegex(self::REGEX_NICKNAMES, 2);
        if ($nicknames) {
            $this->name->setNicknames($nicknames);
            $this->removeTokenWithRegex(self::REGEX_NICKNAMES);
        }

        return $this;
    }

    /**
     * @param  string $suffixes
     *
     * @return Parser
     */
    private function findSuffix(string $suffixes): self
    {
        $regex = "/,* *($suffixes)$/i";
        //var_dump($regex); die;
        //$regex = sprintf(self::REGEX_SUFFIX, $suffixes);
        $suffix = $this->findWithRegex($regex, 1);
        if ($suffix) {
            $this->name->setSuffix($suffix);
            $this->removeTokenWithRegex($regex);
        }

        return $this;
    }

    /**
     * @return Parser
     */
    private function findLastName(string $prefixes): self
    {
        $regex = sprintf(self::REGEX_LAST_NAME, $prefixes);
        $lastName = $this->findWithRegex($regex, 0);
        if ($lastName) {
            $this->name->setLastName($lastName);
            $this->removeTokenWithRegex($regex);
        } elseif ($this->mandatoryLastName) {
            throw new LastNameNotFoundException("Couldn't find a last name.");
        }

        return $this;
    }

    /**
     * @return Parser
     */
    private function findFirstName(): self
    {
        $lastName = $this->findWithRegex(self::REGEX_FIRST_NAME, 0);
        if ($lastName) {
            $this->name->setFirstName($lastName);
            $this->removeTokenWithRegex(self::REGEX_FIRST_NAME);
        } elseif ($this->mandatoryFirstName) {
            throw new FirstNameNotFoundException("Couldn't find a first name.");
        }

        return $this;
    }

    /**
     * @return Parser
     */
    private function findLeadingInitial(): self
    {
        $leadingInitial = $this->findWithRegex(self::REGEX_LEADING_INITIAL, 1);
        if ($leadingInitial) {
            $this->name->setLeadingInitial($leadingInitial);
            $this->removeTokenWithRegex(self::REGEX_LEADING_INITIAL);
        }

        return $this;
    }

    /**
     * @return Parser
     */
    private function findMiddleName(): self
    {
        $middleName = $this->nameToken !== null ? trim($this->nameToken) : '';
        if ($middleName) {
            $this->name->setMiddleName($middleName);
        }

        return $this;
    }


    /**
     * @return string
     */
    private function findWithRegex(string $regex, int $submatchIndex = 0): string|false
    {
        $regex = $regex . "ui"; // unicode + case-insensitive
        if ($this->nameToken === null) {
            return false;
        }
        preg_match($regex, $this->nameToken, $m);
        $subset = (isset($m[$submatchIndex])) ? $m[$submatchIndex] : false;

        return $subset;
    }


    /**
     * @param string $regex
     *
     * @return void
     * @throws NameParsingException
     */
    private function removeTokenWithRegex(string $regex): void
    {
        if ($this->nameToken === null) {
            return;
        }

        $numReplacements = 0;
        $tokenRemoved = preg_replace($regex, ' ', $this->nameToken, -1, $numReplacements);
        if ($numReplacements > 1) {
            throw new NameParsingException('The regex being used has multiple matches.');
        }
        if (!is_string($tokenRemoved)) {
            throw new NameParsingException('The regex being used has multiple matches.');
        }

        $this->nameToken = $this->normalize($tokenRemoved);
    }

    /**
     * Removes extra whitespace and punctuation from string
     * Strips whitespace chars from ends, strips redundant whitespace, converts whitespace chars to " ".
     *
     * @param string $taintedString
     *
     * @return string
     */
    private function normalize(string $taintedString): string
    {
        $result = preg_replace("#^\s*#u", '', $taintedString);
        if ($result === null) {
            return '';
        }

        $result = preg_replace("#\s*$#u", '', $result);
        if ($result === null) {
            return '';
        }

        $result = preg_replace("#\s+#u", ' ', $result);
        if ($result === null) {
            return '';
        }

        $result = preg_replace('#,$#u', ' ', $result);
        if ($result === null) {
            return '';
        }

        return $result;
    }

    /**
     * @return Parser
     *
     * @param string $pattern
     *
     * @throws NameParsingException
     */
    private function flipNameToken(string $pattern = ","): self
    {
        if ($this->nameToken !== null) {
            $this->nameToken = $this->flipStringPartsAround($this->nameToken, $pattern);
        }

        return $this;
    }


    /**
     * Flips the front and back parts of a name with one another.
     * Front and back are determined by a specified character somewhere in the
     * middle of the string.
     *
     * @param string $string unparsed name
     * @param string $char character, for which to flip.
     *
     * @return string
     * @throws NameParsingException
     */
    private function flipStringPartsAround(string $string, string $char): string
    {
        $substrings = preg_split("/$char/u", $string);
        if (!is_array($substrings)) {
            throw new NameParsingException('Could not flip characters.');
        }

        if (\count($substrings) === 2) {
            $string = $substrings[1] . ' ' . $substrings[0];
            $string = $this->normalize($string);
        } elseif (\count($substrings) > 2) {
            throw new NameParsingException("Can't flip around multiple '$char' characters in namestring.");
        }

        return $string;
    }

    /**
     * Gets the value of suffixes.
     *
     * @return array
     */
    /**
     * @return array<string>
     */
    public function getSuffixes(): array
    {
        return $this->suffixes;
    }

    /**
     * Sets the value of suffixes.
     *
     * @param array $suffixes the suffixes
     *
     * @return self
     */
    /**
     * @param array<string> $suffixes
     */
    public function setSuffixes(array $suffixes): self
    {
        $this->suffixes = $suffixes;

        return $this;
    }

    /**
     * Gets the value of prefixes.
     *
     * @return array
     */
    /**
     * @return array<string>
     */
    public function getPrefixes(): array
    {
        return $this->prefixes;
    }

    /**
     * Sets the value of prefixes.
     *
     * @param array $prefixes the prefixes
     *
     * @return self
     */
    /**
     * @param array<string> $prefixes
     */
    public function setPrefixes(array $prefixes): self
    {
        $this->prefixes = $prefixes;

        return $this;
    }

    /**
     * Gets the value of academicTitles.
     *
     * @return array
     */
    /**
     * @return array<string>
     */
    public function getAcademicTitles(): array
    {
        return $this->academicTitles;
    }

    /**
     * Sets the value of academicTitles.
     *
     * @param array $academicTitles the academic titles
     *
     * @return self
     */
    /**
     * @param array<string> $academicTitles
     */
    public function setAcademicTitles(array $academicTitles): self
    {
        $this->academicTitles = $academicTitles;

        return $this;
    }
}
