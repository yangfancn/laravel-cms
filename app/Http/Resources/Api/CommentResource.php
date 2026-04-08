<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at->diffForHumans(),
            'user' => $this->whenLoaded('user', fn () => [
                'name' => $this->user->name,
                'photo' => $this->user->photo,
            ]),
            'children' => $this->whenLoaded('children', fn () => new CommentCollection($this->children)),
            'children_count' => $this->children_count ?? 0,
            'depth' => $this->depth ?? 0,
            'up_votes' => $this->up_votes_count ?? 0,
            'down_votes' => $this->down_votes_count ?? 0,
            'current_user_vote' => $this->currentUserVote(),
        ];
    }
}
