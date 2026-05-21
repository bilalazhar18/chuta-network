<template>
    <div>
        <div class="wt-tabscontenttitle wt-addnew">
            <h2>{{trans('lang.add_your_resource')}}</h2>
            <a href="javascript:void(0);" @click="addResource" class="add-project-btn">{{trans('lang.add_resource')}}</a>
        </div>
        <ul class="wt-experienceaccordion accordion" id="project-list">
            <span v-if="stored_resources" class="project-inner-list">
                 <li v-for="(project, index) in stored_resources" :key="index" class="project-element project-inner-list-item" :id="'project-element-'+index">
                    <div class="wt-accordioninnertitle">
                        <div class="dz-preview dz-file-preview">
                            <a :href="project.project_hidden_image">
                                <i style="font-size:50px;color:linear-gradient(180deg, #d3398a 0%, #7a52d1 100%);" class="fa fa-file" aria-hidden="true"></i>
                            </a>
                        </div>                        
                        <div style="width:60%" :id="'projectaccordion['+project.count+']'" class="wt-projecttitle collapsed" data-toggle="collapse" :data-target="'#projectaccordioninner['+project.count+']'">
                            <div style="width:22%;" :class="project.preview_class"></div>
                            <h3>{{project.resource_title}}<span style="height: 60px; width: auto;">{{project.resource_description}}</span></h3>
                        </div>
                        <div class="wt-rightarea">
                            <a href="javascript:void(0);" class="wt-addinfo wt-skillsaddinfo" :id="'projectaccordion['+project.count+']'" data-toggle="collapse" :data-target="'#projectaccordioninner['+project.count+']'" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
                            <a href="javascript:void(0);" class="wt-deleteinfo delete-project"><i class="lnr lnr-trash"></i></a>
                        </div>
                    </div>
                    <div class="wt-collapseexp collapse hide" :id="'projectaccordioninner['+project.count+']'" :aria-labelledby="'projectaccordion['+project.count+']'" data-parent="#accordion">
                        <fieldset style="width: 60%">
                            <div class="form-group">
                                <p>Enter Title:</p>
                                <input type="text" class="form-control" :placeholder="ph_resource_title" >
                            </div>
                            <div class="form-group">
                                <p>Enter Description:</p>
                                <textarea class="form-control" :placeholder="ph_resource_description"></textarea>
                            </div>
                            <div class="form-group">
                                <p>Enter Price (USD):</p>
                                <input type="number" class="form-control" placeholder="Enter Price (USD)">
                            </div>
                            <div class="form-group" style="display:none">
                                <ul class="wt-attachfile">
                                    <li>
                                        <span class="uploaded-img-name"></span>
                                        <em><a class="dz-remove" href="javascript:;" :id="'remove-uploded-image-'+project.count" @click="removeUploadedImage($event)" >
                                                <span class="lnr lnr-cross"></span>
                                            </a>
                                        </em>
                                    </li>
                                </ul>
                            </div>
                            <uploadimage :option="option" :id="'profile_banner'+'-'+project.count" :img_ref="'profile_banner_ref'+'_'+project.count"></uploadimage>
                            <input type="hidden" :id="'hidden_banner-'+project.count">
                        </fieldset>
                    </div>
                </li>
            </span>
            <span class="project-inner-list">
                <li v-for="(project, index) in resources" :key="index" ref="projectlistelement" class="project-inner-list-item">
                    <div class="wt-accordioninnertitle">
                        <div style="width:60%" :id="'projectaccordion['+project.count+']'" class="wt-projecttitle collapsed" data-toggle="collapse" :data-target="'#projectaccordioninner['+project.count+']'">
                            <div style="width:22%;" :class="project.preview_class"></div>
                            <h3>{{project.resource_title}}<span style="height: 60px; width: auto;">{{project.resource_description}}</span></h3>
                        </div>
                        <div class="wt-rightarea">
                            <a href="javascript:void(0);" class="wt-addinfo wt-skillsaddinfo" :id="'projectaccordion['+project.count+']'" data-toggle="collapse" :data-target="'#projectaccordioninner['+project.count+']'" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
                            <a href="javascript:void(0);" class="wt-deleteinfo delete-project"><i class="lnr lnr-trash"></i></a>
                        </div>
                    </div>
                    <div class="wt-collapseexp collapse hide" :id="'projectaccordioninner['+project.count+']'" :aria-labelledby="'projectaccordion['+project.count+']'" data-parent="#accordion">
                        <fieldset style="width: 60%">
                            <div class="form-group resource-input">
                                <p>Enter Title:</p>
                                <input type="text" v-bind:name="'project['+[project.count]+'][resource_title]'" class="form-control" :placeholder="ph_resource_title" v-model="project.resource_title">
                            </div>
                            <div class="form-group description-input">
                                <p>Enter Description:</p>
                                <textarea v-bind:name="'project['+[project.count]+'][resource_description]'" class="form-control" :placeholder="ph_resource_description" v-model="project.resource_description"></textarea>
                            </div>
                            <div class="form-group price-input">
                                <p>Enter Price (USD):</p>
                                <input type="number" v-bind:name="'project['+[project.count]+'][resource_price]'" class="form-control" placeholder="Enter Price (USD)" v-model="project.resource_price">
                            </div>
                            <div class="form-group image_uploaded_placeholder" style="display:none">
                            <ul class="wt-attachfile">
                                <li>
                                    <span class="uploaded-img-name"></span>
                                    <em><a class="dz-remove" href="javascript:;" :id="'remove-uploded-image-'+project.count" @click="removeUploadedImage($event)" >
                                            <span class="lnr lnr-cross"></span>
                                        </a>
                                    </em>
                                </li>
                            </ul>
                            </div>
                            <uploadimage :option="project.option" :id="project.img_id+'-'+project.count" :img_ref="project.img_ref+'_'+project.count"></uploadimage>
                            <input type="hidden" v-bind:name="'project['+project.count+'][project_hidden_image]'" :id="'hidden_banner-'+project.count">
                        </fieldset>
                    </div>
                </li>
            </span>
        </ul>
    </div>
</template>
<script>
const getImageUploadTemplate = () => `
<div style="border:none" class="wt-uploadingbox">
    <div class="dz-preview dz-file-preview">
        <a style="color:!inherit" :href="stored_project_img">
            <i style="font-size:50px;" class="fa fa-file" aria-hidden="true"></i>
        </a>        
        <a style="padding-left:30%; background-color:white;" class="lnr lnr-cross" href="javascript:;" data-dz-remove=""></a></figure>
    </div>
</div>
`;
import dateTime from './DateTimeComponent'
import uploadimage from './ProjectAwardUploadComponent'
import updateProject from './EditProjectComponent'
export default{
    components: {dateTime, uploadimage, updateProject},
    props: ['widget_title', 'ph_resource_title', 'ph_resource_description', 'ph_resource_price' ],
        data(){
            return {
                start_date: '',
                end_date: '',
                stored_resources:[],
                option:{
                        url: APP_URL+'/student/upload-a-resource',
                        maxFilesize: 2, // MB
                        maxFiles: 1,
                        previewTemplate: getImageUploadTemplate(),
                        previewsContainer: null,
                        paramName:'project_img',
                        headers: {
                            'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
                        },
                        init: function() {
                            var myDropzone = this;
                            this.on("addedfile", function(file) {
                                var fileName = file.name;
                                // console.log(fileName.replace(/\s/g,''))
                                var input_hidden_id = jQuery('#'+myDropzone.element.id).next('input[type=hidden]').attr('id');
                                document.getElementById(input_hidden_id).value = file.name;
                                jQuery('#'+myDropzone.element.id).css("display","none");
                                jQuery('#'+myDropzone.element.id).parents('.project-inner-list-item').find('.image_uploaded_placeholder').css("display","block");
                                jQuery('#'+myDropzone.element.id).parents('.project-inner-list-item').find('.image_uploaded_placeholder ul li span.uploaded-img-name').text(file.name);
                            });
                            this.on("removedfile", function(file) {
                                document.getElementById('hidden_banner').value = '';
                            });
                        }
                    },                
                project: {
                    image_uploaded: false,
                    resource_title: this.ph_resource_title,
                    project_hidden_image:'',
                    resource_description: this.ph_resource_description,
                    resource_price: 0,
                    count: 0,
                    img_id: 'profile_banner',
                    img_ref: 'profile_banner_ref',
                    preview_class:'dropzone-previews',
                    option:{
                        url: APP_URL+'/student/upload-a-resource',
                        maxFilesize: 2, // MB
                        maxFiles: 1,
                        previewTemplate: getImageUploadTemplate(),
                        previewsContainer: '.dropzone-previews',
                        paramName:'project_img',
                        headers: {
                            'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
                        },
                        init: function() {
                            var myDropzone = this;
                            this.on("addedfile", function(file) {
                                var fileName = file.name;
                                // console.log(fileName.replace(/\s/g,''))
                                var input_hidden_id = jQuery('#'+myDropzone.element.id).next('input[type=hidden]').attr('id');
                                document.getElementById(input_hidden_id).value = file.name;
                                jQuery('#'+myDropzone.element.id).css("display","none");
                                jQuery('#'+myDropzone.element.id).parents('.project-inner-list-item').find('.image_uploaded_placeholder').css("display","block");
                                jQuery('#'+myDropzone.element.id).parents('.project-inner-list-item').find('.image_uploaded_placeholder ul li span.uploaded-img-name').text(file.name);
                            });
                            this.on("removedfile", function(file) {
                                document.getElementById('hidden_banner').value = '';
                            });
                        }
                    },
                },
                resources: [],
            }
        },
        methods: {
            getResources(){
                let self = this;
                axios.get(APP_URL + '/student/get-student-projects')
                .then(function (response) {
                    console.log("Response of Resources", response)
                    if(response.data.type == 'success') {
                        self.stored_resources = response.data.projects;
                        console.log("self.stored_resources", self.stored_resources)
                    }
                });
            },
            addResource: function () {
                var expereience_list_count = jQuery('.add-project-btn').parents('.wt-tabsinfo').find('ul#project-list span.project-inner-list li').length;
                var image_placeholder_count = jQuery('.add-project-btn').parents('.wt-tabsinfo').find('ul#project-list span.project-inner-list li').find('figure.dropzone-previews').length;
                if(this.$refs.projectlistelement) {
                    this.project.count = expereience_list_count + this.$refs.projectlistelement.length;
                } else {
                    this.project.count = expereience_list_count -1;
                }
                if(image_placeholder_count) {
                    image_placeholder_count++
                }
                console.log("Before: ", this.project)
                this.resources.push(Vue.util.extend({}, this.project, this.project.count++, this.project.preview_class = this.project.preview_class+'-'+image_placeholder_count ))
                console.log("this.resources",this.resources)
                this.project.option.previewsContainer = this.project.option.previewsContainer+'-'+image_placeholder_count;
            },
            removeStoredProject: function (index) {
                this.stored_resources.splice(index, 1);
            },
            removeUploadedImage: function (event) {
                var element = event.currentTarget;
                var elementID = element.getAttribute('id');
                jQuery('#'+elementID).parents('.image_uploaded_placeholder').css("display","none");
                jQuery('#'+elementID).parents('.project-inner-list-item').find('.wt-uploadingbox').remove();
                jQuery('#'+elementID).parents('.project-inner-list-item').find('.vue-dropzone').css("display","block");
            }
        },
        mounted: function () {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();
            this.start_date = yyyy + '-' + mm + '-' + dd;
            this.end_date = yyyy + '-' + mm + '-' + dd;
            this.project.start_date = yyyy + '-' + mm + '-' + dd;
            this.project.end_date = yyyy + '-' + mm + '-' + dd;
            jQuery(document).on('click', '.delete-project', function (e) {
                e.preventDefault();
                var _this = jQuery(this);
                _this.parents('.project-inner-list-item').remove();
            });
        },
        created: function() {
            this.getResources();
        }
    }
</script>
