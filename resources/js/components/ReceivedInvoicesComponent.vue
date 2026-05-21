<template>
    <div>
        <div class="wt-formtheme wt-skillsform">
            <transition name="fade">
                <div v-if="isShow" class="sj-jump-messeges">{{ trans('lang.no_record') }}</div>
            </transition>
        </div>
        <div class="wt-myskills">
            <ul id="skill_list" class="sortable list">
                <li v-for="(received_req, index) in student_requests" :key="index" v-if="student_requests" class="skill-element wt-skillsaddinfo" :ref="'skill-'+index">
                    <span class="skill-dynamic-html">{{received_req.title}}</span>
                    <span class="skill-dynamic-field sss">
                        <p> Name: {{received_req.first_name}} {{received_req.last_name}}</p>
                        <p> Meeting Slot: {{received_req.meeting_slot}}</p>
                        <p> Payment Amount: {{received_req.amount}} $</p>
                        <p> Status: {{received_req.status}}</p>
                    </span>
                    <figure class="wt-userimg">
                        <img :src="received_req.friend_image" :alt="received_req.first_name">
                    </figure>
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
                student_requests: [],
            }
        },
        methods: {
            getUserFriends(){
                let self = this;
                axios.get(APP_URL + '/student/get-received-invoices')
                .then(function (response) {
                    self.student_requests = response.data.student_requests;
                    if (!self.student_requests) return true;
                    for(let i=0;i<self.student_requests.length;i++){
                        self.isShow=false;
                    }
                });
            }
        },
        created: function() {
            this.getUserFriends();
        } 
    }
</script>