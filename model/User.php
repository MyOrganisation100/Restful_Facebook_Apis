<?php

namespace Models;

use customException\BadRequestException;
use customException\UnAuthorizedException;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends BaseModel
{


    protected $hidden = ["password"];


    public function posts()
    {
        return $this->hasMany(Posts::class);
    }

    /**
     * @param User $resource
     * @param $customField
     * @return void
     * @throws UnAuthorizedException
     */
    public function validateIsUserAuthorizedTo($resource, $customField = "")
    {
        $customField = $customField ?: "user_id";

        if ($this->id !== $resource->$customField) {


            throw new UnAuthorizedException();
        }
    }
}