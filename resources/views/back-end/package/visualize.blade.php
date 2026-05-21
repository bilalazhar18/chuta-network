@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')
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
                                   
                                <li style="height:97px"><span>Price</span></li>
                                <li ><span>Email</span></li>
                                <li ><span>Target</span></li>
                                <li style="height:140px"><span>Profile</span></li>
                                
                                
                                    
                                </ul>
                            </div>


                        </div>
                       

                               
                                    <div class="wt-package wt-baiscpackage">
                                       
                                            <div class="wt-packagehead">
                                                <h3>Package 1</h3>

                                               <!--  <span></span> -->
                                            </div>
                                       
                                        <div class="wt-packagecontent">
                                            <ul class="wt-packageinfo">
                                                <li class="wt-packageprice"><span><sup>$</sup>39.99<sub> 60 Days</sub></span></li>
                                                

                                            <li><span>Email to students upon payment</span></li>
                                            <li><span>Student By degree Area</span></li>
                                            <li><span>- Education Data<br><br>- Personal Data <br><br>e.g location,
                                            <br><br>name, age etc</span></li>
                                                  
                                                       
                                                   
                                                       
                                            </ul>
                                            <a href="http://127.0.0.1:8000/user/package/checkout/6" class="wt-btn"><span>Buy Now</span></a>
                                        
                                        </div>
                                    </div>
                                


                                         <div class="wt-package wt-baiscpackage">
                                       
                                            <div class="wt-packagehead">
                                                <h3>Package 2</h3>
                                               
                                               <!--  <span></span> -->
                                            </div>
                                       
                                        <div class="wt-packagecontent">
                                            <ul class="wt-packageinfo">
                                                <li class="wt-packageprice"><span><sup>$</sup>49.99<sub> 90 Days</sub></span></li>
                                                

                                                        <li><span>Weekly to students degree area</span></li>
                                                        <li><span>Student by University & degree Area Profile </span></li>
                                                        <li><span>- Education Data<br><br>- Personal Data <br><br>e.g location,
                                                        <br><br>name, age etc</span></li>
                                                  
                                                      
                                                   
                                                       
                                            </ul>
                                         <a href="http://127.0.0.1:8000/user/package/checkout/6" class="wt-btn"><span>Buy Now</span></a>
                                        </div>
                                    </div>
                                
                           
                       
                         
                         
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
