@extends('admin.layout')

@section('admin_breadcrumb')
  <li class="active">Kelola Komentar</li>
@endsection

@section('admin_content')
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 20px;">
  <div class="panel-body" style="padding: 20px;">
    <h3 style="margin: 0 0 5px; font-weight: bold; color: #212121;">
      <i class="fa fa-comments" style="color: #FF8D1B;"></i> Kelola Komentar & Ulasan
    </h3>
    <p style="color: #777; margin: 0;">Total terdapat <strong>{{ $comments->total() }}</strong> ulasan dari pengguna. Admin dapat memantau dan menghapus ulasan yang tidak pantas.</p>
  </div>
</div>

<!-- Search Bar -->
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 20px;">
  <div class="panel-body" style="padding: 15px 20px;">
    <form action="{{ route('admin.comments.index') }}" method="GET" class="form-inline">
      <div class="input-group">
        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari isi komentar, nama pengguna, atau judul film..." style="min-width: 350px;">
        <span class="input-group-btn">
          <button class="btn btn-default" type="submit"><i class="fa fa-search"></i> Cari</button>
        </span>
      </div>
      @if(!empty($search))
        <a href="{{ route('admin.comments.index') }}" class="btn btn-default" style="margin-left: 5px;">
          <i class="fa fa-refresh"></i> Reset
        </a>
      @endif
    </form>
  </div>
</div>

<!-- Table Card -->
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08);">
  <div class="panel-heading" style="background: #212121; color: #fff; font-weight: bold;">
    <i class="fa fa-list"></i> Daftar Seluruh Komentar
  </div>
  <div class="panel-body" style="padding: 0;">
    <div class="table-responsive">
      <table class="table table-hover table-striped" style="margin-bottom: 0;">
        <thead>
          <tr>
            <th style="width: 50px;" class="text-center">#</th>
            <th style="width: 18%;">Pengguna</th>
            <th style="width: 22%;">Film</th>
            <th>Isi Komentar</th>
            <th style="width: 15%;">Waktu</th>
            <th style="width: 70px;" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($comments as $index => $c)
            <tr>
              <td class="text-center" style="vertical-align: middle;">
                {{ $comments->firstItem() + $index }}
              </td>
              <td style="vertical-align: middle;">
                <strong>{{ $c->user?->name ?? 'Pengguna tidak dikenal' }}</strong>
              </td>
              <td style="vertical-align: middle;">
                @if($c->film)
                  <a href="{{ route('admin.films.show', $c->film->id) }}" style="color: #FF8D1B; font-weight: 500;">
                    {{ $c->film->title }}
                  </a>
                @else
                  <span class="text-muted">Film tidak ditemukan</span>
                @endif
              </td>
              <td style="vertical-align: middle;">
                {{ $c->comment }}
              </td>
              <td style="vertical-align: middle;">
                <small class="text-muted"><i class="fa fa-clock-o"></i> {{ $c->created_at ? $c->created_at->diffForHumans() : '-' }}</small>
              </td>
              <td class="text-center" style="vertical-align: middle;">
                <form action="{{ route('admin.comments.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-xs btn-danger" title="Hapus Komentar">
                    <i class="fa fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted" style="padding: 30px;">
                <i class="fa fa-comments fa-3x" style="color: #ccc; margin-bottom: 10px; display: block;"></i>
                Belum ada komentar ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($comments->hasPages())
    <div class="panel-footer" style="background: #fff; text-align: center;">
      {{ $comments->links() }}
    </div>
  @endif
</div>
@endsection
