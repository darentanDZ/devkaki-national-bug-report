<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bug_id',
    ];

    /**
     * Get the user that made the vote.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bug that was voted on.
     */
    public function bug()
    {
        return $this->belongsTo(Bug::class);
    }
}
