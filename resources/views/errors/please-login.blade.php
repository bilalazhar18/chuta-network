<div class="wt-emptydata-holder">
    <div class="wt-emptydata">
        <div class="wt-emptydetails wt-empty-person">
        <img src="{{{ asset('/images/empty-images/no-record.png') }}}">
            <em>{{ trans('lang.please_login') }}</em>
            <br>
            <div class="col-6"><a id="wt-loginbtn" class="wt-btn" href="javascript:void(0)">Sign iN</a>&nbsp;&nbsp;<a class="wt-btn" href="{{url('/register')}}">Join Now</a></p>
            </div>
            </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/readmore.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/countTo.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/appear.js') }}"></script>
    <script type="text/javascript">
    	
	$(window).on('load', function () {
        $('.wt-loginarea .wt-loginformhold').show();
    });
     </script>
@endpush