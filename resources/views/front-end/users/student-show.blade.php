@extends(file_exists(resource_path('views/extend/front-end/master.blade.php')) ? 
'extend.front-end.master':
 'front-end.master', ['body_class' => 'wt-innerbgcolor'] )
@push('stylesheets')
    <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
      <style type="text/css">
        .wt-skillholder {
    float: left;
    width: 100%;
    margin-left: 10px;
}
    </style>
@endpush
@section('title'){{ $user_name }} | {{ $tagline }} @stop
@section('description', "$desc")
@section('content')
    <div class="wt-haslayout wt-innerbannerholder wt-innerbannerholdervtwo" style="background-image: url({{{ asset(Helper::getUserProfileBanner($user->id)) }}});">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
                </div>
            </div>
        </div>
    </div>
    <div class="wt-main-section wt-paddingtopnull wt-haslayout la-profile-holder" id="user_profile">
        <div class="preloader-section" v-if="loading" v-cloak>
            <div class="preloader-holder">
                <div class="loader"></div>
            </div>
        </div>
        @if ($display_chat == 'true')
            @if (Auth::user())
                @if ($profile->user_id != Auth::user()->id)
                    <chat :trans_image_alt="'{{trans('lang.img')}}'" :ph_new_msg="'{{ trans('lang.ph_new_msg') }}'" :trans_placeholder="'{{ trans('lang.ph_type_msg') }}'" :receiver_id="'{{$profile->user_id}}'" :receiver_profile_image="'{{{ asset($avatar) }}}'"></chat>
                @endif
            @endif
        @endif
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
                    <div class="wt-userprofileholder">
                        @if (!empty($badge) && !empty($enable_package) && $enable_package === 'true')
                            <span class="wt-featuredtag" style="border-top: 40px solid {{ $badge_color }};">
                                <img src="{{{ asset(Helper::getBadgeImage($badge_img)) }}}" alt="{{ trans('lang.is_featured') }}" data-tipso="Plus Member" class="template-content tipso_style">
                            </span>
                        @endif
                        <div class="col-12 col-sm-12 col-md-12 col-lg-3 float-left">
                            <div class="row">
                                <div class="wt-userprofile">
                                    @if (!empty($avatar))
                                        {{-- <figure><img src="{{{ asset($avatar) }}}" alt="{{{ trans('lang.user_avatar') }}}"></figure> --}}
                                        <figure><img src="{{{ asset(Helper::getImage('uploads/users/' . $profile->user_id,$profile->avater, '' , 'user.jpg')) }}}" alt="{{{ trans('lang.user_avatar') }}}"></figure>
                                    @endif
                                    <div class="wt-title">
                                        @if (!empty($user_name))
                                            <h3>@if ($user->user_verified === 1)<i class="fa fa-check-circle"></i> @endif {{{ $user_name }}}</h3>
                                        @endif
                                        <span>
                                            <div class="wt-proposalfeedback"><span class="wt-starcontent"> {{{ round($average_rating_count) }}}/<i>5</i>&nbsp;<em>({{{ $reviews->count() }}} {{ trans('lang.feedbacks') }})</em></span></div>
                                            @if (!empty($joining_date))
                                                {{{ trans('lang.member_since') }}}&nbsp;{{{ $joining_date }}}
                                            @endif
                                            <br>
                                            <a href="{{url('profile/'.$user->slug)}}">{{ '@' }}{{{ $user->slug }}}</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-9 float-left">
                            <div class="row">
                                <div class="wt-proposalhead wt-userdetails">
                                    @if (!empty($profile->tagline))
                                        <h2>{{{ $profile->tagline }}}</h2>
                                    @endif
                                    <ul class="wt-userlisting-breadcrumb wt-userlisting-breadcrumbvtwo">
                                         @if (!empty($profile->cgpa))
                                            <li><span><i class="fas fa-graduation-cap"></i>{{{ $profile->cgpa }}}</span></li>
                                        @endif
                                        @if (!empty($user->location->title))
                                            <li>
                                                <span>
                                                    <img src="{{{asset(Helper::getLocationFlag($user->location->flag))}}}" alt="{{{ trans('lang.flag_img') }}}"> {{{ $user->location->title }}}
                                                </span>
                                            </li>
                                        @endif
                                        @if (in_array($profile->id, $save_student))
                                            <li class="wt-btndisbaled">
                                                <a href="javascrip:void(0);" class="wt-clicksave wt-clicksave">
                                                    <i class="fa fa-heart"></i>
                                                    {{ trans('lang.saved') }}
                                                </a>
                                            </li>
                                        @else
                                            <li v-bind:class="disable_btn" v-cloak>
                                                <a href="javascrip:void(0);" v-bind:class="click_to_save" id="student-{{$profile->id}}" @click.prevent="add_wishlist('student'-{{$profile->id}}, {{$profile->id}}, 'saved_student', '{{trans("lang.saved")}}')" v-cloak>
                                                    <i v-bind:class="saved_class"></i>
                                                    @{{ text }}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                    @if (!empty($profile->description))
                                        <div class="wt-description">
                                            <p>{{{ $profile->description }}}</p>
                                        </div>
                                    @endif
                                </div>
                                <div id="wt-statistics" class="wt-statistics wt-profilecounter">
                                    <div class="wt-statisticcontent wt-countercolor1">
                                     {{--    @php
                                        $getresource = DB::table('resources')->where('user_id', Auth::user()->id)->count();
                                        $sumresource = DB::table('resources')->where('user_id', Auth::user()->id)->sum('price');
                                        $studentconn = DB::table('offers')->select('is_friend')->where('user_id', Auth::user()->id)->first();
                                        $ty=DB::table('transactions')->where('seller_id',Auth::user()->id)->sum('total_price');
                                        dd($ty);
                                        @endphp --}}
                                    <h3 id="opacity" data-from="0" data-to="{{{ Helper::getsresource($user->id)->count() }}}" data-speed="800" data-refresh-interval="03">{{{ Helper::getsresource($user->id)->count() }}}</h3>
                                        <h4>Uploaded Resources</h4>
                                    </div>
                                    <div id="opacity" class="wt-statisticcontent wt-countercolor2">
                                        <h3 data-from="0" data-to="{{{ Helper::soldresource($user->id)->count() }}}" data-speed="8000" data-refresh-interval="100">{{{ Helper::soldresource($user->id)->count() }}}</h3>
                                        <h4>Sold Resources</h4>
                                    </div>
                                    <div class="wt-statisticcontent wt-countercolor4">
                                        <h3 data-from="0" data-to="{{{ Helper::numconnection($user->id)}}}" data-speed="800" data-refresh-interval="02">{{{ Helper::numconnection($user->id)}}}</h3>
                                        <h4>Number of Connections</h4>
                                    </div>
                                    <div id="opacity" class="wt-statisticcontent wt-countercolor3">
                                        <h3 data-from="0" data-to="{{{ Helper::getsresourceprice($user->id)}}}" data-speed="8000" data-refresh-interval="100">$ {{{ Helper::getsresourceprice($user->id)}}}</h3>
                                        <h4>Resources sold</h4>
                                    </div>
                                    
                                    
                                    @if(!empty(Auth::user()->id))
                                       
                                            <div class="wt-description">
                                                @if ($acceptStatus == 0)
                                                 @if (Auth::user()->id != $profile->user_id)
                                                
                                                    <p id='send-offer-note'>{{ trans('lang.send_offer_note') }}</p>
                                                    <p style='display:none' id='offer-sent-note'>{{ trans('lang.sent_offer_note') }} {{$user_name}}</p>
                                                    <a href="javascript:void(0);" @click.prevent='sendOffer("{{$auth_user}}")' id='sending-request-button' class="wt-btn">{{{ trans('lang.btn_send_offer') }}}</a>
                                                 
                                                    @else
                                                   
                                                     <a href="{{url('student/profile')}}" class="wt-btn">update profile</a>

                                                    @endif

                                                @elseif ($acceptStatus == 1)
                                                  <p id='send-offer-note'>{{ trans('lang.send_offer_note') }}</p>
                                                    <p style='display:none' id='offer-sent-note'>{{ trans('lang.sent_offer_note') }} {{$user_name}}</p>
                                                    <a href="javascript:void(0);" @click.prevent='sendOffer("{{$auth_user}}")' id='sending-request-button' class="wt-btn">{{{ trans('lang.btn_send_offer') }}}</a>
                                                    <!--<p>{{ trans('lang.sent_offer_note') }} {{$user_name}}</p>-->
                                                    <!--<a href="javascript:void(0);" class="wt-btn" style="pointer-events : none;">{{{ trans('lang.btn_sent_offer') }}}</a>-->
                                                @elseif ($acceptStatus == 2)
                                                    <a id='offer-accepted' href="javascript:void(0);" class="wt-btn" style="pointer-events : none;">{{{ trans('lang.friend') }}}</a>
                                                @else ($acceptStatus == 3)
                                                    <div id='accept-reject'>
                                                        <div class="accept-from-profile">
                                                            <p>Accept</p>
                                                            <a href="javascript:void(0);" class="wt-addinfo"  @click.prevent="acceptOrRejectRequest('{{ $requestId }}', 1)" ><i class="fa fa-check fa-3x"></i></a>
                                                        </div>
                                                        <div class="reject-from-profile">
                                                            <p>Reject</p>
                                                            <a href="javascript:void(0);" class="wt-deleteinfo delete-skill" @click.prevent="acceptOrRejectRequest('{{ $requestId }}', 0)"><i class="fa fa-trash fa-3x"></i></a>
                                                        </div>
                                                    </div>
                                                    <p id='send-offer-note-after-rejection'>{{ trans('lang.request_rejected') }}</p>
                                                    <p id='offer-accepted-by-student'>{{ trans('lang.request_accepted') }}</p>
                                                @endif

                                            </div>
                                      
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
           
              @if (!empty($resources) && $resources->count() > 0)
                <div class="container">
                    <div class="row">	
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left" id="opacity">
                            <div class="wt-services-holder">
                                <div class="wt-title">
                                    <h2>Resources</h2>
                                </div>
                                <div class="wt-services-content">
                                    <div class="row">
                                        
                                         @foreach ($resources as $resource)
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 float-left">
                                                <div class="wt-students-info">
                                       
                                                        <span class="wt-featuredtagvtwo">{{ trans('lang.featured') }}</span>
                                                   
                                                    <div class="wt-students-details">
                                                        <figure class="wt-students-img">
                                                            <img src="{{ asset(Helper::getProfileImage($user->id)) }}" alt="img description">
                                                        </figure>
                                                        <div class="wt-students-content">
                                                           <div class="dc-title">
                                                                       <!--  <a href="{{url('resource/'.$resource->slug)}}"> -->
                                                                <a href="" data-toggle="modal" data-target="#exampleModal">
                                                                            <h4 class="title-resource">{{{$resource->title}}}</h2>
                                                                        </a>
                                                                        <p>Subject name: {{{$resource->course_name}}}</p>
                                                                        <p>Author: {{{$resource->user_name}}}</p>
                                                                        <p>Description: {{{$resource->description}}}</p>
                                                                        <span><strong>$ {{{$resource->price}}}</strong> </span>
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
                                                                           <!--  <a href="javascript:void(0)" 
                                                                             @click.prevent='sendreview("{{$resource->slug}}")' 
                                                                             style="color: #fecb02;"> reviews</a> -->
                                                                             <a href="javascript:void(0)"  
                                                                             style="color: #fecb02;"> reviews</a>

                                                                             )
                                                                             @else
                                                                              <a href="javascript:void(0)" 
                                                                              
                                                                             style="color: #fecb02;"> reviews</a>)
                                                                             @endif
                                                                            </i></span>
                                                                        </li>

                                                                    </ul>
                                                                </div>

                                <b-modal ref="myModalRe-{{$resource->slug}}" hide-footer title="Resource Reviews">
                                 
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        
        
        
        
        
        
        
        <div class="container">
            <div class="row">
                <div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7 col-xl-8 float-left">
                        <div class="wt-usersingle">
                            {{-- <div class="wt-clientfeedback la-no-record">
                                <div class="wt-usertitle wt-titlewithselect">
                                    <h2>{{ trans('lang.client_feedback') }}</h2>
                                </div>
                                @if (!empty($reviews) && $reviews->count() > 0)
                                    @foreach ($reviews as $key => $review)
                                        @php
                                            $user = App\User::find($review->user_id);
                                            $stars  = $review->avg_rating != 0 ? $review->avg_rating/5*100 : 0;
                                        @endphp
                                        @if ($review->project_type == 'job')
                                            @php $job = \App\Job::where('id', $review->job_id)->first(); @endphp
                                            @if (!empty($job->employer) && $job->employer->count() > 0)
                                                <div class="wt-userlistinghold wt-userlistingsingle">
                                                    <figure class="wt-userlistingimg">
                                                        <img src="{{ asset(Helper::getProfileImage($review->user_id)) }}" alt="{{{ trans('Employer') }}}">
                                                    </figure>
                                                    <div class="wt-userlistingcontent">
                                                        <div class="wt-contenthead">
                                                            <div class="wt-title">
                                                                <a href="{{{ url('profile/'.$job->employer->slug) }}}">@if ($user->user_verified === 1)<i class="fa fa-check-circle"></i>@endif {{{ Helper::getUserName($review->user_id) }}}</a>
                                                                <h3>{{{ $job->title }}}</h3>
                                                            </div>
                                                            <ul class="wt-userlisting-breadcrumb">
                                                                <li><span><i class="fa fa-dollar-sign"></i><i class="fa fa-dollar-sign"></i> {{{ \App\Helper::getProjectLevel($job->project_level) }}}</span></li>
                                                                @if (!empty($job->location) && $job->location->count() > 0)
                                                                    <li>
                                                                        <span>
                                                                            <img src="{{{asset(App\Helper::getLocationFlag($job->location->flag))}}}" alt="{{{ trans('lang.flag_img') }}}"> {{{ $job->location->title }}}
                                                                        </span>
                                                                    </li>
                                                                @endif
                                                                <li><span><i class="far fa-calendar"></i> {{ Carbon\Carbon::parse($job->created_at)->format('M Y') }} - {{ Carbon\Carbon::parse($job->updated_at)->format('M Y') }}</span></li>
                                                                <li>
                                                                    <span class="wt-stars"><span style="width: {{ $stars }}%;"></span></span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="wt-description">
                                                        @if (!empty($review->feedback))
                                                            <p>“ {{{ $review->feedback }}} ”</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            @if (Helper::getAccessType() == 'both' || Helper::getAccessType() == 'services')
                                                @php $service = \App\Service::where('id', $review->service_id)->first(); @endphp    
                                                @if (!empty($service))
                                                    <div class="wt-userlistinghold wt-userlistingsingle">
                                                        <figure class="wt-userlistingimg">
                                                            <img src="{{ asset(Helper::getProfileImage($review->user_id)) }}" alt="{{{ trans('Employer') }}}">
                                                        </figure>
                                                        <div class="wt-userlistingcontent">
                                                            <div class="wt-contenthead">
                                                                <div class="wt-title">
                                                                    <a href="{{{ url('profile/'.$user->slug) }}}">@if ($user->user_verified == 1)<i class="fa fa-check-circle"></i>@endif {{{ Helper::getUserName($review->user_id) }}}</a>
                                                                    <h3>{{{ $service->title }}}</h3>
                                                                </div>
                                                                <ul class="wt-userlisting-breadcrumb">
                                                                    @if (!empty($service->location))
                                                                        <li>
                                                                            <span>
                                                                                <img src="{{{asset(Helper::getLocationFlag($service->location->flag))}}}" alt="{{{ trans('lang.flag_img') }}}"> {{{ $service->location->title }}}
                                                                            </span>
                                                                        </li>
                                                                    @endif
                                                                    <li><span><i class="far fa-calendar"></i> {{ Carbon\Carbon::parse($service->created_at)->format('M Y') }} - {{ Carbon\Carbon::parse($service->updated_at)->format('M Y') }}</span></li>
                                                                    <li>
                                                                        <span class="wt-stars"><span style="width: {{ $stars }}%;"></span></span>
                                                                    </li> 
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="wt-description">
                                                            @if (!empty($review->feedback))
                                                                <p>“ {{{ $review->feedback }}} ”</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    <div class="wt-userprofile">
                                        @if (file_exists(resource_path('views/extend/errors/no-record.blade.php'))) 
                                            @include('extend.errors.no-record')
                                        @else 
                                            @include('errors.no-record')
                                        @endif
                                    </div>
                                @endif
                            </div> --}}
                            <!--<div class="wt-craftedprojects">-->
                            <!--    <div class="wt-usertitle">-->
                            <!--        <h2>{{{ trans('lang.crafted_projects') }}}</h2>-->
                            <!--    </div>-->
                            <!--    @if (!empty($projects))-->
                            <!--        <crafted_project :no_of_post="3" :project="'{{  json_encode($projects) }}'" :freelancer_id="'{{$profile->user_id}}'" :img="'{{ trans('lang.img') }}'"></crafted_project>-->
                            <!--    @else-->
                            <!--        <div class="wt-userprofile">-->
                            <!--            @if (file_exists(resource_path('views/extend/errors/no-record.blade.php'))) -->
                            <!--                @include('extend.errors.no-record')-->
                            <!--            @else -->
                            <!--                @include('errors.no-record')-->
                            <!--            @endif-->
                            <!--        </div>-->
                            <!--    @endif-->
                            <!--</div>-->
                            @if (!empty($videos))
                                <div class="wt-videos">
                                    <div class="wt-usertitle">
                                        <h2>{{{ trans('lang.videos') }}}</h2>
                                    </div>
                                    <div class="wt-user-videos">
                                        @foreach ($videos as $video)
                                            @php 
                                                $width 	= 367;
                                                $height = 206;
                                                $url = parse_url($video['url']);
                                            @endphp
                                            @if (!empty($url) && !empty($url['query']))
                                                <figure>
                                                    @php
                                                        if ( isset( $url['host'] ) && ( $url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com' ) ) {
                                                            $content_exp = explode("/", $media);
                                                            $content_vimo = array_pop($content_exp);
                                                            echo '<iframe width="' . intval($width) . '" height="' . intval($height) . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
                                                    ></iframe>';
                                                        } elseif ( isset( $url['host'] ) && $url['host'] == 'soundcloud.com') {
                                                            $video = wp_oembed_get($media, array('height' => intval($height)));
                                                            $search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="no"', 'scrolling="no"');
                                                            $video = str_replace($search, '', $video);
                                                            echo str_replace('&', '&amp;', $video);
                                                        } else {
                                                            echo '<iframe width="'.$width.'" height="'.$height.'" src="https://www.youtube.com/embed/'.str_replace("v=", '', $url['query']).'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                                        }
                                                    @endphp
                                                
                                                </figure>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="wt-experience">
                                <div class="wt-usertitle">
                                    <h2>{{{ trans('lang.experience') }}}</h2>
                                </div>
                                @if (!empty($experiences))

                                   {{--  <div class="wt-experiencelisting-hold">
                                        <experience :freelancer_id="'{{$profile->user_id}}'" :no_of_post="2"></experience>
                                    </div> --}}

                                    <div class="wt-userprofile">
                                        @foreach ($experiences as $val)
                                            <div class="wt-skillholder" data-percent="{{{ $val['job_title'] }}}">
                                                <span>{{$val['job_title']}} ({{date("d-m-Y",strtotime($val['start_date']))}}  {{date("d-m-Y",strtotime($val['end_date']))}})</span>
                                                <div class="wt-skillbarholder"><div class="wt-skillbar"></div></div>
                                            </div>
                                        @endforeach
                                    </div>


                                @else
                                    <div class="wt-userprofile">
                                        @if (file_exists(resource_path('views/extend/errors/no-record.blade.php'))) 
                                            @include('extend.errors.no-record')
                                        @else 
                                            @include('errors.no-record')
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="wt-experience wt-education">
                                <div class="wt-usertitle">
                                    <h2>{{ trans('lang.my_skills') }}</h2>
                                </div>

                             {{--    <div class="wt-userprofile"><div class="wt-emptydata-holder"><div class="wt-emptydata"><div class="wt-emptydetails wt-empty-person"><img src="http://127.0.0.1:8000/images/empty-images/no-record.png"> <em>No Record Found</em></div></div></div></div> --}}
                                
                                  @if (!empty($skills) && $skills->count() > 0)
                                    <div class="wt-userprofile">
                                        @foreach ($skills as $skill)
                                            <div class="wt-skillholder" data-percent="{{{ $skill->pivot->skill_rating }}}">
                                                <span>{{{ $skill->title }}}</span>
                                                <div class="wt-skillbarholder"><div class="wt-skillbar"></div></div>
                                            </div>
                                        @endforeach
                                    </div>

                                @else
                                    <div class="wt-userprofile">
                                        @if (file_exists(resource_path('views/extend/errors/no-record.blade.php'))) 
                                            @include('extend.errors.no-record')
                                        @else 
                                            @include('errors.no-record')
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5 col-xl-4 float-left">
                        <aside id="wt-sidebar" class="wt-sidebar">
                            <div id="wt-ourskill" class="wt-widget">
                                <div class="wt-widgettitle">
                                    <h2>{{ trans('lang.my_skills') }}</h2>
                                </div>
                                @if (!empty($skills) && $skills->count() > 0)
                                    <div class="wt-widgetcontent wt-skillscontent">
                                        @foreach ($skills as $skill)
                                            <div class="wt-skillholder" data-percent="{{{ $skill->pivot->skill_rating }}}">
                                                <span>{{{ $skill->title }}}</span>
                                                <div class="wt-skillbarholder"><div class="wt-skillbar"></div></div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p>{{ trans('lang.no_skills') }}</p>
                                @endif
                            </div>
                            @if (!empty($awards))
                                <div class="wt-widget wt-widgetarticlesholder wt-articlesuser">
                                    <div class="wt-widgettitle">
                                        <h2>{{{ trans('lang.awards_certifications') }}}</h2>
                                    </div>
                                    <div class="wt-widgetcontent wt-verticalscrollbar">
                                        @foreach ($awards as $award)
                                            <div class="wt-particlehold">
                                                @if (!empty($award['award_hidden_image']))
                                                    <figure>
                                                        <img src="{{{ asset('uploads/users/'.$profile->user_id.'/awards/'.$award['award_hidden_image']) }}}" alt="{{ trans('lang.img') }}">
                                                    </figure>
                                                @endif
                                                @if (!empty($award['award_title']))
                                                    <div class="wt-particlecontent">
                                                        <h3><a href="javascrip:void(0);">{{{ $award['award_title'] }}}</a></h3>
                                                        <span><i class="lnr lnr-calendar"></i> {{{ $joining_date }}}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="wt-proposalsr">
                                <div class="tg-authorcodescan tg-authorcodescanvtwo">
                                    <figure class="tg-qrcodeimg">
                                        {!! QrCode::size(100)->generate(Request::url('profile/'.$user->slug)); !!}
                                    </figure>
                                    <div class="tg-qrcodedetail">
                                        <span class="lnr lnr-laptop-phone"></span>
                                        <div class="tg-qrcodefeat">
                                            <h3>{{ trans('lang.scan_with_smartphone') }} <span>{{ trans('lang.smartphone') }} </span> {{ trans('lang.get_handy') }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="wt-widget wt-sharejob">
                                <div class="wt-widgettitle">
                                    <h2>{{ trans('lang.share_student') }}</h2>
                                </div>
                                <div class="wt-widgetcontent">
                                    <ul class="wt-socialiconssimple">
                                        <li class="wt-facebook">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" class="social-share">
                                            <i class="fa fa fa-facebook-f"></i>{{ trans('lang.share_fb') }}</a>
                                        </li>
                                        <li class="wt-twitter">
                                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}" class="social-share">
                                            <i class="fa fab fa-twitter"></i>{{ trans('lang.share_twitter') }}</a>
                                        </li>
                                        <li class="wt-pinterest">
                                            <a href="//pinterest.com/pin/create/button/?url={{ urlencode(Request::fullUrl()) }}"
                                            onclick="window.open(this.href, \'post-share\',\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;">
                                            <i class="fa fab fa-pinterest-p"></i>{{ trans('lang.share_pinterest') }}</a>
                                        </li>
                                        <li class="wt-googleplus">
                                            <a href="https://plus.google.com/share?url={{ urlencode(Request::fullUrl()) }}" class="social-share">
                                            <i class="fa fab fa-google-plus-g"></i>{{ trans('lang.share_google') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="wt-widget wt-reportjob">
                                <div class="wt-widgettitle">
                                    <h2>{{ trans('lang.report_user') }}</h2>
                                </div>
                                <div class="wt-widgetcontent">
                                    {!! Form::open(['url' => '', 'class' =>'wt-formtheme wt-formreport', 'id' => 'submit-report',  '@submit.prevent'=>'submitReport("'.$profile->user_id.'","student-report")']) !!}
                                        <fieldset>
                                            <div class="form-group">
                                                <span class="wt-select">
                                                    {!! Form::select('reason', \Illuminate\Support\Arr::pluck($reasons, 'title'), null ,array('class' => '', 'placeholder' => trans('lang.select_reason'), 'v-model' => 'report.reason')) !!}
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::textarea( 'description', null, ['class' =>'form-control', 'placeholder' => trans('lang.ph_desc'), 'v-model' => 'report.description'] ) !!}
                                            </div>
                                            <div class="form-group wt-btnarea">
                                                {!! Form::submit(trans('lang.btn_submit'), ['class' => 'wt-btn']) !!}
                                            </div>
                                        </fieldset>
                                    {!! form::close(); !!}
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
		<b-modal ref="myModalRef" hide-footer title="Send Friend Request">
            <div class="d-block text-center">
                {!! Form::open(['url' => '', 'class' =>'wt-formtheme wt-userform', 'id' =>'send-offer-form', '@submit.prevent'=>'submitProjectOffer("'.$profile->user_id.'")'])!!}
                    <div class="wt-formtheme wt-formpopup">
                        <fieldset>
                            <div class="form-group">
                                {{{ Form::textarea('desc', null, array('placeholder' => trans('lang.ph_add_desc'))) }}}
                            </div>
                            <div class="form-group wt-btnarea">
                                {!! Form::submit(trans('lang.btn_send_offer'), ['class' => 'wt-btn']) !!}
                            </div>
                        </fieldset>
                    </div>
                {!! Form::close() !!}
            </div>
        </b-modal>


        

    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('js/readmore.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/countTo.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/appear.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script>
        /* studentS SLIDER */
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

        var _readmore = jQuery('.wt-userdetails .wt-description');
        _readmore.readmore({
            speed: 500,
            collapsedHeight: 230,
            moreLink: '<a class="wt-btntext" href="#">'+readmore_trans+'</a>',
            lessLink: '<a class="wt-btntext" href="#">'+less_trans+'</a>',
        });
        $('#wt-ourskill').appear(function () {
            jQuery('.wt-skillholder').each(function () {
                jQuery(this).find('.wt-skillbar').animate({
                    width: jQuery(this).attr('data-percent')
                }, 2500);
            });
        });
        var popupMeta = {
            width: 400,
            height: 400
        }
        $(document).on('click', '.social-share', function(event){
            event.preventDefault();

            var vPosition = Math.floor(($(window).width() - popupMeta.width) / 2),
                hPosition = Math.floor(($(window).height() - popupMeta.height) / 2);

            var url = $(this).attr('href');
            var popup = window.open(url, 'Social Share',
                'width='+popupMeta.width+',height='+popupMeta.height+
                ',left='+vPosition+',top='+hPosition+
                ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

            if (popup) {
                popup.focus();
                return false;
            }
        });


        function showuser_detail(e)
{
  var id=$(e).attr('data-id');
  $.ajax({

          url:'{{url('view_admin')}}',
          data:{id:id},
          type:'GET',
          dataType: "json",
          success:function(data)
          {
            console.log(data.data);
            // $('#listingofdaysorders').html(iq);
            $('#firstname').html(data.data.firstname);
            $('#lasttname').html(data.data.lastname);
            $('#username').html(data.data.username);
            $('#email').html(data.data.email);
         
            $('#status').html(data.data.status);
             $('#modal-lg').modal('show');


          }

});
}


    </script>
@endpush

