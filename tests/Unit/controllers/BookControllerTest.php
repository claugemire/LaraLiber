<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\User;
use App\Models\Book;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    /**
     * Test Book Listing For Anonymous User.
     *
     * @return void
     */
    public function testBookListingDeniedForAnonymousUser()
    {
        // Arrange

        // Act
        $response = $this->get("/api/books/1/broken");

        // Assert
        $response->assertStatus(302);
    }

    /**
     * Test Book Listing For Logged In User.
     *
     * @return void
     */
    public function testBookListingForLoggedInUser()
    {
        // Arrange
        $user = User::factory()->create();
        $user_id = $user->id;
        $bookList = Book::factory()->count(3)->create(["user_id" => $user_id]);
        $bookIds = [];
        foreach ($bookList as $book) {
            array_push($bookIds, $book->id);
        }

        // Act
        $response = $this->actingAs($user)->get("/api/books/$user_id/$user->api_secret");

        // clean up
        Book::destroy($bookIds);
        User::destroy($user_id);

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test Book Storage For Anonymous User.
     *
     * @return void
     */
    public function testBookStorageDeniedForAnonymousUser()
    {
        // Arrange

        // Act
        $response = $this->post("/api/books/store");

        // Assert
        $response->assertStatus(302);
    }

    /**
     * Test Book Storage For Logged In User.
     *
     * @return void
     */
    public function testBookStorageForLoggedInUser()
    {
        // Arrange
        $user = User::factory()->create();
        $user_id = $user->id;
        $book = Book::factory()->make(["user_id" => $user_id]);
        $params = [
            "user_id" => $book->user_id,
            "api_secret" => 'secret',
            "title" => $book->title,
            "author" => $book->author,
            "thumbnail" => $book->thumbnail,
            "isbn" => $book->isbn,
            "description" => $book->description,
            "user_id" => $book->user_id,
            "page_count" => $book->page_count
        ];

        // Act
        $response = $this->post("/api/books/store", $params);

        // clean up
        Book::destroy($book->id);
        User::destroy($user_id);

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test Book Read Status Update For Loged In User.
     *
     * @return void
     */
    public function testBookReadStatusUpdateForLogedInUser()
    {
        // Arrange
        $params = [
            "user_id" => 2,
            "api_secret" => "secret",
            "read" => 1
        ];

        // Act
        $response = $this->patch("api/books/status/20", $params);

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test Book Read All Order Updates For Loged In User.
     *
     * @return void
     */
    public function testBookReadAllOrderUpdatesForLogedInUser()
    {
        // Arrange
        $params = [
            2,
            3,
            4
        ];

        // Act
        $response = $this->patch("api/books/orders", $params);

        // Assert
        $response->assertStatus(200);
    }

    /**
     * Test Book Deletion For Anonymous User.
     *
     * @return void
     */
    public function testBookDeletionForAnonymousUser()
    {
        // Arrange
        $params = [
            "user_id" => 2,
            "api_secret" => "secret"
        ];
        $book = Book::factory()->create(["user_id" => 2]);

        // Act
        $response = $this->delete("/api/books/$book->id", $params);

        // Assert
        $response->assertStatus(200);
    }


}
