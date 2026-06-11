<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'role', // 👈 Enabled mass assignment for your new system roles
        'password',
        
        // --- Custom Northern Home of Hope Fields ---
        'phone_number',
        'county',
        'sub_county',
        'ward',
        'status',
        'group_id',
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
        ];
    }

    /**
     * Get the group cluster that this member belongs to.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // =========================================================================
    // --- Role-Based Access Control (RBAC) Verification Gateways ---
    // =========================================================================

    /**
     * Check if the user is a system-wide Super Administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is an assigned Group Cluster Representative/Leader.
     */
    public function isLeader(): bool
    {
        return $this->role === 'leader';
    }

    /**
     * Check if the user is a standard organization contributor/member.
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }
}