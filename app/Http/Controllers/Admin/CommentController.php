<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\DataTable\Button;
use App\Services\DataTable\DataTable;
use App\Services\DataTable\StatusButton;
use App\Services\DataTable\TextColumn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function index(Comment $comment): Response
    {
        if (Auth::user()->hasPermissionTo('comments own resource')) {
            $comment = $comment->where('user_id', Auth::id());
        }

        $table = new DataTable(
            $comment->with('user'),
            'Comments List',
            'content',
        );

        $table
            ->addColumn(new TextColumn('id', 'ID', sortable: true))
            ->addColumn(new TextColumn('user.name', 'Username'))
            ->addColumn(new TextColumn('content', 'Comment Content'))
            ->addColumn(new TextColumn('approved', 'Approved'))
            ->addColumn(new TextColumn('created_at', 'Create Time', sortable: true))
            ->batchDeleteAction('admin.comments.batchDestroy')
            ->addRowAction(
                (new StatusButton(
                    '',
                    'mdi-close',
                    '',
                    'mdi-check',
                    'is_approved',
                    'admin.comments.approve',
                    'id'
                ))
                    ->negativeColor('positive')
                    ->positiveColor('negative')
                    ->method('PUT')
                    ->glossy()
            )
            ->addRowAction(
                (new Button('', 'mdi-delete', 'admin.comments.destroy', 'id'))
                    ->color('negative')
                    ->withConfirm()
                    ->flat()
                    ->method('delete')
            );

        return $table->make();
    }

    public function approve(Comment $comment): RedirectResponse
    {
        if ($comment->is_approved) {
            $comment->disapprove();
        } else {
            $comment->approve();
        }

        return redirect()->back();
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()->back();
    }

    public function batchDestroy(): RedirectResponse
    {
        return $this->batchDelete(new Comment);
    }
}
