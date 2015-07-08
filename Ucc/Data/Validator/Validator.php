<?php

namespace Ucc\Data\Validator;

/**
 * Ucc\Data\Validator\Validator
 * Provides methods to validate data against defined checks
 *
 * @author Kris Rybak <kris@krisrybak.com>
 */
class Validator
{
    /**
     * @var     array   Array of checks to perform
     */
    private $checks;

    public function __construct()
    {
        $this->checks = array();
    }

    /**
     * Gets list of checks
     *
     * @return  array
     */
    public function getChecks()
    {
        return $this->checks;
    }

    /**
     * Sets checks
     *
     * @param   array   $checks     Array of checks to perform
     * @return  Validator
     */
    public function setChecks(array $checks)
    {
        foreach ($checks as $key => $check) {
            $this->setCheck($key, $check);
        }

        return $this;
    }

    /**
     * Sets checks
     *
     * @param   string  $key        Input key to apply check to
     * @param   array   $check      Validation criteria
     * @return  Validator
     */
    public function setCheck($key, $check)
    {
        $this->checks[$key] = $check;

        return $this;
    }

    /**
     * Clears all validation checks.
     *
     * @return  Validator
     */
    public function clearChecks()
    {
        $this->checks = array();

        return $this;
    }

    /**
     * Checks inputs conformity with field checks set.
     */
    public function validate()
    {

    }
}
