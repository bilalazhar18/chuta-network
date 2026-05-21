<div class="wt-dashboardtabs">
    <ul class="wt-tabstitle nav navbar-nav">
        <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='personalDetail'? 'active': '' }}}" href="{{{ route('personalDetail') }}}">{{{ trans('lang.personal_detail') }}}</a>
        </li>
        <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='experienceEducation'? 'active': '' }}}" href="{{{ route('experienceEducation') }}}">{{{ trans('lang.experience_education') }}}</a>
        </li>
        <!-- <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='resources'? 'active': '' }}}" href="{{{ route('resources') }}}">{{{ trans('lang.my_resources') }}}</a>
        </li>
        <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='friend-requests'? 'active': '' }}}" href="{{{ route('friend-requests') }}}">{{{ trans('lang.requests') }}}</a>
        </li>
        <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='friends'? 'active': '' }}}" href="{{{ route('friends') }}}">{{{ trans('lang.friends') }}}</a>
        </li>
        <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='courses'? 'active': '' }}}" href="{{{ route('courses') }}}">{{{ trans('lang.courses') }}}</a>
        </li> -->
        <!-- <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='tutors'? 'active': '' }}}" href="{{{ route('tutors') }}}">{{{ trans('lang.tutors') }}}</a>
        </li> -->
        <!-- <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='book-meeting'? 'active': '' }}}" href="{{{ route('book-meeting') }}}">{{{ trans('lang.book_meeting') }}}</a>
        </li>
        <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='my-rooms'? 'active': '' }}}" href="{{{ route('my-rooms') }}}">{{{ trans('lang.my_rooms') }}}</a>
        </li>         -->
        <!-- <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='billing'? 'active': '' }}}" href="{{{ route('billing') }}}">{{{ trans('lang.billing') }}}</a>
        </li>
        <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='invoices'? 'active': '' }}}" href="{{{ route('invoices') }}}">{{{ trans('lang.sent_and_received_invoices') }}}</a>
        </li> -->
    </ul>
</div>
