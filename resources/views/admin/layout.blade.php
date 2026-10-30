@extends('templates.index')

@section('content')
<div class="faq" style="padding-top: 1.5em; padding-bottom: 3.5em;">
  <div class="container">
    <div class="agileits-news-top" style="padding: 0.5em 0 1.5em 0;">
      <ol class="breadcrumb">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
        @yield('admin_breadcrumb')
      </ol>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
      <div class="alert alert-success alert-dismissible" role="alert" style="box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <i class="fa fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="alert alert-danger alert-dismissible" role="alert" style="box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
      </div>
    @endif

    <div class="row">
      <!-- Admin Sidebar -->
      <div class="col-md-3 col-sm-4">
        <!-- Admin Profile Box -->
        <div class="panel panel-default text-center" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08); border-top: 4px solid #FF8D1B; margin-bottom: 15px;">
          <div class="panel-body" style="padding: 20px 15px;">
            <div style="width: 70px; height: 70px; margin: 0 auto 12px; background: #212121; color: #FF8D1B; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px;">
              <i class="fa fa-user-secret"></i>
            </div>
            <h4 style="margin: 0 0 5px; font-weight: bold; color: #212121;">{{ Auth::user()->name }}</h4>
            <span class="label label-warning" style="background: #FF8D1B; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Administrator</span>
            <p style="margin: 8px 0 0; color: #777; font-size: 12px;">{{ Auth::user()->email }}</p>
          </div>
        </div>

        <!-- Admin Nav Menu -->
        <div class="list-group" style="box-shadow: 0 2px 5px rgba(0,0,0,0.08);">
          <div class="list-group-item" style="background: #212121; color: #fff; font-weight: bold; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
            <i class="fa fa-bars"></i> Navigasi Admin
          </div>
          <a href="{{ route('admin.dashboard') }}" class="list-group-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" style="{{ request()->routeIs('admin.dashboard') ? 'background: #FF8D1B; border-color: #FF8D1B;' : '' }}">
            <i class="fa fa-dashboard fa-fw"></i> Dashboard
          </a>
          <a href="{{ route('admin.films.index') }}" class="list-group-item {{ (request()->routeIs('admin.films.index') || request()->routeIs('admin.films.show') || request()->routeIs('admin.films.edit')) ? 'active' : '' }}" style="{{ (request()->routeIs('admin.films.index') || request()->routeIs('admin.films.show') || request()->routeIs('admin.films.edit')) ? 'background: #FF8D1B; border-color: #FF8D1B;' : '' }}">
            <i class="fa fa-film fa-fw"></i> Kelola Film
          </a>
          <a href="{{ route('admin.films.create') }}" class="list-group-item {{ request()->routeIs('admin.films.create') ? 'active' : '' }}" style="{{ request()->routeIs('admin.films.create') ? 'background: #FF8D1B; border-color: #FF8D1B;' : '' }}">
            <i class="fa fa-plus-circle fa-fw"></i> Tambah Film Baru
          </a>
          <a href="{{ route('admin.comments.index') }}" class="list-group-item {{ request()->routeIs('admin.comments*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.comments*') ? 'background: #FF8D1B; border-color: #FF8D1B;' : '' }}">
            <i class="fa fa-comments fa-fw"></i> Kelola Komentar
          </a>
          <a href="{{ route('admin.users.index') }}" class="list-group-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.users*') ? 'background: #FF8D1B; border-color: #FF8D1B;' : '' }}">
            <i class="fa fa-users fa-fw"></i> Kelola Pengguna
          </a>
          <a href="{{ route('home') }}" class="list-group-item" target="_blank">
            <i class="fa fa-globe fa-fw"></i> Lihat Website <i class="fa fa-external-link" style="font-size: 11px;"></i>
          </a>
          <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
            @csrf
            <button type="submit" class="list-group-item text-danger" style="width: 100%; text-align: left; border-radius: 0; background: #fff; color: #d9534f; cursor: pointer; border-top: 1px solid #ddd;">
              <i class="fa fa-sign-out fa-fw"></i> Logout
            </button>
          </form>
        </div>
      </div>

      <!-- Main Admin Content -->
      <div class="col-md-9 col-sm-8">
        @yield('admin_content')
      </div>
    </div>
  </div>
</div>
@endsection
