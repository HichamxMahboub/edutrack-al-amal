<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'specialty',
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
            'role' => UserRole::class,
        ];
    }

    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'teacher_id');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(UserRole::Admin);
    }

    public function isTeacher(): bool
    {
        return $this->hasRole(UserRole::Enseignant);
    }

    public function isEncadrant(): bool
    {
        return $this->hasRole(UserRole::Encadrant);
    }

    public function isMentor(): bool
    {
        return $this->isEncadrant();
    }

    public function hasRole(UserRole|string|array $roles): bool
    {
        $currentRole = $this->role instanceof UserRole ? $this->role : UserRole::from((string) $this->role);

        $roleValues = is_array($roles)
            ? array_map(fn (UserRole|string $role) => $role instanceof UserRole ? $role->value : $role, $roles)
            : [$roles instanceof UserRole ? $roles->value : $roles];

        return in_array($currentRole->value, $roleValues, true);
    }

    public function roleLabel(): string
    {
        $role = $this->role instanceof UserRole ? $this->role : UserRole::from((string) $this->role);

        return $role->label();
    }

    public function getRoleLabelAttribute(): string
    {
        return $this->roleLabel();
    }
}
