@extends('admin.layout')

@section('admin_breadcrumb')
  <li class="active">Kelola Pengguna</li>
@endsection

@section('admin_content')
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 20px;">
  <div class="panel-body" style="padding: 20px;">
    <h3 style="margin: 0 0 5px; font-weight: bold; color: #212121;">
      <i class="fa fa-users" style="color: #FF8D1B;"></i> Kelola Pengguna Terdaftar
    </h3>
    <p style="color: #777; margin: 0;">Total terdapat <strong>{{ $users->total() }}</strong> akun pengguna dalam sistem.</p>
  </div>
</div>

<!-- Search Bar -->
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 20px;">
  <div class="panel-body" style="padding: 15px 20px;">
    <form action="{{ route('admin.users.index') }}" method="GET" class="form-inline">
      <div class="input-group">
        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama, email, atau role..." style="min-width: 320px;">
        <span class="input-group-btn">
          <button class="btn btn-default" type="submit"><i class="fa fa-search"></i> Cari</button>
        </span>
      </div>
      @if(!empty($search))
        <a href="{{ route('admin.users.index') }}" class="btn btn-default" style="margin-left: 5px;">
          <i class="fa fa-refresh"></i> Reset
        </a>
      @endif
    </form>
  </div>
</div>

<!-- Table Card -->
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08);">
  <div class="panel-heading" style="background: #212121; color: #fff; font-weight: bold;">
    <i class="fa fa-list"></i> Daftar Akun Pengguna
  </div>
  <div class="panel-body" style="padding: 0;">
    <div class="table-responsive">
      <table class="table table-hover table-striped" style="margin-bottom: 0;">
        <thead>
          <tr>
            <th style="width: 50px;" class="text-center">#</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>Role</th>
            <th>Total Ulasan</th>
            <th>Tanggal Terdaftar</th>
            <th style="width: 70px;" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $index => $u)
            <tr>
              <td class="text-center" style="vertical-align: middle;">
                {{ $users->firstItem() + $index }}
              </td>
              <td style="vertical-align: middle;">
                <strong>{{ $u->name }}</strong>
                @if($u->id === Auth::id())
                  <span class="badge" style="background: #5cb85c; font-size: 10px;">Anda</span>
                @endif
              </td>
              <td style="vertical-align: middle;">
                {{ $u->email }}
              </td>
              <td style="vertical-align: middle;">
                <span class="label {{ $u->role === 'admin' ? 'label-warning' : 'label-primary' }}" style="font-size: 11px;">
                  {{ $u->role }}
                </span>
              </td>
              <td style="vertical-align: middle;">
                <span class="badge" style="background: #777;">{{ $u->kritiks_count }}</span>
              </td>
              <td style="vertical-align: middle;">
                <small class="text-muted">{{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}</small>
              </td>
              <td class="text-center" style="vertical-align: middle;">
                @if($u->id !== Auth::id())
                  <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna &quot;{{ addslashes($u->name) }}&quot;?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus Pengguna">
                      <i class="fa fa-trash"></i>
                    </button>
                  </form>
                @else
                  <span class="text-muted" title="Tidak dapat menghapus akun sendiri">-</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted" style="padding: 30px;">
                <i class="fa fa-users fa-3x" style="color: #ccc; margin-bottom: 10px; display: block;"></i>
                Belum ada pengguna ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($users->hasPages())
    <div class="panel-footer" style="background: #fff; text-align: center;">
      {{ $users->links() }}
    </div>
  @endif
</div>
@endsection
