<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FeaturedBook;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiEndpointsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_api_books_index(): void
    {
        $response = $this->getJson('/api/books');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'author',
                    'category',
                    'cover_url',
                    'rating',
                ]
            ]
        ]);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_api_books_show(): void
    {
        $book = FeaturedBook::first();
        if (!$book) {
            $book = FeaturedBook::create([
                'title' => 'Buku API Test',
                'author' => 'Penulis API',
                'category' => 'Sains',
                'is_active' => true,
            ]);
        }

        $response = $this->getJson('/api/books/' . $book->id);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
            ]
        ]);
    }

    public function test_api_home_endpoint(): void
    {
        $response = $this->getJson('/api/home');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'greeting',
                'banners',
                'popular_books',
                'latest_book',
                'editor_pick',
            ]
        ]);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_api_categories_endpoint(): void
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_api_login_endpoint(): void
    {
        $user = User::factory()->create([
            'email' => 'apitest_' . time() . '@gmail.com',
            'password' => bcrypt('Password123!'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'email' => $user->email,
                'name' => $user->name,
            ]
        ]);
    }
}
