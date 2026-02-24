<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Passwords\PasswordBrokerManager;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->extend('auth.password', function ($manager, $app) {
            return new class($app) extends PasswordBrokerManager {
                protected function createTokenRepository(array $config)
                {
                    $key = $this->app['config']['app.key'];

                    if (str_starts_with($key, 'base64:')) {
                        $key = base64_decode(substr($key, 7));
                    }

                    $connection = $config['connection'] ?? null;

                    return new \App\Override\CustomTokenRepository(
                        $this->app['db']->connection($connection),
                        $this->app['hash'],
                        $config['table'],
                        $key,
                        $config['expire'],
                        $config['throttle'] ?? 0
                    );
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
