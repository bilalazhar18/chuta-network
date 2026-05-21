@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')
    <div class="wt-dbsectionspace wt-haslayout la-ps-student">
        <div class="student-profile" id="invoice_list">
            <div class="preloader-section" v-if="loading" v-cloak>
                <div class="preloader-holder">
                    <div class="loader"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                    <div class="wt-dashboardbox wt-dashboardtabsholder">
                        <!-- @if (file_exists(resource_path('views/extend/back-end/student/payouts/tabs.blade.php')))
                            @include('extend.back-end.student.payouts.tabs')
                        @else
                            @include('back-end.student.payouts.tabs')
                        @endif -->
                        <div class="wt-tabscontent tab-content">
                            <div class="wt-tabscontenttitle">
                                <h2>{{ trans('lang.payout_settings') }}</h2>
                            </div>
                            @if(empty($profile->payout_settings))
                                <div class="wt-settingscontent">
                                    <div class="wt-description">
                                        <p>Please connect with Stripe before buying or selling any resource. All the earning will be sent to below selected payout method</p>
                                    </div>
                                    <a target="_blank" href="https://connect.stripe.com/express/oauth/authorize?response_type=code&amp;client_id=ca_HgGqBIZEJkuDvfYlbDsKdD52s5M0LPju&amp;scope=read_write" class="connect-button" id="connect-button">
                                        <img height=60 width=50 src='../images/payouts/stripe.png'>
                                        <span style="color:white">
                                            Connect with Stripe
                                        </span>
                                    </a>                            
                                </div>
                            @else
                                <div class="wt-description">
                                    <p>Thank you for connecting your account with us. You can now purchase and sell resources.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>

</script>