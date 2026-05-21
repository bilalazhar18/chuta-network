<template>
  <div>
    <div class="wt-formtheme wt-skillsform">
      <transition name="fade">
        <div v-if="isShow" class="sj-jump-messeges">{{ trans("lang.no_record") }}</div>
      </transition>
    </div>
    <div class="wt-myskills">
      <ul id="skill_list" class="sortable list">
        <li
          v-for="(student_skill, index) in student_skills"
          :key="index"
          v-if="student_skills"
          class="skill-element wt-skillsaddinfo"
          :ref="'skill-' + index"
        >
          <div class="wt-dragdroptool">
            <a href="javascript:void(0)" class="lnr lnr-menu"></a>
          </div>
          <span class="skill-dynamic-field" style="font-size: 30px; padding-top: 20px">
            {{ student_skill.course_name }}
          </span>
          <span class="skill-dynamic-html">{{ student_skill.title }}</span>
          <span class="skill-dynamic-field sss" style="padding-top: 5%">
            Resource Description:<br />
            {{ student_skill.description }}
          </span>
          <span class="skill-dynamic-field sss" style="padding-top: 5%">
            Resource Title:<br />
            {{ student_skill.title }}
          </span>
          <span class="skill-dynamic-field sss" style="padding-top: 5%">
            <a v-if="!student_skill.display_file" :href="student_skill.url_of_file">
              <h6>Download Resource from here<img
                :src="'https://chuta.network/dev/public/uploads/badges/down.jpg'"
                style="width: 50px;margin-left:15px"
              />
              </h6>
              <!-- <img
                :src="'http://127.0.0.1:8000/uploads/badges/down.jpg'"
                style="width: 50px"
              /> -->
            </a>
          </span>
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
export default {
  props: ["widget_title", "ph_rate_skills"],
  data() {
    return {
      isShow: false,
      stored_skills: [],
      selected_skill: "",
      selected_skill_text: "",
      edit_class: false,
      edit_skill: "",
      skill: {
        id: 0,
        resource_description: null,
        resource_title: null,
        resource_price: 0,
        resource_file: "",
        title: "",
        count: 0,
        is_save_course: 1,
      },
      skills: [],
      student_skills: [],
      counts: 0,
      notificationSystem: {
        error: {
          position: "topRight",
          timeout: 4000,
        },
      },
    };
  },
  methods: {
    myFunc(index) {
      console.log("I AM INDEX", index);
      console.log(URL.createObjectURL(index));
      this.skills[sec.count]["url_of_document"] = URL.createObjectURL(index);
      console.log("MODIFIED this.skills", this.skills);
    },
    showError(error) {
      return this.$toast.error(" ", error, this.notificationSystem.error);
    },
    getCourseName(id) {
      console.log("id", id);
      console.log("self.stored_skills", this.stored_skills);
      for (let i = 0; i < this.stored_skills.length; i++) {
        if (parseInt(id) == this.stored_skills[i]["id"]) {
          console.log("It is ", this.stored_skills[i]["title"]);
          return this.stored_skills[i]["title"];
        }
      }
    },
    getSkills() {
      let self = this;
      axios.get(APP_URL + "/get-student-skills").then(function (response) {
        self.stored_skills = response.data.skills;
      });
    },
    getUserSkills() {
      let self = this;
      axios.get(APP_URL + "/student/get-student-resources").then(function (response) {
        // self.student_skills = response.data.student_resources;
        // console.log("self.student_skills",self.student_skills);
      });
    },
    getPurchasedResources() {
      let self = this;
      axios.get(APP_URL + "/student/get-purchased-resources").then(function (response) {
        console.log("RESPONSE", response);
        self.student_skills = response.data.purchased_resources;
        console.log("self.student_skills", self.student_skills);
        if (self.student_skills == undefined) {
          self.isShow = true;
        } else if (self.student_skills.length == 0) {
          self.isShow = true;
        }
      });
    },
  },
  mounted: function () {
    jQuery(document).on("click", ".wt-addinfo", function (e) {
      e.preventDefault();
      var _this = jQuery(this);
      _this.addClass("wt-skillsactive");
      _this.parents("li").addClass("wt-skillsaddinfo");
    });
    jQuery(document).on("click", ".wt-skillsactive", function (e) {
      e.preventDefault();
      var _this = jQuery(this);
      _this.removeClass("wt-skillsactive");
      _this.parents("li").removeClass("wt-skillsaddinfo");
    });
    // $('#i_file').change( function(event) {
    //     var tmppath = URL.createObjectURL(event.target.files[0]);
    //     $("img").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));

    //     $("#disp_tmp_path").html("Temporary Path(Copy it and try pasting it in browser address bar) --> <strong>["+tmppath+"]</strong>");
    // });
  },
  created: function () {
    this.getSkills();
    this.getUserSkills();
    this.getPurchasedResources();
  },
};
</script>
