<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Table('book_user')]
#[Fillable(['user_id', 'book_id', 'status', 'rating', 'review', 'requested_at', 'borrowed_at', 'return_requested_at', 'returned_at'])]
class BookUser extends Pivot
{
    public $incrementing = true;

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'rating' => 'integer',
            'requested_at' => 'datetime',
            'borrowed_at' => 'datetime',
            'return_requested_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
