@extends('admin.layout')

@section('admin_breadcrumb')
  <li class="active">Dashboard</li>
@endsection

@section('admin_content')
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 25px;">
  <div class="panel-body" style="padding: 20px;">
    <h3 style="margin: 0 0 5px; font-weight: bold; color: #212121;">
      <i class="fa fa-dashboard" style="color: #FF8D1B;"></i> Admin Dashboard
    </h3>
    <p style="color: #777; margin: 0;">Selamat datang di panel kendali sistem OneMovies. Kelola data film, ulasan, dan pengguna dengan mudah.</p>
  </div>
</div>

<!-- Stat Cards -->
<div class="row" style="margin-bottom: 20px;">
  <div class="col-md-3 col-sm-6" style="margin-bottom: 15px;">
    <div class="panel panel-default text-center" style="border-top: 4px solid #FF8D1B; box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 0;">
      <div class="panel-body">
        <i class="fa fa-film fa-2x" style="color: #FF8D1B; margin-bottom: 8px;"></i>
        <h3 style="margin: 5px 0; font-size: 26px; font-weight: bold;">{{ $stats['total_films'] }}</h3>
        <p style="color: #777; margin: 0; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Total Film</p>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6" style="margin-bottom: 15px;">
    <div class="panel panel-default text-center" style="border-top: 4px solid #00bcd4; box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 0;">
      <div class="panel-body">
        <i class="fa fa-th-list fa-2x" style="color: #00bcd4; margin-bottom: 8px;"></i>
        <h3 style="margin: 5px 0; font-size: 26px; font-weight: bold;">{{ $stats['total_genres'] }}</h3>
        <p style="color: #777; margin: 0; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Total Genre</p>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6" style="margin-bottom: 15px;">
    <div class="panel panel-default text-center" style="border-top: 4px solid #4caf50; box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 0;">
      <div class="panel-body">
        <i class="fa fa-users fa-2x" style="color: #4caf50; margin-bottom: 8px;"></i>
        <h3 style="margin: 5px 0; font-size: 26px; font-weight: bold;">{{ $stats['total_users'] }}</h3>
        <p style="color: #777; margin: 0; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Total Pengguna</p>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6" style="margin-bottom: 15px;">
    <div class="panel panel-default text-center" style="border-top: 4px solid #e91e63; box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 0;">
      <div class="panel-body">
        <i class="fa fa-comments fa-2x" style="color: #e91e63; margin-bottom: 8px;"></i>
        <h3 style="margin: 5px 0; font-size: 26px; font-weight: bold;">{{ $stats['total_kritiks'] }}</h3>
        <p style="color: #777; margin: 0; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Total Komentar</p>
      </div>
    </div>
  </div>
</div>

<!-- Quick Action Buttons -->
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 25px;">
  <div class="panel-body" style="padding: 15px 20px;">
    <strong style="margin-right: 15px; color: #555;"><i class="fa fa-bolt" style="color: #FF8D1B;"></i> Akses Cepat:</strong>
    <a href="{{ route('admin.films.create') }}" class="btn btn-warning btn-sm" style="background: #FF8D1B; border-color: #FF8D1B; margin-right: 8px;">
      <i class="fa fa-plus-circle"></i> Tambah Film Baru
    </a>
    <a href="{{ route('admin.films.index') }}" class="btn btn-default btn-sm" style="margin-right: 8px;">
      <i class="fa fa-film"></i> Kelola Semua Film
    </a>
    <a href="{{ route('admin.comments.index') }}" class="btn btn-default btn-sm" style="margin-right: 8px;">
      <i class="fa fa-comments"></i> Kelola Komentar
    </a>
    <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-sm">
      <i class="fa fa-users"></i> Kelola Pengguna
    </a>
  </div>
</div>

<!-- Two Tables Section (Recent Films & Recent Users) -->
<div class="row">
  <div class="col-md-6" style="margin-bottom: 20px;">
    <div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 0;">
      <div class="panel-heading" style="background: #212121; color: #fff; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
        <span><i class="fa fa-film"></i> Film Terbaru</span>
        <a href="{{ route('admin.films.index') }}" style="color: #FF8D1B; font-size: 12px;">Lihat Semua &rarr;</a>
      </div>
      <div class="panel-body" style="padding: 0;">
        <table class="table table-hover table-striped" style="margin-bottom: 0;">
          <thead>
            <tr>
              <th>Judul Film</th>
              <th>Tahun</th>
              <th style="width: 80px;" class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentFilms as $film)
              <tr>
                <td><strong>{{ $film->title }}</strong></td>
                <td>{{ $film->year }}</td>
                <td class="text-right">
                  <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-xs btn-default" title="Edit"><i class="fa fa-pencil"></i></a>
                  <a href="{{ route('admin.films.show', $film->id) }}" class="btn btn-xs btn-info" title="Lihat"><i class="fa fa-eye"></i></a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted">Belum ada data film</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6" style="margin-bottom: 20px;">
    <div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 0;">
      <div class="panel-heading" style="background: #212121; color: #fff; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
        <span><i class="fa fa-users"></i> Pengguna Terbaru</span>
        <a href="{{ route('admin.users.index') }}" style="color: #FF8D1B; font-size: 12px;">Lihat Semua &rarr;</a>
      </div>
      <div class="panel-body" style="padding: 0;">
        <table class="table table-hover table-striped" style="margin-bottom: 0;">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Role</th>
              <th>Terdaftar</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentUsers as $u)
              <tr>
                <td><strong>{{ $u->name }}</strong><br><small class="text-muted">{{ $u->email }}</small></td>
                <td>
                  <span class="label {{ $u->role === 'admin' ? 'label-warning' : 'label-primary' }}">
                    {{ $u->role }}
                  </span>
                </td>
                <td><small class="text-muted">{{ $u->created_at ? $u->created_at->diffForHumans() : '-' }}</small></td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted">Belum ada user</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Recent Comments Section -->
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-top: 10px;">
  <div class="panel-heading" style="background: #212121; color: #fff; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
    <span><i class="fa fa-comments"></i> Komentar / Ulasan Terbaru</span>
    <a href="{{ route('admin.comments.index') }}" style="color: #FF8D1B; font-size: 12px;">Lihat Semua &rarr;</a>
  </div>
  <div class="panel-body" style="padding: 0;">
    <div class="table-responsive">
      <table class="table table-hover table-striped" style="margin-bottom: 0;">
        <thead>
          <tr>
            <th style="width: 20%;">Pengguna</th>
            <th style="width: 22%;">Film</th>
            <th>Komentar</th>
            <th style="width: 15%;">Waktu</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentKritiks as $k)
            <tr>
              <td>
                <strong>{{ $k->user?->name ?? 'Pengguna tidak dikenal' }}</strong>
              </td>
              <td>
                @if ($k->film)
                  <a href="{{ route('movies.show', $k->film->id) }}" target="_blank" style="color: #FF8D1B; font-weight: 500;">
                    {{ $k->film->title }}
                  </a>
                @else
                  <span class="text-muted">Film tidak ditemukan</span>
                @endif
              </td>
              <td>{{ \Illuminate\Support\Str::limit($k->comment, 80) }}</td>
              <td>
                @if ($k->created_at)
                  <small class="text-muted"><i class="fa fa-clock-o"></i> {{ $k->created_at->diffForHumans() }}</small>
                @else
                  -
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted">Belum ada komentar</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
