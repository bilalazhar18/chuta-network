<template>
    <div>
        <div class="wt-formtheme wt-skillsform">
            <transition name="fade">
                <div v-if="selected=='no meeting booked'" class="sj-jump-messeges">{{ trans('lang.no_meeting_booked') }}</div>
            </transition>
        </div>
        <div class="wt-formtheme wt-skillsform">
            <transition name="fade">
                <div v-if="selected=='meeting not completed'" class="sj-jump-messeges">{{ trans('lang.meeting_not_completed') }}</div>
            </transition>
        </div>        
        <div class="preloader-section" v-if="selected == ''" v-cloak>
            <div class="preloader-holder">
                <div class="loader"></div>
            </div>
        </div>        
        <div class="wt-formtheme wt-skillsform" v-if="selected !== 'no meeting booked' && selected !== 'meeting not completed' && selected !== ''">
            <fieldset>
                <div class="courses-input-fields">
                    <div>{{trans('lang.select_meeting_slots')}}</div>
                    <span class="wt-select">
                        <select id="student_skill" @change="changeMeetingSlot">
                            <option v-for="(stored_skill, index) in meeting_slots" :key="index.id" :value="stored_skill">{{stored_skill}}</option>
                        </select>
                    </span>
                </div>
                <div class="courses-input-fields">
                    <div>{{trans('lang.select_student')}}</div>
                    <span class="wt-select">
                        <select id="student_skill" @change="getFriendId">
                            <option v-for="(received_req, index) in student_friends" :key="index.id" :value="received_req.first_name+received_req.last_name">{{received_req.first_name}} {{received_req.last_name}}</option>
                        </select>
                    </span>
                </div>
                <div class="checkbox-manual-amount">
                    <div>{{trans('lang.manual_amount_check')}}</div>
                    <switch_button v-model="manualInsertion"></switch_button>
                </div>
                <div class="courses-input-fields" v-if="manualInsertion==true">
                    <div>{{trans('lang.enter_amount')}}</div>
                    <input v-model="finalAmountManual" type="number" placeholder="Enter Amount in $" class="form-control">
                </div>
                <div class="courses-input-fields">
                    <div>{{trans('lang.ph_desc')}}</div>
                    <textarea class="form-control" id="description_of_meeting" placeholder="Enter Description of Bill (Optional)"></textarea>
                </div>
                <div class="amount-of-invoice-manual" v-if="manualInsertion==true">
                    <div>Bill for this meeting: {{paymentFunctionManual()}} $ </div>
                </div>
                <div class="amount-of-invoice-default" v-if="manualInsertion==false">
                    <div>Bill for this meeting: {{paymentFunctionDefault()}} $ </div>
                </div>                

                <div class="btn-course-add">
                    <a href="javascript:void(0);" class="wt-btn" @click="sendBill">{{trans('lang.send_bill')}}</a>
                </div>
            </fieldset>
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
                meeting_slots:[],
                meeting: {
                    date_time:null,
                    display_date_time:null,
                    description:"",
                    count: 0,
                },
                selected: '',
                finalAmountDefault: 0,
                finalAmountManual: 0,
                manualInsertion: false,
                student_friends: [],
                meetings: [],
                durationOfMeeting: [],
                meetingSlotIndex: 0,
                hourlyRate: 0,
                counts:0,
                userId: null,
                receiverId: null,
                selectedIndex: 0,
                notificationSystem: {
                options: {
                    success: {
                        position: "topRight",
                        timeout: 4000
                        },
                    error: {
                        position: "topRight",
                        timeout: 7000
                        }
                    }
                },
            }
        },
        methods: {
            showError(error){
                return this.$toast.error(' ', error, this.notificationSystem.options.error);
            },
            paymentFunctionManual(){
                return (this.finalAmountManual);
            },
            paymentFunctionDefault(){
                return ((this.finalAmountDefault * this.hourlyRate).toFixed(4));
            },
            showInfo(message) {
                return this.$toast.info(' ', message, this.notificationSystem.options.info);
            },
            changeMeetingSlot: function (event) {
                this.selectedIndex = this.meeting_slots.indexOf(event.target.value);
                this.finalAmountDefault = this.durationOfMeeting[this.selectedIndex];
            },
            getFriendId: function (event) {
                console.log(event.target.selectedIndex);
                console.log("self.student_friends", this.student_friends);
                console.log(this.student_friends[event.target.selectedIndex]['id']);
                this.receiverId = this.student_friends[event.target.selectedIndex]['user_id'];
            },
            getMeetingsSentData(){
                let self = this;
                axios.get(APP_URL + '/student/get-rooms')
                .then(function (response) {
                    if(response.data.type == "success"){
                        self.meeting_slots = response.data.timeStamp;
                        self.durationOfMeeting = response.data.durationOfMeeting;
                        self.selected = response.data.durationOfMeeting[0];
                        self.finalAmountDefault = response.data.durationOfMeeting[0];
                        self.hourlyRate = response.data.hourlyRate;
                        self.userId = response.data.sender;
                        console.log("response of rooms", response);
                    } else if(response.data.type == "noMeetingBooked"){
                        console.log("You dont have any meeting booked!")
                        self.selected = "no meeting booked"
                    } else if(response.data.type == "meetingNotCompleted"){
                        console.log("Please complete your meeting first!")
                        self.selected = "meeting not completed"
                    }
                });
            },
            getFriends(){
                let self = this;
                axios.get(APP_URL + '/student/get-student-friends')
                .then(function (response) {
                    self.student_friends = response.data.student_friends;
                    if (!self.student_friends) return true;
                    console.log("response of friends", response);                
                    self.receiverId = response.data.student_friends[0]['freelancer_id']
                    self.selectedIndex = 0
                });
            },

            sendBill: function () {
                self = this;
                console.log("Should I submit ?")
                let finalPayment = 0;
                if(this.manualInsertion == true){
                    finalPayment = parseFloat(this.paymentFunctionManual());
                    if(parseFloat(this.paymentFunctionManual()) > parseFloat(this.paymentFunctionDefault())){
                        this.showError('You cannot charge amount more than defaulf bill.');
                        return true;
                    }
                } else{
                    finalPayment = parseFloat(this.paymentFunctionDefault());
                }
                const meeting_slot = this.meeting_slots[this.selectedIndex]
                console.log("sender_id: ", this.userId);
                console.log("receiver_Id: ", this.receiverId);
                console.log("amount: ", finalPayment);
                console.log("description: ", document.getElementById("description_of_meeting").value);
                console.log("meeting_slot: ", meeting_slot);
                console.log("status: ", "unpaid");
                console.log("transaction_Id", null);
                axios.post(APP_URL + '/student/invoice-for-meeting', {
                    type: "sendingInvoice",
                    senderId: this.userId,
                    receiverId: this.receiverId,
                    finalPayment: finalPayment,
                    description: document.getElementById("description_of_meeting").value,
                    meetingSlot: meeting_slot,
                    status: "unpaid",
                    transactionId: null
                }).then(function(response){
                    console.log("DONE",response);
                    if(response.data.type == 'error'){
                        self.showError(response.data.message);                        
                    }else{
                        self.showInfo(response.data.message);
                    }
                })
                

            },
        },
        created: function() {
            this.getMeetingsSentData();
            this.getFriends();
        } 
    }
</script>