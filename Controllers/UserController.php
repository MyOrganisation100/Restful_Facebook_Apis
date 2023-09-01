<?php

namespace Controller;

use constants\Rules;
use helpers\RequestHelper;
use helpers\ResourceHelper;
use Illuminate\Database\Eloquent\Model;
use Models\User;
use customException\SourceNotFound;
use Exception;

class UserController extends BaseController
{


    protected $validationSchema = [
        'create' => [
            "payload" => [
                "name" => [Rules::REQUIRED, Rules::STRING],
                "userName" => [Rules::REQUIRED,
                    Rules::STRING,
                    Rules::UNIQUE=>[
                        "model"=>User::class
                    ]
                ],
                "email" => [Rules::REQUIRED,
                    Rules::STRING,
                    Rules::UNIQUE=>[
                        "model"=>User::class
                    ]
                ],
                "profile_img" => [Rules::STRING],
                "password" => [Rules::REQUIRED, Rules::STRING]
            ]
        ],
        "update" => [
            "payload" => [
                "name" => [Rules::STRING],
                "userName" => [Rules::STRING, Rules::UNIQUE=>[
                    "model"=>User::class
                ]],
                "email" => [Rules::STRING,
                    Rules::UNIQUE=>[
                        "model"=>User::class
                    ]],
                "profile_image" => [Rules::STRING],
                "password" => [Rules::STRING]

            ]
        ]
    ];


    /*
     * [GET/user] index()-> get the list of resources
     * [GET/user/{id}]  git resource by id
     *[POST/user] create mew resource
     *[PUT/user/{id}] update resource by id
     * [DELETE/user/{id}] delete resource by id
     */

    protected function index()
    {


        $current_page=key_exists('page',$_GET)?$_GET['page']:1;
        $limit=key_exists('limit',$_GET)?$_GET['limit']:2;
        $paginator=User::query()->paginate($limit,['id','userName','profile_image'],"page",$current_page);
        return $paginator->items();
    }


    protected function show($id)
    {

        $user =ResourceHelper::findResourceOR404Exception(User::class,$id);
        if (!$user) {
            throw new SourceNotFound();
        }
        return $user;
    }

    protected function create()
    {
        $payload = RequestHelper::getRequestPayload();
        $payload['password'] = md5($payload['password']);

        $user = User::create($payload);

        return [
            "id" => $user->id
        ];
    }

    protected function update($id)
    {
        $payload = RequestHelper::getRequestPayload();

        if (key_exists("password",$payload) ) {
            throw new Exception("password can't be update by this API.");
        }

        $user = ResourceHelper::findResourceOR404Exception(User::class,$id);
        $user->update($payload);

        return [
            "message" => "update "
        ];

    }

    protected function delete($id)
    {
        $payload = RequestHelper::getRequestPayload();

        $user = ResourceHelper::findResourceOR404Exception(User::class,$id);
        $user->delete($payload);

        return [
            "message" => "deleted "
        ];

    }

}




