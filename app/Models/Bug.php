<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bug extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'app_id',
        'category_id',
        'title',
        'description',
        'steps_to_reproduce',
        'expected_behavior',
        'actual_behavior',
        'device_info',
        'os_version',
        'app_version',
        'attachments',
        'severity',
        'status',
        'upvotes',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
        ];
    }

    /**
     * Get the user that reported the bug.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the app that this bug belongs to.
     */
    public function app()
    {
        return $this->belongsTo(App::class);
    }

    /**
     * Get the category of this bug.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the comments for this bug.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the votes for this bug.
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Increment view count.
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Scope a query to only include bugs with specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include bugs with specific severity.
     */
    public function scopeSeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }
}
