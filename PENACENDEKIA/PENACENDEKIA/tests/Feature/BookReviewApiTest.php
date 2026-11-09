<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BookReviewApiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_review_submission_is_saved_for_moderation(): void
    {
        $response = $this->postJson('/api/reviews', [
            'book_id' => 12,
            'book_title' => 'Buku Uji',
            'reviewer_name' => 'Pembaca',
            'rating' => 5,
            'review_text' => 'Buku ini sangat membantu.',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.is_approved', false);

        $this->assertDatabaseHas('book_reviews', [
            'book_id' => 12,
            'book_title' => 'Buku Uji',
            'rating' => 5,
            'review_text' => 'Buku ini sangat membantu.',
            'is_approved' => false,
        ]);
    }

    public function test_review_submission_requires_valid_rating_and_text(): void
    {
        $response = $this->postJson('/api/reviews', [
            'book_title' => 'Buku Uji',
            'reviewer_name' => 'Pembaca',
            'rating' => 6,
            'review_text' => '',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['rating', 'review_text']);
    }
}
