@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')
<section class="wt-haslayout wt-dbsectionspace wt-insightuser" id="dashboard">
    @if (Session::has('message'))
    <div class="flash_msg">
        <flash_messages :message_class="'success'" :time ='5' :message="'{{{ Session::get('message') }}}'" v-cloak></flash_messages>
    </div>
    @php session()->forget('message');  @endphp
    @endif
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="wt-insightsitemholder">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="wt-insightsitem wt-dashboardbox {{ $notify_class }}">
                            <figure class="wt-userlistingimg">
                                {{ Helper::getImages('uploads/settings/icon',$latest_new_message_icon, 'smile') }}
                            </figure>
                            <div class="wt-insightdetails">
                                <div class="wt-title">
                                    <h3>{{ trans('lang.new_msgs') }}</h3>
                                    <a href="{{{ route('message') }}}">{{ trans('lang.click_view') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="wt-insightsitem wt-dashboardbox">
                            <figure class="wt-userlistingimg">
                                {{ Helper::getImages('uploads/settings/icon',$latest_saved_item_icon, 'lnr lnr-heart') }}
                            </figure>
                            <div class="wt-insightdetails">
                                <div class="wt-title">
                                    <h3>{{ trans('lang.view_saved_items') }}</h3>
                                    <a href="{{url('student/saved-items')}}">{{ trans('lang.click_view') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($access_type == 'jobs' || $access_type== 'both')
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="wt-insightsitem wt-dashboardbox">
                            <figure class="wt-userlistingimg">
                                {{ Helper::getImages('uploads/settings/icon',$latest_cancel_project_icon, 'users') }}
                            </figure>
                            <div class="wt-insightdetails">
                                <div class="wt-title">
                                    <h3>{{ trans('lang.requests') }}</h3>
                                    <a href="{{{ url('student/dashboard/friend-requests') }}}">{{ trans('lang.click_view') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="wt-insightsitem wt-dashboardbox">
                            <figure class="wt-userlistingimg">
                                {{ Helper::getImages('uploads/settings/icon',$latest_ongoing_project_icon, 'users') }}
                            </figure>
                            <div class="wt-insightdetails">
                                <div class="wt-title">
                                    <h3>{{ trans('lang.friends') }}</h3>
                                    <a href="{{{ url('student/dashboard/friends') }}}">{{ trans('lang.click_view') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="wt-insightsitem wt-dashboardbox">
                            <figure class="wt-userlistingimg">
                                {{ Helper::getImages('uploads/settings/icon',$latest_pending_balance_icon, 'cart') }}
                            </figure>
                            <div class="wt-insightdetails">
                                <div class="wt-title">
                                    <h3>{{ trans('lang.payout_settings') }}</h3>
                                    <a href="{{{ url('student/payout-settings') }}}">{{ trans('lang.click_view') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($access_type == 'services' || $access_type== 'both')
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="wt-insightsitem wt-dashboardbox">
                            <figure class="wt-userlistingimg">
                                {{ Helper::getImages('uploads/settings/icon',$ongoing_services_icon, 'camera-video') }}
                            </figure>
                            <div class="wt-insightdetails">
                                <div class="wt-title">
                                    <h3>{{ trans('lang.book_meeting') }}</h3>
                                    <a href="{{{ url('student/dashboard/book-meeting') }}}">{{ trans('lang.click_view') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="wt-insightsitem wt-dashboardbox">
                            <figure class="wt-userlistingimg">
                                {{ Helper::getImages('uploads/settings/icon',$completed_services_icon, 'camera-video') }}
                            </figure>
                            <div class="wt-insightdetails">
                                <div class="wt-title">
                                    <h3>{{ trans('lang.my_rooms') }}</h3>
                                    <a href="{{{ url('student/dashboard/my-rooms') }}}">{{ trans('lang.click_view') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="wt-insightsitem wt-dashboardbox">
                            <figure class="wt-userlistingimg">
                                {{ Helper::getImages('uploads/settings/icon',$cancelled_services_icon, 'book') }}
                            </figure>
                            <div class="wt-insightdetails">
                                <div class="wt-title">
                                    <h3>{{ trans('lang.courses') }}</h3>
                                    <a href="{{{ url('student/dashboard/courses') }}}">{{ trans('lang.click_view') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="wt-insightsitem wt-dashboardbox">
                           <strong>Coming soon</strong>
                           <figure class="wt-userlistingimg" id="opacity">
                            {{ Helper::getImages('uploads/settings/icon',$published_services_icon, 'file-add') }}
                        </figure>
                        <div class="wt-insightdetails" id="opacity">
                            <div class="wt-title">
                                <h3>{{ trans('lang.my_resources') }}</h3>
                                <!-- <a href="{{{ url('student/dashboard/resources') }}}">{{ trans('lang.click_view') }}</a> -->
                                <a data-toggle="modal" data-target="#exampleModal">{{ trans('lang.click_view') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                    <div class="wt-insightsitem wt-dashboardbox" >
                        <strong>Coming soon</strong>
                        <figure class="wt-userlistingimg" id="opacity">
                            {{ Helper::getImages('uploads/settings/icon',$cancelled_services_icon, 'gift') }}
                        </figure>
                        <div class="wt-insightdetails" id="opacity">
                            <div class="wt-title">
                                <h3>{{ trans('lang.purchased_resources') }}</h3>
                                           <!--  <a href="{{{ url('student/dashboard/purchased-resources') }}}">{{ trans('lang.click_view') }}</a>
                                           -->
                                           <a data-toggle="modal" data-target="#exampleModal">{{ trans('lang.click_view') }}</a>
                                       </div>
                                   </div>
                               </div>
                               @endif
                           </div>

                           <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                            <div class="wt-insightsitem wt-dashboardbox">
                                <strong>Coming soon</strong>
                                <figure class="wt-userlistingimg" id="opacity">
                                 <span class="lnr lnr-graduation-hat"></span>
                             </figure>
                             <div class="wt-insightdetails"  id="opacity">
                                <div class="wt-title">
                                    <h3>Tutoring Service</h3>
                                    <!-- <a href="{{{ url('student/dashboard/tutors') }}}">{{ trans('lang.click_view') }}</a> -->
                                    <a data-toggle="modal" data-target="#exampleModal">{{ trans('lang.click_view') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 float-left">
                <div class="wt-dashboardbox  wt-ongoingproject la-ongoing-projects">
                    <div class="wt-dashboardboxtitle wt-titlewithsearch">
                        <h2>{{ trans('lang.past_earnings') }}</h2>
                    </div>
                    @php
                        $pastearing_check = '';
                        if (Schema::hasTable('services') && Schema::hasTable('service_user')) {
                            $pastearing_check = Helper::getstudentServices('completed', Auth::user()->id, 'completed')->count() > 0;
                        }
                    @endphp
                    @if ((!empty($completed_projects_history) && $completed_projects_history->count() > 0) || $pastearing_check)
                        @php
                            $commision = \App\SiteManagement::getMetaValue('commision');
                            $admin_commission = !empty($commision[0]['commision']) ? $commision[0]['commision'] : 0;
                        @endphp
                        <div class="wt-dashboardboxcontent wt-hiredfreelance">
                            <table class="wt-tablecategories">
                                <thead>
                                    <tr>
                                        <th>{{trans('lang.resource_title')}}</th>
                                        <th>{{trans('lang.date')}}</th>
                                        <th>{{trans('lang.earnings')}}</th>
                                    </tr>
                                </thead>
                                @if ($access_type == 'jobs' || $access_type== 'both')
                                    @if (!empty($completed_projects_history) && $completed_projects_history->count() > 0)
                                        <tbody>
                                            @foreach ($completed_projects_history as $projects)
                                                @php
                                                    $project = \App\Proposal::find($projects->id);
                                                    $user_name = Helper::getUsername($project->job->user_id);
                                                    $amount = !empty($project->amount) ? $project->amount - ($project->amount / 100) * $admin_commission : 0;
                                                @endphp
                                                <tr class="wt-earning-contents">
                                                    <td class="wt-earnig-single" data-th="Project Title"><span class="bt-content">{{{ $project->job->title }}}</span></td>
                                                    <td data-th="Date"><span class="bt-content">{{$project->updated_at}}</span></td>
                                                    <td data-th="Earnings"><span class="bt-content">{{ !empty($symbol['symbol']) ? $symbol['symbol'] : '$' }}{{{$amount}}}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                @endif
                                @if ($access_type == 'services' || $access_type == 'both')
                                    @if (Helper::getstudentServices('completed', Auth::user()->id)->count() > 0)
                                        <tbody>
                                            @foreach (Helper::getstudentServices('completed', Auth::user()->id) as $service)
                                                @php
                                                    $student = Helper::getServiceSeller($service->id);
                                                    $user_name = !empty($student) ? Helper::getUsername($student->seller_id) : '';
                                                    $amount = !empty($service->price) ? $service->price - ($service->price / 100) * $admin_commission : 0;
                                                @endphp
                                                <tr class="wt-earning-contents">
                                                    <td class="wt-earnig-single" data-th="Project Title"><span class="bt-content">{{{ $service->title }}}</span></td>
                                                    <td data-th="Date"><span class="bt-content">{{$service->updated_at}}</span></td>
                                                    <td data-th="Earnings"><span class="bt-content">{{ !empty($symbol['symbol']) ? $symbol['symbol'] : '$' }}{{{$amount}}}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                @endif
                            </table>
                        </div>
                    @else
                        @if (file_exists(resource_path('views/extend/errors/no-record.blade.php'))) 
                            @include('extend.errors.no-record')
                        @else 
                            @include('errors.no-record')
                        @endif
                    @endif
                </div>
            </div>
        </div> -->


       


</section>
@endsection
