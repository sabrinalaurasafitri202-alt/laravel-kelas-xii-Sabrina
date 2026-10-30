<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Cast;
use App\Models\Peran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminFilmController extends Controller
{
    /**
     * Display a listing of films with search and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $genreFilter = $request->input('genre_id');

        $query = Film::with(['genre:id,name', 'kritiks:id,film_id']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%")
                  ->orWhere('sinopsis', 'like', "%{$search}%");
            });
        }

        if (!empty($genreFilter)) {
            $query->where('genre_id', $genreFilter);
        }

        $films = $query->latest('id')->paginate(10)->withQueryString();
        $genres = Genre::orderBy('name')->get();

        return view('admin.films.index', compact('films', 'genres', 'search', 'genreFilter'));
    }

    /**
     * Show the form for creating a new film.
     */
    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        $casts  = Cast::orderBy('name')->get();
        return view('admin.films.create', compact('genres', 'casts'));
    }

    /**
     * Store a newly created film in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'genre_id'    => ['required', 'integer', 'exists:genres,id'],
            'year'        => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 5)],
            'sinopsis'    => ['required', 'string'],
            'poster_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'poster_url'  => ['nullable', 'string', 'max:500'],
            'casts'       => ['nullable', 'array'],
            'casts.*.cast_id' => ['nullable', 'integer', 'exists:casts,id'],
            'casts.*.actor'   => ['nullable', 'string', 'max:255'],
        ], [
            'title.required'    => 'Judul film wajib diisi.',
            'title.max'         => 'Judul film maksimal 255 karakter.',
            'genre_id.required' => 'Pilih genre film.',
            'genre_id.exists'   => 'Genre yang dipilih tidak valid.',
            'year.required'     => 'Tahun rilis film wajib diisi.',
            'year.integer'      => 'Tahun harus berupa angka.',
            'year.min'          => 'Tahun rilis minimal 1900.',
            'year.max'          => 'Tahun rilis maksimal ' . (date('Y') + 5) . '.',
            'sinopsis.required' => 'Sinopsis film wajib diisi.',
            'poster_file.image' => 'File poster harus berupa gambar (JPG, PNG, WebP).',
            'poster_file.max'   => 'Ukuran file poster maksimal 2MB.',
        ]);

        $posterPath = 'template/images/m1.jpg'; // default poster

        if ($request->hasFile('poster_file')) {
            $file = $request->file('poster_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/posters');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $file->move($destinationPath, $filename);
            $posterPath = 'storage/posters/' . $filename;
        } elseif (!empty($validated['poster_url'])) {
            $posterPath = ltrim(trim($validated['poster_url']), '/');
        }

        $film = Film::create([
            'title'    => $validated['title'],
            'genre_id' => $validated['genre_id'],
            'year'     => $validated['year'],
            'sinopsis' => $validated['sinopsis'],
            'poster'   => $posterPath,
        ]);

        if ($request->has('casts') && is_array($request->input('casts'))) {
            foreach ($request->input('casts') as $c) {
                if (!empty($c['cast_id']) && !empty($c['actor'])) {
                    $film->perans()->create([
                        'cast_id' => $c['cast_id'],
                        'actor'   => trim($c['actor']),
                    ]);
                }
            }
        }

        return redirect()->route('admin.films.index')
            ->with('success', 'Film "' . $film->title . '" berhasil ditambahkan!');
    }

    /**
     * Display the specified film details.
     */
    public function show(Film $film)
    {
        $film->load([
            'genre',
            'kritiks.user:id,name',
            'perans.cast',
        ]);

        return view('admin.films.show', compact('film'));
    }

    /**
     * Show the form for editing the specified film.
     */
    public function edit(Film $film)
    {
        $film->load('perans.cast');
        $genres = Genre::orderBy('name')->get();
        $casts  = Cast::orderBy('name')->get();
        return view('admin.films.edit', compact('film', 'genres', 'casts'));
    }

    /**
     * Update the specified film in storage.
     */
    public function update(Request $request, Film $film)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'genre_id'    => ['required', 'integer', 'exists:genres,id'],
            'year'        => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 5)],
            'sinopsis'    => ['required', 'string'],
            'poster_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'poster_url'  => ['nullable', 'string', 'max:500'],
            'casts'       => ['nullable', 'array'],
            'casts.*.cast_id' => ['nullable', 'integer', 'exists:casts,id'],
            'casts.*.actor'   => ['nullable', 'string', 'max:255'],
        ], [
            'title.required'    => 'Judul film wajib diisi.',
            'title.max'         => 'Judul film maksimal 255 karakter.',
            'genre_id.required' => 'Pilih genre film.',
            'genre_id.exists'   => 'Genre yang dipilih tidak valid.',
            'year.required'     => 'Tahun rilis film wajib diisi.',
            'year.integer'      => 'Tahun harus berupa angka.',
            'year.min'          => 'Tahun rilis minimal 1900.',
            'year.max'          => 'Tahun rilis maksimal ' . (date('Y') + 5) . '.',
            'sinopsis.required' => 'Sinopsis film wajib diisi.',
            'poster_file.image' => 'File poster harus berupa gambar (JPG, PNG, WebP).',
            'poster_file.max'   => 'Ukuran file poster maksimal 2MB.',
        ]);

        $posterPath = $film->poster;

        if ($request->hasFile('poster_file')) {
            $file = $request->file('poster_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('storage/posters');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $file->move($destinationPath, $filename);
            $posterPath = 'storage/posters/' . $filename;
        } elseif (!empty($validated['poster_url'])) {
            $posterPath = ltrim(trim($validated['poster_url']), '/');
        }

        $film->update([
            'title'    => $validated['title'],
            'genre_id' => $validated['genre_id'],
            'year'     => $validated['year'],
            'sinopsis' => $validated['sinopsis'],
            'poster'   => $posterPath,
        ]);

        // Sync perans (casts)
        $film->perans()->delete();
        if ($request->has('casts') && is_array($request->input('casts'))) {
            foreach ($request->input('casts') as $c) {
                if (!empty($c['cast_id']) && !empty($c['actor'])) {
                    $film->perans()->create([
                        'cast_id' => $c['cast_id'],
                        'actor'   => trim($c['actor']),
                    ]);
                }
            }
        }

        return redirect()->route('admin.films.index')
            ->with('success', 'Film "' . $film->title . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified film from storage with related records safely removed.
     */
    public function destroy(Film $film)
    {
        $title = $film->title;

        DB::transaction(function () use ($film) {
            $film->kritiks()->delete();
            $film->perans()->delete();
            $film->delete();
        });

        return redirect()->route('admin.films.index')
            ->with('success', 'Film "' . $title . '" beserta ulasannya berhasil dihapus!');
    }
}
