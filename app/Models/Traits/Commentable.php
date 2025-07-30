<?php

namespace App\Models\Traits;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Commentable
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function comment(string $comment, ?User $user = null, ?int $comment_id = null): Comment
    {
        $user = $user ?: \Auth::user();
        $fields = [
            'content' => $comment,
            'user_id' => $user->id,
            'is_approved' => $user->can('comments approve'),
            'commentable_type' => get_class($this),
            'commentable_id' => $this->getKey(),
        ];

        if ($comment_id) {
            /**
             * @var Comment $comment
             */
            $comment = $this->comments()->findOrFail($comment_id);

            return $comment->children()->create($fields);
        }

        return Comment::create($fields);
    }
}
