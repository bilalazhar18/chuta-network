<aside id="wt-sidebar" class="wt-sidebar">
    {!! Form::open(['url' => url('search-filters'), 'method' => 'get', 'class' => 'wt-formtheme wt-formsearch']) !!}
        <input type="hidden" value="job" name="type">
        <div class="wt-widget wt-effectiveholder wt-startsearch">
            <div class="wt-widgettitle">
                <h2>{{ trans('lang.start_search') }}</h2>
            </div>
            <div class="wt-widgetcontent">
                <div class="wt-formtheme wt-formsearch">
                    <fieldset>
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="{{ trans('lang.ph_search_jobs') }}">
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        
        <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>Select Degree</h2>
            </div>
            <div class="wt-widgetcontent">
                   <fieldset>
                    <div class="form-group">
                        <input type="text" class="form-control filter-course2" placeholder="Search Degree">
                        <a href="javascrip:void(0);" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></a>
                    </div>
                </fieldset>
                <fieldset>
                    @if (!empty($degree))
                        <div class="wt-checkboxholder wt-verticalscrollbar degreeshow" style="max-height: 150px;overflow: hidden;overflow-y: auto;">
                          {{--   @foreach ($degree as $category)
                                @php $checked = ( !empty($_GET['category']) && in_array($category->slug, $_GET['category'] )) ? 'checked' : ''; @endphp
                                <span class="wt-checkbox6">
                                    <input id="cat-{{{ $category->title }}}" type="checkbox" name="degree[]" value="{{{ $category->title }}}" {{$checked}} >
                                    <label style="margin-top: -25px;display: block;padding-left: 15px;color: #666;font-weight: 400;margin-bottom: 10px;margin-left: 5px"; for="cat-{{{ $category->title }}}"> {{{ $category->title }}}</label>
                                </span>
                            @endforeach --}}
                        </div>
                    @endif
                </fieldset>
            </div>
        </div>
        <div class="wt-widget wt-effectiveholder">
            <div class="wt-widgettitle">
                <h2>{{ trans('lang.locations') }}</h2>
            </div>
            <div class="wt-widgetcontent">
                <fieldset>
                    <div class="form-group">
                        <input type="text" class="form-control filter-recordsloc" placeholder="{{ trans('lang.ph_search_locations') }}">
                        <a href="javascrip:void(0);" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></a>
                    </div>
                </fieldset>
                <fieldset>
                    @if (!empty($locations))
                        <div class="wt-checkboxholder wt-verticalscrollbar showjobloc">
                            
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
    {!! Form::close() !!}
</aside>
