<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'leader_id'];

    /**
     * Get the representative leader/admin for this group cluster.
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Get all the registered members belonging to this specific group.
     */
    public function members()
    {
        return $this->hasMany(User::class);
    }
}