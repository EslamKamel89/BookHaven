<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::each(function (User $user) {
            $bookIds = Book::query()
                ->inRandomOrder()
                ->take(rand(3, 5))
                ->pluck('id');

            foreach ($bookIds as $bookId) {
                $status = collect(Status::cases())->random();

                $requestedAt = now()->subDays(rand(5, 30));

                $borrowedAt = match ($status) {
                    Status::Borrowed,
                    Status::Returned => $requestedAt->copy()->addDays(rand(1, 5)),
                    default => null,
                };

                $returnRequestedAt = match ($status) {
                    Status::Returned => $borrowedAt?->copy()->addDays(rand(3, 7)),
                    default => null,
                };

                $returnedAt = match ($status) {
                    Status::Returned => $returnRequestedAt?->copy()->addDays(rand(1, 3)),
                    default => null,
                };

                $rating = $status === Status::Returned
                    ? rand(3, 5)
                    : null;

                $review = $status === Status::Returned
                    ? fake()->paragraph()
                    : null;

                $user->books()->attach($bookId, [
                    'status' => $status->value,
                    'rating' => $rating,
                    'review' => $review,
                    'requested_at' => $requestedAt,
                    'borrowed_at' => $borrowedAt,
                    'return_requested_at' => $returnRequestedAt,
                    'returned_at' => $returnedAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
