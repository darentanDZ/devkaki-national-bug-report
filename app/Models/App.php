<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class App extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'company',
        'website',
        'logo_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the bugs for this app.
     */
    public function bugs()
    {
        return $this->hasMany(Bug::class);
    }
}
