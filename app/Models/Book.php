<?php

namespace App\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['title', 'author', 'image', 'description'])]
class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(BookUser::class)
            ->as('loan')
            ->withPivot(['status', 'rating', 'review', 'requested_at', 'borrowed_at', 'return_requested_at', 'returned_at'])
            ->withTimestamps();
    }
}
