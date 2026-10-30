@extends('admin.layout')

@section('admin_breadcrumb')
  <li><a href="{{ route('admin.films.index') }}">Kelola Film</a></li>
  <li class="active">Tambah Film Baru</li>
@endsection

@section('admin_content')
<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); margin-bottom: 20px;">
  <div class="panel-body" style="padding: 20px;">
    <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
      <div class="col-sm-8">
        <h3 style="margin: 0 0 5px; font-weight: bold; color: #212121;">
          <i class="fa fa-plus-circle" style="color: #FF8D1B;"></i> Tambah Film Baru
        </h3>
        <p style="color: #777; margin: 0;">Silakan isi formulir di bawah ini dengan lengkap untuk menambahkan film baru.</p>
      </div>
      <div class="col-sm-4 text-right" style="margin-top: 10px;">
        <a href="{{ route('admin.films.index') }}" class="btn btn-default">
          <i class="fa fa-arrow-left"></i> Kembali ke Daftar
        </a>
      </div>
    </div>
  </div>
</div>

@if ($errors->any())
  <div class="alert alert-danger" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08);">
    <strong style="display: block; margin-bottom: 5px;"><i class="fa fa-exclamation-circle"></i> Terdapat kesalahan pengisian formulir:</strong>
    <ul style="margin-bottom: 0; padding-left: 20px;">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="panel panel-default" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08);">
  <div class="panel-heading" style="background: #212121; color: #fff; font-weight: bold;">
    <i class="fa fa-pencil-square-o"></i> Formulir Tambah Film
  </div>
  <div class="panel-body" style="padding: 25px;">
    <form action="{{ route('admin.films.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <!-- Judul Film -->
        <div class="col-md-8">
          <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title" style="font-weight: 600; color: #333;">Judul Film <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" class="form-control input-lg" value="{{ old('title') }}" placeholder="Contoh: Interstellar, Inception, Avengers..." required>
            @if ($errors->has('title'))
              <span class="help-block">{{ $errors->first('title') }}</span>
            @endif
          </div>
        </div>

        <!-- Tahun Rilis -->
        <div class="col-md-4">
          <div class="form-group {{ $errors->has('year') ? 'has-error' : '' }}">
            <label for="year" style="font-weight: 600; color: #333;">Tahun Rilis <span class="text-danger">*</span></label>
            <input type="number" name="year" id="year" class="form-control input-lg" value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') + 5 }}" placeholder="Tahun (cth: {{ date('Y') }})" required>
            @if ($errors->has('year'))
              <span class="help-block">{{ $errors->first('year') }}</span>
            @endif
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Genre Film -->
        <div class="col-md-6">
          <div class="form-group {{ $errors->has('genre_id') ? 'has-error' : '' }}">
            <label for="genre_id" style="font-weight: 600; color: #333;">Genre Film <span class="text-danger">*</span></label>
            <select name="genre_id" id="genre_id" class="form-control" required style="height: 42px;">
              <option value="">-- Pilih Genre --</option>
              @foreach($genres as $genre)
                <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                  {{ $genre->name }}
                </option>
              @endforeach
            </select>
            @if ($errors->has('genre_id'))
              <span class="help-block">{{ $errors->first('genre_id') }}</span>
            @endif
          </div>
        </div>

        <!-- File Upload Poster -->
        <div class="col-md-6">
          <div class="form-group {{ $errors->has('poster_file') ? 'has-error' : '' }}">
            <label for="poster_file" style="font-weight: 600; color: #333;">Upload Gambar Poster (Opsional)</label>
            <input type="file" name="poster_file" id="poster_file" class="form-control" accept="image/*" style="height: 42px; padding: 6px 12px;">
            <small class="text-muted">Format: JPG, PNG, WebP. Maksimal 2MB.</small>
            @if ($errors->has('poster_file'))
              <span class="help-block">{{ $errors->first('poster_file') }}</span>
            @endif
          </div>
        </div>
      </div>

      <!-- Alternatif Path Poster -->
      <div class="form-group {{ $errors->has('poster_url') ? 'has-error' : '' }}">
        <label for="poster_url" style="font-weight: 600; color: #333;">Atau Gunakan Path / URL Poster Bawaan</label>
        <input type="text" name="poster_url" id="poster_url" class="form-control" value="{{ old('poster_url', 'template/images/m1.jpg') }}" placeholder="Contoh: template/images/m1.jpg atau storage/images/c1.jpg">
        <small class="text-muted">Bisa dibiarkan default jika tidak ingin upload file gambar sendiri.</small>
        @if ($errors->has('poster_url'))
          <span class="help-block">{{ $errors->first('poster_url') }}</span>
        @endif
      </div>

      <!-- Sinopsis -->
      <div class="form-group {{ $errors->has('sinopsis') ? 'has-error' : '' }}">
        <label for="sinopsis" style="font-weight: 600; color: #333;">Sinopsis / Deskripsi Film <span class="text-danger">*</span></label>
        <textarea name="sinopsis" id="sinopsis" rows="6" class="form-control" placeholder="Tuliskan jalan cerita atau rangkuman sinopsis film di sini..." required>{{ old('sinopsis') }}</textarea>
        @if ($errors->has('sinopsis'))
          <span class="help-block">{{ $errors->first('sinopsis') }}</span>
        @endif
      </div>

      <!-- Kelola Cast / Pemeran -->
      <div class="panel panel-default" style="margin-top: 20px; border: 1px solid #e0e0e0; box-shadow: none;">
        <div class="panel-heading" style="background: #f7f7f7; font-weight: bold; color: #333; display: flex; justify-content: space-between; align-items: center;">
          <span><i class="fa fa-users"></i> Cast / Pemeran Film</span>
          <button type="button" class="btn btn-xs btn-success" id="btn-add-cast">
            <i class="fa fa-plus"></i> Tambah Cast
          </button>
        </div>
        <div class="panel-body" style="padding: 15px;">
          <p class="text-muted" style="font-size: 13px; margin-bottom: 12px;">Pilih nama cast/aktor dan tentukan nama peran/karakternya di film ini.</p>
          <div id="cast-rows-container">
            <div class="row cast-row" style="margin-bottom: 10px;">
              <div class="col-sm-5">
                <select name="casts[0][cast_id]" class="form-control">
                  <option value="">-- Pilih Cast / Aktor --</option>
                  @foreach($casts as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-sm-5">
                <input type="text" name="casts[0][actor]" class="form-control" placeholder="Nama Peran / Karakter (cth: Tony Stark)">
              </div>
              <div class="col-sm-2">
                <button type="button" class="btn btn-default btn-block btn-remove-cast" title="Hapus baris ini">
                  <i class="fa fa-trash text-danger"></i> Hapus
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <hr style="margin: 25px 0;">

      <!-- Submit Buttons -->
      <div class="form-group text-right" style="margin-bottom: 0;">
        <a href="{{ route('admin.films.index') }}" class="btn btn-default" style="margin-right: 10px; padding: 8px 20px;">
          Batal
        </a>
        <button type="submit" class="btn btn-warning" style="background: #FF8D1B; border-color: #FF8D1B; font-weight: bold; padding: 8px 25px;">
          <i class="fa fa-save"></i> Simpan Film
        </button>
      </div>

    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('cast-rows-container');
    var addBtn = document.getElementById('btn-add-cast');
    var castOptions = `@foreach($casts as $c)<option value="{{ $c->id }}">{{ addslashes($c->name) }}</option>@endforeach`;
    var rowIndex = 1;

    if (addBtn && container) {
      addBtn.addEventListener('click', function () {
        var newRow = document.createElement('div');
        newRow.className = 'row cast-row';
        newRow.style.marginBottom = '10px';
        newRow.innerHTML = `
          <div class="col-sm-5">
            <select name="casts[` + rowIndex + `][cast_id]" class="form-control">
              <option value="">-- Pilih Cast / Aktor --</option>
              ` + castOptions + `
            </select>
          </div>
          <div class="col-sm-5">
            <input type="text" name="casts[` + rowIndex + `][actor]" class="form-control" placeholder="Nama Peran / Karakter">
          </div>
          <div class="col-sm-2">
            <button type="button" class="btn btn-default btn-block btn-remove-cast" title="Hapus baris ini">
              <i class="fa fa-trash text-danger"></i> Hapus
            </button>
          </div>
        `;
        container.appendChild(newRow);
        rowIndex++;
      });

      container.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-remove-cast');
        if (btn) {
          var row = btn.closest('.cast-row');
          if (container.querySelectorAll('.cast-row').length > 1) {
            row.remove();
          } else {
            row.querySelector('select').value = '';
            row.querySelector('input').value = '';
          }
        }
      });
    }
  });
</script>
@endsection
