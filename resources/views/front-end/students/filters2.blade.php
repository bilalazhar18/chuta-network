<aside id="wt-sidebar" class="wt-sidebar wt-usersidebar">
    {!! Form::open(['url' => url('search-filters'), 'method' => 'get', 'class' => 'wt-formtheme wt-formsearch']) !!}
        <input type="hidden" value="tutor" name="type">
        <div class="wt-widget wt-effectiveholder wt-startsearch">
            <div class="wt-widgettitle">
                <h2>{{ trans('lang.start_search') }}</h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">
                    <fieldset>
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="Search Tutor">
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>Course</h2>
            </div>
            <div class="wt-widgetcontent">
                   <fieldset>
                    <div class="form-group">
                        <input type="text" class="form-control filter-course" placeholder="Search Course">
                       <!--  <a href="javascrip:void(0);" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></a> -->
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
        </div>

        <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>{{ trans('lang.location') }}</h2>
            </div>
            <div class="wt-widgetcontent">
                <fieldset>
                    <div class="form-group">
                        <input type="text" class="form-control filter-recordsloc" placeholder="{{ trans('lang.search_loc') }}">
                        <a href="javascrip:void(0);" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></a>
                    </div>
                </fieldset>
                <fieldset>
                    @if (!empty($locations))
                        <div class="wt-checkboxholder wt-verticalscrollbar showjobloc">
                           {{--  @foreach ($locations as $location)
                                @php $checked = ( !empty($_GET['locations']) && in_array($location->slug, $_GET['locations'])) ? 'checked' : '' @endphp
                                <span class="wt-checkbox3">
                                    <input id="location-{{{ $location->slug }}}" type="checkbox" name="locations[]" value="{{{$location->slug}}}" {{$checked}} >
                                    <label for="location-{{{ $location->slug }}}" style="margin-top: -25px;display: block;padding-left: 15px;color: #666;font-weight: 400;margin-bottom: 10px;margin-left: 5px;"> <img src="{{{asset(App\Helper::getLocationFlag($location->flag))}}}" alt="{{ trans('lang.img') }}"> {{{ $location->title }}}</label>
                                </span>
                            @endforeach --}}
                        </div>
                    @endif
                </fieldset>
            </div>
        </div>
  
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