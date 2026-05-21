@extends(file_exists(resource_path('views/extend/front-end/master.blade.php')) ?
'extend.front-end.master':
 'front-end.master', ['body_class' => 'wt-innerbgcolor'] )
@push('stylesheets')
    <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet">
    <style type="text/css">
    .abc{
     display: block;
  border: none;         /* Reset default border */
  height: 100%;        /* Viewport-relative units */
  width: calc(100% + 17px);
}
.aga{
    overflow-x: hidden;
}
    .overlay{
  position:fixed;
  width:100%;
  height: 100%;
  background-color:rgba(0, 0, 0, 0.6);
  pointer-events: none;
}
</style>
@endpush
@section('content')
    <div class="wt-haslayout wt-innerbannerholder">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
                    <div class="wt-innerbannercontent">
                    <div class="wt-title"><h2>Resource Detail</h2></div>
                    @if (!empty($show_breadcrumbs) && $show_breadcrumbs === 'true')
                        @if (count($breadcrumbs))
                            <ol class="wt-breadcrumb">
                                @foreach ($breadcrumbs as $breadcrumb)
                                    @if ($breadcrumb->url && !$loop->last)
                                        <li><a href="{{{ $breadcrumb->url }}}">{{{ $breadcrumb->title }}}</a></li>
                                    @else
                                        <li class="active">{{{ $breadcrumb->title }}}</li>
                                    @endif
                                @endforeach
                            </ol>
                        @endif
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wt-haslayout wt-main-section" id="services">
        @if (Session::has('message'))
            <div class="flash_msg">
                <flash_messages :message_class="'success'" :time ='5' :message="'{{{ Session::get('message') }}}'" v-cloak></flash_messages>
            </div>
        @elseif (Session::has('error'))
            <div class="flash_msg">
                <flash_messages :message_class="'danger'" :time ='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
            </div>
        @endif
        @if(!empty($resource))
            <div class="container">
                <div class="row">
                    @if(!empty($is_login))
                       
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 float-left">
                                <div class="wt-usersingle wt-servicesingle-holder">
                                    <div class="wt-servicesingle">
                                   
                                        <h1>{{{$resource->title}}}</h1>
                                        
                                        <h3>Details: </h3>    
                                        <p>Author: {{{$resource->user_name}}}</p>
                                        <p>Subject name: {{{$resource->course_name}}}</p>
                                        <p>Description: {{{$resource->description}}}</p>
                                                                                
                                      <span><strong><h6>Price: AUD {{$resource->price}}</h6></strong><span>
                                         
                                        <p><strong><h6>Average Rating: 5 / 5</h6></strong></p>
                                        <p>(5 being Most Helpful and 1 being Least Helpful)</p>
                                        <br>
                                        @if(empty($is_purchased))
                                            @if($is_login->id != $resource->user_id)
                                                <a class="wt-btn" href="{{url('user/package/checkout/'.$resource->slug)}}"><span>{{ trans('lang.buy_now') }}</span></a>
                                            @endif
                                        @else

                                        @if(!empty($is_purchased))
                                        <p> <span>Download Resource from here
                                        <a href="{{$resource->url_of_file}}" download><img src="{{asset('uploads/badges/down.jpg')}}" width="50px">
                                        </a>
                                        </span>
                                        </p>
                                        
                                            @endif

                                            @if($is_purchased->is_feedback_given == 0)
                                                <div id="rating-form">
                                                    @if($is_login->id !== $resource->user_id)
                                                        <h3>Rate this resource:</h3>
                                                        <br>
                                                        <h5>Score 1/5:</h5>
                                                        <span id="resource-rating star-1" class="fa fa-star" ></span>
                                                        <span id="resource-rating star-2" class="fa fa-star" ></span>
                                                        <span id="resource-rating star-3" class="fa fa-star" ></span>
                                                        <span id="resource-rating star-4" class="fa fa-star" ></span>
                                                        <span id="resource-rating star-5" class="fa fa-star" ></span>
                                                        <input id="rating-score" type="hidden"/>
                                                        <br><br>
                                                        <h5>Comment:</h5>
                                                        <textarea class="form-control" id="description-of-rating" placeholder="Feedback of Resource"></textarea>
                                                        <br><br>
                                                        <i id ='please-add-rating' style='display:none;font-size:10px;'>Please rate and write comment before proceeding</i>
                                                        <br><br>
                                                         @if($is_purchased->is_feedback_given == 1)
                                                         <a class="wt-btn" style="cursor:pointer" onclick="submitFeedback()"><span id = "rating-text" style="color:white;">Submit Feedback</span></a>
                                                        @else
                                                       
                                                        @endif
                                                   
                                                </div>                                                
                                            <!-- @elseif($is_purchased->is_feedback_given == 1)
                                                <div id="thank-you-for-rating">
                                                    <p>Thank you for rating this resource</p>
                                                </div>
                                            @endif -->
                                            <div id="thank-you-for-rating" style="display:none;">
                                                <p>Thank you for rating this resource</p>
                                            </div>
                                        @endif
                                        @endif
                                    </div>

                                



                               


                            </div>


                          


                        </div>

                        <div class="col-md-4 col-lg-4">
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
                               <img src="{{$resource->imagepreview}}" height="auto">


                               <?php

                           }
                           

                           
                           
                           else {
                               ?>
                              
                        
      

                            {{--   <embed style="overflow:hidden; width: 100px; height: 100px;float:right" src="{{asset('uploads/users/'.$resource->user_id.'/projects/'.$resource->name_of_file.'')}}"> --}}
                               
                               <div class="aga">
                               <iframe height="380px" src="{{$resource->imagepreview}}"></iframe>
                             </div>
                               </div>
                               <?php

                           }
                               ?>
                                       
                                       
                                       <!--<i class="fa fa-file-pdf-o" aria-hidden="true" style="float:right;font-size:20vh"></i>-->
                                   </div>

                                </div>
                            </div>
                             </div>



                             <div class="col-md-8 col-lg-8">

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

                         
                        

                    @else
                        @include('errors.please-login')
                    @endif
                  

                </div>
                
            </div>
        @else
            @include('errors.404')
        @endif

    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/readmore.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/countTo.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/appear.js') }}"></script>
    <script>
        let isHover = e => e.parentElement.querySelector(':hover') === e;    

        const starsArray = ['resource-rating star-1', 
            'resource-rating star-2', 'resource-rating star-3', 'resource-rating star-4', 
            'resource-rating star-5'];
        
        document.getElementById("resource-rating star-1").addEventListener("click", function(){
            document.getElementById("rating-score").value = 1; });
        document.getElementById("resource-rating star-2").addEventListener("click", function(){
            document.getElementById("rating-score").value = 2; });
        document.getElementById("resource-rating star-3").addEventListener("click", function(){
            document.getElementById("rating-score").value = 3; });
        document.getElementById("resource-rating star-4").addEventListener("click", function(){
            document.getElementById("rating-score").value = 4; });
        document.getElementById("resource-rating star-5").addEventListener("click", function(){
            document.getElementById("rating-score").value = 5; });            
        
        document.addEventListener('mousemove', function checkHover() {
            let hovered0 = isHover(document.getElementById(starsArray[0]));
            let hovered1 = isHover(document.getElementById(starsArray[1]));
            let hovered2 = isHover(document.getElementById(starsArray[2]));
            let hovered3 = isHover(document.getElementById(starsArray[3]));
            let hovered4 = isHover(document.getElementById(starsArray[4]));
            let is_match = 0; let matched_index = 0;
            let previous_rating = document.getElementById("rating-score").value;
            let is_hovered_array = [ hovered0, hovered1, hovered2, hovered3, hovered4 ];
            for( let i = 0;i < is_hovered_array.length; i++ ){
                if(is_hovered_array[i] == true){
                    is_match = 1;
                    matched_index = i
                    while( matched_index >= 0 ){
                        document.getElementById(starsArray[matched_index]).style.color = "purple";                    
                        matched_index--;
                    }
                }else{
                    if( document.getElementById('rating-score').value == "" ){
                        document.getElementById(starsArray[i]).style.color = "black";
                    } else if ( parseInt(document.getElementById('rating-score').value) >= 0 ){
                        if(is_match == 1 ){
                            document.getElementById(starsArray[i]).style.color = "black";
                        }else if(i < parseInt(document.getElementById('rating-score').value)){
                            document.getElementById(starsArray[i]).style.color = "purple";
                        }else{
                            document.getElementById(starsArray[i]).style.color = "black";
                        }
                    } else{
                        document.getElementById(starsArray[i]).style.color = "black";
                    }
                }
            }
        });


        function submitFeedback(){

            if( document.getElementById("rating-score").value == "" || document.getElementById("description-of-rating").value == "" ){
                document.getElementById("please-add-rating").style.display = "block";
                document.getElementById("please-add-rating").style.color = "red";
            } else {
                console.log("Details: ");
                const seller_id = "<?php echo $resource->user_id?>";
                const seller_name = "<?php echo $resource->user_name?>";
                const buyer_id = "<?php echo $is_login?$is_login->id:0?>";
                const buyer_name = "<?php echo $is_login?$is_login->first_name. " ".$is_login->last_name:"" ?>";
                const buyers_rating = parseInt(document.getElementById("rating-score").value);
                const resource_slug = "<?php echo $resource->slug ?>";
                const comment = document.getElementById("description-of-rating").value;

                axios.post(APP_URL + '/student/rate-a-resource', {
                        seller_id: seller_id,
                        seller_name: seller_name,
                        buyer_id: buyer_id,
                        buyer_name: buyer_name,
                        buyers_rating: buyers_rating,
                        resource_slug: resource_slug,
                        comment: comment
                    }).then(function(response){
                        console.log("DONE",response)
                        if(response['data']['type'] == "success"){
                            console.log(document.getElementById("rating-text"))
                            document.getElementById("rating-text").innerHTML = "RATED";
                            document.getElementById("rating-form").style.transition = "opacity 1s ease-out";
                            document.getElementById("rating-form").style.transition = "display 1s";
                            document.getElementById("rating-form").style.opacity = 0;
                            document.getElementById("rating-form").style.display = "none";
                            document.getElementById("thank-you-for-rating").style.display = "block";
                            
                        }
                });
            }

            
            // document.getElementById('rating-form').style.display="none";
        }
        /* SERVICE SLIDER */
        function customerFeedback(){
            var sync1 = jQuery('#wt-servicesslider');
            var sync2 = jQuery('#wt-servicesgallery');
            var slidesPerPage = 3;
            var syncedSecondary = true;
            sync1.owlCarousel({
                items : 1,
                loop: true,
                nav: false,
                dots: false,
                autoplay: false,
                slideSpeed : 2000,
                navClass: ['wt-prev', 'wt-next'],
                navContainerClass: 'wt-search-slider-nav',
                navText: ['<span class="lnr lnr-chevron-left"></span>', '<span class="lnr lnr-chevron-right"></span>'],
                responsiveRefreshRate : 200,
            }).on('changed.owl.carousel', syncPosition);

            sync2.on('initialized.owl.carousel', function () {
                sync2.find(".owl-item").eq(0).addClass("current");
            })

            .owlCarousel({
                // items : slidesPerPage,
                items:8,
                dots: false,
                nav: false,
                margin:10,
                smartSpeed: 200,
                slideSpeed : 500,
                slideBy: slidesPerPage,
                responsiveRefreshRate : 100,
            }).on('changed.owl.carousel', syncPosition2);

            function syncPosition(el) {
                var count = el.item.count-1;
                var current = Math.round(el.item.index - (el.item.count/2) - .5);
                if(current < 0) {
                    current = count;
                }
                if(current > count) {
                    current = 0;
                }
                sync2.find(".owl-item").removeClass("current").eq(current).addClass("current")
                var onscreen = sync2.find('.owl-item.active').length - 1;
                var start = sync2.find('.owl-item.active').first().index();
                var end = sync2.find('.owl-item.active').last().index();
                if (current > end) {
                    sync2.data('owl.carousel').to(current, 100, true);
                }
                if (current < start) {
                    sync2.data('owl.carousel').to(current - onscreen, 100, true);
                }
            }
            function syncPosition2(el) {
                if(syncedSecondary) {
                    var number = el.item.index;
                    sync1.data('owl.carousel').to(number, 100, true);
                }
            }
            sync2.on("click", ".owl-item", function(e){
                e.preventDefault();
                var number = jQuery(this).index();
                sync1.data('owl.carousel').to(number, 300, true);
            });
        }
        customerFeedback();
        var popupMeta = {
            width: 400,
            height: 400
        }
        $(document).on('click', '.social-share', function(event){
            event.preventDefault();

            var vPosition = Math.floor(($(window).width() - popupMeta.width) / 2),
                hPosition = Math.floor(($(window).height() - popupMeta.height) / 2);

            var url = $(this).attr('href');
            var popup = window.open(url, 'Social Share',
                'width='+popupMeta.width+',height='+popupMeta.height+
                ',left='+vPosition+',top='+hPosition+
                ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

            if (popup) {
                popup.focus();
                return false;
            }
        })
    </script>
@endpush
