@extends(file_exists(resource_path('views/extend/front-end/master.blade.php')) ? 
'extend.front-end.master':
 'front-end.master', ['body_class' => 'wt-innerbgcolor'] )
@push('sliderStyle') 
    <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
@endpush
@push('stylesheets')
    <link href="{{ asset('css/prettyPhoto-min.css') }}" rel="stylesheet">
@endpush
@section('title')
        @if ($home == false)
            {{ $page['title'] }}
        @else 
            {{ config('app.name') }} 
        @endif
    @stop
@section('description', "$meta_desc")
@if ($slider_style == 'style2' || $slider_style == 'style3' && $slider_order == 0)
    @section('homeSlider')
        <div id="slider">
            @if ($slider_style == 'style2')
                <second-slider 
                    :page_id="{{$page['id']}}">
                </second-slider>
            @elseif ($slider_style == 'style3') 
                <third-slider 
                    :page_id="{{$page['id']}}">
                </third-slider>
            @endif
        </div>
    @endsection
@endif
@section('content')
    @if ($show_banner_image == true && $home == false)
        @php $breadcrumbs = Breadcrumbs::generate('showPage',$page, $slug); @endphp
        <div class="wt-haslayout wt-innerbannerholder" style="background-image:url({{{ asset($banner) }}})">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
                        <div class="wt-innerbannercontent">
                            @if (!empty($page) && $show_title == true)
                                <div class="wt-title">
                                    <h2>{{{ $page['title'] }}}</h2>
                                </div>
                            @endif
                            @if (!empty($show_breadcrumbs) && $show_breadcrumbs === 'true')
                                @if (count($breadcrumbs))
                                    <ol class="wt-breadcrumb">
                                        @foreach ($breadcrumbs as $breadcrumb)
                                            @if ($breadcrumb->url && !$loop->last)
                                                <li><a href="{{{ $breadcrumb->url }}}">{{{ $breadcrumb->title }}}</a></li>
                                            @else
                                                <li class="active">{{{ $breadcrumb->title }}}</li>
                                            @endif
                                        @endforeach
                                    </ol>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div id="pages-list">
        @if (Session::has('error'))
            <div class="flash_msg">
                <flash_messages :message_class="'danger'" :time ='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
            </div>
            @php session()->forget('error'); @endphp
        @endif
        @if ($home == false)
            @if ($show_banner_image == false && !empty($page['title']) && $show_title == true)
                <div class="wt-innerbannercontent wt-without-banner-title">
                    <div class="wt-title">
                        <h2>{{{ $page['title'] }}}</h2>
                    </div>
                </div>
            @endif
        @endif
        @if (!empty($page))
            @if (!empty($sections))
                <show-page 
                :sections_list="'{{json_encode($sections)}}'"
                :page="'{{json_encode($page)}}'"
                :type="'{{$type}}'">
                </show-page>
            @endif
            @if (!empty($description && $description != 'null'))
                <div class="dc-contentwrappers">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
                                <div class="dc-howitwork-hold dc-haslayout">
                                    <div class="dc-haslayout dc-main-section">
                                        @php echo htmlspecialchars_decode(stripslashes($description)); @endphp
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            @if (file_exists(resource_path('views/extend/errors/404.blade.php'))) 
                @include('extend.errors.404')
            @else 
                @include('errors.404')
            @endif
        @endif
        
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/prettyPhoto-min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    @if ($slider_style == 'style2')
        <script>
            jQuery('#wt-header').addClass('wt-headervthhree')
            jQuery('#wt-header').removeClass('wt-headervtwo')
            jQuery('.wt-formtheme.wt-formbanner.wt-formbannervtwo').remove()
        </script>
        @if (isset($_SERVER["SERVER_NAME"]) && $_SERVER["SERVER_NAME"] === 'amentotech.com')
            <script>
                jQuery('.wt-logo a img').attr('src',(APP_URL+'/images/logo-white.png'));
            </script>
        @endif
    @else
        <script src="{{ asset('js/tilt.jquery.js') }}"></script>
    @endif
@endpush
