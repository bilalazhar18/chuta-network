@extends(file_exists(resource_path('views/extend/front-end/master.blade.php')) ? 
'extend.front-end.master':
 'front-end.master', ['body_class' => 'wt-innerbgcolor'] )
@push('stylesheets')
    <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
@endpush

@section('content')
  
      {{--   <div class="wt-haslayout wt-innerbannerholder" style="background-image:url({{{ asset(Helper::getBannerImage($f_inner_banner, 'uploads/settings/general')) }}})"> --}}

        <div class="wt-haslayout wt-innerbannerholder" style="background-image:url({{{asset('images/bannerimg/img-02.jpg') }}})">


            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
                        <div class="wt-innerbannercontent">
                            <div class="wt-title">
                                <h2>{{ trans('lang.students') }}</h2>
                            </div>
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
  
    <div class="wt-haslayout wt-main-section" id="user_profile">
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
                          <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
                            @if (file_exists(resource_path('views/extend/front-end/students/filters.blade.php'))) 
                            @include('extend.front-end.students.filters')
                            @else 
                            @include('front-end.students.filters')
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
                            <div class="wt-userlistingholder wt-userlisting wt-haslayout">
                                <!--<div class="wt-userlistingtitle">-->
                                <!--    @if (!empty($users))-->
                                <!--        <span>{{ trans('lang.01') }} {{$users->count()}} of {{\App\User::role('student')->count()}} results @if (!empty($keyword)) for <em>"{{{$keyword}}}"</em> @endif</span>-->
                                <!--    @endif-->
                                <!--</div>-->
                              
                                @if (count($users)>0)
                                    @foreach ($users as $key => $student)
                                        @php
                                      
                                        $user_image = !empty($student->profile->avater) ?
                                                            '/uploads/users/'.$student->id.'/'.$student->profile->avater :
                                                            'images/user.jpg';
                    
                      $loc=DB::table('locations')->select('title')->where('id',$student->location_id)->first(); 
                    
                    $profil=DB::table('profiles')->select('description','cgpa')->where('user_id',$student->id)->first();
                    
                     
                                          $loc=DB::table('locations')->select('title')->where('id',$student->location_id)->first(); 
                                        
                    
                                        $flag = !empty($student->location->flag) ? Helper::getLocationFlag($student->location->flag) :
                                                    '/images/img-01.png';
                                                   
                                            $feedbacks = \App\Review::select('feedback')->where('receiver_id', $student->id)->count();
                                            $avg_rating = App\Review::where('receiver_id', $student->id)->sum('avg_rating');
                                            $rating  = $avg_rating != 0 ? round($avg_rating/\App\Review::count()) : 0;
                                            $reviews = \App\Review::where('receiver_id', $student->id)->get();
                                            $stars  = $reviews->sum('avg_rating') != 0 ? (($reviews->sum('avg_rating')/$feedbacks)/5)*100 : 0;
                                            $average_rating_count = !empty($feedbacks) ? $reviews->sum('avg_rating')/$feedbacks : 0;
                                            $verified_user = \App\User::select('user_verified')->where('id', $student->id)->pluck('user_verified')->first();
                                          
                                            $save_student = !empty(auth()->user()->profile->saved_student) ? unserialize(auth()->user()->profile->saved_student) : array();
                                            $badge = Helper::getUserBadge($student->id);
                                            if (!empty($enable_package) && $enable_package === 'true') {
                                                $feature_class = (!empty($badge) && $student->expiry_date >= $current_date) ? 'wt-featured' : 'wt-exp';
                                                $badge_color = !empty($badge) ? $badge->color : '';
                                                $badge_img  = !empty($badge) ? $badge->image : '';
                                            } else {
                                                $feature_class = 'wt-exp';
                                                $badge_color = '';
                                                $badge_img    = '';
                                            }
                                        @endphp
                                      
                                        <div class="wt-userlistinghold {{ $feature_class }}">
                                            @if(!empty($enable_package) && $enable_package === 'true')
                                                @if ($student->expiry_date >= $current_date && !empty($student->badge_id))
                                                    <span class="wt-featuredtag" style="border-top: 40px solid {{ $badge_color }};">
                                                        @if (!empty($badge_img))
                                                            <img src="{{{ asset(Helper::getBadgeImage($badge_img)) }}}" alt="{{ trans('lang.is_featured') }}" data-tipso="Plus Member" class="template-content tipso_style">
                                                        @else
                                                            <i class="wt-expired fas fa-bold"></i>
                                                        @endif
                                                    </span>
                                                @endif
                                            @endif
                                          
                                          
                                            <figure class="wt-userlistingimg">
                                                @if(!empty($user_image))
                                                <img src="{{{ asset($user_image) }}}" alt="{{ trans('lang.img') }}">
                                                @else
                                                <img src="{{{ asset(Helper::getImageWithSize('uploads/users/'.$student->id, $student->avater, 'listing')) }}}" alt="{{ trans('lang.img') }}">
                                                @endif
                                            </figure>
                                            
                                               
                                          
                                          <!--uploads/users/'.$student->id.'/'.$student->profile->avater-->
                                            
                                            <div class="wt-userlistingcontent">
                                                <div class="wt-contenthead">
                                                    <div class="wt-title">
                                                        <a href="{{{ url('profile/'.$student->slug) }}}">
                                                            @if ($verified_user === null ||$verified_user == 1)
                                                                <i class="fa fa-check-circle"></i>
                                                                
                                                            @endif
                                                           {{$student->first_name}} {{$student->last_name}}
                                                        </a>
                                                        
                                                      
                                                        
                                                        
                                                        @if (!empty($student->tagline))
                                                            <h2><a href="{{{ url('profile/'.$student->slug) }}}">{{{ $student->tagline }}}</a></h2>
                                                        @endif
                                                          
                                                    </div>
                                                    
                                                   
                                                    <ul class="wt-userlisting-breadcrumb">
                                                        @if (!empty($profil->cgpa))
                                                            <li><span><i class="fas fa-graduation-cap"></i>
                                                                {{{ $profil->cgpa }}}</span>
                                                            </li>
                                                        @endif

                                                        @if(!empty($student->faculty))
                                                         <li><span><i class="fas fa-industry"></i>
                                                                 {{{ Helper::getFacultyName($student->faculty) }}}</span>
                                                            </li>
                                                        
                                                         @endif
                                                        
                                                        
                                                         @if (!empty($student->location_id))
                                                            <li><span><img src="{{{ asset($flag)}}}" alt="Flag"> {{{ !empty($loc->title) ? $loc->title : '' }}}</span></li>
                                                        @endif
                                                        
                                                        
                                                     
                                                        @if (in_array($student->id, $save_student))
                                                            <li class="wt-btndisbaled">
                                                                <a href="javascrip:void(0);" class="wt-clicksave wt-clicksave">
                                                                    <i class="fa fa-heart"></i>
                                                                    {{ trans('lang.saved') }}
                                                                </a>
                                                            </li>
                                                            
                                                        @else
                                                        
                                                            <li v-cloak>
                                                                <a href="javascrip:void(0);" class="wt-clicklike" id="student-{{$student->id}}" @click.prevent="add_wishlist('student-{{$student->id}}', {{$student->id}}, 'saved_student', '{{trans("lang.saved")}}')">
                                                                    <i class="fa fa-heart"></i>
                                                                    <span class="save_text">{{ trans('lang.click_to_save') }}</span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="wt-rightarea">
                                                    <span class="wt-stars"><span style="width: {{ $stars }}%;"></span></span>
                                                    <span class="wt-starcontent">
                                                        {{{ round($average_rating_count) }}}<sub>{{ trans('lang.5') }}</sub> <em>({{{ $feedbacks }}} {{ trans('lang.feedbacks') }})</em>
                                                    </span>
                                                </div>
                                            </div>
                                            @if (!empty($profil->description))
                                                <div class="wt-description">
                                                    <p>{{{ str_limit($profil->description, 180) }}}</p>
                                                </div>
                                            @endif
                                            @if (!empty($student->skills))
                                                <div class="wt-tag wt-widgettag">
                                                    @foreach($student->skills as $skill)
                                                        <!--<a href="{{{url('search-results?type=job&skills%5B%5D='.$skill->slug)}}}">{{{ $skill->title }}}</a>-->
                                                        
                                                         <a href="javascript:void(0)">{{{ $skill->title }}}</a>
                                                        
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    
                                        @else
                                        @include('errors.no-record')
                                        @endif
                                    
                
                                    
                                    <!--@if ( method_exists($users,'links') )-->
                                    <!--    {{ $users->links('pagination.custom') }}-->
                                    <!--@endif-->
                             
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
        <script>
            if (APP_DIRECTION == 'rtl') {
                var direction = true;
            } else {
                var direction = false;
            }
            
            jQuery("#wt-categoriesslider").owlCarousel({
                item: 6,
                rtl:direction,
                loop:true,
                nav:false,
                margin: 0,
                autoplay:false,
                center: true,
                responsiveClass:true,
                responsive:{
                    0:{items:1,},
                    481:{items:2,},
                    768:{items:3,},
                    1440:{items:4,},
                    1760:{items:6,}
                }
            });
        </script>
    @endpush
@endsection
