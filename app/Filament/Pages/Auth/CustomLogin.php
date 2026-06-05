<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\Facades\Activity;

class CustomLogin extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        $user = User::where('email', $data['email'])->first();

        if ($user && ! $user->is_active) {
            Activity::causedBy($user)
                ->withProperties(['email' => $data['email'], 'ip' => request()->ip()])
                ->log('Attempted login to inactive account');

            throw ValidationException::withMessages([
                'data.email' => 'Sorry, your account is not active yet, please contact your administrator',
            ]);
        }

        if ($user && $user->is_locked) {
            Activity::causedBy($user)
                ->withProperties(['email' => $data['email'], 'ip' => request()->ip()])
                ->log('Attempt login to locked account');

            throw ValidationException::withMessages([
                'data.email' => 'Your account is locked, please contact your system administrator',
            ]);
        }

        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Activity::causedBy($user)
                ->withProperties(['email' => $data['email'], 'ip' => request()->ip()])
                ->log('Too many request has been made');

            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $sessionUser = Filament::auth()->user();

        if (
            ($sessionUser instanceof FilamentUser) &&
            (! $sessionUser->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        session([
            'department_id' => $sessionUser->department_id,
            'section_id' => $sessionUser->section_id,
        ]);

        Activity::causedBy($user)
            ->withProperties(['email' => $data['email'], 'ip' => request()->ip(), 'agent' => request()->header('Sec-CH-UA')])
            ->log('Authentication success');

        return app(LoginResponse::class);
    }
}
