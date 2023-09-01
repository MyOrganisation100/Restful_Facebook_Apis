<?php

namespace Models;


class Comments extends BaseModel
{
    protected $hidden=["post_id","user_id"];
    public function user(){
        return $this->belongsTo(User::class);
    }


}