<template>
    <div>
        <div class="wt-formtheme wt-skillsform">
            <transition name="fade">
                <div v-if="isShow" class="sj-jump-messeges">{{ trans('lang.no_course') }}</div>
                <div v-if="noFriendsMessage" class="sj-jump-messeges">{{ trans('lang.no_friend') }}</div>
            </transition>
            <fieldset v-if="!isShow && !noFriendsMessage">
                <div class="courses-input-fields">
                    <div>{{trans('lang.select_student')}}</div>
                    <span class="wt-select">
                        <select id="student_skill" @change="getFriendId">
                            <option v-for="(received_req, index) in student_friends" :key="index.id" :value="received_req.first_name+received_req.last_name">{{received_req.first_name}} {{received_req.last_name}}</option>
                        </select>
                    </span>
                </div>                
                <div class="courses-input-fields">
                    <div>{{trans('lang.select_date')}}</div>
                    <input type="datetime-local" class="form-control" id="date_time">
                </div>
                <div class="courses-input-fields">
                    <div>{{trans('lang.ph_desc')}}</div>
                    <textarea class="form-control" id="description_of_meeting" placeholder="Description of meeting"></textarea>
                </div>
                <div class="btn-course-add">
                    <a href="javascript:void(0);" class="wt-btn" @click="bookMeeting">{{trans('lang.book_meeting')}}</a>
                </div>
            </fieldset>
        </div>
        <div>
            <ul id="skill_list" class="sortable list">
                <li v-for="(student_skill, index) in student_skills" :key="index" v-if="student_skills" class="skill-element wt-skillsaddinfo" :ref="'skill-'+index" style="list-style-type: none;">
                    <b>Description:</b> {{student_skill.description}}<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; <i>Date & time:</i> {{student_skill.date_time}}<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; <i>Room name:</i>  {{student_skill.room_name}}<br>
                </li>
                <li v-for="(meeting, index) in meetings" :key="index+meeting.count" style="list-style-type: none;">
                    <input type="hidden" v-bind:name="'browserTime'" :value="dateAfterTomorrow" v-bind:id="index">
                    <b>Description:</b> {{meeting.description}}<br>
                    <textarea  style="display:none;" type="hidden" class="form-control" id="description_of_meeting"  v-bind:name="'meetings['+[meeting.count]+'][description]'" :value="meeting.description" v-bind:id="index"></textarea>
                    &nbsp;&nbsp;&nbsp;&nbsp; <i>Date & time:</i> {{meeting.display_date_time}}<br>
                    <input type="hidden" v-bind:name="'meetings['+[meeting.count]+'][date_time]'" :value="meeting.date_time" v-bind:id="index">
                    &nbsp;&nbsp;&nbsp;&nbsp; <i>Room name:</i>  {{meeting.roomName}}<br>
                    <input type="hidden" v-bind:name="'meetings['+[meeting.count]+'][roomName]'" :value="meeting.roomName" v-bind:id="index">
                    <input type="hidden" v-bind:name="'meetings['+[meeting.count]+'][receiverId]'" :value="receiverId" v-bind:id="index">
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
                noFriendsMessage: false,
                stored_skills:[],
                selected_skill: '',
                selected_skill_text:'',
                edit_class: true,
                edit_skill: '',
                dateAfterTomorrow:null,
                meeting: {
                    date_time:null,
                    display_date_time:null,
                    description:"",
                    count: 0,
                },
                meetings: [],
                student_friends: [],
                receiverId: null,
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
            getUserFriends(){
                let self = this;
                axios.get(APP_URL + '/student/get-student-friends')
                .then(function (response) {
                    console.log("I am resposne", response)
                    self.student_friends = response.data.student_friends;
                    if (!self.student_friends){
                        console.log("self.student_friends Hey!",self.student_friends);
                        self.student_friends == undefined ? self.noFriendsMessage = true: self.noFriendsMessage = false;                        
                        return true;
                    }
                    self.receiverId = response.data.student_friends[0]['picture_id']                     
                });
            },            
            getFriendId: function (event) {
                console.log(event.target.selectedIndex);
                console.log("self.student_friends", this.student_friends);
                console.log(this.student_friends[event.target.selectedIndex]['id']);
                this.receiverId = this.student_friends[event.target.selectedIndex]['picture_id'];
            },
            getUserSkills(){
                let self = this;
                var currentDate = new Date();
                axios.get(APP_URL + '/student/get-booked-meetings')
                .then(function (response) {
                    const expiredRooms = [];
                    console.log('response.data.meetings', response.data.meetings)
                    
                    if(response.data.meetings !== undefined){
                    let lengthOfMeetings = response.data.meetings.length;
                    console.log("lengthOfMeetings", lengthOfMeetings);
                    for(let i = 0; i < lengthOfMeetings; i++){
                        console.log("strande", response.data.meetings[i])
                        if(((currentDate - new Date(response.data.meetings[i]['date_time'])) / 36e5) > 24 ){
                            expiredRooms.push(response.data.meetings[i]['room_name']);
                        }
                    }

                    for(let i = 0; i < lengthOfMeetings; i++){
                        if(!expiredRooms.includes(response.data.meetings[i]['room_name'])){
                            console.log(response.data.meetings[i]['room_name'],' demands deletion')
                            // expiredRooms.push(response.data.meetings[i]['room_name']);
                            self.student_skills.push(response.data.meetings[i])
                        }
                    }

                    console.log("expired rooms", expiredRooms)
                    }
                });
            },
            insertDataInMeetings(count, meetingDateSelect, 
                        meetingDescriptionSelect, randomRoomName, receiverId){
                console.log(meetingDateSelect.replace(/ T/g," "));
                console.log(typeof(meetingDateSelect))
                this.meetings.push(
                Vue.util.extend({}, 
                    this.meeting, count, 
                    this.meeting.display_date_time=meetingDateSelect.replace(/T/g," ")+":00",
                    this.meeting.date_time = meetingDateSelect, 
                    this.meeting.description = meetingDescriptionSelect,
                    this.meeting.roomName = randomRoomName,
                    this.meeting.receiverId = receiverId ))
            
                console.log("After", this.meetings)
            },

            bookMeeting: function () {
                var meetingDateSelect = document.getElementById("date_time");
                var meetingDescriptionSelect = document.getElementById("description_of_meeting");
                var meetingDescriptionSelect = document.getElementById("description_of_meeting");
                
                let randomRoomName = Math.random().toString(36).substring(7);
                
                var currentDateSelect = new Date();
                var objMeetingDate = new Date(meetingDateSelect.value);

                this.dateAfterTomorrow = currentDateSelect.getFullYear() + '-' + 
                    ((currentDateSelect.getMonth() + 1) >= 10 ? (currentDateSelect.getMonth() + 1) : '0' + (currentDateSelect.getMonth() + 1)) + '-' + 
                    ((currentDateSelect.getDate() + 1) >= 10 ? ((currentDateSelect.getDate() + 1)) : '0' + (currentDateSelect.getDate() + 1)) + ' ' +
                    (currentDateSelect.getHours() >= 10 ? currentDateSelect.getHours() : '0' + currentDateSelect.getHours()) + ':' + 
                    (currentDateSelect.getMinutes() >= 10 ? currentDateSelect.getMinutes() : '0' + currentDateSelect.getMinutes()) + ':' + 
                    (currentDateSelect.getSeconds() >= 10 ? currentDateSelect.getSeconds() : '0' + currentDateSelect.getSeconds());

                console.log("DATE FORMED!", this.dateAfterTomorrow)
                console.log(currentDateSelect.getDate() + 2)
                console.log(objMeetingDate.getDate())
                console.log("this.noFriendsMessage", this.noFriendsMessage);
                if (this.noFriendsMessage == true) {
                    this.showError("Sorry, you don't have any friends to meet with");
                } else if (objMeetingDate <= currentDateSelect) {
                    this.showError('Please select future date and time');
                } else if (objMeetingDate.getDate() > currentDateSelect.getDate() + 2 || objMeetingDate.getMonth() > currentDateSelect.getMonth()) {
                    this.showError('Please select date with 3 days');
                } else if(this.student_skills !== undefined){
                    console.log("I am here!")
                    if ( this.student_skills.length >=3 || ( this.student_skills.length + this.meetings.length ) >=3 ){
                        this.showError('You cannot book more than 3 meeting at one time');
                    } else{
                        this.insertDataInMeetings(this.meeting.count++, meetingDateSelect.value, 
                        meetingDescriptionSelect.value, randomRoomName, this.receiverId)
                    }
                } else if (this.meetings.length == 3 ){
                    this.showError('You cannot book more than 3 meeting at one time');
                }
                else {
                    console.log("Mayday!")
                        this.insertDataInMeetings(this.meeting.count++, meetingDateSelect.value, 
                        meetingDescriptionSelect.value, randomRoomName, this.receiverId)
                }
            },
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
            this.getUserSkills();
            this.getUserFriends();
        } 
    }
</script>