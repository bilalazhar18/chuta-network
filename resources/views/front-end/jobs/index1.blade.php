@extends(file_exists(resource_path('views/extend/front-end/master.blade.php')) ? 
'extend.front-end.master':
'front-end.master', ['body_class' => 'wt-innerbgcolor'] )

@section('content')


    <div class="wt-haslayout wt-innerbannerholder" style="background-image:url({{{asset('images/bannerimg/img-02.jpg') }}})">

   <div class="container">
      <div class="row justify-content-md-center">
         <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
            <div class="wt-innerbannercontent">
               <div class="wt-title">
                  <h2>{{ trans('lang.jobs') }}</h2>
               </div>
          {{--      @if (!empty($show_breadcrumbs) && $show_breadcrumbs === 'true')
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

<div class="wt-haslayout wt-main-section">
   
   @if (Session::has('payment_message'))
   @php $response = Session::get('payment_message') @endphp
   <div class="flash_msg">
      <flash_messages :message_class="'{{{$response['code']}}}'" :time ='5' :message="'{{{ $response['message'] }}}'" v-cloak></flash_messages>
   </div>
   @endif
   <div class="wt-haslayout">
      <div class="container zahraaaa">
         <div class="row">
            <div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
               <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
                            @if (file_exists(resource_path('views/extend/front-end/jobs/filters.blade.php'))) 
                                @include('extend.front-end.jobs.filters')
                            @else 
                                @include('front-end.jobs.filters')
                            @endif
                        </div>
               <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
                  <div class="wt-userlistingholder wt-haslayout">
                 
                     @if (!empty($jobs) && $jobs->count() > 0)
                     @foreach ($jobs as $job)

                    
                     <div class="wt-userlistinghold wt-userlistingholdvtwo">
                        
                        <div class="wt-userlistingcontent">
                           <div class="wt-contenthead">
                              <div class="wt-title">
                                 
                                 <a href="javascrip:void(0)"><i class="fa fa-check-circle"></i> {{{ Helper::getUserName($job->employer->id) }}}</a>
                                 <h2><a href="{{url('job/'.$job->slug)}}">{{{$job->title}}}</a></h2>
                              </div>
                              <div class="wt-description">
                                 <p>{{ str_limit(stripslashes(strip_tags($job->description)), 200) }}</p>
                              </div>
                              
                               <div class="wt-tag wt-widgettag">
                                        
                                        
                                        <?php
                                        if(!empty($job->course_id))
                                        {
                                        $getrecord = DB::table('skills')->whereIn('id',explode(",", $job->course_id))->get();
                                        foreach($getrecord as $val)
                                        {
                                        
                                        ?>
                                        
                                        <!--<a href="{{{url('search-results?type=job&skills%5B%5D='.$val->slug)}}}">{{$val->title}}</a>-->
                                        <a href="javascript:void(0)">{{$val->title}}</a>
                                        <?php
                                        }
                                        }
                                        ?>
                                        
                                        </div>
                              
                           </div>
                           <div class="wt-viewjobholder">
                              <ul>
                                
                                 @if (!empty($job->location->title))
                                 <li><span><img src="{{{asset(Helper::getLocationFlag($job->location->flag))}}}" alt="{{{ trans('lang.location') }}}"> {{{ $job->location->title }}}</span></li>
                                 @endif
                                 
                                 @php
                                   $faculty  = Helper::getFacultyName($job->faculty);
                                   @endphp
                                  @if(!empty($faculty))
                                              <li><span><i class="fas fa-industry"></i>{{$faculty}}</span></li>
                                              @endif
                                 
                                 
                                  <li><span><i class="far fa-folder wt-viewjobfolder"></i>{{{$job->worktype}}}</span></li>
                               
                               
                                    <li><span><i class="far fa-clock wt-viewjobclock"></i>{{ucwords($job->paytype)}}</span></li>
                                 
                                 <li><span><i class="fa fa-tag wt-viewjobtag"></i>{{{ trans('lang.job_id') }}} {{{$job->code}}}</span></li>
                                 
                                  @if (!empty($user->profile->saved_jobs) && in_array($job->id, unserialize($user->profile->saved_jobs)))
                                        <li style=pointer-events:none;><a href="javascript:void(0);" class="wt-clicklike wt-clicksave"><i class="fa fa-heart"></i> {{trans("lang.saved")}}</a></li>
                                                                @else
                                                                    <li>
                                                                        <a href="javascrip:void(0);" class="wt-clicklike" id="job-{{$job->id}}" @click.prevent="add_wishlist('job-{{$job->id}}', {{$job->id}}, 'saved_jobs', '{{trans("lang.saved")}}')" v-cloak>
                                                                            <i class="fa fa-heart"></i>
                                                                            <span class="save_text">{{ trans('lang.click_to_save') }}</span>
                                                                        </a>
                                                                    </li>
                                                                @endif
                                 
                               
                               
                                 <li class="wt-btnarea"><a href="{{url('job/'.$job->slug)}}" class="wt-btn">{{{ trans('lang.view_job') }}}</a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                   
                     @endforeach
                     @if ( method_exists($jobs,'links') )
                     {{ $jobs->links('pagination.custom') }}
                     @endif
                     @else
                     @if (file_exists(resource_path('views/extend/errors/no-record.blade.php'))) 
                     @include('extend.errors.no-record')
                     @else 
                     @include('errors.no-record')
                     @endif
                     @endif
                  </div>
               </div>
            </div>
           
           
         </div>
      </div>
   </div>
</div>
@endsection
