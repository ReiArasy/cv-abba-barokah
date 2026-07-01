<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Illuminate\Auth\Events\Login as LoginEvent;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;

class Login extends BaseLogin
{
    public function getHeading(): string
    {
        return 'Halo, Selamat Datang Admin!';
    }

    public function getSubheading(): ?string
    {
        return 'Mohon Masukkan Email dan Password yang Valid Untuk Mengakses Panel Admin';
    }

    /**
     * Memodifikasi Form untuk Menghapus Checkbox "Remember Me"
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->autocomplete()
                    ->autofocus(),
                $this->getPasswordFormComponent(),
                // Checkbox 'remember' sudah dihapus dari sini
            ])
            ->statePath('data');
    }

    /**
     * Mengubah Logika Login agar Selalu FALSE untuk "Remember Me"
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (\Filament\Exceptions\RateLimitingException $exception) {
            $this->getRateLimitedNotification($exception)->send();
            return null;
        }

        $data = $this->form->getState();

        // Di sini dikunci menjadi false agar Laravel tidak mencoba mengisi remember_token
        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        event(new LoginEvent(
            guard: Filament::getAuthGuard(),
            user: $user,
            remember: false,
        ));

        return app(LoginResponse::class);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('authenticate')
                ->label('Login')
                ->submit('authenticate'),
        ];
    }
}