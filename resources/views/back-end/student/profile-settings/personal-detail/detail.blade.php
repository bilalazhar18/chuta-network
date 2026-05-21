<div class="wt-tabscontenttitle">
    <h2>{{{ trans('lang.your_details') }}}</h2>
</div>
<div class="wt-formtheme">
    <fieldset>
     
           <div class="form-group form-group-half">
                <span class="wt-select">
                <select name="gender">
                 <option value="">Select Gender</option>
                 <option value="1" @if ($gender==1) selected='selected' @endif>Male</option>
                 <option value="2" @if ($gender==2) selected='selected' @endif>Female</option>
                 <option value="3" @if ($gender==3) selected='selected' @endif>Other (Specify)</option>
                </select></span>
        </div>
        
     
        <div class="form-group form-group-half">
            {!! Form::text( 'first_name', e(Auth::user()->first_name), ['class' =>'form-control', 'placeholder' => trans('lang.ph_first_name')] ) !!}
        </div>
        <div class="form-group form-group-half">
            {!! Form::text( 'last_name', e(Auth::user()->last_name), ['class' =>'form-control', 'placeholder' => trans('lang.ph_last_name')] ) !!}
        </div>
        
                    @if($role_id==3)
                    <div class="form-group form-group-half">
                    <input placeholder="CGPA" name="cgpa" type="number" value="{{$profile->cgpa}}" step="any" class="form-control">
                    </div>
                    @endif
        
            <div class="form-group form-group-half">
                <span class="wt-select">
                <select name="faculty">

                @foreach($faculty as $key => $val)
                <option value="{{ $key }}" {{ ($key == Auth::user()->faculty) ? 'selected' : '' }}>{{$val}}
                @endforeach
                </select></span>
            </div>
             @if($role_id==3)
            <div class="form-group form-group-half">
                <input placeholder="University" name="university" type="text" value="{{$profile->university}}" class="form-control">
            </div>
            @endif

        <div class="form-group">
            {!! Form::text( 'tagline', e($tagline), ['class' =>'form-control', 'placeholder' => trans('lang.ph_add_tagline')] ) !!}
        </div>

        <!-- <div class="form-group">-->
        <!--        <span class="wt-select">-->
        <!--        <select name="degree">-->
        <!--         <option value="">Select Degree</option>-->
        <!--        @foreach($degree as $key => $val)-->
        <!--        <option value="{{$val->id}}" @if($profile->degree_id==$val->id) selected @endif>{{$val->title}}</option>-->
        <!--        @endforeach-->
        <!--        </select></span>-->
        <!--</div>-->
        
        <div class="form-group">
                        <span class="wt-select">
                        <select  data-placeholder="Select Degree" name="degree" class="chosen-select" style="display: none;">
                        <ul>
                        <li>
                        <?php 
                        foreach ($degree as $val)
                        {
                        if($profile->degree_id==$val->id)
                        {
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


        <div class="form-group">
            {!! Form::textarea( 'description', e($description), ['class' =>'form-control', 'placeholder' => trans('lang.ph_desc')] ) !!}
        </div>
    </fieldset>
</div>