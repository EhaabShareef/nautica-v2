<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'id_card',
        'address',
        'is_active',
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
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Client-specific relationships and methods
     */
    public function vessels()
    {
        return $this->hasMany(Vessel::class);
    }

    public function isClient(): bool
    {
        return $this->user_type === 'client';
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->is_active ? 'active' : 'inactive';
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name . ($this->id_card ? " ({$this->id_card})" : '');
    }

    /**
     * Validation rules for client management
     */
    public function getValidationRules($isUpdate = false): array
    {
        $emailRule = $isUpdate ? 'unique:users,email,' . $this->id : 'unique:users,email';
        $idCardRule = $isUpdate ? 'unique:users,id_card,' . $this->id : 'unique:users,id_card';
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|' . $emailRule,
            'phone' => 'nullable|string|max:20',
            'id_card' => 'nullable|string|max:50|' . $idCardRule,
            'address' => 'nullable|string|max:500',
            'user_type' => 'required|in:admin,client',
            'is_active' => 'boolean',
            'password' => $isUpdate ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Scope for filtering clients only
     */
    public function scopeClients($query)
    {
        return $query->where('user_type', 'client');
    }

    /**
     * Scope for active users only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }
}
