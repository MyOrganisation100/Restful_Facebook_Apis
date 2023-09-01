<?php

namespace Mixins;
use customException\BadRequestException;
use helpers\RequestHelper;
use Models;
trait DatabaseRulesValidation
{
    /**
     * the implementation of 'unique' rule.
     *
     * @throws \BadFunctionCallException
     * @var Model $model
     *
     * */

    public static function validate_rule_is_unique($key, $value, $level,$model)
    {
        //exist email   saleh@gmail.com   // update user has email to saleh@gmail.com has id =5
        // userName
        if ($value==null) {              //
            return;
        }

        $modelId=RequestHelper::extractResourcefromRequestPath();
        $fetchModel=$model::query()->where($key, $value)->first();
        if ($modelId) {

            $matchModel = $model::query()->find($modelId);

            if ($fetchModel && $modelId != $fetchModel->id) {

                throw new BadRequestException("$key (in $level within value = $value should be unique.");

            }
        }

    }


}