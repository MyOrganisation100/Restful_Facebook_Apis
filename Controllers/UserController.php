<?php

namespace Controller;

use constants\Rules;
use customException\UnAuthorizedException;
use helpers\RequestHelper;
use helpers\ResourceHelper;
use Mixins\AuthenticateUser;
use Models\User;
use customException\SourceNotFound;
use Exception;

class UserController extends BaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->skipHandler=['index','show','create'];

    }
    use AuthenticateUser;


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


    /**
     * @return User array
     *
     * @API users
     */
    protected function index()
    {

        $current_page=key_exists('page',$_GET)?$_GET['page']:1;
        $limit=key_exists('limit',$_GET)?$_GET['limit']:2;
        $paginator=User::query()->paginate($limit,['id','userName','profile_image'],"page",$current_page);
        return $paginator->items();
    }


    /**
     * @param $id
     * @return resource
     * @throws SourceNotFound
     *
     * @API users/{id}
     */
    protected function show($id)
    {

        $user =ResourceHelper::findResourceOR404Exception(User::class,$id);
        if (!$user) {
            throw new SourceNotFound();
        }
        return $user;
    }

    /**
     * @return User id
     *
     * @API users
     *
     */
    protected function create()
    {

        $payload = RequestHelper::getRequestPayload();
        $payload['password'] = md5($payload['password']);

        $user = User::create($payload);

        return [
            "id" => $user->id
        ];
    }

    /**
     * @param $id
     * @return string[]
     * @throws SourceNotFound
     * @throws UnAuthorizedException
     *
     * @API users/{id}
     */
    protected function update($id)
    {
        $payload = RequestHelper::getRequestPayload();

        $user = ResourceHelper::findResourceOR404Exception(User::class,$id);

        $this->userAuthenticated->validateIsUserAuthorizedTo($user,"id");

        if (key_exists("password",$payload) ) {
            throw new Exception("password can't be update by this API.");
        }

        $user->update($payload);

        return [
            "message" => "update "
        ];

    }

    /**
     * @param $id
     * @return string[]
     * @throws SourceNotFound
     * @throws UnAuthorizedException
     *
     * @API  users/{id}
     *
     */
    protected function delete($id)
    {
        $payload = RequestHelper::getRequestPayload();

        $user = ResourceHelper::findResourceOR404Exception(User::class,$id);

        $this->userAuthenticated->validateIsUserAuthorizedTo($user,"id");

        $user->delete($payload);

        return [
            "message" => "deleted "
        ];

    }

}




