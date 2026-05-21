<!-- <student_courses_completed
    :ph_course_title="'{{ trans('lang.ph_course_title') }}'"
    :ph_credit_hours_title="'{{ trans('lang.ph_credit_hours_title') }}'">
</student_courses_completed> -->

<div class="wt-tabscontenttitle">
    <h2>{{{ trans('lang.my_friends') }}}<span class="float-right"><a href="{{url('message-center')}}">View Profile</span></a></h2>
</div>

<my_friends :ph_rate_skills="'{{ trans('lang.ph_rate_skills') }}'"></my_friends >
