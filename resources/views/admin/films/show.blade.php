@extends('admin.layout')

@section('admin_breadcrumb')
  <li><a href="{{ route('admin.films.index') }}">Kelola Film</a></li>
  <li class="active">{{ $film->title }}</li>
@endsection

@section('admin_content')
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 20px;">
  <div class="panel-body" style="padding: 20px;">
    <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
      <div class="col-sm-8">
        <h3 style="margin: 0 0 5px; font-weight: bold; color: #212121;">
          <i class="fa fa-film" style="color: #FF8D1B;"></i> Detail Film: {{ $film->title }}
        </h3>
        <p style="color: #777; margin: 0;">ID Film: <strong>#{{ $film->id }}</strong> &bull; Genre: <strong>{{ $film->genre ? $film->genre->name : 'N/A' }}</strong> &bull; Tahun: <strong>{{ $film->year }}</strong></p>
      </div>
      <div class="col-sm-4 text-right" style="margin-top: 10px;">
        <a href="{{ route('movies.show', $film->id) }}" class="btn btn-default" target="_blank" style="margin-right: 5px;">
          <i class="fa fa-external-link"></i> Lihat di Web
        </a>
        <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-warning" style="background: #FF8D1B; border-color: #FF8D1B; margin-right: 5px;">
          <i class="fa fa-pencil"></i> Edit Film
        </a>
        <a href="{{ route('admin.films.index') }}" class="btn btn-default">
          <i class="fa fa-arrow-left"></i> Kembali
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Poster Column -->
  <div class="col-md-4" style="margin-bottom: 20px;">
    <div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); text-align: center; padding: 20px;">
      <img src="/{{ $film->poster }}" alt="{{ $film->title }}" style="width: 100%; max-width: 250px; border-radius: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.25);" onerror="this.src='/template/images/m1.jpg'">
      <h4 style="margin: 15px 0 5px; font-weight: bold;">{{ $film->title }}</h4>
      <p style="color: #777; margin: 0;"><i class="fa fa-calendar"></i> Tahun {{ $film->year }} &bull; {{ $film->genre ? $film->genre->name : 'N/A' }}</p>

      <hr style="margin: 15px 0;">

      <form action="{{ route('admin.films.destroy', $film->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus film &quot;{{ addslashes($film->title) }}&quot;?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-block">
          <i class="fa fa-trash"></i> Hapus Film Ini
        </button>
      </form>
    </div>
  </div>

  <!-- Details Column -->
  <div class="col-md-8" style="margin-bottom: 20px;">
    <div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 20px;">
      <div class="panel-heading" style="background: #212121; color: #fff; font-weight: bold;">
        <i class="fa fa-info-circle"></i> Ringkasan & Sinopsis
      </div>
      <div class="panel-body">
        <h4 style="margin-top: 0; font-weight: bold;">Sinopsis</h4>
        <p style="color: #555; line-height: 1.8; text-align: justify;">{{ $film->sinopsis }}</p>

        <hr style="margin: 15px 0;">

        <div class="row">
          <div class="col-sm-6">
            <strong>Dibuat Pada:</strong><br>
            <span class="text-muted">{{ $film->created_at ? $film->created_at->format('d M Y, H:i') : '-' }}</span>
          </div>
          <div class="col-sm-6">
            <strong>Terakhir Diperbarui:</strong><br>
            <span class="text-muted">{{ $film->updated_at ? $film->updated_at->format('d M Y, H:i') : '-' }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Casts on this film -->
    <div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 20px;">
      <div class="panel-heading" style="background: #212121; color: #fff; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
        <span><i class="fa fa-users"></i> Cast / Pemeran Film ({{ $film->perans->count() }})</span>
        <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-xs btn-warning" style="background: #FF8D1B; border-color: #FF8D1B;">
          <i class="fa fa-pencil"></i> Kelola Cast
        </a>
      </div>
      <div class="panel-body" style="padding: 0;">
        <table class="table table-hover table-striped" style="margin-bottom: 0;">
          <thead>
            <tr>
              <th style="width: 45%;">Nama Cast / Aktor</th>
              <th>Nama Peran / Karakter</th>
            </tr>
          </thead>
          <tbody>
            @forelse($film->perans as $p)
              <tr>
                <td><strong>{{ $p->cast?->name ?? 'Cast tidak diketahui' }}</strong></td>
                <td>{{ $p->actor }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="2" class="text-center text-muted" style="padding: 20px;">
                  Data cast belum tersedia untuk film ini.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Comments on this film -->
    <div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08);">
      <div class="panel-heading" style="background: #212121; color: #fff; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
        <span><i class="fa fa-comments"></i> Ulasan Pada Film Ini ({{ $film->kritiks->count() }})</span>
      </div>
      <div class="panel-body" style="padding: 0;">
        <table class="table table-hover table-striped" style="margin-bottom: 0;">
          <thead>
            <tr>
              <th style="width: 25%;">Pengguna</th>
              <th>Komentar</th>
              <th style="width: 20%;">Waktu</th>
              <th style="width: 60px;" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($film->kritiks as $k)
              <tr>
                <td><strong>{{ $k->user?->name ?? 'Pengguna tidak dikenal' }}</strong></td>
                <td>{{ $k->comment }}</td>
                <td><small class="text-muted">{{ $k->created_at ? $k->created_at->diffForHumans() : '-' }}</small></td>
                <td class="text-center">
                  <form action="{{ route('admin.comments.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted" style="padding: 20px;">
                  Belum ada ulasan untuk film ini.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
