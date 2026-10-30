<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Kritik;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_films'   => Film::count(),
            'total_genres'  => Genre::count(),
            'total_users'   => User::count(),
            'total_kritiks' => Kritik::count(),
        ];

        $recentFilms   = Film::latest()->limit(5)->get();
        $recentUsers   = User::latest()->limit(5)->get();
        $recentKritiks = Kritik::with(['user:id,name', 'film:id,title'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentFilms', 'recentUsers', 'recentKritiks'));
    }

    /**
     * Display listing of comments with search and pagination.
     */
    public function comments(Request $request)
    {
        $search = $request->input('search');

        $query = Kritik::with(['user:id,name', 'film:id,title']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('film', function ($fq) use ($search) {
                      $fq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $comments = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.comments.index', compact('comments', 'search'));
    }

    /**
     * Delete a comment.
     */
    public function destroyComment(Kritik $comment)
    {
        $comment->delete();
        return back()->with('success', 'Komentar berhasil dihapus!');
    }

    /**
     * Display listing of users with search and pagination.
     */
    public function users(Request $request)
    {
        $search = $request->input('search');

        $query = User::withCount('kritiks');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Delete a user safely.
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            $user->kritiks()->delete();
            $user->delete();
        });

        return back()->with('success', 'Pengguna "' . $user->name . '" berhasil dihapus!');
    }
}
