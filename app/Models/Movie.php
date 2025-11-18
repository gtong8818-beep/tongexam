<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'reviews',
        'image_path',
        'user_id'
    ];

    protected $casts = [
        'reviews' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function validateReview($review)
    {
        return is_numeric($review) && $review >= 1 && $review <= 10;
    }
}
