<template>
    <section class="wt-haslayout wt-main-section" v-bind:style="{ background: app.sectionColor}">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 float-left">
                    <figure class="wt-mobileimg">
<video src="http://chuta.network/dev/public/uploads/pages/5/Chuta.mp4" type="video/mp4" width="auto" height="auto" controls></video>

                    </figure>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-6 float-left">
                    <div class="wt-experienceholder">
                        <div class="wt-sectionhead">
                            <div class="wt-sectiontitle">
                                <h3>{{app.title}}</h3>
                                <span>{{app.subtitle}}</span>
                            </div>
                            <div class="wt-description" v-html="app.description"></div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
export default {
    props:['app', 'page_id'],
    data() {
        return {
            baseUrl: APP_URL,
            imageUrl:APP_URL+'/uploads/pages/'+this.page_id+'/',
        }
    },
    mounted: function() {
        
    } 
};
</script>