<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

use App\Http\Responses\RegisterResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('correo_usuario', $request->correo_usuario)->first();

            if (! $user) {
                return null;
            }

            if ($user->estado_usuario === 'bloqueado') {
                throw ValidationException::withMessages([
                    Fortify::username() => 'Tu cuenta ha sido bloqueada. Por favor, contacta con nuestro servicio de atención al cliente para más información.',
                ]);
            }

            if ($user->estado_usuario === 'pendiente') {
                throw ValidationException::withMessages([
                    Fortify::username() => 'Tu cuenta está pendiente de aprobación por parte del administrador. Por favor, espera a que sea activada.',
                ]);
            }

            if (! Hash::check($request->password, $user->hash_contrasena_usuario)) {
                return null;
            }

            return $user;
        });
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn() => view('auth.login'));
        Fortify::verifyEmailView(fn() => view('auth.verify-email'));
        Fortify::twoFactorChallengeView(fn() => view('auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn() => view('auth.confirm-password'));
        Fortify::registerView(fn() => view('auth.register'));
        Fortify::resetPasswordView(fn() => view('auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn() => view('auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
