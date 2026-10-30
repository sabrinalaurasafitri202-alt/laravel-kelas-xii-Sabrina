<div class="header">
  <div class="container">
    <div class="w3layouts_logo">
      <a href="{{ route('home') }}"><h1>One<span>Movies</span></h1></a>
    </div>
    <div class="w3_search">
      <form action="#" method="post">
        <input type="text" name="Search" placeholder="Search" required="">
        <input type="submit" value="Go">
      </form>
    </div>
    <div class="w3l_sign_in_register" style="width: auto; max-width: 45%;">
      <ul>
        @auth
          <li style="color: #212121; padding-right: 12px; font-weight: bold;">
            <i class="fa fa-user"></i> {{ Auth::user()->name }}
            @if(Auth::user()->isAdmin())
              <span class="label label-warning" style="margin-left: 5px; font-size: 11px;">Admin</span>
            @endif
          </li>
          @if(Auth::user()->isAdmin())
            <li>
              <a href="{{ route('admin.dashboard') }}" style="background: #2196F3; padding: 6px 16px; margin-right: 6px;">
                <i class="fa fa-dashboard"></i> Dashboard
              </a>
            </li>
          @endif
          <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="background: #e53935; padding: 6px 16px;">
              <i class="fa fa-sign-out"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        @else
          <li><a href="#" data-toggle="modal" data-target="#myModal">Login</a></li>
        @endauth
      </ul>
    </div>
    <div class="clearfix"> </div>
  </div>
</div>