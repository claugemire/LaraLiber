<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\User;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testBookListingDeniedForAnonymousUser()
    {
        $response = $this->get('/books');
        $response->assertStatus(302);
    }

    public function testBookListingForLoggedInUser()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/books');
        $response->assertStatus(200);
        $response->assertViewIs('book.index');
        $response->assertViewHas('books');
    }
}
