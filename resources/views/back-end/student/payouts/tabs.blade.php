<div class="wt-dashboardtabs">
    <ul class="wt-tabstitle nav navbar-nav">
        <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='studentPayoutsSettings'? 'active': '' }}}" href="{{{ route('studentPayoutsSettings') }}}">{{{ trans('lang.payout_settings') }}}</a>
        </li>
        <li class="nav-item">
            <a class="{{{ \Request::route()->getName()==='getstudentPayouts'? 'active': '' }}}" href="{{{ route('getstudentPayouts') }}}">{{{ trans('lang.payouts') }}}</a>
        </li>
    </ul>
</div>