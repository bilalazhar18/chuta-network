<template>
    <div>
        <div class="wt-formtheme wt-skillsform">
            <transition name="fade">
                <div v-if="isShow" class="sj-jump-messeges">{{ trans('lang.no_record') }}</div>
            </transition>
        </div>
        <div class="wt-myskills">
            <ul id="skill_list" class="sortable list">
                <li v-for="(received_req, index) in student_friends" :key="index" v-if="student_friends" class="skill-element wt-skillsaddinfo" :ref="'skill-'+index">
                    <span class="skill-dynamic-html">{{received_req.title}} </span>
                    <span class="skill-dynamic-field sss">
                        <p> Name: {{received_req.first_name}} {{received_req.last_name}}</p>
                        <p> Description: {{received_req.description}}</p>
                    </span>
                    <figure class="wt-userimg">
                        <img :src="received_req.friend_image" :alt="received_req.first_name">
                    </figure>
                    <div class="wt-rightarea">
                        <a href="javascript:void(0);" class="wt-deleteinfo delete-skill" @click="removeStoredSkill(received_req.id, received_req.user_id, received_req.freelancer_id)"><i class="lnr lnr-trash"></i></a>
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
                isShow: true,
                student_friends: [],
            }
        },
        methods: {
            getUserFriends(){
                let self = this;
                axios.get(APP_URL + '/student/get-student-friends')
                .then(function (response) {
                    console.log("I am resposne",response)
                    self.student_friends = response.data.student_friends;
                    if (!self.student_friends) return true;
                    for(let i=0;i<self.student_friends.length;i++){
                        self.isShow=false;
                    }
                    console.log("self.student_friends Hey!",self.student_friends);
                });
            },
            removeStoredSkill: function (index, senderId, receiverId) {
                var id = index;
                console.log(index, senderId, receiverId);
                axios.post(APP_URL + '/student/delete-from-friends-table', {
                    id: id,
                    senderId: senderId,
                    receiverId: receiverId
                }).then(function(response){
                    console.log("DONE",response)
                });
                var self = this;
                self.student_friends = self.student_friends.filter(function( obj ) {
                    return obj.id !== index;
                });
                if(!self.student_friends[0]){
                    self.isShow = true;
                }                
            },
        },
        created: function() {
            this.getUserFriends();
        } 
    }
</script>