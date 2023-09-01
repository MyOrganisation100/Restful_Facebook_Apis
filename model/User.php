<?php

namespace Models;
class User extends BaseModel
{

    protected $hidden =["password"];
    public function posts(){
        return $this->hasMany(Posts::class);
    }
}