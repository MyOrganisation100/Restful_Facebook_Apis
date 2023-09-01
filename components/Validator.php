<?php

namespace component;

use constants\Rules;
use Illuminate\Database\Eloquent\Model;
use Mixins\BasicRulesValidation, Mixins\DatabaseRulesValidation;

use Models\User;
use Exception;

class Validator
{

    use BasicRulesValidation, DatabaseRulesValidation;

    public function __construct()
    {
        $this->validateImplementedRulesByConstants();
    }


    /**
     *
     * @throws \Exception
     * */
    private function validateImplementedRulesByConstants()
    {
        $rules = (new Rules())->listOfExistConstant();
        foreach ($rules as $rule) {
            $this->validateRuleIsImplemented($rule
                , "Please sync your Rules constants with existing implementations in Validator component.");
        }
    }

    /**
     *
     * @throws \Exception
     * */
    private function validateRuleIsImplemented($rule, $messageException)
    {
        if (!method_exists($this, "validate_rule_is_" . $rule)) {
            throw new \Exception($messageException);
        }
    }

    /**
     *
     * represent shared validation data method
     *
     * @throws \BadFunctionCallException
     * */

    public  function validate($schema, $values, $level)
    {
        if ($schema != null) {
            foreach ($schema as $key => $rules) {
                $value = null;

                if (key_exists($key, $values)) {
                    $value = $values[$key];


                    $argument = [];
                    foreach ($rules as $specialKey => $rule) {

                        if ($rule == Rules::UNIQUE && !is_array($rule)) {
                            throw new Exception("'unique' rule should have another level of data having 'model' value .");
                        }

                        $argument = [$key, $value, $level];
                        if (is_array($rule)) {
                            if (key_exists('model', $rule)) {
                                $argument[] = $rule['model'];
                            }
                        }

                        if (!is_integer($specialKey)) {
                            $rule = $specialKey;
                        }


                        $rule_method = "validate_rule_is_" . $rule;

                        $this->$rule_method(...$argument);
                    }
                }
            }
            }

        }


    /**
     * represent validation Url Variables level
     *
     * @param array $schema (associative array)
     * @param array $values (associative array)
     * @param string $level
     * @return void
     * @throws \BadFunctionCallException
     * @var Model $model
     * */

    public  function validateUrlVariables($schema, $values)
    {
        self::validate($schema, $values, "url variables level ");

    }

    /**
     * represent validation query  params level
     *
     * @param array $schema (associative array)
     * @param array $values (associative array)
     * @param string $level
     *
     * @return void
     *
     * @throws \Exception
     * */

    public  function validateQueryParams($schema, $values)

    {
        self::validate($schema, $values, "query params level ");

    }

    /**
     * represent validation payload data level
     *
     * @param array $schema (associative array)
     * @param array $values (associative array)
     * @param string $level
     *
     * @return void
     *
     * @throws \BadFunctionCallException
     * */

    public  function validatePayloadData($schema, $values)
    {
        self::validate($schema, $values, " payload data level ");

    }


}
