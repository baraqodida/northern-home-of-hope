<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContributionHistory extends Model
{
    use HasFactory;

    // This tells Laravel which fields can be saved to the database
    protected $fillable = [
        'contribution_id', 
        'amount', 
        'week_label'
    ];

    /**
     * Get the contribution that owns the history.
     */
    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }
}