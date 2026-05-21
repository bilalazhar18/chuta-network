<template>
    <section class="wt-haslayout wt-main-section wt-paddingnull wt-companyinfohold" v-bind:style="style">
        <div class="container">
            <template v-if="this.user_id">
            <div class="row" v-if="this.user_id=='2'">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="wt-companydetails">
                        <div class="wt-companycontent employeesellresource">
                            <div class="wt-companyinfotitle">
                                <h2>Post new Job</h2>
                            </div>
                            <div class="wt-description" v-html="welcome.first_description"></div>
                            <div class="wt-btnarea">
                                <a :href="postnewjobs" class="wt-btn">{{welcome.first_url_button}}</a>
                            </div>
                        </div>

                        <div class="wt-companycontent">
                            <div class="wt-companyinfotitle">
                                <h2>View Top Students</h2>
                            </div>
                            <div class="wt-description" v-html="welcome.second_description"></div>
                            <div class="wt-btnarea">
                                <a href="#" class="wt-btn">{{ welcome.second_url_button }}</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="row" v-if="this.user_id=='3'">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="wt-companydetails">
                        <div class="wt-companycontent studentsellresource">
                            <div class="wt-companyinfotitle">
                                <h2>Connect with Peers</h2>
                            </div>
                            <div class="wt-description">Sign up to the chuta network to connect and collaborate with peers</div>
                            <div class="wt-btnarea">
                                <a href="#" class="wt-btn">{{welcome.first_url_button}}</a>
                            </div>
                        </div>

                        <div class="wt-companycontent">
                            <div class="wt-companyinfotitle">
                                <h2>View Latest Jobs</h2>
                            </div>
                            <div class="wt-description">Sign up to the chuta network to view and apply for student centred jobs from Australia’s best businesses.</div>
                            <div class="wt-btnarea">
                                <a :href="'search-results?type=job'" class="wt-btn">{{ welcome.second_url_button }}</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            </template>
           <template v-else>
              <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="wt-companydetails">
                        <div class="wt-companycontent homesellresource">
                            <div class="wt-companyinfotitle">
                                <h2>Connect with Peers</h2>
                            </div>
                            <div class="wt-description">Sign up to the chuta network to connect and collaborate with peers</div>
                            <div class="wt-btnarea">
                                <a :href="welcome.first_url" class="wt-btn">{{welcome.first_url_button}}</a>
                            </div>
                        </div>
                        <div class="wt-companycontent">
                            <div class="wt-companyinfotitle">
                                <h2>View Latest Jobs</h2>
                            </div>
                            <div class="wt-description">Sign up to the chuta network to view and apply for student centred jobs from Australia’s best businesses.</div>
                            <div class="wt-btnarea">
                                <a :href="'search-results?type=job'" class="wt-btn">{{ welcome.second_url_button }}</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
           </template>

        </div>
    </section>
</template>

<script>
export default {
    props:['parent_index', 'element_id', 'welcome_data', 'type', 'page_id'],
    data() {
        return {
            url:APP_URL,
            welcome:{},
            user_id:'',
            imageURL:APP_URL+'/uploads/pages/'+this.page_id+'/',
             postnewjobs: 'dashboard/packages/employer',
            style: {
                background: '',
                backgroundImage: ''
            }
        }
    },
    methods:{
        
            getuser:function(){
             let self = this;
             console.log('runnnnnnn');
                axios.get(APP_URL + '/search/getuserss')
                .then(function (response) {
                    if ( response.data.type == 'success') {
                    self.user_id = response.data.roles.role_id;
                        console.log(response);
                        console.log(self.user_id);
                        console.log('sssssssssssssssssss');
                        
                        
                        

                    }
                });
            }


    },

     mounted() {

            this.getuser();
               
            },

    created: function() {
        var index = this.getArrayIndex(this.welcome_data, 'id', this.element_id)
        if (this.welcome_data[index]) {
            this.welcome = this.welcome_data[index]
            if (this.welcome.welcome_background) {
                this.style.backgroundImage = 'url('+this.imageURL+'/'+this.welcome.welcome_background+')' 
            } else {
                this.style.background = this.welcome.sectionColor
            }
        }
        this.welcome.parentIndex = this.parent_index
    },
};
</script>
