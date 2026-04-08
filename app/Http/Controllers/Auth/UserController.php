<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRequest;
use App\Models\User;
use App\Services\Form\Elements\Input;
use App\Services\Form\Form;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function create(): Response
    {
        $form = (new Form(route('sign-up')))
            ->add(Input::make('name', 'Name')->prependIcon('mdi-account'))
            ->add(Input::make('email', 'Email')->prependIcon('mdi-mail'))
            ->add(Input::make('password', 'Password')->password()->prependIcon('mdi-lock'))
            ->create();

        return Inertia::render('Users/Create', compact('form'))->rootView('admin::app');
    }

    public function store(UserRequest $request, User $user): \Illuminate\Http\Response
    {
        $user->fill($request->validated())->save();
        Auth::login($user, true);

        return response('', 409, [
            'X-Inertia-Location' => route('home'),
        ]);
    }
}
