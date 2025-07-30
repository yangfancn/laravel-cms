<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Votable
{
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function upVotes(): MorphMany
    {
        return $this->votes()->where('vote', true);
    }

    public function downVotes(): MorphMany
    {
        return $this->votes()->where('vote', false);
    }

    public function currentUserVoted(): MorphOne
    {
        return $this->morphOne(Vote::class, 'votable')->where('user_id', \Auth::id());
    }

    public function vote(bool $vote): ?bool
    {
        $voteModel = $this->votes()->firstOrCreate([
            'user_id' => \Auth::id(),
            'votable_type' => get_class($this),
            'votable_id' => $this->getKey(),
        ], [
            'vote' => $vote,
        ]);

        // update or delete
        if (! $voteModel->wasRecentlyCreated) {
            if ($vote === $voteModel->vote) {
                $voteModel->delete();

                return null;
            }
            $voteModel->vote = $vote;
            $voteModel->save();
        }

        return $vote;
    }

    public function currentUserVote(): ?bool
    {
        $voted = $this->relationLoaded('currentUserVoted') ? $this->currentUserVoted : $this->currentUserVoted()->first();
        if ($voted) {
            return $voted->vote;
        }

        return null;
    }

    public function hasBeenVotedBy(?User $user = null): bool
    {
        $user = $user ?? \Auth::user();

        return ($this->relationLoaded('votes') ? $this->votes : $this->votes())->where('user_id', $user->id)->exists();
    }
}
