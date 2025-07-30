<?php

namespace App\Http\Controllers\Auth;

use App\Facades\InertiaMessage;
use App\Services\Form\Elements\Input;
use App\Services\Form\Form;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;

class PasswordController extends Controller
{
    public function showConfirmPasswordForm(): Response
    {
        $form = (new Form(route('password.confirm.process')))
            ->add(Input::make('password', 'Password')->password())
            ->create();
        $title = 'Password Confirmation';

        return Inertia::render('Auth/Form', compact('form', 'title'))
            ->rootView('admin::app');
    }

    public function confirmPassword(Request $request): RedirectResponse
    {
        if (! Hash::check($request->str('password'), $request->user()->password)) {
            InertiaMessage::error('Wrong password.');

            return redirect()->back();
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended();
    }

    public function showRequestForm(): Response
    {
        $form = (new Form(route('password.email')))
            ->add(Input::make('email', 'Email')->email()->prependIcon('mail'))
            ->create();
        $title = 'Reset Password';

        return Inertia::render('Auth/Form', compact('form', 'title'))
            ->rootView('admin::app');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            InertiaMessage::success(__($status));

            return redirect()->back();
        }

        return redirect()->back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, string $token): Response
    {
        $email = $request->str('email')->value();

        $form = (new Form(route('password.update'), data: compact('token', 'email')))
            ->add(Input::make('email', 'Email')->email()->prependIcon('mail')->disable())
            ->add(Input::make('password', 'Password')->password())
            ->add(Input::make('password_confirmation', 'Confirm Password')->password())
            ->create();

        $title = 'Reset Password';

        return Inertia::render('Auth/Form', compact('form', 'title'))
            ->rootView('admin::app');
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            InertiaMessage::success(__($status));

            return redirect()->route('login');
        }

        return redirect()->back()->withErrors(['email' => __($status)]);
    }
}
