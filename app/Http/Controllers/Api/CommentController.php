<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CommentRequest;
use App\Http\Resources\Api\CommentCollection;
use App\Http\Resources\Api\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment', [
            'except' => ['index'],
        ]);

        $this->middleware('auth', [
            'except' => ['index'],
        ]);
    }

    public function index(Request $request, Comment $comment): JsonResponse
    {
        $builder = $comment
            ->isApproved()
            ->where('commentable_type', getModelName($request->string('commentable_type')))
            ->where('commentable_id', $request->integer('commentable_id'))
            ->withDepth()
            ->having('depth', '=', 0);

        $data = (clone $builder)
            ->reversed()
            ->with([
                'children' => fn ($builder) => $builder
                    ->isApproved()
                    ->with('currentUserVoted')
                    ->withCount(['upVotes', 'downVotes', 'children'])
                    ->withUser()
                    ->withDepth()
                    ->having('depth', '=', 1)
                    ->limit(3),
                'currentUserVoted',
            ])
            ->withCount(['upVotes', 'downVotes', 'children'])
            ->offset($request->integer('offset'))
            ->limit(10)
            ->withUser()
            ->get();

        return response()->json([
            'data' => new CommentCollection($data),
            'count' => $builder->count(),
        ]);
    }

    public function store(CommentRequest $request): JsonResponse
    {
        $modelType = $request->validated('commentable_type');
        $model = (new (getModelName($modelType)))
            ->where('id', $request->validated('commentable_id'))
            ->firstOrFail();

        /**
         * @var Comment $comment
         */
        $comment = $model->comment($request->validated('content'), comment_id: $request->validated('comment_id'));
        $comment->load('user');

        return response()->json(new CommentResource($comment));
    }

    public function show(Request $request, Comment $comment): JsonResponse
    {
        $depth = $comment->ancestors()->count() + 2;
        $data = $comment
            ->children()
            ->with([
                'children' => fn ($builder) => $builder
                    ->with('currentUserVoted')
                    ->withCount(['upVotes', 'downVotes'])
                    ->withUser()
                    ->withDepth()
                    ->withCount('children')
                    ->having('depth', '=', $depth)
                    ->limit(3),
                'currentUserVoted',
            ])
            ->withCount('children')
            ->withCount(['upVotes', 'downVotes'])
            ->reversed()
            ->offset($request->integer('offset'))
            ->limit(10)
            ->withUser()
            ->withDepth()
            ->get();

        return response()->json([
            'data' => new CommentCollection($data),
        ]);
    }
}
