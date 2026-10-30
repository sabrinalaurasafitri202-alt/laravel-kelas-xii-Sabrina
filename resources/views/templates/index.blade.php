
<!--
author: W3layouts
author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html lang="en">

@include('templates.component.head')
	
<body>
<!-- header -->
@include('templates.component.header')
<!-- //header -->
<!-- bootstrap-pop-up -->
@include('templates.component.login')
<!-- //bootstrap-pop-up -->
<!-- nav -->
@include('templates.component.navbar')
<!-- //nav -->
<div class="general_social_icons">
	<nav class="social">
		<ul>
			<li class="w3_twitter"><a href="#">Twitter <i class="fa fa-twitter"></i></a></li>
			<li class="w3_facebook"><a href="#">Facebook <i class="fa fa-facebook"></i></a></li>
			<li class="w3_dribbble"><a href="#">Dribbble <i class="fa fa-dribbble"></i></a></li>
			<li class="w3_g_plus"><a href="#">Google+ <i class="fa fa-google-plus"></i></a></li>				  
		</ul>
  </nav>
</div>
<!-- /w3l-medile-movies-grids -->
{{-- Flash Message Global --}}
@if(!request()->routeIs('admin.*'))
  @if(session('success') && !session('open_login_modal') && !session('open_register_modal'))
  <div class="container" style="margin-top: 10px;">
    <div class="alert alert-success alert-dismissible" role="alert" style="font-size: 14px;">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
  </div>
  @endif
  @if(session('error'))
  <div class="container" style="margin-top: 10px;">
    <div class="alert alert-danger alert-dismissible" role="alert" style="font-size: 14px;">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
    </div>
  </div>
  @endif
@endif
@yield('content')
	<!-- //comedy-w3l-agileits -->
<!-- footer -->
	@include('templates/component/footer')
<!-- //footer -->
<!-- Bootstrap Core JavaScript -->

<script src="{{ asset('template/js/bootstrap.min.js') }}"></script>
<script>
	$(document).ready(function(){
		$(".dropdown").hover(            
			function() {
				$('.dropdown-menu', this).stop( true, true ).slideDown("fast");
				$(this).toggleClass('open');        
			},
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideUp("fast");
            $(this).toggleClass('open');       
					}
    );
});
</script>
<!-- //Bootstrap Core JavaScript -->
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
				};
			*/
			
			$().UItoTop({ easingType: 'easeOutQuart' });
			
		});
		@stack('script')
		</script>
<!-- //here ends scrolling icon -->
</body>
</html>