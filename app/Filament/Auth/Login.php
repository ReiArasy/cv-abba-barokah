<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Actions\Action;

class Login extends BaseLogin
{
    public function getHeading(): string
    {
        return 'Hi, Welcome Back!';
    }

    public function getSubheading(): ?string
    {
        return 'Please enter valid email and password to access Admin Panel';
    }

   protected function getFormActions(): array
    {
        return [
            Action::make('authenticate')
                ->label('Login') // Mengubah label tombol menjadi "Login"
                ->submit('authenticate'),
        ];
    }
}