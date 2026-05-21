@extends(file_exists(resource_path('views/extend/front-end/master.blade.php')) ? 
'extend.front-end.master':
 'front-end.master', ['body_class' => 'wt-innerbgcolor'] )
@push('stylesheets')
    <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
@endpush
@section('title'){{ $service_list_meta_title }} @stop
@section('description', $service_list_meta_desc)
@section('content')
    @if ($show_service_banner == 'true')
        @php $breadcrumbs = Breadcrumbs::generate('searchResults'); @endphp
        {{-- <div class="wt-haslayout wt-innerbannerholder" style="background-image:url({{{ asset(Helper::getBannerImage($service_inner_banner, 'uploads/settings/general')) }}})"> --}}

                <div class="wt-haslayout wt-innerbannerholder" style="background-image:url({{{asset('images/bannerimg/img-02.jpg') }}})">


            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
                        <div class="wt-innerbannercontent">
                            <div class="wt-title">
                                <h2>{{ trans('lang.resources') }}</h2>
                            </div>
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
    <div class="wt-haslayout wt-main-section" id="services">
        @if (Session::has('payment_message'))
            @php $response = Session::get('payment_message') @endphp
            <div class="flash_msg">
                <flash_messages :message_class="'{{{$response['code']}}}'" :time ='5' :message="'{{{ $response['message'] }}}'" v-cloak></flash_messages>
            </div>
        @endif
        <div class="wt-haslayout">
            <div class="container">
                <div class="row">
                    <div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
                        @if(!empty($is_login))
                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
                                @if (file_exists(resource_path('views/extend/front-end/services/filters.blade.php'))) 
                                    @include('extend.front-end.services.filters')
                                @else 
                                    @include('front-end.services.filters')
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
                                <div class="row">
                                    <div class="wt-students-holder la-students-grid">
                                        @if (!empty($keyword))
                                            <div class="wt-userlistingtitle">
                                                <span>{{ trans('lang.01') }} {{$resources->count()}} of {{$services_total_records}} results for <em>"{{{$keyword}}}"</em></span>
                                            </div>
                                        @endif
                                        @if (!empty($resources) && $resources->count() > 0)
                                            @foreach ($resources as $resource)
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 float-left">
                                                        <div class="wt-students-info">
                                                            <span class="wt-featuredtagvtwo">{{ trans('lang.featured') }}</span>
                                                            <div class="wt-students-details">
                                                                <div class="wt-students-content">
                                                                    <div class="dc-title">
                                                                        <a href="{{url('resource/'.$resource->slug)}}">
                                                                            <h3 class="title-resource"><strong>{{{$resource->title}}}</strong></h3>
                                                                        </a>
                                                                        <p>Subject name: {{{$resource->course_name}}}</p>
                                                                        <p>Author: {{{$resource->user_name}}}</p>
                                                                        <p>Description: {{{$resource->description}}}</p>
                                                                        <span><strong>AUD {{{$resource->price}}}</strong> </span>
                                                                    </div>
                                                                </div>
                                                                 <div class="wt-students-rating">
                                                                    <ul>
                                                                        <li><span><i class="fa fa-star">
                                                                            Average Rating: {{{$resource->average_rating}}} / 5   
                                                                        </i></span></li>
                                                                        <li>

                                                                            <span><i class="fa fa-star">
                                                                            (  {{{Helper::ratingresource($resource->slug)->count() }}}
                                                                            @if(Helper::ratingresource($resource->slug)->count()>0)
                                                                            <a href="javascript:void(0)"      
                                                                             @click.prevent='givereviews("{{$resource->slug}}")' 
                                                                             style="color: #fecb02;"> reviews</a>)
                                                                             @else
                                                                              <a href="javascript:void(0)" 
                                                                              
                                                                             style="color: #fecb02;"> reviews</a>)
                                                                             @endif
                                                                            </i></span>
                                                                        </li>

                                                                    </ul>
                                                                </div>

                                                                <b-modal ref="myModalRefs-{{$resource->slug}}" hide-footer title="Resource Reviews">
                                 
                                    @php
                                    $ratingbox=DB::table('users')
                                    ->select('users.id','users.first_name','users.last_name','transactions.comment','transactions.buyers_rating')
                                    ->join('transactions','transactions.buyer_id','=','users.id')
                                    ->where('transactions.resource_slug',$resource->slug)
                                    ->get();
                                  

                                    @endphp

                                    <div class="wt-usersingle wt-servicesingle-holder"> 
                                     <div class="text-center"><strong> Reviews Count : </strong>{{{Helper::ratingresource($resource->slug)->count() }}}
                                     </div>
                                     <div class="wt-servicesingle">
                                    @foreach($ratingbox as $val)
                                    
                                    
                                    @if($val->buyers_rating==5)
                                            <div class="wt-students-rating">
                                            <ul>
                                            <li>
                                            <span>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            </span>
                                            </li>
                                            </ul>
                                            </div>
                                    @elseif($val->buyers_rating==4)
                                        <div class="wt-students-rating">
                                            <ul>
                                            <li>
                                            <span>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            </span>
                                            </li>
                                            </ul>
                                        </div>

                                         @elseif($val->buyers_rating==3)
                                        <div class="wt-students-rating">
                                            <ul>
                                            <li>
                                            <span>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            </span>
                                            </li>
                                            </ul>
                                        </div>


                                         @elseif($val->buyers_rating==2)
                                        <div class="wt-students-rating">
                                            <ul>
                                            <li>
                                            <span>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            </span>
                                            </li>
                                            </ul>
                                        </div>

                                         @else
                                        <div class="wt-students-rating">
                                            <ul>
                                            <li>
                                            <span>
                                            <i class="fa fa-star"></i>
                                            </span>
                                            </li>
                                            </ul>
                                        </div>


                                            @endif

                                           

                                    <h5>{{$val->first_name}} {{$val->last_name}}</h5>
                                    <p>{{$val->comment}}</p>

                                 

                                   @endforeach
                                    </div>
                                    </div>
                                     
                                </b-modal>


                                                            </div>
                                                        </div>
                                                    </div>                                        
                                            @endforeach
                                        @else
                                            @include('errors.no-record')
                                        @endif
                                    </div>
                                       

                                </div>
                            </div>
                        @else
                            @include('errors.please-login')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('js/readmore.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/countTo.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/appear.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script>
   
        var _wt_studentslider = jQuery('.wt-studentslider')
        _wt_studentslider.owlCarousel({
            items: 1,
            loop:true,
            nav:true,
            margin: 0,
            autoplay:false,
            navClass: ['wt-prev', 'wt-next'],
            navContainerClass: 'wt-search-slider-nav',
            navText: ['<span class="lnr lnr-chevron-left"></span>', '<span class="lnr lnr-chevron-right"></span>'],
        });
    </script>
@endpush