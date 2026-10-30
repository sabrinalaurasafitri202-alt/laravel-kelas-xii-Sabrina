<div class="modal video-modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Sign In & Sign Up
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>						
      </div>
      <section>
        <div class="modal-body">
          <div class="w3_login_module">
            <div class="module form-module">
              <div class="toggle"><i class="fa fa-times fa-pencil"></i>
                <div class="tooltip">Click Me</div>
              </div>

              <!-- Form Login -->
              <div class="form">
                <h3>Login to your account</h3>

                @if (isset($errors) && $errors->has('login'))
                  <div class="alert alert-danger" style="padding: 10px; margin-bottom: 15px; font-size: 13px;">
                    <i class="fa fa-exclamation-circle"></i> {{ $errors->first('login') }}
                  </div>
                @endif

                @if (session('success') && !old('form_type'))
                  <div class="alert alert-success" style="padding: 10px; margin-bottom: 15px; font-size: 13px;">
                    <i class="fa fa-check-circle"></i> {{ session('success') }}
                  </div>
                @endif

                <form action="{{ route('login.post') }}" method="post">
                  @csrf
                  <input type="hidden" name="form_type" value="login">
                  <input type="hidden" name="redirect_to" value="{{ old('redirect_to', request()->fullUrl()) }}">
                  <input type="text" name="login" value="{{ old('login') }}" placeholder="Username atau Email" required="">
                  <input type="password" name="password" placeholder="Password" required="">
                  <input type="submit" value="Login">
                </form>
              </div>

              <!-- Form Register -->
              <div class="form">
                <h3>Create an account</h3>

                @if (isset($errors) && old('form_type') === 'register' && $errors->any())
                  <div class="alert alert-danger" style="padding: 10px; margin-bottom: 15px; font-size: 13px;">
                    <ul style="margin: 0; padding-left: 18px; text-align: left;">
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <form action="{{ route('register.post') }}" method="post">
                  @csrf
                  <input type="hidden" name="form_type" value="register">
                  <input type="text" name="name" value="{{ old('name') }}" placeholder="Username / Nama Lengkap" required="">
                  <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required="">
                  <input type="password" name="password" placeholder="Password (Min. 8 Karakter)" required="">
                  <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required="">
                  <input type="submit" value="Register">
                </form>
              </div>

              <div class="cta">
                <a href="#" id="toggleFormLink">Switch Login / Register</a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>

<script>
  function switchAuthForms() {
    $('.toggle').children('i').toggleClass('fa-pencil');
    $('.form-module .form').animate({
      height: "toggle",
      'padding-top': 'toggle',
      'padding-bottom': 'toggle',
      opacity: "toggle"
    }, "slow");
  }

  $('.toggle').click(function(){
    switchAuthForms();
  });

  $('#toggleFormLink').click(function(e){
    e.preventDefault();
    switchAuthForms();
  });

  @if(session('open_register_modal') || (isset($errors) && old('form_type') === 'register' && $errors->any()))
    $(document).ready(function(){
      $('#myModal').modal('show');
      $('.form-module .form').first().hide();
      $('.form-module .form').last().show();
      $('.toggle').children('i').addClass('fa-pencil');
    });
  @elseif(session('open_login_modal') || (isset($errors) && ($errors->has('login') || (old('form_type') === 'login' && $errors->any()))))
    $(document).ready(function(){
      $('#myModal').modal('show');
    });
  @endif
</script>