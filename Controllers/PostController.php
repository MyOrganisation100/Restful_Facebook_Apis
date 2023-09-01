<?php

namespace Controller;

use constants\Rules;
use customException\BadRequestException;
use helpers\RequestHelper;
use helpers\ResourceHelper;
use Models\Likes;
use Models\Posts;
use customException\SourceNotFound;
use Models\User;
use function Symfony\Component\String\u;

class PostController extends BaseController
{
    protected $validationSchema = [
        "index" => [
            "url" => [
                "userId" => [Rules::INTEGER]
            ],
            "query" => [
                "limit" => [Rules::INTEGER],
                "page" => [Rules::INTEGER]
            ]
        ], "create" => [
            "url" => [
                "userId" => [Rules::INTEGER]
            ],
            "payload" => [
                "content" => [
                    Rules::REQUIRED, Rules::STRING]

            ]
        ], "show" => [
            "url" => [
                "postId" => [Rules::REQUIRED, Rules::INTEGER]
            ]
        ], "update" => [
            "url" => [
                "postId" => [Rules::REQUIRED, Rules::INTEGER]
            ],
            "payload" => [
                "content" => [Rules::REQUIRED, Rules::STRING]
            ]
        ], "delete" => [
            "url" => [
                "postId" => [Rules::REQUIRED, Rules::INTEGER]
            ]
        ],
        "like" => [
            "url" => [
                "userId" => [Rules::REQUIRED, Rules::INTEGER],
                "postId" => [Rules::INTEGER]
            ]
        ]


    ];

    protected function index($userId)
    {


        $current_page = key_exists('page', $_GET) ? $_GET['page'] : 1;
        $limit = key_exists('limit', $_GET) ? $_GET['limit'] : 2;
        $user = ResourceHelper::findResourceOR404Exception(User::class, $userId);
        $posts = $user
            ->posts()
            ->with('user:id,name,profile_image', 'likes', 'comments', 'comments.user:id,name,profile_image')
            ->paginate($limit, ['id', 'content', 'created', 'user_id'], 'page', $current_page)
            ->items();

        return ResourceHelper::loadOnlyForList(
            ["id", "content", "created", "publisher_user", "like_count", "recent_likes", "comments_count", "recent_comments"]
            ,
            $posts
        );

    }

    /**
     *  - name (publisher user)
     *  - avatar (publisher user)
     *  - date of post created (post)
     *  - content (post)
     *  - likes
     *      - 1. count of likes
     *      - 2. the last 2 users (if exists) names that made likes
     *  - comments:
     *      - 1. count of comments
     *      - 2. the last 5 (if exists) comments in the posts.
     *  [.]
     *  [Request] GET api/v1/posts/{postId}
     *  [Response] JSON
     *  {
     *      "id": int,
     *      "content": text,
     *      "created": date,
     *      "publisher_user": {
     *          "id": int,
     *          "name": string,
     *          "avatar": url
     *      },
     *      "likes_count": int,
     *      "recent_likes": [string, ...],
     *      "comments_like": int,
     *      "recent_comments": [
     *          {
     *              "id": int,
     *              "content": string,
     *              "created": string,
     *              "user": {
     *                  "id": int,
     *                  "name": string,
     *                  "avatar": url
     *              }
     *          }, ...
     *      ]
     *  }
     */


    protected function show($postId)
    {

        $post = ResourceHelper::findResourceOR404Exception(
            Posts::class,
            $postId,
            ['user:id,name,profile_image', 'likes', 'comments', 'comments.user:id,name,profile_image']
        );

        return ResourceHelper::loadOnly(
            ["id", "content", "created", "publisher_user", "like_count", "recent_likes", "comments_count", "recent_comments"],
            $post
        );

    }

    protected function create($userId)
    {
        $user = User::query()->find($userId);
        $payload = RequestHelper::getRequestPayload();
        if (!$user) {
            throw new SourceNotFound();
        }
        $user->posts()->create([
            "content" => $payload['content']
        ]);

        return ["message" => "post created within id ##$user->id"];

    }

    protected function update($postId)
    {
        $post = Posts::query()->find($postId);
        $payload = RequestHelper::getRequestPayload();
        if (!$post) {
            throw new SourceNotFound();
        }
        Posts::query()->find($postId)->update([
                "content" => $payload['content']
            ]
        );

        return ["message " => "post updated "];


    }

    protected function delete($postId)
    {
        if (!(Posts::query()->where("id", $postId)->exists())) {
            throw new SourceNotFound();
        }
        Posts::query()->find($postId)->delete();
        return ["message " => "post deleted "];

    }

    protected function like($userId, $postId)
    {

        $user = ResourceHelper::findResourceOR404Exception(User::class, $userId);
        $post = ResourceHelper::findResourceOR404Exception(Posts::class, $postId);

        $isLikeExists = Likes::query()->where("user_id", $user->id)->where("post_id", $post->id)->exists();
        if ($isLikeExists) {

            throw new BadRequestException("this user ( " . $user->userName . " ) is already like the post ");
        }

        Likes::create([
            "user_id" => $user->id,
            "post_id" => $post->id
        ]);

        return ["message" => "user ( " . $user->id . ") like the post that have content ( " . $post->content . " ). "];
    }

    protected function unLike($userId, $postId)
    {

        $user = ResourceHelper::findResourceOR404Exception(User::class, $userId);
        $post = ResourceHelper::findResourceOR404Exception(Posts::class, $postId);

        $like = Likes::query()->where("user_id", $user->id)->where("post_id", $post->id)->first();
        if ($like == null) {

            throw new BadRequestException("this user ( " . $user->userName . " ) should be liked the post first to remove his like   ");

        }


        $like->delete();
        return ["message" => "user ( " . $user->id . ") un-like the post that have content ( " . $post->content . " ). "];


    }

}