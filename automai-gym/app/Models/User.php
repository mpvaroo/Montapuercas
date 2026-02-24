<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable implements MustVerifyEmail, CanResetPasswordContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, CanResetPassword;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_usuario';

    /**
     * Disable Laravel's default timestamps as we use custom Spanish ones.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre_mostrado_usuario',
        'correo_usuario',
        'hash_contrasena_usuario',
        'estado_usuario',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'hash_contrasena_usuario',
        'remember_token',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->hash_contrasena_usuario;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_creacion_usuario' => 'datetime',
            'hash_contrasena_usuario' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->nombre_mostrado_usuario)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'usuarios_roles', 'id_usuario', 'id_rol')
            ->withPivot('fecha_asignacion_rol');
    }

    /**
     * Get the profile associated with the user.
     */
    public function perfil(): HasOne
    {
        return $this->hasOne(Perfil::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the settings associated with the user.
     */
    public function ajustes(): HasOne
    {
        return $this->hasOne(Ajustes::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the security settings associated with the user.
     */
    public function seguridad(): HasOne
    {
        return $this->hasOne(Seguridad::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the reservations for the user.
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(ReservaClase::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the routines for the user.
     */
    public function rutinas(): HasMany
    {
        return $this->hasMany(RutinaUsuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the progress records for the user.
     */
    public function registrosProgreso(): HasMany
    {
        return $this->hasMany(RegistroProgreso::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('nombre_rol', $role)->exists();
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string|array
     */
    public function routeNotificationForMail($notification)
    {
        return $this->correo_usuario;
    }

    /**
     * Get the first role (helper for legacy view code).
     */
    public function getRolAttribute()
    {
        return $this->roles->first();
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification(): string
    {
        return $this->correo_usuario;
    }

    /**
     * Get the name of the email verified at column.
     *
     * @return string
     */
    public function getEmailVerifiedAtColumn(): string
    {
        return 'email_verified_at';
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailSpanish());
    }

    /**
     * Get the URL for the user's avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->perfil?->avatar_url ?? asset('avatars/nada.png');
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->correo_usuario;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordSpanish($token));
    }
}
