<?php

namespace App\Http\Controllers;

use App\Models\Kritik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KritikController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:500'],
            'film_id' => ['required', 'integer', 'exists:films,id'],
        ], [
            'comment.required' => 'Komentar tidak boleh kosong.',
            'comment.max'      => 'Komentar maksimal 500 karakter.',
            'film_id.required' => 'Film tidak ditemukan.',
            'film_id.exists'   => 'Film tidak ditemukan.',
        ]);

        Kritik::create([
            'comment' => $validated['comment'],
            'rating'  => 0,
            'film_id' => $validated['film_id'],
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
