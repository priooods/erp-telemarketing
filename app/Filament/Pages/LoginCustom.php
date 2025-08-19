<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Pages\Auth\Login as BasePage;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;

class LoginCustom extends BasePage
{
    // protected static ?string $navigationIcon = 'heroicon-o-document-text';
    // protected static string $view = 'filament.pages.login-custom';
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        } elseif (
            $user->m_status_tabs_id === 2
        ) {
            Filament::auth()->logout();

            throw ValidationException::withMessages([
                'data.email' => 'Your account is not active.',
            ]);
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
