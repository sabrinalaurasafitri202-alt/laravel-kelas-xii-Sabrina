<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Film;
use App\Models\Genre;
use App\Models\Cast;
use App\Models\Peran;

class CastFilmTest extends TestCase
{
    protected $admin;
    protected $genre;

    protected function setUp(): void
    {
        parent::setUp();

        $this->genre = Genre::firstOrCreate(['name' => 'Action']);
        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@example.com'],
            [
                'name'     => 'admin_test',
                'password' => bcrypt('password123'),
                'role'     => 'admin',
                'role_id'  => 2,
            ]
        );
    }

    public function test_movie_detail_shows_cast_name_and_role(): void
    {
        $film = Film::create([
            'title'    => 'Test Cast Film',
            'genre_id' => $this->genre->id,
            'year'     => 2026,
            'sinopsis' => 'Film test untuk cast',
            'poster'   => 'template/images/m1.jpg',
        ]);

        $cast = Cast::firstOrCreate(
            ['name' => 'Robert Downey Jr.'],
            ['age' => 59, 'bio' => 'Actor']
        );

        Peran::create([
            'film_id' => $film->id,
            'cast_id' => $cast->id,
            'actor'   => 'Tony Stark',
        ]);

        $response = $this->get(route('movies.show', $film->id));

        $response->assertStatus(200);
        $response->assertSee('Robert Downey Jr.');
        $response->assertSee('Tony Stark');
    }

    public function test_movie_detail_shows_fallback_when_no_cast(): void
    {
        $film = Film::create([
            'title'    => 'Film Tanpa Cast',
            'genre_id' => $this->genre->id,
            'year'     => 2026,
            'sinopsis' => 'Film tanpa pemeran sama sekali',
            'poster'   => 'template/images/m1.jpg',
        ]);

        $response = $this->get(route('movies.show', $film->id));

        $response->assertStatus(200);
        $response->assertSee('Data cast belum tersedia');
    }

    public function test_admin_can_create_film_with_casts(): void
    {
        $cast1 = Cast::firstOrCreate(
            ['name' => 'Chris Evans'],
            ['age' => 43, 'bio' => 'Actor']
        );

        $response = $this->actingAs($this->admin)->post(route('admin.films.store'), [
            'title'    => 'Avengers Assemble',
            'genre_id' => $this->genre->id,
            'year'     => 2026,
            'sinopsis' => 'Pahlawan super berkumpul.',
            'casts'    => [
                [
                    'cast_id' => $cast1->id,
                    'actor'   => 'Captain America',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.films.index'));

        $this->assertDatabaseHas('films', ['title' => 'Avengers Assemble']);
        $this->assertDatabaseHas('perans', [
            'cast_id' => $cast1->id,
            'actor'   => 'Captain America',
        ]);
    }

    public function test_admin_can_update_film_casts(): void
    {
        $film = Film::create([
            'title'    => 'Film Update Cast',
            'genre_id' => $this->genre->id,
            'year'     => 2025,
            'sinopsis' => 'Sinopsis sebelum update',
            'poster'   => 'template/images/m1.jpg',
        ]);

        $cast2 = Cast::firstOrCreate(
            ['name' => 'Scarlett Johansson'],
            ['age' => 40, 'bio' => 'Actress']
        );

        $response = $this->actingAs($this->admin)->put(route('admin.films.update', $film->id), [
            'title'    => 'Film Update Cast Selesai',
            'genre_id' => $this->genre->id,
            'year'     => 2025,
            'sinopsis' => 'Sinopsis sesudah update',
            'poster_url' => 'template/images/m1.jpg',
            'casts'    => [
                [
                    'cast_id' => $cast2->id,
                    'actor'   => 'Black Widow',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.films.index'));

        $this->assertDatabaseHas('perans', [
            'film_id' => $film->id,
            'cast_id' => $cast2->id,
            'actor'   => 'Black Widow',
        ]);
    }
}

