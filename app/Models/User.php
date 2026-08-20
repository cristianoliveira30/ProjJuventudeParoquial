<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use AuthenticationLoggable, HasApiTokens, HasFactory, HasPushSubscriptions, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'password_verify',
        'is_active',
        'role_id',
        'profile_photo_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_verify' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function hasPermission(string $permission): bool
    {
        if (! $this->is_active || ! $this->role) {
            return false;
        }

        if ($this->role->sigla === 'ADMIN') {
            return true;
        }

        return $this->role->permissions->contains('slug', $permission);
    }

    public function deliveriesNotifications()
    {
        return $this->hasMany(NotificationDeliveries::class, 'user_id', 'id');
    }

    public function unreadNotifications()
    {
        return $this->deliveriesNotifications()
            ->whereNull('read_at')
            ->with('notification')
            ->with('notification.creator:id,name,username,email');
    }
}
