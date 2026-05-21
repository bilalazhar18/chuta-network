<template>
    <div>
        <div class="wt-accordioninnertitle">
                <div style="width: 62%;" v-if="this.stored_project_img" :id="this.main_accordion_id" class="wt-projecttitle collapsed" data-toggle="collapse" :data-target="'#'+this.inner_accordion_id">
                    <div class="resources-with-attachment" style="width:20%;display:inline-block">
                        <div v-if="uploaded_project_image == false" style="width:30%">
                            <div class="dz-preview dz-file-preview">
                            <div>
                                <a :href="stored_project_img">
                                    <i style="font-size:50px;color:linear-gradient(180deg, #d3398a 0%, #7a52d1 100%);" class="fa fa-file" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div>        
                                <a style="margin-left:80%" class="dz-remove" href="javascript:;" @click="removeImage()" >
                                    <span class="lnr lnr-cross"></span>
                                </a>
                                </div>
                            </div>
                        </div>
                        <div :class="img_hidden_id" v-else style="width:70%"></div>                        
                    </div>
                    <div style="width:70%;display:inline-block">
                            <h3>{{this.stored_resource_title}}
                                <span style="height: 60px; width: auto;">{{this.stored_resource_description}}</span>
                            </h3>
                        </div>
                </div>
                <div v-else :id="this.main_accordion_id" class="wt-projecttitle collapsed" data-toggle="collapse" :data-target="'#'+this.inner_accordion_id">
                    <div :class="img_hidden_id"></div>
                    <h3>{{this.stored_resource_title}}<span style="height: 60px; width: auto;">{{this.stored_resource_description}}</span></h3>
                </div>
                
                <div class="wt-rightarea">
                    <a href="javascript:void(0);" class="wt-addinfo wt-skillsaddinfo" ref="storedProjectelement" :id="this.main_accordion_id" data-toggle="collapse"  :data-target="'#'+this.inner_accordion_id" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
                    <a href="javascript:void(0);" class="wt-deleteinfo" @click="removeElement()"><i class="lnr lnr-trash"></i></a>
                </div>
            </div>
            <div class="wt-collapseexp collapse hide" :id="this.inner_accordion_id" :aria-labelledby="this.main_accordion_id" data-parent="#accordion">
                <fieldset style="width: 60%">
                    <div class="form-group resource-input">
                        <p>Enter Title:</p>
                        <input type="text" :value="this.stored_resource_title" v-bind:name="this.resource_title_name" 
                        class="form-control" :placeholder="this.resource_title" 
                        :v-model="this.stored_resource_title">
                    </div>
                    <div class="form-group description-input">
                        <p>Enter Description:</p>
                        <!-- <input type="text" :value="this.stored_resource_description" v-bind:name="this.resource_description_name" class="form-control" :placeholder="this.resource_description"> -->
                        <textarea @change="someHandler" :value="999999999" v-bind:name="this.resource_description_name" class="form-control" :placeholder="this.resource_description"></textarea>
<input v-model="message" placeholder="edit me">
<p>Message is: {{ message }}</p>
                    </div>
                    <div class="form-group price-input">
                        <p>Enter Price:</p>
                        <input type="number" :value="this.stored_resource_price" v-bind:name="this.resource_price_name" class="form-control" placeholder="Enter Price (USD)">
                    </div>                    
                    <div class="form-group" v-if="this.stored_project_img">
                        <div class="wt-labelgroup" v-if="uploaded_project_image">
                            <vue-dropzone :options="dropzoneOptions" :id="this.dropzone_id" :useCustomSlot=true :ref="this.img_ref" @vdropzone-file-added="vfileAdded" v-on:vdropzone-error="failed">
                                <div class="form-group form-group-label test">
                                    <div class="wt-labelgroup">
                                        <label for="file">
                                            <span class="wt-btn">Select Files</span>
                                        </label>
                                        <span>Drop files here to upload</span>
                                    </div>
                                </div>
                            </vue-dropzone>
                            <input type="hidden" v-bind:name="this.img_hidden_name" :id="this.img_hidden_id" value="">
                        </div>
                        <ul class="wt-attachfile" v-else>
                            <li>
                                <span>{{this.stored_image_name}}</span>
                                <em><a class="dz-remove" href="javascript:;" @click="removeImage(img_hidden_id)" :id="this.uploaded_image_remove_id" >
                                        <span class="lnr lnr-cross"></span>
                                    </a>
                                </em>
                                <input type="hidden" v-bind:name="this.img_hidden_name" :id="this.img_hidden_id" :value="this.stored_image_name">
                            </li>
                        </ul>
                    </div>
                    <div v-else>
                        <vue-dropzone :options="dropzoneOptions" :id="this.dropzone_id" :useCustomSlot=true :ref="this.img_ref" @vdropzone-file-added="vfileAdded" v-on:vdropzone-error="failed">
                            <div class="form-group form-group-label test">
                                <div class="wt-labelgroup">
                                    <label for="file">
                                        <span class="wt-btn">Select Files</span>
                                    </label>
                                    <span>Drop files here to upload</span>
                                </div>
                            </div>
                        </vue-dropzone>
                        <input type="hidden" v-bind:name="this.img_hidden_name" :id="this.img_hidden_id" value="">
                    </div>
                </fieldset>
            </div>
    </div>
</template>

<script>
const getImageUploadTemplate = () => `
<div class="wt-uploadingbox">
    <div class="dz-preview dz-file-preview">
        <a :href="stored_project_img">
            <i style="font-size:50px;color:linear-gradient(180deg, #d3398a 0%, #7a52d1 100%);" class="fa fa-file" aria-hidden="true"></i>
        </a>
        <a style="padding-left:30%; background-color:white;" class="lnr lnr-cross" href="javascript:;" data-dz-remove=""></a></figure>        
    </div>
</div>
`;
import vue2Dropzone from 'vue2-dropzone'
//import 'vue2-dropzone/dist/vue2Dropzone.css'
export default {
    props: ['stored_image_name', 'dropzone_id', 'img_hidden_id', 'img_hidden_name', 'img_ref', 'main_accordion_id', 'inner_accordion_id', 'stored_resource_title', 'stored_resource_description','stored_resource_price', 'stored_project_img', 'resource_title_name', 'resource_description_name', 'resource_price_name', 'previewer_class', 'remove_uploded_image_id', 'uploaded_image_remove_id', 'resource_title', 'resource_description', 'resource_price'],
    components: {
        vueDropzone: vue2Dropzone
    },
    data: function () {
        return {
            options: {
            error: {
                position: 'center',
                    timeout: 3000,
                },
            },
            message:"pop",

            image_url:'',
            uploaded_project_image:false,
            stored_projects:[],
            name:this.stored_image_name,
            img_preview: this.getImagePreview(),
            img_previews_container:'.'+this.img_hidden_id,
            img_uploder: this.getImageuploader(),
            dropzoneOptions: {
                url: APP_URL+'/student/upload-a-resource',
                maxFilesize: 10, // MB
                maxFiles: 1,
                previewTemplate: getImageUploadTemplate(),
                previewsContainer: '.'+this.previewerClass(),
                paramName:'project_img',
                //addRemoveLinks: true,
                headers: {
                    'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
                },
                init: function() {
                    var myDropzone = this;
                    var self=this;
                    this.on("addedfile", function(file) {
                        var input_hidden_id = jQuery('#'+myDropzone.element.id).parents('.project-inner-list-item').find('fieldset input[type=hidden]').attr('id');
                        document.getElementById(input_hidden_id).value = file.name;
                    });
                    this.on("removedfile", function(file) {
                        var input_hidden_id = jQuery('#'+myDropzone.element.id).next('input[type=hidden]').attr('id');
                        document.getElementById(input_hidden_id).value = '';
                    });
                }
            }
        }
    },
    methods: {
        someHandler(e){
            console.log(e.target.value);
            console.log("I am Event ", e)
        },
        showError(message){
            return this.$toast.error(' ', message, this.options.error);
        },
        previewerClass() {
            return this.img_hidden_id;
        },
        removeElement() {
            this.$emit('removeElement');
        },
        removeImage: function() {
            this.uploaded_project_image = true;
        },
        getImagePreview() {
            if (this.stored_project_img) {
                return this.img_preview = true;
            } else {
                return this.img_preview = false;
            }
        },
        getImageuploader() {
            if (this.stored_project_img) {
                return this.img_uploder = true;
            } else {
                return this.img_uploder = false;
            }
        },
        vfileAdded(file) {
            console.log("file", file)
            console.log(this.$refs[this.img_ref].id);
        },
        failed:function(file,message,xhr){
            if (file.type != this.$refs[this.img_ref].options.acceptedFiles) {
                if (message == 'You can not upload any more files.') {
                    message = 'you need to remove file before uploading new one.'
                }
                this.showError(message);
                this.$refs[this.img_ref].removeFile(file);
                var input_hidden_id = jQuery('#'+this.$refs[this.img_ref].id).parents('.wt-settingscontent').find('.wt-userform input[type=hidden]').attr('id');
                document.getElementById(input_hidden_id).value = '';
            }
        }
    },
    mounted: function () {
        console.log("THIS", this)
        // if(this.stored_project_img){
        //     this.image_url = APP_URL+"/uploads/users/"+USERID+"/projects/"+this.stored_project_img;
        // }
    },
    created: function() {}
}
</script>
