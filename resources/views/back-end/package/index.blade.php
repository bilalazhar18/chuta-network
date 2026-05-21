@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')


{{--      @php 
$asas=serialize(array('email'=>'Email to students upon payment','duration'=>60,'target'=>'Student By degree Area','profile'=>'- Education Data -Personal Data e.g location, name, age etc'));
print_r($asas);
die;
@endphp --}}
    <section class="wt-haslayout wt-dbsectionspace">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 float-left" id="packages">
                @if (Session::has('success'))
                    <div class="flash_msg">
                        <flash_messages :message_class="'success'" :time ='5' :message="'{{{ Session::get('success') }}}'" v-cloak></flash_messages>
                    </div>
                    @php session()->forget('success'); @endphp
                @endif

                <div class="wt-dashboardbox">
                    <div class="wt-dashboardboxtitle">
                        <h2>{{ trans('lang.packages') }}</h2>
                    </div>
                    <div class="wt-dashboardboxcontent wt-packages">
                        <div class="wt-package wt-packagedetails">
                            <div class="wt-packagehead">
                            </div>

                            <div class="wt-packagecontent">
                                <ul class="wt-packageinfo">
                                    @foreach($package_options as $options)
                                        <li @if ($options == 'Price') class="wt-packageprices" @endif><span>{{{$options}}}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        @if (!empty($packages) && $packages->count() > 0)
                            @foreach ($packages as $key => $package)

                                @php

                                $options = unserialize($package->options);
                                session()->put(['product_id' => e($package->id)]);
                                session()->put(['product_title' => e($package->title)]);
                                session()->put(['product_price' => e($package->cost)]);
                                session()->put(['type' => 'package']);
                                 @endphp

                                @if (!empty($package))
                                    <div class="wt-package wt-baiscpackage">
                                        @if (!empty($package->title || $package->subtitle ))
                                            <div class="wt-packagehead">
                                                <h3>{{{$package->title}}}</h3>
                                                <span>{{{ $package->subtitle }}}</span>
                                            </div>
                                        @endif
                                        <div class="wt-packagecontent">
                                            <ul class="wt-packageinfo">
                                                <li class="wt-packageprice"><span><sup>{{ !empty($symbol['symbol']) ? $symbol['symbol'] : '$' }}</sup>{{{$package->cost}}}<sub>\ {{{ Helper::getPackageDurationList($options['duration']) }}}</sub></span></li>
                                                @foreach ($options as $key => $option)
                                                    @if ($key == 'duration')   
                                                        @else
                                                        <li><span> {{ $option }}</span></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                              {{--   <p id="blabla">hassan</p> --}}
                                                {{-- <a class="wt-btn" href="{{url('user/package/checkout/'.$package->id)}}"><span>{{ trans('lang.buy_now') }}</span></a> --}}
                                                 @if ($mode == 'true' && !empty($payment_gateway))
                                                  @foreach ($payment_gateway as $gatway)

                                                   @if ($gatway == "stripe")
                                               {{--  <a class="wt-btn" href="javascrip:void(0);" v-on:click.prevent="getStriprForm()"><span>{{ trans('lang.buy_now') }}</span></a> --}}


                                                 <a class="wt-btn" @click="openModal({{$package->id}})" href="javascrip:void(0);" ><span>{{ trans('lang.buy_now') }}</span></a>

                                                @endif
                                                @endforeach
                                                @endif
                                               
                                            

                                        </div>
                                    </div>
                                @endif
                            @endforeach

                           {{--  @php 
                            echo "<pre>";
                            foreach(Session()->get('product_price') as $gelo)


                            die();
                            @endphp --}}

                        @else
                            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 float-left">
                                <div class="wt-jobalerts">
                                    <div class="alert alert-warning alert-dismissible fade show">
                                        <em>{{ trans('lang.alert') }}</em> <span> {{ trans('lang.curr_on_trial') }}</span>
                                        <a href="javascript:void(0)" class="wt-alertbtn warning">{{ trans('lang.buy_now') }}</a>
                                        <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="Close"><i class="fa fa-close"></i></a>
                                    </div>
                                </div>
                            </div>
                            @if (file_exists(resource_path('views/extend/errors/no-record.blade.php'))) 
                                @include('extend.errors.no-record')
                            @else 
                                @include('errors.no-record')
                            @endif
                        @endif
                           <b-modal ref="myModalRef" hide-footer title="Stripe Payment" class="la-pay-stripe" :no-close-on-backdrop="true">
                    <div class="d-block text-center">
                        <form class="wt-formtheme wt-form-paycard" method="POST" id="stripe-payment-form" role="form" action="" @submit.prevent='submitStripeFrom1'>
                            {{ csrf_field() }}
                            <fieldset>
                                

                                <div class="form-group wt-inputwithicon {{ $errors->has('card_no') ? ' has-error' : '' }}">
                                    <label>{{ trans('lang.card_no') }}</label>
                                    <img src="{{asset('images/pay-icon.png')}}">
                                    <input id="card_no" type="text" class="form-control" name="card_no" value="{{ old('card_no') }}" autofocus>
                                    @if ($errors->has('card_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('card_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('ccExpiryMonth') ? ' has-error' : '' }}">
                                    <label>{{ trans('lang.expiry_month') }}</label>
                                    <input id="ccExpiryMonth" type="number" class="form-control" name="ccExpiryMonth" value="{{ old('ccExpiryMonth') }}" min="1" max="12" autofocus>
                                    @if ($errors->has('ccExpiryMonth'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('ccExpiryMonth') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('ccExpiryYear') ? ' has-error' : '' }}">
                                    <label>{{ trans('lang.expiry_year') }}</label>
                                    <input id="ccExpiryYear" type="text" class="form-control" name="ccExpiryYear" value="{{ old('ccExpiryYear') }}" autofocus>
                                    @if ($errors->has('ccExpiryYear'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('ccExpiryYear') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group wt-inputwithicon {{ $errors->has('cvvNumber') ? ' has-error' : '' }}">
                                    <label>{{ trans('lang.cvc_no') }}</label>
                                    <img src="{{asset('images/pay-img.png')}}">
                                    <input id="cvvNumber" type="number" class="form-control" name="cvvNumber" value="{{ old('cvvNumber') }}" autofocus>
                                    <input type="hidden" name="seller_id" value="{{Auth::user()->id}}">
                                    <input type="hidden"   v-bind:value="title" name="title">

                                    <input v-bind:value="slug" type="hidden" class="form-control" name="slug">
                                    
                                   
                                    @if ($errors->has('cvvNumber'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cvvNumber') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group wt-btnarea">
                                     
                                    <input type="submit" v-bind:value="cost" id="costprice" name="button" class="wt-btn">
                                </div>
                            </fieldset>
                        </form>
                    </b-modal>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
    