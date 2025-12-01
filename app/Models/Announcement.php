<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'announcement_date',
        'publishedBy',
    ];

    protected $casts = [
        'announcement_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];



    /**
     * Get the user who published this announcement
     */
    public function publisher()
    {
        return $this->belongsTo(User::class, 'publishedBy');
    }
}
