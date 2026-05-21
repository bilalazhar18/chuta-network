<!doctype html>
<!--[if lt IE 7]>		<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>			<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>			<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="" dir="{{Helper::getTextDirection()}}">
<!--<![endif]-->
@php
	$userId = 0
@endphp

@if(Auth::user())
	@php
		$sender = 99;
		$receiver = 77;
		$meeting_room = "testing room";
		$userId = Auth::user()->id;
	@endphp
@endif
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	@if (trim($__env->yieldContent('title')))
		<title>@yield('title')</title>
	@else 
		<title>{{ config('app.name') }}</title>
	@endif
	<meta name="description" content="@yield('description')">
	<meta name="keywords" content="@yield('keywords')">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<link rel="icon" href="{{{ asset(Helper::getSiteFavicon()) }}}" type="image/x-icon">
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/normalize-min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/scrollbar-min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome/fontawesome-all.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/themify-icons.css') }}" rel="stylesheet">
	<link href="{{ asset('css/jquery-ui-min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/linearicons.css') }}" rel="stylesheet">
	@stack('sliderStyle')
	<link href="{{ asset('css/main.css') }}" rel="stylesheet">
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
	<link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
	<link href="{{ asset('css/rtl.css') }}" rel="stylesheet">
	<link href="{{ asset('css/color.css') }}" rel="stylesheet">
	@php echo \App\Typo::setSiteStyling(); @endphp
    <link href="{{ asset('css/transitions.css') }}" rel="stylesheet">
	@stack('stylesheets')
	<script type="text/javascript">
		var APP_URL = {!! json_encode(url('/')) !!}
		var readmore_trans = {!! json_encode(trans('lang.read_more')) !!}
		var less_trans = {!! json_encode(trans('lang.less')) !!}
		var Map_key = {!! json_encode(Helper::getGoogleMapApiKey()) !!}
		var APP_DIRECTION = {!! json_encode(Helper::getTextDirection()) !!}
	</script>
	@if (Auth::user())
		<script type="text/javascript">
			var USERID = {!! json_encode(Auth::user()->id) !!}
			window.Laravel = {!! json_encode([
			'csrfToken'=> csrf_token(),
			'user'=> [
				'authenticated' => auth()->check(),
				'id' => auth()->check() ? auth()->user()->id : null,
				'name' => auth()->check() ? auth()->user()->first_name : null,
				'image' => !empty(auth()->user()->profile->avater) ? asset('uploads/users/'.auth()->user()->id .'/'.auth()->user()->profile->avater) : asset('images/user-login.png'),
				'image_name' => !empty(auth()->user()->profile->avater) ? auth()->user()->profile->avater : '',
				]
				])
			!!};
		</script>
	@endif
	<script>
		window.trans = <?php
		$lang_files = File::files(resource_path() . '/lang/' . App::getLocale());
		$trans = [];
		foreach ($lang_files as $f) {
			$filename = pathinfo($f)['filename'];
			$trans[$filename] = trans($filename);
		}
		echo json_encode($trans);
		?>;
	</script>
</head>
<style>
/* The Modal (background) */
.incoming-call-modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.incoming-call-modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.closing-incoming-call-modal {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closing-incoming-call-modal:hover,
.closing-incoming-call-modal:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>
<body class="wt-login {{Helper::getBodyLangClass()}} {{Helper::getTextDirection()}} {{empty(Request::segment(1)) ? 'home-wrapper' : '' }}">
	{{ \App::setLocale(env('APP_LANG')) }}
	<!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<div class="preloader-outer">
		<div class="preloader-holder">
			<div class="loader"></div>
		</div>
	</div>
	<div id="wt-wrapper" class="wt-wrapper wt-haslayout">
		<div class="wt-contentwrapper">
			@yield('header')
			@yield('slider')
			@yield('main')
			@yield('footer')
		</div>
	</div>
	
	 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: #D1398C">

                <div class="modal-body">
                    <h5 class="text-center" style="color:#fff">Resource Trading Coming soon</h5>
                    <p class="mt-3 text-center" style="color:#fff">
                        Would you like to see this at your university?
                    </p>
                    <div class="row">
                        <div class="col-md-3">
                        </div>
                        <!-- <div class="col-md-4 w-10 mb-2">
                            <button class="wt-btn btn btn-outline-light col-sm-12">No</button>
                        </div> -->
						<?php

						if(Auth::user()) {
                        
                       
                        $result=DB::table('resourcefeedbacks')->where('user_id','=',Auth::user()->id)->first();
                        if ($result===null) {
                    	
						?>
                        <div class="col-md-3 mb-2 w-50 messageresource">
                        <div class="wt-logininfo" style="/*border:solid #fff;border-top-left-radius: 12px;border-top-right-radius: 12px;bosrder-bottom-right-radius: 12px;border-bottom-left-radius: 12px;*/">
                        <button type="submit" class="btn btn-outline-light btn do-login-button wt-btn resourcefeed" style="border: 1px solid #fff !important;" value="0">No</button></div>
                        </div>

                         <div class="col-md-3 w-50 messageresource">
                        <div class="wt-logininfo">
                        	<button type="submit" class="wt-btn do-login-button resourcefeed" value="1">Yes</button></div>
                        </div>

                       

                        <div class="col-md-1">
                        </div>
                        <p class="wt-btn do-login-button text-center thanks"></p>
                        <?php
                    	
                    	}

                    	else{
                    		?>
                    		<div class="wt-logininfo1 text-center">
                    		<p class="wt-btn do-login-button">You Have already Submitted</p>
                    		</div>
                    		

                    	<?php
                    	}
                    }
                    	?>
                        



                        <!-- <div class="col-md-4 w-10">
                            <button class="wt-btn col-sm-12">Yes</button>
                        </div> -->

                    </div>

                </div>
                </div>
                </div>
        </div>
	<script>
		function rejectCall(){
			//Rejecting incoming call
			var x = document.getElementById("myAudio"); 
			x.pause();
			x.currentTime = 0;
			document.getElementById("id-call-modal").style.display="none";
		}
		function acceptCall(){
			document.getElementById("id-call-modal").style.display="none";
			var x = document.getElementById("myAudio");
			x.pause();
			x.currentTime = 0;
		}
	</script>
	<div>
	<!-- The Modal -->
		<div id="id-call-modal" class="incoming-call-modal">
			<!-- Modal content -->
			<div class="incoming-call-modal-content"style="width: 45%;height: 35%;">
				<span onclick="rejectCall()" class="closing-incoming-call-modal">&times;</span>
					<!-- <a href="myFunc()"></a> -->
					<!-- $sender = 0 -->
					<p id="caller"></p>
					<div style="text-align: center">
						<p>Incoming call from <b id="sender-name"></b></p>
					<div>
					<div style="text-align: center">
						<i aria-hidden="true" style="font-size: 70px; width:100px">
							<a class="fa fa-phone" href="" target="_blank" id="accept-call" onclick="acceptCall()"></a>
						</i>
						<i aria-hidden="true" style="font-size: 70px; width:100px">
							<a class="fa fa-times" href="#" onclick="rejectCall()" ></a>
						</i>
					</div>
					<audio id="myAudio" allow="autoplay">
						<source src="http://127.0.0.1:8000/uploads/settings/general/incoming_call.mp3" type="audio/mpeg">
						Your browser does not support the audio element.
					</audio>
				
			</div>

		</div>
	</div>
	






	<script src="https://js.pusher.com/6.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

@if(Auth::user())
    <script>
		// Enable pusher logging - don't include this in production
		Pusher.logToConsole = false;	
		var pusher = new Pusher('1c16fd4ad400872f278e', {
			cluster: 'ap2'
		});
		var channel = pusher.subscribe('my-channel');
		channel.bind('video-call', function(data) {
			var userId = "<?php echo $userId; ?>";
			
			console.log(parseInt(userId), data.receiver);
			if(parseInt(userId) == parseInt(data.receiver)){
				//Playing incoming call				
				var x = document.getElementById("myAudio"); 
				x.play();

				//Pasting calling details 
				document.getElementById("accept-call").href=data.meeting_url;			
				document.getElementById("sender-name").innerHTML = data.sender_name;
				document.getElementById("id-call-modal").style.display="block";
			}
			
			
		});


		$(document).on("click",".resourcefeed",function(){
			$.ajaxSetup({
			headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
			});
			var element=$(this).attr("value");
			if(element!='')
			{
				$.ajax({
					 url:'{{url("post_resource_feed")}}',
					 type:'POST',
					 data:{element:element},
					 success:function(data)
					{
						//console.log(data);
						$('.messageresource').hide();
						$('.thanks').html('Thank You!').delay(1000).fadeOut();
//					$('#msg').html(data).fadeIn('slow');
//					$('#msg').delay(5000).fadeOut('slow');

					}

				})
			}
		})


    </script>
@endif
	<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
	<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
	@yield('bootstrap_script')
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/vendor/jquery-library.js') }}"></script>
	<script src="{{ asset('js/scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-min.js') }}"></script>
    @stack('scripts')
    <script>
        jQuery(window).load(function () {
            jQuery(".preloader-outer").delay(500).fadeOut();
            jQuery(".pins").delay(500).fadeOut("slow");
        });
    </script>
</body>
</html>
