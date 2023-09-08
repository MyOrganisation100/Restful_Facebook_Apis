<?php

namespace helpers;


use customException\BadRequestException;
use customException\SourceNotFound;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Model;

class ResourceHelper
{

    /**
     * @param $model
     * @param $resourceId
     * @return  resource is match by resourceId.
     * @throw  Exception when model isn't subclass of Eloquent Model.
     */
    public static function findResource($model, $resourceId, $with)
    {
        if (!(new $model instanceof Model)) {

            throw new \Exception("[bad usage] the passed 'model' within 'find`resource ' method should be subclass of /Eloquent/Model");
        }
        return $model::query()->with($with)->find($resourceId);

    }

    /**
     * @param $model
     * @param $resourceId
     * @return resource
     * @throws ResourceNotFound|SourceNotFound when corresponding model match by resourceId isn't exists.
     */
    public static function findResourceOR404Exception($model, $resourceId, $with = [])
    {
        $resource = self::findResource($model, $resourceId, $with);
        if (!$resource) {
            throw new SourceNotFound();
        }
        return $resource;
    }

    public static function loadOnly($attributes, $recourse)
    {

        if (!is_array($attributes)) {
            throw new BadRequestException("[Bad Usage] the passed 'attribute' should be array");
        }
        if (!($recourse instanceof Model)) {
            throw new BadRequestException("[Bad Usage] the passed 'recourse' method should be instanceof Eloquent/Model");
        }
        $loaded_data = [];

        foreach ($attributes as $attribute) {
            $loaded_data[$attribute] = $recourse->$attribute;
        }
        return $loaded_data;
    }

    public static function loadOnlyForList($attribute, $recourses)
    {

        $recourses_collection = [];

        foreach ($recourses as $recourse) {

            $recourses_collection [] = self::loadOnly($attribute, $recourse);
        }
        return $recourses_collection;
    }
}