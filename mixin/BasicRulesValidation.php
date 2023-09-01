<?php
namespace Mixins;

use customException\BadRequestException;
use mysql_xdevapi\Exception;

trait BasicRulesValidation{


    /**
     * the implementation of 'boolean' rule.
     *
     * @throws \Exception
     * */
    public static function validate_rule_is_boolean($key, $value, $level)

    {
        $booleanValue = ["true", "false", true, false];

        if ($value!=null && !in_array($value, $booleanValue,true)) {

            throw new BadRequestException("$key (in $level within value = $value should be boolean ");;
        }
    }

    /**
     * the implementation of 'integer' rule.
     *
     * @throws \Exception
     * */

    public static function validate_rule_is_integer($key, $value, $level)
    {
        if ($value != null && !ctype_digit("$value")) {


            throw new BadRequestException("$key (in $level within value = $value should be integer.");
        }
    }

    /**
     * the implementation of 'string' rule.
     *
     * @throws \Exception
     * */

    public static function validate_rule_is_string($key, $value, $level)
    {
        if ($value != null && !is_string($value))
            throw new BadRequestException("$key (in $level within value = $value should be string.");

    }

    /**
     * the implementation of 'required' rule.
     *
     * @throws \Exception
     * */
    public static function validate_rule_is_required($key, $value, $level)
    {

        if (!$value)
            throw new BadRequestException("The value of \" $key \" cannot be null in $level.");



    }


}//