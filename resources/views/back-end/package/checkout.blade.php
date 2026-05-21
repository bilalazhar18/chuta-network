@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')
<style type="text/css">
    .chart {
  position: relative;
}

.chart i {
  position: absolute;
  right: 0px;
  top: 0px;
  opacity: 0.9;
}
/*.sj-checkouttable td{
    width: 10% !important;
}
.sj-checkouttable th{
    width: 10% !important;
}*/


</style>
    <section class="wt-haslayout wt-dbsectionspace">    
        <div class="row">
            <div class=" col-sm-12 col-md-8 push-md-2 col-lg-8 push-lg-2" id="packages">
                <div class="preloader-section" v-if="loading" v-cloak>
                    <div class="preloader-holder">
                        <div class="loader"></div>
                    </div>
                </div>
                <div class="wt-dashboardbox wt-submitorder">
                @if (Session::has('message'))
                    <div class="flash_msg">
              <flash_messages :message_class="'success'" :time ='5' :message="'{{{ Session::get('message') }}}'" v-cloak></flash_messages>
                    </div>
                    @php session()->forget('message') @endphp;
                @elseif (Session::has('error'))
                    <div class="flash_msg">
                        <flash_messages :message_class="'danger'" :time ='5' :message="'{{{ str_replace("'s", " ", Session::get('error')) }}}'" v-cloak></flash_messages>
                    </div>
                    @php session()->forget('error'); @endphp
                @endif
                <div class="sj-checkoutjournal">
                    <div class="sj-title">
                       @if(!empty($resource->title))
                        <h3>{{{trans('lang.checkout')}}} of '{{{$resource->title}}}'</h3>
                        @else
                        <h3></h3>
                        @endif
                    </div>
                    @php
                        $options = unserialize($package->options);
                        $banner = $options['banner_option'] = 1 ? 'ti-check' : 'ti-na';
                        $chat = $options['private_chat'] = 1 ? 'ti-check' : 'ti-na';
                        session()->put(['product_id' => e($package->id)]);
                        session()->put(['product_title' => e($package->title)]);
                        session()->put(['product_price' => e($package->cost)]);
                        session()->put(['type' => 'package']);
                    @endphp
                    <div class="chart">
                    <table class="sj-checkouttable" style="width:550px !important">
                        <thead>
                            <tr>
                                <th >Resource Title</th>
                            <th >{{trans('lang.details')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                                <td>Author</td>
                              
                                @if(!empty($resource->user_name))
                                <td>{{{$resource->user_name}}}</td>
                                @else
                                <td></td>
                                @endif
                            </tr>
                            <tr>
                                <td>Description</td>
                                 @if(!empty($resource->description))
                                <td>{{{$resource->description}}}</td>
                              
                                {{-- <i class="fa fa-file-pdf-o" aria-hidden="true" style="margin-top: 10%;margin-right: 20%;width: 10%;font-size:15vh"></i> --}}
                                @else
                                <td></td>
                                @endif
                            </tr>                            
                            <tr>
                                <td>Price</td>
                                 @if(!empty($resource->price))
                                <td>AUD {{$resource->price}}</td>
                                @else
                                <td></td>
                                @endif
                            </tr>
                            @if ($mode == 'false')
                                <tr>
                                    <td>{{ trans('lang.status') }}</td>
                                    <td>{{ trans('lang.pending') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                </div>
                    @if ($mode == 'true' && !empty($payment_gateway))
                        <div class="sj-checkpaymentmethod">
                            <div class="sj-title">
                                <h3>{{ trans('lang.select_pay_method') }}</h3>
                                
                            </div>
                            @if(!empty($resource->user_name))
                            <ul class="sj-paymentmethod">
                                @foreach ($payment_gateway as $gatway)
                                    <li>
                                        @if ($gatway == "stripe")
                                            <a href="javascrip:void(0);" v-on:click.prevent="getStriprForm">
                                                <i class="fab fa-stripe-s"></i>
                                                <span><em>{{ trans('lang.pay_amount_via') }}</em> {{ Helper::getPaymentMethodList($gatway)['title']}} {{ trans('lang.pay_gateway') }}</span>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            @else
                            @endif
                        </div>
                    @else
                        <div class="sj-checkpaymentmethod">
                            <div class="form-group wt-btnarea">
                                <a class="wt-btn" href="javascript:;" v-on:click.prevent="generateOrder('{{$package->id}}')">
                                    {{ trans('lang.pay_order')}} 
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <b-modal ref="myModalRef" hide-footer title="Stripe Payment" class="la-pay-stripe" :no-close-on-backdrop="true">
                    <div class="d-block text-center">
                        <form class="wt-formtheme wt-form-paycard" method="POST" id="stripe-payment-form" role="form" action="" @submit.prevent='submitStripeFrom'>
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
                                    <input id="slug" type="hidden" class="form-control" name="slug" value="@if(!empty($resource->slug)){{$resource->slug}} @else '' @endif" autofocus>
                                    <input type="hidden" value="@if(!empty($resource->user_id)) {{$resource->user_id}} @else '' @endif" name="seller_id"/>
                                    @if ($errors->has('cvvNumber'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cvvNumber') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group wt-btnarea">
                                    <input type="submit" name="button" class="wt-btn" value="Pay AUD @if(!empty($resource->price)){{ !empty($symbol['symbol']) ? $symbol['symbol'] : 'AUD' }}{{$resource->price}} @else '' @endif">
                                </div>
                            </fieldset>
                        </form>
                    </b-modal>
                </div>
            </div>


            <div class=" col-sm-12 col-md-4 push-md-2 col-lg-4 push-lg-2">
                <div class="wt-usersingle wt-servicesingle-holder">
                    <div class="wt-servicesingle">
                        <div>
                                
                            <?php
                   $supported_image = array(
                   'gif',
                   'jpg',
                   'jpeg',
                   'png'
                   );

                   //$src_file_name = 'abskwlfd.PNG';
                   $src_file_name=$resource->imagepreview;
                   $ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
                   if (in_array($ext, $supported_image)) {
                   ?>
                   <img src="{{$resource->imagepreview}}" height="100px">


                   <?php

               } else {
                   ?>
                  
                {{--   <embed style="overflow:hidden; width: 100px; height: 100px;float:right" src="{{asset('uploads/users/'.$resource->user_id.'/projects/'.$resource->name_of_file.'')}}"> --}}
                   
                   <div class="aga">
                   <iframe height="380px" class="abc" src="{{$resource->imagepreview}}"></iframe>
                 </div>
                   </div>
                   <?php

               }
                   ?>
                           
                           
                           <!--<i class="fa fa-file-pdf-o" aria-hidden="true" style="float:right;font-size:20vh"></i>-->
                       </div>

                    </div>
            </div>

            
                     

                                   <div class="col-md-8 col-lg-8 mt-3">

                            <div class="wt-usersingle wt-servicesingle-holder">

                                @if(sizeof($ratingbox)>0)
                                @foreach($ratingbox as $val)
                                <div class="wt-servicesingle">
                                     <span><h4>Resource Reviews</h4></span>
                                    @if($val->buyers_rating==5)
                                            <div class="wt-students-rating">
                                            <ul>
                                            <li>
                                            <span>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            </span>
                                            </li>
                                            </ul>
                                            </div>
                                    @elseif($val->buyers_rating==4)
                                        <div class="wt-students-rating">
                                            <ul>
                                            <li>
                                            <span>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            </span>
                                            </li>
                                            </ul>
                                        </div>

                                         @elseif($val->buyers_rating==3)
                                        <div class="wt-students-rating">
                                            <ul>
                                            <li>
                                            <span>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            </span>
                                            </li>
                                            </ul>
                                        </div>


                                         @elseif($val->buyers_rating==2)
                                        <div class="wt-students-rating">
                                            <ul>
                                            <li>
                                            <span>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            </span>
                                            </li>
                                            </ul>
                                        </div>

                                         @else
                                        <div class="wt-students-rating">
                                            <ul>
                                            <li>
                                            <span>
                                            <i class="fa fa-star"></i>
                                            </span>
                                            </li>
                                            </ul>
                                        </div>


                                            @endif
                                            <br>

                                    <h5>{{$val->first_name}} {{$val->last_name}}</h5>
                                    <p>{{$val->comment}}</p>

                                   </div>

                                   @endforeach

                                   


                                </div>
                            </div>



                              </div>
                        @else
                                    
                            <div class="wt-usersingle wt-servicesingle-holder">
                                        <div class="wt-servicesingle">
                                   <p><strong>
                                    This Resource has no reviews.
                                    </strong>
                                   </p>
                               </div>
                           </div>
                       </div>

                                   @endif






                        
                        </div>




        </div>

    </section>
@endsection
