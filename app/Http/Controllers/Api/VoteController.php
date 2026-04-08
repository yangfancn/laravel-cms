<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\VoteRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class VoteController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function vote(VoteRequest $request): JsonResponse
    {
        Gate::authorize('vote');

        $model = (new (getModelName($request->validated('votable_type'))))
            ->where('id', $request->validated('votable_id'))
            ->firstOrFail();

        return new JsonResponse([
            'vote' => $model->vote($request->validated('vote')),
        ], Response::HTTP_CREATED);
    }
}
