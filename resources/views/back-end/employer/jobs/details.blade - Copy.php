@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')
    <section class="wt-haslayout wt-dbsectionspace">
        <div class="row">
            <div class=" col-sm-12 col-md-8 push-md-2 col-lg-8 push-lg-2" id="packages">
                <div class="preloader-section" v-if="loading" v-cloak>
                    <div class="preloader-holder">
                        <div class="loader"></div>
                    </div>
                </div>
                <div class="wt-dashboardbox">
             
                <div class="sj-checkoutjournal">
                    <div class="sj-title">
                        <h3>{{{trans('lang.job_det')}}}</h3>
                    </div>
                   
                    <table class="sj-checkouttable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{trans('lang.details')}}</th>
                            </tr>
                        </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="sj-producttitle">
                                        <div class="sj-checkpaydetails">
                                        <h4><b>Title</b></h4>
                                        </div>
                                        </div>
                                    </td>
                                    <td> {{$job->title}}</td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="sj-producttitle">
                                        <div class="sj-checkpaydetails">
                                        <h4><b>Category</b></h4>
                                        </div>
                                        </div>
                                    </td>
                                    <td> {{$job->category}}</td>
                                </tr>


                                 <tr>
                                    <td>
                                        <div class="sj-producttitle">
                                        <div class="sj-checkpaydetails">
                                        <h4><b>Work Type</b></h4>
                                        </div>
                                        </div>
                                    </td>
                                    <td> {{$job->worktype}}</td>
                                </tr>

                                   <tr>
                                    <td>
                                        <div class="sj-producttitle">
                                        <div class="sj-checkpaydetails">
                                        <h4><b>Pay Type</b></h4>
                                        </div>
                                        </div>
                                    </td>
                                    <td> {{$job->paytype}}</td>
                                </tr>

                                   <tr>
                                    <td>
                                        <div class="sj-producttitle">
                                        <div class="sj-checkpaydetails">
                                        <h4><b>Pay Range</b></h4>
                                        </div>
                                        </div>
                                    </td>
                                    <td> {{$job->payrange}}</td>
                                </tr>

                                   <tr>
                                    <td>
                                        <div class="sj-producttitle">
                                        <div class="sj-checkpaydetails">
                                        <h4><b>Degree</b></h4>
                                        </div>
                                        </div>
                                    </td>
                                    <td> {{$job->degree}}</td>
                                </tr>

                                     @if(!empty($item->item_name))
                                   <tr>
                                    <td>
                                        <div class="sj-producttitle">
                                        <div class="sj-checkpaydetails">
                                        <h4><b>University Applicant</b></h4>
                                        </div>
                                        </div>
                                    </td>
                                    <td> {{$job->universityapp}}</td>
                                </tr>
                                @endif

                                  <tr>
                                    <td>
                                        <div class="sj-producttitle">
                                        <div class="sj-checkpaydetails">
                                        <h4><b>Locations</b></h4>
                                        </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($job->location_id==1)
                                        Australia
                                        @elseif($job->location_id==2)
                                        Canada
                                        @elseif($job->location_id==3)
                                        England
                                        @elseif($job->location_id==4)
                                        India
                                        @elseif($job->location_id==5)
                                        India
                                        @elseif($job->location_id==6)
                                        United Emirates
                                        @elseif($job->location_id==7)
                                        United Kingdom
                                        @else
                                        United States
                                        @endif
                                     </td>
                                </tr>


                                
                                
                            </tbody>
                        </table>
                    </div>
                 

                </div>
              
                </div>
            </div>
        </div>
    </section>
@endsection
