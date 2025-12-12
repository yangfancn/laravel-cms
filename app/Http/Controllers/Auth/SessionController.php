<?php

namespace App\Http\Controllers\Auth;

use App\Facades\InertiaMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SessionRequest;
use App\Services\Form\Elements\Input;
use App\Services\Form\Elements\Toggle;
use App\Services\Form\Form;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class SessionController extends Controller
{
    public function create(Request $request): InertiaResponse
    {
        if (! $request->session()->has('url.intended')) {
            $prevRoute = optional(
                app('router')->getRoutes()->match(
                    Request::create(url()->previous())
                )
            )->getName();

            // 除非上一页是 login/logout/sign-up
            if (! in_array($prevRoute, ['login', 'logout', 'sign-up'], true)) {
                $request->session()->put('url.intended', url()->previous());
            }
        }

        $form = (new Form(route('login')))
            ->add(Input::make('email', 'Email')->prependIcon('mdi-account'))
            ->add(Input::make('password', 'Password')->password()->prependIcon('mdi-lock'))
            ->add(Toggle::make('remember', 'Remember Me')->leftLabel(false)->cols(6))
            ->disablePrecognitive()
            ->create();

        return Inertia::render('Session/Create', compact('form'))->rootView('admin::app');
    }

    public function store(SessionRequest $request): Response|RedirectResponse
    {
        if (! Auth::attempt($request->all(['email', 'password']), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('messages.UsernamePasswordNotMatch'),
                'password' => __('messages.UsernamePasswordNotMatch'),
            ]);
        }

        $request->session()->regenerate();

        InertiaMessage::success(__('messages.LoginSuccess'));

        if (Auth::user()->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return Inertia::location(redirect()->intended(route('home'))->getTargetUrl());
    }

    public function destroy(Request $request): RedirectResponse
    {
        $redirect = route(Auth::user()?->hasRole('admin') ? 'admin.dashboard' : 'home');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->intended($redirect);
    }
}
