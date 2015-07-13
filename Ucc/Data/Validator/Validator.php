<?php

namespace Ucc\Data\Validator;

use Ucc\Data\Validator\Check\Check;

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

    /**
     * @var     mixed   Error message if validation fails, otherwise false
     */
    private $error;

    public function __construct()
    {
        $this->checks   = array();
        $this->error    = false;
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
            if (is_a($check, 'Ucc\Data\Validator\Check\Check')) {
                $this->addCheck($check);
            } elseif (is_array($check)) {
                $checkObj = new Check();
                $checkObj->fromArray(array($key => $check));
                $this->addCheck($checkObj);
            }
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
    public function addCheck(Check $check)
    {
        $this->checks[$check->getKey()] = $check;

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
     * Gets error
     *
     * @return  mixed           String if error occurred, otherwise false
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set error
     *
     * @param   mixed       $message
     * @return  Validator
     */
    public function setError($message)
    {
        $this->error = $message;

        return $this;
    }

    /**
     * Checks inputs conformity with field checks set.
     */
    public function validate()
    {

    }
}
