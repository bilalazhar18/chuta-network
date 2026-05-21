<!-- <student_courses_completed
    :ph_course_title="'{{ trans('lang.ph_course_title') }}'"
    :ph_credit_hours_title="'{{ trans('lang.ph_credit_hours_title') }}'">
</student_courses_completed> -->

<div class="wt-tabscontenttitle">
    <h2>{{{ trans('lang.received_requests') }}}</h2>
</div>

<received_requests :ph_rate_skills="'{{ trans('lang.ph_rate_skills') }}'"></received_requests>

<div class="wt-tabscontenttitle">
    <h2>{{{ trans('lang.sent_requests') }}}</h2>
</div>

<sent_requests :ph_rate_skills="'{{ trans('lang.ph_rate_skills') }}'"></sent_requests>