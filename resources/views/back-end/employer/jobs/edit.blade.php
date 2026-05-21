@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')
    <div class="wt-haslayout wt-dbsectionspace">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 float-left" id="post_job">
                @if (Session::has('error'))
                    <div class="flash_msg">
                        <flash_messages :message_class="'danger'" :time='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
                    </div>
                @endif
                <div class="preloader-section" v-if="loading" v-cloak>
                    <div class="preloader-holder">
                        <div class="loader"></div>
                    </div>
                </div>
                <div class="wt-haslayout wt-post-job-wrap">
                    {!! Form::open(['url' => '', 'class' =>'post-job-form wt-haslayout', 'id' => 'job_edit_form', '@submit.prevent'=>'updateJob("'.$job->id.'")']) !!}
                        <div class="wt-dashboardbox">
                            <div class="wt-dashboardboxtitle">
                                <h2>{{ trans('lang.edit_job') }}</h2>
                            </div>
                            <div class="wt-dashboardboxcontent">
                                <div class="wt-jobdescription wt-tabsinfo">
                                    <div class="wt-tabscontenttitle">
                                        <h2>{{ trans('lang.job_det') }}</h2>
                                    </div>
                                    <div class="wt-formtheme wt-userform wt-userformvtwo la-job-details-form">
                                        <fieldset>
                                            <div class="form-group">
                                                {!! Form::text('title', $job->title, array('class' => 'form-control','placeholder' => trans('lang.job_title'))) !!}
                                            </div>

                                        <div class="form-group form-group-half wt-formwithlabel">
                                                <span class="wt-select">
                                                    <select name="worktype">

                                                 @foreach($worktype as $key => $val)
                                                <option value="{{ $key }}" {{ ($key == $job->worktype) ? 'selected' : '' }}>{{$val}}
                                                    @endforeach
                                                </option>

                                                    </select>
                                                </span>
                                            </div>

                                        <div class="form-group form-group-half wt-formwithlabel">
                                            <span class="wt-select">
                                                <select name="paytype">
                                                

                                                @foreach($paytype as $key => $val)
                                                <option value="{{ $key }}" {{ ($key == $job->paytype) ? 'selected' : '' }}>{{$val}}
                                                    @endforeach
                                                </option>



                                                </select>
                                            </span>
                                        </div>

                                        <div class="form-group form-group-half">
                                            <input type="text" name="payrange" value="{{$job->payrange}}" placeholder="Pay Range" class="form-control">
                                        </div>

                                       

                                    <div class="form-group wt-formwithlabel">
                                        <span class="wt-select">
                                        <select name="degreearea" class="form-control">
                                        <option selected="selected" value="">Select Degree</option>
                                        @foreach($degree as $val)
                                        <option value="{{$val->id}}" {{$job->degree_id == $val->id  ? 'selected' : ''}}>{{$val->title}}</option>
                                        @endforeach
                                        </select>
                                        </span>
                                    </div>   

                                        <div class="form-group">
                                            <textarea placeholder="Business Details" name="business_details" cols="92" rows="10">{{$job->business_details}}
                                            </textarea>
                                        </div>

                                        @if(!empty($item->item_name))
                                        <div class="form-group form-group-half">
                                            <input type="text" name="universityapp" class="form-control" placeholder="{{ trans('lang.universityapp') }}" value="{{$job->universityapp}}">
                                        </div>
                                       
                                       
                                        @endif
                                        

                                        </fieldset>
                                    </div>
                                </div>
                                <div class="wt-jobskills wt-tabsinfo">
                                    <div class="wt-tabscontenttitle">
                                        <h2>{{ trans('lang.job_cats') }}</h2>
                                    </div>
                                    <div class="wt-divtheme wt-userform wt-userformvtwo">
                                        <div class="form-group">
                                            <span class="wt-select">
                                               <select name="faculty">
                                               
                                           @foreach($faculty as $key => $val)
                                                <option value="{{ $key }}" {{ ($key == $job->faculty) ? 'selected' : '' }}>{{$val}}
                                                    
                                                @endforeach
                                               
                                               </select>
                                            </span>
                                        </div>
                                    </div>
                                </div>



                             <div class="wt-languages-holder wt-tabsinfo">
                                <div class="wt-tabscontenttitle">
                                    <h2>{{ trans('lang.select_course') }}</h2>
                                </div>
                                <div class="wt-divtheme wt-userform wt-userformvtwo">
                                    
                                    <div class="form-group"><span class="wt-select">
                                        <select multiple="multiple" data-placeholder="Select Course" name="skills[]" class="chosen-select" style="display: none;">
                                            <ul>
                                            <li>
                                          
                                            <?php 
                                            foreach ($skills as $val)
                                            {
                                                $mark=explode(',', $job->course_id);
                                                if(in_array($val->id, $mark)){
                                                ?>
                                                <option value="{{$val->id}}" selected>{{$val->title}}</option>
                                                <?php

                                                }else{
                                                ?>
                                                <option value="{{$val->id}}" >{{$val->title}}</option>
                                                <?php
                                                }
                                                ?>
                                            
                                            <?php
                                    }
                                    ?>
                                            </li>
                                            </ul>
                                    </select>
                                    </span>
                                    </div>
                                    </div>
                                    </div>


                              
                                <div class="wt-jobdetails wt-tabsinfo">
                                    <div class="wt-tabscontenttitle">
                                        <h2>{{ trans('lang.job_dtl') }}</h2>
                                    </div>
                                    <div class="wt-formtheme wt-userform wt-userformvtwo">
                                        {!! Form::textarea('description', $job->description, ['class' => 'wt-tinymceeditor', 'id' => 'wt-tinymceeditor', 'placeholder'
                                        => trans('lang.job_dtl_note')]) !!}
                                    </div>
                                </div>

                                
                            <div class="wt-joblocation wt-tabsinfo">
                                <div class="wt-tabscontenttitle">
                                    <h2>Your Location</h2></div> 
                                    <div class="wt-formtheme wt-userform">
                                        <fieldset>
                                            <div class="form-group">
                                                <span class="wt-select">
                                                    <select name="locations">
                                                        @foreach($locations as $val)
                                                        
                                                         <option value="{{$val->id}}" {{$job->location_id == $val->id  ? 'selected' : ''}}>{{$val->title}}</option>
                                                        @endforeach
                                                       
                                                    </select>
                                                </span>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="wt-updatall">
                            <i class="ti-announcement"></i>
                            <span>{{{ trans('lang.save_changes_note') }}}</span> {!! Form::submit(trans('lang.btn_save_update'), ['class' => 'wt-btn',
                            'id'=>'submit-profile']) !!}
                        </div>
                    {!! form::close(); !!}
                </div>
            </div>
        </div>
    </div>
@endsection
