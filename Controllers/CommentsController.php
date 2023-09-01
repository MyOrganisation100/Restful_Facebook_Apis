<?php

namespace Controller;

use constants\Rules;
use customException\BadRequestException;
use helpers\RequestHelper;
use helpers\ResourceHelper;
use Models\Comments;
use Models\Likes;
use Models\Posts;
use Models\User;

class CommentsController extends BaseController
{

    protected $validationSchema = [
        "create" =>
            [
                "url" => [
                    "userId" => [Rules::REQUIRED, Rules::INTEGER],
                    "postId" => [Rules::INTEGER]
                ],
                "payload" => [
                    "content" => [Rules::REQUIRED, Rules::STRING]
                ]
            ],
        "update" => [
            "url" => [
                "commentId" => [Rules::REQUIRED, Rules::INTEGER]
            ],
            "payload" => [
                "content" => [Rules::REQUIRED, Rules::STRING]
            ]
        ]

    ];

    protected function index($postId)
    {

        $current_page = key_exists('page', $_GET) ? $_GET['page'] : 1;
        $limit = key_exists('limit', $_GET) ? $_GET['limit'] : 2;
        $post = ResourceHelper::findResourceOR404Exception(Posts::class, $postId);
        return $post
            ->comments()
            ->with("user:id,name,profile_image")
            ->paginate($limit, ["*"], "page", $current_page)
            ->items();
    }

    protected function create($userId, $postId)
    {

        $user = ResourceHelper::findResourceOR404Exception(User::class, $userId);
        $post = ResourceHelper::findResourceOR404Exception(Posts::class, $postId);
        $payload = RequestHelper::getRequestPayload();

        $payload["post_id"] = $post->id;
        $payload["user_id"] = $user->id;
        Comments::create($payload);


        return ["message" => "user ( " . $user->id . ") comment on the post that have content ( " . $post->content . " ). "];
    }

    protected function update($commentId)
    {
        $comment = ResourceHelper::findResourceOR404Exception(Comments::class, $commentId);
        $payload = RequestHelper::getRequestPayload();

        $content = $payload["content"];
        $comment->update([
            "content" => $content
        ]);

        return ["message" => "user ( " . $comment->user_id . ")  is update comment on the post . "];
    }

}