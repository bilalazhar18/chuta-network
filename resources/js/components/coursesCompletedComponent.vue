
<template>
    <div>
        <div class="wt-tabscontenttitle wt-addnew">
            <h2>Tutor Form</h2>
        </div>
        <div class="wt-formtheme wt-skillsform">
            <transition name="fade">
                <div v-if="isShow" class="sj-jump-messeges">{{ trans('lang.no_record') }}</div>
            </transition>
            <fieldset>
                <div class="courses-input-fields">
                    <input type="hidden" :value="skill.is_tutor" name="is_tutor" id="is_tutor" />
                    <input type="hidden" :value="skill.is_save_course" name="is_save_course" id="is_save_course" />

                </div>                
            </fieldset>

            
        </div>
        <div class="wt-myskills">
         <fieldset>
                <div class="courses-input-fields">
                   <h6>You can Tutor For</h6>

                </div>                
            </fieldset>
            <ul id="skill_list" class="sortable list">
                <li v-for="(student_skill, index) in student_skills" :key="index" v-if="student_skills" class="skill-element" :ref="'skill-'+index">
                    <div class="wt-dragdroptool">
                        <a href="javascript:void(0)" class="lnr lnr-menu"></a>
                    </div>
                    <span class="skill-dynamic-html">{{student_skill.title}}</span>
                    <span class="skill-dynamic-field sss">
                       <strong>Mark as Tutor</strong>
                        <input type="hidden" v-bind:name="'skills['+index+'][user_id]'" :value="student_skill.pivot.user_id">
                        <input type="hidden" v-bind:name="'skills['+index+'][id]'" :value="student_skill.id">
                        <switch_button v-model="student_skill.pivot.is_tutor"></switch_button>
                        <input type="hidden" v-bind:name="'skills['+index+'][is_tutor]'" :value="student_skill.pivot.is_tutor" v-bind:id="'storedIsTutor'+index">
                    <label><strong>Course CGPA</strong></label><span><input type="number" v-bind:name="'skills['+index+'][cgpa]'" :value="student_skill.pivot.cgpa" class="form-control" style="background: #f7f7f7" step="any"></span>

                    <label><strong>Add Title</strong></label><span><input type="text" v-bind:name="'skills['+index+'][title]'" class="form-control" style="background: #f7f7f7"></span>

                    <label><strong>Tutor Description</strong></label><span><textarea  v-bind:name="'skills['+index+'][description]'" class="form-control" style="background: #f7f7f7"></textarea></span>

                     <label><strong>Tutoring Price</strong></label><span><input type="number" class="form-control" v-bind:name="'skills['+index+'][price]'" style="background: #f7f7f7"></span>

                      <label><strong>Upload Resource File</strong></label><span><input type="file" class="form-control" accept="image/png,image/jpeg,.pdf" v-bind:name="'skills['+index+'][file]'" style="background: #f7f7f7"></span>


                       <label><strong>Upload Resource Preview Image</strong></label><span><input type="file" class="form-control" accept="image/png,image/jpeg,.pdf" v-bind:name="'skills['+index+'][image_preview]'" style="background: #f7f7f7"></span>


                    </span>
                    <div class="wt-rightarea">
                        <a href="javascript:void(0);" class="wt-addinfo" @click="editInput(index)"><i class="lnr lnr-pencil"></i></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
<script>
 export default{
    props: ['widget_title', 'ph_rate_skills'],
        data(){
            return {
                isShow: false,
                stored_skills:[],
                selected_skill: '',
                selected_skill_text:'',
                edit_class: false,
                edit_skill: '',
                skill: {
                    id: '',
                    is_tutor:false,
                    title:'',
                    count: 0,
                    is_save_course:1
                },
                skills: [],
                student_skills: [],
                counts:0,
                notificationSystem: {
                    error: {
                        position: "topRight",
                        timeout: 4000
                    }
                },
            }
        },
        methods: {
            showError(error){
                return this.$toast.error(' ', error, this.notificationSystem.error);
            },
            getSkills(){
                let self = this;
                axios.get(APP_URL + '/get-student-skills')
                .then(function (response) {
                    self.stored_skills = response.data.skills;
                });
            },
            getUserSkills(){
                let self = this;
                axios.get(APP_URL + '/student/get-student-skills')
                .then(function (response) {
                    self.student_skills = response.data.student_skills;
                    for(let i=0;i<self.student_skills.length;i++){
                        self.student_skills[i].pivot.is_tutor==1?self.student_skills[i].pivot.is_tutor=true:self.student_skills[i].pivot.is_tutor=false;
                    }
                    console.log("self.student_skills",self.student_skills);
                });
            },
            editInput: function (index) {
                var x = document.getElementsByClassName('wt-skillsactive')
                setTimeout(() => {  
                    console.log("World!", x[0]); 
                        if(x[0]===undefined){
                            this.skill.is_save_course=1;
                            // this.student_skills[index]['pivot']['isSaveCourse'];
                        }else{
                            this.skill.is_save_course=0;
                        }
                    }, 300);
                this.edit_class = true;
            }
        },
        mounted: function () {
            jQuery(document).on('click', '.wt-addinfo', function (e) {
                e.preventDefault();
                var _this = jQuery(this);
                _this.addClass('wt-skillsactive');
                _this.parents('li').addClass('wt-skillsaddinfo');
            });
            jQuery(document).on('click', '.wt-skillsactive', function (e) {
                e.preventDefault();
                var _this = jQuery(this);
                _this.removeClass('wt-skillsactive');
                _this.parents('li').removeClass('wt-skillsaddinfo');
            });
        },
        created: function() {
            this.getSkills();
            this.getUserSkills();
        } 
    }
</script>