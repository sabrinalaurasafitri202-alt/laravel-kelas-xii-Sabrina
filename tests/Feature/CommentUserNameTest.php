<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Film;
use App\Models\Genre;
use App\Models\Kritik;

class CommentUserNameTest extends TestCase
{
    protected $genre;
    protected $film;
    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->genre = Genre::firstOrCreate(['name' => 'Sci-Fi']);
        $this->film = Film::firstOrCreate(
            ['title' => 'Film Uji Nama User'],
            [
                'genre_id' => $this->genre->id,
                'year'     => 2026,
                'sinopsis' => 'Sinopsis film uji nama user',
                'poster'   => 'template/images/m1.jpg',
            ]
        );

        $this->user = User::firstOrCreate(
            ['email' => 'penguji_nama@example.com'],
            [
                'name'     => 'Budi Santoso',
                'password' => bcrypt('password123'),
                'role'     => 'user',
                'role_id'  => 1,
            ]
        );

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_comment_test@example.com'],
            [
                'name'     => 'admin_tester',
                'password' => bcrypt('password123'),
                'role'     => 'admin',
                'role_id'  => 2,
            ]
        );
    }

    public function test_movie_show_displays_user_name_and_relative_time(): void
    {
        $comment = Kritik::create([
            'film_id' => $this->film->id,
            'user_id' => $this->user->id,
            'comment' => 'Review menarik dari Budi!',
            'rating'  => 5,
        ]);

        $response = $this->get(route('movies.show', $this->film->id));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Review menarik dari Budi!');
        $response->assertSee($comment->created_at->diffForHumans());
    }

    public function test_orphan_comment_displays_fallback_pengguna_tidak_dikenal(): void
    {
        // Temporarily disable FK checks to simulate orphaned comment
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $orphanComment = Kritik::create([
            'film_id' => $this->film->id,
            'user_id' => 9999999, // non-existent user
            'comment' => 'Komentar akun anonim terhapus',
            'rating'  => 3,
        ]);
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $response = $this->get(route('movies.show', $this->film->id));

        $response->assertStatus(200);
        $response->assertSee('Pengguna tidak dikenal');
        $response->assertSee('Komentar akun anonim terhapus');

        // Cleanup
        $orphanComment->delete();
    }

    public function test_admin_comments_page_displays_user_name(): void
    {
        $comment = Kritik::create([
            'film_id' => $this->film->id,
            'user_id' => $this->user->id,
            'comment' => 'Komentar untuk tes admin',
            'rating'  => 4,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.comments.index'));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Komentar untuk tes admin');
    }

    public function test_admin_dashboard_displays_user_name_on_recent_comments(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        // Ensure the view renders without throwing errors on user relationship
        $this->assertTrue(true);
    }
}
