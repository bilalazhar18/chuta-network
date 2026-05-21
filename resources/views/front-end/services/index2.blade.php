@extends(file_exists(resource_path('views/extend/front-end/master.blade.php')) ? 
'extend.front-end.master':
'front-end.master', ['body_class' => 'wt-innerbgcolor'] )
@push('stylesheets')
<link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
@endpush
{{-- @section('title'){{ $service_list_meta_title }} @stop
@section('description', $service_list_meta_desc)
@section('content')
@if ($show_service_banner == 'true')
@php $breadcrumbs = Breadcrumbs::generate('searchResults'); @endphp --}}
{{-- 
<div class="wt-haslayout wt-innerbannerholder" style="background-image:url({{{ asset(Helper::getBannerImage($service_inner_banner, 'uploads/settings/general')) }}})">
--}}
@section('content')
<div class="wt-haslayout wt-innerbannerholder" style="background-image:url({{{asset('images/bannerimg/img-02.jpg') }}})">
   <div class="container">
      <div class="row justify-content-md-center">
         <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
            <div class="wt-innerbannercontent">
               <div class="wt-title">
                  <h2>{{ trans('lang.resources') }}</h2>
               </div>
            {{--    @if (!empty($show_breadcrumbs) && $show_breadcrumbs === 'true')
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
               @endif --}}
            </div>
         </div>
      </div>
   </div>
</div>
{{-- @endif --}}
<div class="wt-haslayout wt-main-section" id="services">
   {{-- @if (Session::has('payment_message'))
   @php $response = Session::get('payment_message') @endphp
   <div class="flash_msg">
      <flash_messages :message_class="'{{{$response['code']}}}'" :time ='5' :message="'{{{ $response['message'] }}}'" v-cloak></flash_messages>
   </div>
   @endif --}}
   <div class="wt-haslayout">
      <div class="container">
         <div class="row">
            <div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
              @if(!empty($is_login))
               <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
                  @if (file_exists(resource_path('views/extend/front-end/services/filters1.blade.php'))) 
                  @include('extend.front-end.services.filters1')
                  @else 
                  @include('front-end.services.filters1')
                  @endif
               </div>
               <div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
                  <div class="row">
                     <div class="wt-students-holder la-students-grid">
                        {{-- @if (!empty($keyword))
                        <div class="wt-userlistingtitle">
                           <span>{{ trans('lang.01') }} {{$resources->count()}} of {{$services_total_records}} results for <em>"{{{$keyword}}}"</em></span>
                        </div>
                        @endif --}}

                        @if (!empty($resources) && $resources->count() > 0)
                        @foreach ($resources as $resources)
                       
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 float-left">
                           <div class="wt-students-info">
                              <span class="wt-featuredtagvtwo">{{ trans('lang.featured') }}</span>
                              <div class="wt-students-details">
                                 <div class="wt-students-content">
                                    <div class="dc-title">
                                       <a href="{{url('resource/'.$resources->slug)}}">
                                          <h2 class="title-resource">{{{$resources->title}}}</h2>
                                       </a>
                                       <p>Subject name: {{{$resources->course_name}}}</p>
                                       <p>Author: {{{$resources->user_name}}}</p>
                                       <span><strong><h6>Price: AUD {{$resources->price}}</h6></strong><span>
                                         
                                        <!-- <p><strong><h6>Average Rating: 5 / 5</h6></strong></p>
                                        <p>(5 being Most Helpful and 1 being Least Helpful)</p>
                                        <br> -->
                                    </div>
                                 </div>
                                 <div class="wt-students-rating">
                                    <ul>
                                       <li><span><i class="fa fa-star">
                                          Average Rating: {{{$resources->average_rating}}} / 5   
                                          <br><br>
                                          ( {{{$resources->no_of_transactions}}} reviews) 
                                          </i></span>
                                       </li>
                                    </ul>
                                 </div>
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
      </div>
   </div>
</div>
@endsection
@push('scripts')
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