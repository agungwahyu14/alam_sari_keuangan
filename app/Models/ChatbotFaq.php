<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotFaq extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'keywords' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk hanya data yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
