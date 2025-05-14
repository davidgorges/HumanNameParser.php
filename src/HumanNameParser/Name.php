<?php

namespace HumanNameParser;

class Name
{
    /**
     * @var string|null
     */
    private $leadingInitial = null;

    /**
     * @var string|null
     */
    private $firstName = null;

    /**
     * @var string|null
     */
    private $nicknames = null;

    /**
     * @var string|null
     */
    private $middleName = null;

    /**
     * @var string|null
     */
    private $lastName = null;

    /**
     * @var string|null
     */
    private $academicTitle = null;

    /**
     * @var string|null
     */
    private $suffix = null;

    /**
     * Gets the value of firstName.
     *
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Sets the value of firstName.
     *
     * @param string $firstName the first name
     *
     * @return self
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Gets the value of nicknames.
     *
     * @return string
     */
    public function getNicknames(): ?string
    {
        return $this->nicknames;
    }

    /**
     * Sets the value of nicknames.
     *
     * @param string $nicknames the nicknames
     *
     * @return self
     */
    public function setNicknames(?string $nicknames): self
    {
        $this->nicknames = $nicknames;

        return $this;
    }

    /**
     * Gets the value of middleName.
     *
     * @return string
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * Sets the value of middleName.
     *
     * @param string $middleName the middle name
     *
     * @return self
     */
    public function setMiddleName(?string $middleName): self
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Gets the value of lastName.
     *
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Sets the value of lastName.
     *
     * @param string $lastName the last name
     *
     * @return self
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Gets the value of suffix.
     *
     * @return string
     */
    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    /**
     * Sets the value of suffix.
     *
     * @param string $suffix the suffix
     *
     * @return self
     */
    public function setSuffix(?string $suffix): self
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Gets the value of leadingInitial.
     *
     * @return string
     */
    public function getLeadingInitial(): ?string
    {
        return $this->leadingInitial;
    }

    /**
     * Sets the value of leadingInitial.
     *
     * @param string $leadingInitial the leading initial
     *
     * @return self
     */
    public function setLeadingInitial(?string $leadingInitial): self
    {
        $this->leadingInitial = $leadingInitial;

        return $this;
    }

    /**
     * Gets the value of academicTitle.
     *
     * @return string
     */
    public function getAcademicTitle(): ?string
    {
        return $this->academicTitle;
    }

    /**
     * Sets the value of academicTitle.
     *
     * @param string $academicTitle the academic title
     *
     * @return self
     */
    public function setAcademicTitle(?string $academicTitle): self
    {
        $this->academicTitle = $academicTitle;

        return $this;
    }
}
