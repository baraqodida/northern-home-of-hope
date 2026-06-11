<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    /** @use HasFactory<\Database\Factories\ContributionFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Protects database columns from unexpected array data injection anomalies.
     */
    protected $fillable = [
        'user_id',
        'amount',
        'purpose',
        'status',
    ];

    /**
     * Relational connection link.
     * Establishes that every contribution entry belongs to an underlying User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}