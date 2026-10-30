<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Film;
use App\Models\Genre;
use App\Models\Kritik;

class CommentAuthTest extends TestCase
{
    protected $genre;
    protected $film;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->genre = Genre::firstOrCreate(['name' => 'Drama']);
        $this->film = Film::firstOrCreate(
            ['title' => 'Film Uji Komentar'],
            [
                'genre_id' => $this->genre->id,
                'year'     => 2026,
                'sinopsis' => 'Sinopsis uji komentar',
                'poster'   => 'template/images/m1.jpg',
            ]
        );

        $this->user = User::firstOrCreate(
            ['email' => 'user_komentar@example.com'],
            [
                'name'     => 'user_komentar',
                'password' => bcrypt('password123'),
                'role'     => 'user',
                'role_id'  => 1,
            ]
        );
    }

    public function test_guest_cannot_post_comment_directly(): void
    {
        $response = $this->post(route('comments.store'), [
            'film_id' => $this->film->id,
            'comment' => 'Komentar dari guest ilegal',
        ]);

        // Unauthenticated request must be redirected away from comment store
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('kritiks', [
            'comment' => 'Komentar dari guest ilegal',
        ]);
    }

    public function test_guest_sees_login_prompt_on_film_page(): void
    {
        $response = $this->get(route('movies.show', $this->film->id));

        $response->assertStatus(200);
        $response->assertSee('Silakan login terlebih dahulu untuk berkomentar.');
        $response->assertSee('Login untuk berkomentar');
        $response->assertDontSee('<textarea name="comment"', false);
    }

    public function test_authenticated_user_sees_comment_form_and_can_comment(): void
    {
        $response = $this->actingAs($this->user)->get(route('movies.show', $this->film->id));

        $response->assertStatus(200);
        $response->assertSee('name="comment"', false);

        // Submit comment
        $commentText = 'Komentar uji valid dari user login ' . time();
        $postResponse = $this->actingAs($this->user)->post(route('comments.store'), [
            'film_id' => $this->film->id,
            'comment' => $commentText,
        ]);

        $postResponse->assertSessionHas('success');
        $this->assertDatabaseHas('kritiks', [
            'film_id' => $this->film->id,
            'user_id' => $this->user->id,
            'comment' => $commentText,
        ]);
    }

    public function test_comment_validation_empty_and_max_length(): void
    {
        // Test empty comment
        $responseEmpty = $this->actingAs($this->user)->post(route('comments.store'), [
            'film_id' => $this->film->id,
            'comment' => '',
        ]);
        $responseEmpty->assertSessionHasErrors(['comment']);

        // Test over 500 characters
        $responseTooLong = $this->actingAs($this->user)->post(route('comments.store'), [
            'film_id' => $this->film->id,
            'comment' => str_repeat('a', 501),
        ]);
        $responseTooLong->assertSessionHasErrors(['comment']);
    }
}

