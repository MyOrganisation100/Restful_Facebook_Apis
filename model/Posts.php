<?php

namespace Models;


use function Symfony\Component\String\s;

class Posts extends BaseModel
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Likes::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'post_id');
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
     *      "comments_count": int,
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


    protected $appends = ["publisher_user", "like_count", "recent_likes", "comments_count", "recent_comments"];

    public function getPublisherUserAttribute()
    {
        return $this->user;

    }

    public function getLikeCountAttribute()
    {

        return sizeof($this->likes);
    }

    public function getRecentLikesAttribute()
    {

        $recent_user_like = [];
        foreach ($this->likes->sortByDesc('created') as $like) {

            $recent_user_like[] = $like->user->name;

            if (sizeof($recent_user_like) == 2) {
                return $recent_user_like;
            }
            return $recent_user_like;

        }

    }

    public function getCommentsCountAttribute()
    {

        return sizeof($this->comments);

    }

    public function getRecentCommentsAttribute()
    {
        $recent_comment = [];
        foreach ($this->comments->sortByDesc('created') as $comment) {

            $recent_comment[] = [
                "id" => $comment->id,
                "content" => $comment->content,
                "created" => $comment->created,
                "user" => [
                    "id" => $comment->user->id,
                    "name" => $comment->user->name,
                    "profile_image" => $comment->user->profile_image
                ]
            ];

            if (sizeof($recent_comment) == 2) {
                return $recent_comment;
            }
            return $recent_comment;


        }
    }


}