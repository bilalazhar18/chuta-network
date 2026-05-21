<template>
    <div id="bannerstyle" class="wt-haslayout wt-bannerholder" :style="'background-image:url('+imageUrl+'/'+slider.slider_image[0]+')'">
    <div class="container">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="wt-bannercontent" style="padding: 80px 0px">
                    <div class="wt-bannerhead text-center">
                        <div class="wt-title mt-2">
                            <h3 style="color:#fff">
                                <span>  The Student Community enhancing culture, courses and careers digitally</span>                                
                            </h3>
                        </div>
                        <div class="wt-description" style="color:white">Search for resources, student jobs, and stay connect with peers to collaborate, excel, and connect your course to a career.</div>
                    </div>
                    <search-form
                    :widget_type="'home'"
                    :placeholder="trans('lang.looking_for')"
                    :student_placeholder="trans('lang.search_filter_list.student')"
                    :employer_placeholder="trans('lang.search_filter_list.employers')"
                    :job_placeholder="trans('lang.search_filter_list.jobs')"
                    :service_placeholder="trans('lang.search_filter_list.services')"
                    :no_record_message="trans('lang.no_record')"
                    >
                    </search-form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    props:['slider', 'page_id'],
    data() {
        return {
            imageUrl:APP_URL+'/uploads/pages/'+this.page_id+'/',
        }
    },
    mounted: function() {
        var self = this;
        setTimeout(function(){
            jQuery("a[data-rel]").each(function () {
                jQuery(this).attr("rel", jQuery(this).data("rel"));
            });
            jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
                animation_speed: 'normal',
                theme: 'dark_square',
                slideshow: 3000,
                default_width: 800,
                default_height: 500,
                allowfullscreen: true,
                autoplay_slideshow: false,
                social_tools: false,
                iframe_markup: "<iframe src='{path}' width='{width}' height='{height}' frameborder='no' allowfullscreen='true'></iframe>",
                deeplinking: false
            });
             $('.wt-bannermanimg').tilt();
        },1000);
    } 
};
</script>