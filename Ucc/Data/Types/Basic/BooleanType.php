<?php

namespace Ucc\Data\Types\Basic;

use Ucc\Data\Types\TypeInterface;
use Ucc\Exception\Data\InvalidDataTypeException;
use Ucc\Exception\Data\InvalidDataValueException;
use Ucc\Exception\Data\InvalidDataException;

/**
 * Ucc\Data\Types\Basic\BooleanType
 * Defines BooleanType data
 *
 * @author Kris Rybak <kris@krisrybak.com>
 */
class BooleanType implements TypeInterface
{
    public static $requirementsOptions = array(
    );

    public static $strBoolean = array(
        'false' => false,
        'true'  => true,
        '0'     => false,
        '1'     => true,
    );

    public static $integerBoolean = array(
        0   => false,
        1   => true,
    );

    /**
     * Returns list of requirements options together with
     * their description.
     *
     * @return array
     */
    public static function getRequirementsOptions()
    {
        return self::$requirementsOptions;
    }

    /**
     * Checks if the value is of a given type and
     * passes the value the requirements specified.
     *
     * @param   mixed   $value          Value to be checked
     * @param   array   $requirements   Additional constraints
     * @return  mixed   Cleared value
     * @throws  InvalidDataTypeException | InvalidDataValueException
     */
    public static function check($value, array $requirements = array())
    {
        if(is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            if (array_key_exists(strtolower($value), $map = self::$strBoolean)) {
                return $map[strtolower($value)];
            }
        }

        if (is_integer($value)) {
            if (array_key_exists($value, $map = self::$integerBoolean)) {
                return $map[strtolower($value)];
            }
        }

        throw new InvalidDataTypeException("value must be a boolean type");
    }

    /**
     * Checks if the value is of a given type and
     * that the value passes requirements specified.
     *
     * @param   mixed   $value          Value to be checked
     * @param   array   $requirements   Additional constraints
     * @return  boolean                 True if value is of a given type and
     *                                  meets requirements
     */
    public static function is($value, array $requirements = array())
    {
        try {
            self::check($value, $requirements);
        } catch (InvalidDataException $e) {
            return false;
        }

        return true;
    }
}
