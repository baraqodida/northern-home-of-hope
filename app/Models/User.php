<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'role', 'password', 
        'phone_number', 'county', 'sub_county', 'ward', 'status', 'group_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELATIONSHIPS ---

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * THIS WAS MISSING: This method fixes your error.
     */
    public function contribution()
    {
        return $this->hasOne(Contribution::class, 'user_id');
    }

    // --- RBAC METHODS ---

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isLeader(): bool { return $this->role === 'leader'; }
    public function isMember(): bool { return $this->role === 'member'; }
}