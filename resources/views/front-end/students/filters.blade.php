<aside id="wt-sidebar" class="wt-sidebar wt-usersidebar">
    {!! Form::open(['url' => url('search-filters'), 'method' => 'get', 'class' => 'wt-formtheme wt-formsearch']) !!}
        <input type="hidden" value="{{$type}}" name="type">
        <div class="wt-widget wt-effectiveholder wt-startsearch">
            <div class="wt-widgettitle">
                <h2>{{ trans('lang.start_search') }}</h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">
                    <fieldset>
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="{{ trans('lang.ph_search_student') }}" value="{{$keyword}}">
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
      <!--   <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>Course</h2>
            </div>
            <div class="wt-widgetcontent">
                   <fieldset>
                    <div class="form-group">
                        <input type="text" class="form-control filter-course" placeholder="Search Course">
                    
                    </div>
                </fieldset>

                <fieldset>
                    @if (!empty($skills))
                        <div class="wt-checkboxholder wt-verticalscrollbar hells" style="max-height: 150px;overflow: hidden;overflow-y: auto;">

                            {{-- @foreach ($skills as $key => $skill)
                                @php $checked = ( !empty($_GET['skills']) && in_array($skill->slug, $_GET['skills'])) ? 'checked' : '' @endphp
                                <span class="wt-checkbox2">
                                    <input id="skill-{{{ $key }}}" type="checkbox" name="skills[]" value="{{{$skill->slug}}}" {{$checked}} >
                                    <label style="margin-top: -25px;display: block;padding-left: 15px;color: #666;font-weight: 400;margin-bottom: 10px;margin-left: 5px" for="skill-{{{ $key }}}">{{{ $skill->title }}}</label>
                                </span>
                            @endforeach --}}
                        </div>
                    @endif
                </fieldset>
            </div>
        </div> -->


         <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>Select Faculty</h2>
            </div>
            <div class="wt-widgetcontent">
                   <!-- <fieldset>
                    <div class="form-group">
                        <input type="text" class="form-control filter-course3" placeholder="Search Faculty">
                        <a href="javascrip:void(0);" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></a>
                    </div>
                </fieldset> -->
                <fieldset>
                    @if (!empty($faculty))
                        <div class="wt-checkboxholder wt-verticalscrollbar facultyshow1" style="max-height: 150px;overflow: hidden;overflow-y: auto;">
                           @foreach ($faculty as $faculty)
                                @php $checked = ( !empty($_GET['faculty']) && in_array($faculty->slug, $_GET['faculty'] )) ? 'checked' : ''; @endphp
                                <span class="wt-checkbox6">
                                    <input id="cat-{{{ $faculty->title }}}" type="checkbox" name="faculty[]" value="{{{ $faculty->id }}}" {{$checked}} >
                                    <label style="margin-top: -25px;display: block;padding-left: 15px;color: #666;font-weight: 400;margin-bottom: 10px;margin-left: 5px"; for="cat-{{{ $faculty->title }}}"> {{{ $faculty->title }}}</label>
                                </span>
                            @endforeach
                        </div>
                    @endif
                </fieldset>
            </div>
        </div>



        <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>{{ trans('lang.location') }}</h2>
            </div>
            <div class="wt-widgetcontent">
                <fieldset>
                    <!-- <div class="form-group">
                        <input type="text" class="form-control filter-recordsloc" placeholder="{{ trans('lang.search_loc') }}">
                        <a href="javascrip:void(0);" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></a>
                    </div> -->
                </fieldset>
                <fieldset>
                    @if (!empty($locations))
<!--                         <div class="wt-checkboxholder wt-verticalscrollbar showjobloc"> -->
                    <div class="wt-checkboxholder wt-verticalscrollbar">
                             @foreach ($locations as $location)
                                @php $checked = ( !empty($_GET['locations']) && in_array($location->slug, $_GET['locations'])) ? 'checked' : '' @endphp
                                <span class="wt-checkbox3">
                                    <input id="location-{{{ $location->slug }}}" type="checkbox" name="locations[]" value="{{{$location->id}}}" {{$checked}} >
                                    <label for="location-{{{ $location->slug }}}" style="margin-top: -25px;display: block;padding-left: 15px;color: #666;font-weight: 400;margin-bottom: 10px;margin-left: 5px;">{{{ $location->title }}}</label>
                                </span>
                            @endforeach
                        </div>
                    @endif
                </fieldset>
            </div>
        </div>
     {{--    <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>{{{ trans('lang.hourly_rate') }}}</h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">
                    <fieldset>
                        <div class="form-group">
                            <input type="text" class="form-control filter-records" placeholder="{{ trans('lang.ph_search_rate') }}">
                            <a href="javascrip:void(0);" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></a>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="wt-checkboxholder wt-verticalscrollbar">
                            @foreach (Helper::getHourlyRate() as $key => $hourly_rate)
                                @php $checked = ( !empty($_GET['hourly_rate']) && in_array($key, $_GET['hourly_rate'])) ? 'checked' : '' @endphp
                                <span class="wt-checkbox">
                                    <input id="rate-{{ $key }}" type="checkbox" name="hourly_rate[]" value="{{ $key }}" {{ $checked }}>
                                    <label for="rate-{{ $key }}">{{ $hourly_rate }}</label>
                                </span>
                            @endforeach
                        </div>
                    </fieldset>
                </div>
            </div>
        </div> --}}
        
    {{--     <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>{{ trans('lang.student_type') }}</h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">
                    <div class="wt-checkboxholder wt-verticalscrollbar">
                        @foreach (Helper::getstudentLevelList() as $key => $student_level)
                        @php $checked = ( !empty($_GET['freelaner_type']) && in_array($key, $_GET['freelaner_type'])) ? 'checked' : '' @endphp
                            <span class="wt-checkbox">
                                <input id="rate-{{ $key }}" type="checkbox" name="freelaner_type[]" value="{{ $key }}" {{ $checked }}>
                                <label for="rate-{{ $key }}">{{ $student_level }}</label>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div> --}}
     {{--    <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>{{ trans('lang.english_level') }}</h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">
                    <fieldset>
                        <div class="wt-checkboxholder wt-verticalscrollbar">
                            @foreach (Helper::getEnglishLevelList() as $key => $english_level)
                                @php $checked = ( !empty($_GET['english_level']) && in_array($key, $_GET['english_level'])) ? 'checked' : '' @endphp
                                <span class="wt-checkbox">
                                    <input id="rate-{{ $key }}" type="checkbox" name="english_level[]" value="{{ $key }}" {{ $checked }}>
                                    <label for="rate-{{ $key }}">{{ $english_level }}</label>
                                </span>
                            @endforeach
                        </div>
                    </fieldset>
                </div>
            </div>
        </div> --}}
      {{--   <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>{{ trans('lang.langs') }}</h2>
            </div>
            <div class="wt-widgetcontent">
                <fieldset>
                    <div class="form-group">
                        <input type="text" class="form-control filter-records" placeholder="{{ trans('lang.ph_search_langs') }}">
                        <a href="javascrip:void(0);" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></a>
                    </div>
                </fieldset>
                <fieldset>
                    @if (!empty($languages))
                        <div class="wt-checkboxholder wt-verticalscrollbar">
                            @foreach ($languages as $language)
                                @php $checked = ( !empty($_GET['languages']) && in_array($language->slug, $_GET['languages'])) ? 'checked' : '' @endphp
                                <span class="wt-checkbox">
                                    <input id="language-{{{ $language->slug }}}" type="checkbox" name="languages[]" value="{{{$language->slug}}}" {{$checked}} >
                                    <label for="language-{{{ $language->slug }}}">{{{ $language->title }}}</label>
                                </span>
                            @endforeach
                        </div>
                    @endif
                </fieldset>
            </div>
        </div> --}}
        <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgetcontent">
                <div class="wt-applyfilters">
                    <span>{{ trans('lang.apply_filter') }}<br> {{ trans('lang.changes_by_you') }}</span>
                    {!! Form::submit(trans('lang.btn_apply_filters'), ['class' => 'wt-btn']) !!}
                </div>
            </div>
        </div>
    {!! form::close(); !!}
</aside>