@extends(file_exists(resource_path('views/extend/front-end/master.blade.php')) ?
'extend.front-end.master':
 'front-end.master', ['body_class' => 'wt-innerbgcolor'] )
@section('title'){{ $job->title }} @stop
@section('description', "$job->description")
@section('content')
    @php $breadcrumbs = Breadcrumbs::generate('jobDetail',$job->slug); @endphp
    <div class="wt-haslayout wt-innerbannerholder">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
                    <div class="wt-innerbannercontent">
                    <div class="wt-title"><h2>{{ trans('lang.job_detail') }}</h2></div>
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
    <div class="wt-haslayout wt-main-section">
        <div class="container">
            <div class="row">
                <div class="job-single wt-haslayout">
                    <div id="jobs" class="wt-twocolumns wt-haslayout">
                        @if (Session::has('error'))
                            <div class="flash_msg">
                                <flash_messages :message_class="'danger'" :time='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
                            </div>
                        @endif
                        @if (!empty($job))
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-left">
                                <div class="wt-proposalholder">
                                    @if (!empty($job->is_featured) && $job->is_featured === 'true')
                                        <span class="wt-featuredtag"><img src="{{{ asset('images/featured.png') }}}" alt="{{ trans('lang.img') }}" data-tipso="Plus Member" class="template-content tipso_style"></span>
                                    @endif
                                    @if (
                                        !empty($job->professional_level) ||
                                        !empty($job->title) ||
                                        !empty($location['title'])  ||
                                        !empty($job->project_type) 
                                        )
                                        <div class="wt-proposalhead">
                                            @if (!empty($job->title))
                                                <h2>{{{ $job->title }}}</h2>
                                            @endif
                                            
                                                <ul class="wt-userlisting-breadcrumb wt-userlisting-breadcrumbvtwo">
                                                   

                                                    @if (!empty($job->location))
                                                        <li><span><img src="{{{asset(Helper::getLocationFlag($job->location->flag))}}}" alt="{{ trans('lang.img') }}"> {{{ $job->location->title }}}</span></li>
                                                    @endif
                                                    
                                                     @if (!empty($job->faculty))
                                                       <li><span><i class="fas fa-industry">{{{Helper::getFacultyName($job->faculty) }}}</i></span></li>
                                                   @endif
                                                   
                                                    @if (!empty($job->worktype))
                                                        <li><span class="wt-clicksavefolder"><i class="far fa-folder wt-viewjobfolder"></i> {{ trans('lang.type') }} {{{$job->worktype}}}</span></li>
                                                    @endif

                                                    <!--@if (!empty($job->payrange))-->
                                                    <!--    <li><span><i class="fa fa-tag wt-viewjobtag"></i><b> Pay Range </b>{{$job->payrange}}</span></li>-->
                                                    <!--@endif-->

                                                     @if (!empty($job->paytype))
                                                        <li><span><i class="fa fa-tag wt-viewjobtag"></i> <b>Pay Type </b>{{$job->paytype}}</span></li>
                                                    @endif
                                                   
                                                  
                                                </ul>
                                           
                                        </div>
                                    @endif
                                     <div class="wt-btnarea"><a href="javascript:void(0);" @click.prevent="check_auth('{{{ url('job/proposal/'.$job->id) }}}')" class="wt-btn">Apply for job</a></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
                                <div class="wt-projectdetail-holder">
                                    @if (!empty($job->description))
                                        <div class="wt-projectdetail">
                                            <div class="wt-title">
                                                <h3>{{ trans('lang.project_detail') }}</h3>
                                            </div>
                                            <div class="wt-description">
                                                @php echo htmlspecialchars_decode(stripslashes($job->description)); @endphp
                                            </div>
                                        </div>
                                    @endif
                                    <!--@if (!empty($job->skills) && $job->skills->count() > 0)-->
                                    <!--    <div class="wt-skillsrequired">-->
                                    <!--        <div class="wt-title">-->
                                    <!--            <h3>{{ trans('lang.skills_req') }}</h3>-->
                                    <!--        </div>-->
                                    <!--        <div class="wt-tag wt-widgettag">-->
                                    <!--            @foreach ($job->skills as $skill)-->
                                    <!--                <a href="{{{url('search-results?type=job&skills%5B%5D='.$skill->slug)}}}">{{{ $skill->title }}}</a>-->
                                    <!--            @endforeach-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--@endif-->
                                 
                                </div>
                            </div>


                               

                        @endif

                         <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
                                <div class="wt-projectdetail-holder">
                                    @if (!empty($job->business_details))
                                        <div class="wt-projectdetail">
                                            <div class="wt-title">
                                                <h3>Business Details</h3>
                                            </div>
                                            <div class="wt-description">
                                                @php echo htmlspecialchars_decode(stripslashes($job->business_details)); @endphp
                                            </div>
                                        </div>
                                    @endif
                                  
                                 
                                </div>
                            </div>

                      
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
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
        })
    </script>
@endpush
