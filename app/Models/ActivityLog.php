<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // Disable automatic Eloquent timestamps
    public $timestamps = false;

    // Allow these fields to be mass-assigned
    protected $fillable = [
        'user_id', 
        'action', 
        'target_model', 
        'target_id', 
        'details',
        'created_at' // Added to allow manual setting in Controller
    ];

    // Relationship to easily fetch the user who performed the action
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}