<?php

namespace Models;


class Likes extends BaseModel
{
    public function user(){
     return $this->belongsTo(user::class)   ;
    }

}