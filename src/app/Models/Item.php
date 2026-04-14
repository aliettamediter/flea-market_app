<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
    'name',
    'description',
    'price',
    'brand',
    'condition_id',
    'user_id',
    'status',
    'image',
    ];
    public function categories()
    {
    return $this->belongsToMany(Category::class);
    }
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function purchases()
    {
        return $this->hasOne(Purchase::class);
    }
    public function isSoldOut(): bool
    {
        return $this->status === 'sold';
    }
    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    public function getImageUrlAttribute(): string
    {
        return str_starts_with($this->image, 'http')
            ? $this->image
            : asset('storage/' . $this->image);
    }
}
