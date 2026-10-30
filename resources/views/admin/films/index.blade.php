@extends('admin.layout')

@section('admin_breadcrumb')
  <li class="active">Kelola Film</li>
@endsection

@section('admin_content')
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 20px;">
  <div class="panel-body" style="padding: 20px;">
    <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
      <div class="col-sm-6">
        <h3 style="margin: 0 0 5px; font-weight: bold; color: #212121;">
          <i class="fa fa-film" style="color: #FF8D1B;"></i> Kelola Data Film
        </h3>
        <p style="color: #777; margin: 0;">Total terdapat <strong>{{ $films->total() }}</strong> film terdaftar dalam database.</p>
      </div>
      <div class="col-sm-6 text-right" style="margin-top: 10px;">
        <a href="{{ route('admin.films.create') }}" class="btn btn-warning" style="background: #FF8D1B; border-color: #FF8D1B; font-weight: bold;">
          <i class="fa fa-plus-circle"></i> Tambah Film Baru
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Search & Filter Card -->
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 20px;">
  <div class="panel-body" style="padding: 15px 20px;">
    <form action="{{ route('admin.films.index') }}" method="GET" class="form-inline">
      <div class="form-group" style="margin-right: 10px; margin-bottom: 5px;">
        <div class="input-group">
          <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari judul, tahun, sinopsis..." style="min-width: 260px;">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
          </span>
        </div>
      </div>

      <div class="form-group" style="margin-right: 10px; margin-bottom: 5px;">
        <select name="genre_id" class="form-control" onchange="this.form.submit();">
          <option value="">-- Semua Genre --</option>
          @foreach($genres as $g)
            <option value="{{ $g->id }}" {{ (string)$genreFilter === (string)$g->id ? 'selected' : '' }}>
              {{ $g->name }}
            </option>
          @endforeach
        </select>
      </div>

      <button type="submit" class="btn btn-primary" style="background: #212121; border-color: #212121; margin-bottom: 5px;">
        <i class="fa fa-filter"></i> Filter
      </button>

      @if(!empty($search) || !empty($genreFilter))
        <a href="{{ route('admin.films.index') }}" class="btn btn-default" style="margin-bottom: 5px;">
          <i class="fa fa-refresh"></i> Reset
        </a>
      @endif
    </form>
  </div>
</div>

<!-- Film Table Card -->
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08);">
  <div class="panel-heading" style="background: #212121; color: #fff; font-weight: bold;">
    <i class="fa fa-list"></i> Daftar Film
  </div>
  <div class="panel-body" style="padding: 0;">
    <div class="table-responsive">
      <table class="table table-hover table-striped" style="margin-bottom: 0;">
        <thead>
          <tr>
            <th style="width: 50px;" class="text-center">#</th>
            <th style="width: 70px;">Poster</th>
            <th>Judul Film</th>
            <th>Genre</th>
            <th>Tahun</th>
            <th>Ulasan</th>
            <th style="width: 170px;" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($films as $index => $film)
            <tr>
              <td class="text-center" style="vertical-align: middle;">
                {{ $films->firstItem() + $index }}
              </td>
              <td style="vertical-align: middle;">
                <img src="/{{ $film->poster }}" alt="{{ $film->title }}" style="width: 45px; height: 60px; object-fit: cover; border-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" onerror="this.src='/template/images/m1.jpg'">
              </td>
              <td style="vertical-align: middle;">
                <strong><a href="{{ route('admin.films.show', $film->id) }}" style="color: #212121;">{{ $film->title }}</a></strong>
                <br>
                <small class="text-muted">{{ \Illuminate\Support\Str::limit($film->sinopsis, 60) }}</small>
              </td>
              <td style="vertical-align: middle;">
                <span class="label label-info" style="font-size: 11px;">
                  {{ $film->genre ? $film->genre->name : 'N/A' }}
                </span>
              </td>
              <td style="vertical-align: middle;">
                <strong>{{ $film->year }}</strong>
              </td>
              <td style="vertical-align: middle;">
                <span class="badge" style="background: #777;">{{ $film->kritiks->count() }}</span>
              </td>
              <td class="text-center" style="vertical-align: middle;">
                <div class="btn-group" role="group">
                  <a href="{{ route('movies.show', $film->id) }}" class="btn btn-xs btn-default" target="_blank" title="Lihat di Web">
                    <i class="fa fa-external-link"></i>
                  </a>
                  <a href="{{ route('admin.films.show', $film->id) }}" class="btn btn-xs btn-info" title="Detail Film">
                    <i class="fa fa-eye"></i>
                  </a>
                  <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-xs btn-warning" style="background: #FF8D1B; border-color: #FF8D1B;" title="Edit Film">
                    <i class="fa fa-pencil"></i>
                  </a>
                  <form action="{{ route('admin.films.destroy', $film->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus film &quot;{{ addslashes($film->title) }}&quot;? Semua ulasan terkait juga akan terhapus.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus Film">
                      <i class="fa fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted" style="padding: 30px;">
                <i class="fa fa-film fa-3x" style="color: #ccc; margin-bottom: 10px; display: block;"></i>
                @if(!empty($search) || !empty($genreFilter))
                  Tidak ditemukan film dengan kata kunci pencarian tersebut.
                  <br>
                  <a href="{{ route('admin.films.index') }}" class="btn btn-default btn-xs" style="margin-top: 10px;">Lihat Semua Film</a>
                @else
                  Belum ada data film dalam database.
                @endif
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($films->hasPages())
    <div class="panel-footer" style="background: #fff; text-align: center;">
      {{ $films->links() }}
    </div>
  @endif
</div>
@endsection
