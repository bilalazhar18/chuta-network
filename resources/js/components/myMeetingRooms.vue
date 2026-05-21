<template>
    <div>
        <div class="wt-formtheme wt-skillsform">
            <transition name="fade">
                <div v-if="isShow" class="sj-jump-messeges">{{ trans('lang.no_course') }}</div>
            </transition>
        </div>
        <div>
            <ul id="skill_list" class="sortable list">
                <li v-for="(meeting_room, index) in meeting_rooms" :key="index" v-if="meeting_rooms" class="skill-element wt-skillsaddinfo" :ref="'skill-'+index" style="list-style-type: none;">
                    <b>Description:</b> {{meeting_room.description}}<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; <i>Date & time:</i> {{meeting_room.date_time}}<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; <i>Room name:</i>  {{meeting_room.room_name}}<br>
                    &nbsp;&nbsp;&nbsp;&nbsp; <i>Video URL:</i> 
                    <a :href="meeting_room.link_url">Click me to open video link</a>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
 export default{
    props: ['widget_title', 'ph_rate_skills'],
        data(){
            return {
                isShow: false,
                meeting_rooms: [],
                counts:0,                
            }
        },
        methods: {
            getUserMeetingRooms(){
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
                            self.meeting_rooms.push(response.data.meetings[i])
                        }
                    }

                    console.log("expired rooms", expiredRooms)
                    }
                });
            },
        },
        created: function() {
            this.getUserMeetingRooms();
        } 
    }
</script>