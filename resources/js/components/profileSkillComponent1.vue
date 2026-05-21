<template>
    <div>
        <div class="wt-formtheme wt-skillsform">
            <transition name="fade">
                <div v-if="isShow" class="sj-jump-messeges">{{ trans('lang.no_record') }}</div>
            </transition>
            <fieldset>
                <div class="courses-input-fields">
                    <div>{{trans('lang.name')}}</div>
                    <span class="wt-select">
                        <select id="student_skill">
                            <option v-for="(stored_skill, index) in stored_skills" :key="index" :value="stored_skill.id">{{stored_skill.title}}</option>
                        </select>
                    </span>
                </div>
                <div class="courses-input-fields">
                    <div>{{trans('lang.starting_date')}}</div>
                    <input type="date" class="form-control" id="starting_date">
                </div>
                <div class="courses-input-fields">
                    <div>{{trans('lang.completing_date')}}</div>
                    <input type="date" class="form-control" id="selected_completing_date">
                </div>
                <div class="courses-input-fields">
                    <input type="hidden" :value="skill.is_save_course" name="is_save_course" id="is_save_course" />
                </div>
                <div class="btn-course-add">
                    <a href="javascript:void(0);" class="wt-btn" @click="addSkill">{{trans('lang.add_courses')}}</a>
                </div>
            </fieldset>
        </div>
        <div class="wt-myskills">
            <ul id="skill_list" class="sortable list">
                <li v-for="(student_skill, index) in student_skills" :key="index" v-if="student_skills" class="skill-element" :ref="'skill-'+index">
                    <div class="wt-dragdroptool">
                        <a href="javascript:void(0)" class="lnr lnr-menu"></a>
                    </div>
                    <span class="skill-dynamic-html">{{student_skill.title}}</span>
                    <span class="skill-dynamic-field sss">
                        {{trans('lang.starting_date')}}
                        <input type="hidden" v-bind:name="'skills['+index+'][id]'" :value="student_skill.id">
                        <input type="date" v-bind:name="'skills['+index+'][starting_date]'" :value="student_skill.pivot.starting_date" v-bind:id="'savedStart'+index">
                    </span>
                    <span class="skill-dynamic-field sss">
                        {{trans('lang.completing_date')}}
                        <input type="hidden" v-bind:name="'skills['+index+'][id]'" :value="student_skill.id">
                        <input type="date" v-bind:name="'skills['+index+'][completing_date]'" :value="student_skill.pivot.completing_date" v-bind:id="'savedComplete'+index">
                    </span>
                    <div class="wt-rightarea">
                        <a href="javascript:void(0);" class="wt-addinfo" @click="editInput(index)"><i class="lnr lnr-pencil"></i></a>
                        <a v-if="skill.is_save_course" href="javascript:void(0);" class="wt-deleteinfo delete-skill" @click="removeStoredSkill(index)"><i class="lnr lnr-trash"></i></a>
                    </div>
                </li>
                <li v-for="(skill, index) in skills" :key="index+skill.count">
                    <div class="wt-dragdroptool">
                        <a href="javascript:void(0)" class="lnr lnr-menu"></a>
                    </div>
                    <span class="skill-dynamic-html">{{skill.title}}</span>
                    <span class="skill-dynamic-field">
                        <input type="hidden" v-bind:name="'skills['+[skill.count]+'][id]'" :value="skill.id">
                    </span>
                    <span class="skill-dynamic-field">
                        {{trans('lang.starting_date')}}
                        <input type="hidden" v-bind:name="'skills['+[skill.count]+'][id]'" :value="skill.id">
                        <input type="date" v-bind:name="'skills['+[skill.count]+'][starting_date]'" :value="skill.starting_date" v-bind:id="'freshStart'+index">
                    </span>
                    <span class="skill-dynamic-field">
                        {{trans('lang.completing_date')}}
                        <input type="hidden" v-bind:name="'skills['+[skill.count]+'][id]'" :value="skill.id">
                        <input type="date" v-bind:name="'skills['+[skill.count]+'][completing_date]'" :value="skill.completing_date" v-bind:id="'freshComplete'+index">
                    </span>
                    <div class="wt-rightarea">
                        <a href="javascript:void(0);" class="wt-addinfo" @click="debug(index)"><i class="lnr lnr-pencil"></i></a>
                        <a v-if="skill.is_save_course" href="javascript:void(0);" class="wt-deleteinfo" @click="removeSkill(index)"><i class="lnr lnr-trash"></i></a>
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
                    starting_date:null,
                    completing_date:null,
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
            debug(index){
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

                console.log("DEBUG FUNCTION")
                console.log("index", index)
                console.log("this.skills[index]",this.skills[index])
                console.log(document.getElementById("freshStart" + index).value);
                console.log(document.getElementById("freshComplete" + index).value);
                this.skills[index]['starting_date'] = document.getElementById("freshStart" + index).value
                this.skills[index]['completing_date'] = document.getElementById("freshComplete" + index).value
            },
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
                    console.log("self.student_skills",self.student_skills);
                });
            },
            addSkill: function () {
                var skillsSelect = document.getElementById("student_skill");
                var startingDateSelect = document.getElementById("starting_date");
                var completingDateSelect = document.getElementById("selected_completing_date");
                var objStartingDate = new Date(startingDateSelect.value);
                var objCompletingDate = new Date(completingDateSelect.value);
                var currentYear = new Date();
                if (objStartingDate.getTime() > objCompletingDate.getTime()) {
                    this.showError('Starting date must be less than Completing date');
                }else if (objStartingDate.getTime() === objCompletingDate.getTime()) {
                    this.showError('Starting date and Completing date must be different');
                }else if (objStartingDate.getFullYear() > currentYear.getFullYear() || objCompletingDate.getFullYear() > currentYear.getFullYear()) {
                    this.showError('You cannot enter future date');
                }else if(objStartingDate.getFullYear() < 1980){
                    this.showError('Starting date cannot be before 1980');
                }else if (skillsSelect.value === "" || startingDateSelect.value === "" || completingDateSelect.value === "") {
                    this.showError('empty field not allow');
                } else {
                    var skill_list_count = jQuery('.wt-btn').parents('.wt-skillsform').next('.wt-myskills').find('ul#skill_list li').length;
                    skill_list_count = skill_list_count - 1;
                    this.skill.count = skill_list_count;
                    
                    if(skillsSelect.options[skillsSelect.selectedIndex]) {
                        this.selected_skill_text = skillsSelect.options[skillsSelect.selectedIndex].text;
                        this.selected_skill = document.getElementById("student_skill").value;
                        this.selected_starting = document.getElementById("starting_date").value;
                        this.selected_completing = document.getElementById("selected_completing_date").value;
                        this.skills.push(Vue.util.extend({}, this.skill, this.skill.count++, this.skill.title = this.selected_skill_text, this.skill.id = this.selected_skill, this.skill.starting_date = this.selected_starting, this.skill.completing_date=this.selected_completing ))
                        skillsSelect.remove(skillsSelect.selectedIndex);
                    } else {
                        this.isShow = true;
                        var self = this;
                        setTimeout(function () {
                            self.isShow = false;
                        }, 3000);
                        
                    }
                }
            },
            removeSkill: function (index) {
                var self = this;
                this.$swal({
                    title: "Delete Skill",
                    text: "Are you Sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                    showLoaderOnConfirm: true
                  }).then((result) => {
                    var self = this;
                    if(result.value) {
                        let option = self.skills[index];
                        var select = document.getElementById("student_skill");
                        select.options[select.options.length] = new Option(option.title, option.id, false, false);
                        self.skills.splice(index, 1);
                        self.$swal('Deleted', 'Skill Deleted', 'success')
                    } else {
                        this.$swal.close()
                    }
                  })
            },
            removeStoredSkill: function (index) {
                var self = this;
                this.$swal({
                    title: "Delete Skill",
                    text: "Are you Sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                    showLoaderOnConfirm: true
                  }).then((result) => {
                    var self = this;
                    if(result.value) {
                        let option = self.student_skills[index];
                        var select = document.getElementById("student_skill");
                        console.log("BEFORE self.student_skills",self.student_skills)
                        select.options[select.options.length] = new Option(option.title, option.id, false, false);
                        self.student_skills.splice(index, 1);
                        console.log("AFTER self.student_skills",self.student_skills)
                        self.$swal('Deleted', 'Skill Deleted', 'success')
                        } else {
                        this.$swal.close()
                    }
                  })
            },
            editInput: function (index) {
                var x = document.getElementsByClassName('wt-skillsactive')
                setTimeout(() => {  
                    console.log("World!", x[0]); 
                        if(x[0]==undefined){
                            this.skill.is_save_course=1;
                            // this.student_skills[index]['pivot']['isSaveCourse'];
                        }else{
                            this.skill.is_save_course=0;
                        }
                    }, 300);
                console.log("FOUND 3.0!", x)
                console.log("FOUND 4.0!", x[0])

                console.log("EDITINPUT FUNCTION")
                console.log("index", index)
                console.log("student_skills[index]['pivot']",this.student_skills[index]['pivot'])
                console.log(document.getElementById("savedStart" + index).value);
                console.log(document.getElementById("savedComplete" + index).value);
                this.student_skills[index]['pivot']['starting_date'] = document.getElementById("savedStart" + index).value
                this.student_skills[index]['pivot']['completing_date'] = document.getElementById("savedComplete" + index).value
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