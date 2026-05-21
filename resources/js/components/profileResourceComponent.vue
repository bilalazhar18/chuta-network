<template>
  <div>
    <div class="wt-formtheme wt-skillsform">
      <transition name="fade">
        <div v-if="isShow" class="sj-jump-messeges">{{ trans("lang.no_record") }}</div>
      </transition>
      <fieldset>
        <div class="courses-input-fields">
          <div>Subject Name:</div>
          <span class="wt-select">
            <select id="student_skill">
              <option
                v-for="(stored_skill, index) in stored_skills"
                :key="index"
                :value="stored_skill.id"
              >
                {{ stored_skill.title }}
              </option>
            </select>
          </span>
        </div>
        <div class="courses-input-fields">
          <div>Resource Description:</div>
          <textarea type="text" class="form-control" id="resource_description"></textarea>
        </div>
        <div class="courses-input-fields">
          <div>Resource Title</div>
          <input type="text" class="form-control" required id="selected_resource_title" />
        </div>
        <div class="courses-input-fields">
          <div>Resource Price</div>
          <input type="number" class="form-control" required id="selected_resource_price" />
        </div>
        <!-- <div class="courses-input-fields">
                    <div>Resource File</div>
                    <input type="file" class="form-control" id="selected_resource_file" value="c:/passwords.txt">
                </div> -->
        <div class="courses-input-fields">
          <input
            type="hidden"
            :value="skill.is_save_course"
            name="is_save_course"
            id="is_save_course"
          />
        </div>
        <div class="btn-course-add" style="padding: 2%">
          <a href="javascript:void(0);" class="wt-btn" @click="addSkill">{{
            trans("lang.add_resources")
          }}</a>
        </div>
      </fieldset>
    </div>
    <div class="wt-myskills">
      <ul id="skill_list" class="sortable list">
        <li
          v-for="(student_skill, index) in student_skills"
          :key="index"
          v-if="student_skills"
          class="skill-element"
          :ref="'skill-' + index"
        >
          <div class="wt-dragdroptool">
            <a href="javascript:void(0)" class="lnr lnr-menu"></a>
          </div>
          <span class="skill-dynamic-field" style="font-size: 30px; padding-top: 20px">
            {{ getCourseName(student_skill.course_id) }}
          </span>
          <span class="skill-dynamic-html">{{ student_skill.title }}</span>
          <span class="skill-dynamic-field sss" style="padding-top: 5%">
            Edit Resource Description:
            <input
              type="hidden"
              v-bind:name="'student_skills[' + index + '][id]'"
              :value="student_skill.id"
            />
            <input
              type="text"
              v-bind:name="'student_skills[' + index + '][description]'"
              :value="student_skill.description"
              v-bind:id="'savedDescription' + index"
            />
          </span>
          <span class="skill-dynamic-field sss" style="padding-top: 5%">
            Edit Resource Title:
            <input
              type="hidden"
              v-bind:name="'student_skills[' + index + '][id]'"
              :value="student_skill.id"
            />
            <input
              type="text"
              v-bind:name="'student_skills[' + index + '][title]'"
              :value="student_skill.title"
              v-bind:id="'savedTitle' + index"
            />
          </span>
          <span class="skill-dynamic-field sss" style="padding-top: 5%">
            Edit Resource Price:
            <input
              type="hidden"
              v-bind:name="'student_skills[' + index + '][id]'"
              :value="student_skill.id"
            />
            <input
              type="number"
              v-bind:name="'student_skills[' + index + '][price]'"
              :value="student_skill.price"
              v-bind:id="'savedPrice' + index"
            />
          </span>
          <span class="skill-dynamic-field sss" style="padding-top: 5%">
            Change Resource file:<br />
            <a v-if="!student_skill.display_file" :href="student_skill.url_of_file">
              <i style="font-size: 50px" class="fa fa-file" aria-hidden="true"></i>
            </a>
            <i v-if="!student_skill.display_file">{{ student_skill.name_of_file }}</i>
            <input
              type="hidden"
              v-bind:name="'student_skills[' + index + '][id]'"
              :value="student_skill.id"
            />
            <input
              type="file"
              v-bind:name="'student_skills[' + index + '][name_of_file]'"
              value="sample.pdf"
              accept="image/png,image/jpeg,.pdf"
              v-bind:id="'storedFile' + index"
            />
            <input
              type="hidden"
              v-bind:name="'student_skills[' + index + '][random_key]'"
              :value="student_skill.random_key"
            />
            <input
              type="hidden"
              v-bind:name="'student_skills[' + index + '][resource_file_name]'"
              :value="student_skill.resource_file_name"
            />
            <input
              type="hidden"
              v-bind:name="'student_skills[' + index + '][courseName]'"
              :value="getCourseName(student_skill.course_id)"
            />
          </span>
          <div class="wt-rightarea">
            <a href="javascript:void(0);" class="wt-addinfo" @click="editInput(index)"
              ><i class="lnr lnr-pencil"></i
            ></a>
            <a
              :id="'hiding-saved-icon' + index"
              href="javascript:void(0);"
              class="wt-deleteinfo delete-skill"
              @click="removeStoredSkill(index)"
              ><i class="lnr lnr-trash"></i
            ></a>
          </div>
        </li>
        <li v-for="(skill, index) in skills" :key="index + skill.count">
          <div class="wt-dragdroptool">
            <a href="javascript:void(0)" class="lnr lnr-menu"></a>
          </div>
          <span class="skill-dynamic-html">{{ skill.resource_title }}</span>
          <span class="skill-dynamic-field">
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][id]'"
              :value="parseInt(skill.id)"
            />
          </span>
          <span class="skill-dynamic-field" style="font-size: 30px">
            {{ getCourseName(skill.course) }}
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][course]'"
              :value="skill.course"
            />
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][courseName]'"
              :value="getCourseName(skill.course)"
            />
          </span>
          <span class="skill-dynamic-field" style="padding-top: 5%">
            Resource Description:
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][id]'"
              :value="skill.id"
            />
            <input
              type="text"
              v-bind:name="'skills[' + [skill.count] + '][resource_description]'"
              :value="skill.resource_description"
              v-bind:id="'freshStart' + index"
            />
          </span>
          <span class="skill-dynamic-field" style="padding-top: 5%">
            Resource Title
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][id]'"
              :value="skill.id"
            />
            <input
              type="text"
              v-bind:name="'skills[' + [skill.count] + '][resource_title]'"
              :value="skill.resource_title"
              v-bind:id="'freshComplete' + index"
            />
          </span>
          <span class="skill-dynamic-field" style="padding-top: 5%">
            Resource Price
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][id]'"
              :value="skill.id"
            />
            <input
              type="number"
              v-bind:name="'skills[' + [skill.count] + '][resource_price]'"
              :value="skill.resource_price"
              v-bind:id="'freshPrice' + index"
            />
          </span>
          <span class="skill-dynamic-field" style="padding-top: 5%">
            Resource File
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][id]'"
              :value="skill.id"
            />
            <input
              type="file"
              v-bind:name="'skills[' + [skill.count] + '][resource_file]'"
              accept="image/png,image/jpeg,.pdf"
              v-bind:id="'freshFile' + index"
              :onchange="myFunc"
            />
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][url_of_document]'"
              :value="skill.url_of_document"
            />
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][file_url]'"
              :value="skill.file_url"
            />
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][resource_file_name]'"
              :value="skill.resource_file_name"
              v-bind:id="'freshFileName' + index"
            />
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][random_key]'"
              :value="skill.random_key"
            />
          </span>

          <span class="skill-dynamic-field" style="padding-top: 5%">
            Resource Preview File
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][id]'"
              :value="skill.id"
            />
            <input
              type="file"
              v-bind:name="'skills[' + [skill.count] + '][resource_file_image]'"
              accept="image/png,image/jpeg,.pdf"
              v-bind:id="'freshFile1' + index"
              :onchange="myFunc1"
            />
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][url_of_document1]'"
              :value="skill.url_of_document1"
            />
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][file_url1]'"
              :value="skill.file_url1"
            />
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][resource_file_image_name]'"
              :value="skill.resource_file_image_name"
              v-bind:id="'freshFileName1' + index"
            />
            <input
              type="hidden"
              v-bind:name="'skills[' + [skill.count] + '][random_key]'"
              :value="skill.random_key"
            />
          </span>

          <div class="wt-rightarea">
            <a href="javascript:void(0);" class="wt-addinfo" @click="debug(index)"
              ><i class="lnr lnr-pencil"></i
            ></a>
            <a
              :id="'hiding-fresh-icon' + index"
              href="javascript:void(0);"
              class="wt-deleteinfo"
              @click="removeSkill(index)"
              ><i class="lnr lnr-trash"></i
            ></a>
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
        resource_file_image: "",
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
        success: {
            position: "topRight",
            timeout: 4000,
            class: 'success_notification'
        }
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
    myFunc1(index) {
      console.log("I AM INDEX", index);
      console.log(URL.createObjectURL(index));
      this.skills[sec.count]["url_of_document1"] = URL.createObjectURL(index);
      console.log("MODIFIED this.skills", this.skills);
    },

    debug(index) {
      var x = document.getElementsByClassName("wt-skillsactive");
      setTimeout(() => {
        console.log("World!", x[0]);
        if (x[0] === undefined) {
          this.skill.is_save_course = 1;
          // this.student_skills[index]['pivot']['isSaveCourse'];
          var abc = document.getElementById("hiding-fresh-icon" + index);
          console.log("abc 1", abc);
          abc.style.display = "block";
        } else {
          this.skill.is_save_course = 0;
          var abc = document.getElementById("hiding-fresh-icon" + index);
          console.log("abc 2", abc);
          abc.style.display = "none";
        }
      }, 300);

      console.log("DEBUG FUNCTION");
      console.log("index", index);
      console.log("this.skills[index]", this.skills[index]);
      console.log(document.getElementById("freshStart" + index).value);
      console.log(document.getElementById("freshComplete" + index).value);
      this.skills[index]["resource_description"] = document.getElementById(
        "freshStart" + index
      ).value;
      this.skills[index]["resource_title"] = document.getElementById(
        "freshComplete" + index
      ).value;
      this.skills[index]["resource_price"] = document.getElementById(
        "freshPrice" + index
      ).value;
      console.log("1. ", document.getElementById("freshFile" + index));
      console.log("2. ", document.getElementById("freshFile" + index).value);
      if (document.getElementById("freshFile" + index).value) {
        this.skills[index]["resource_file"] = document.getElementById(
          "freshFile" + index
        ).files[0];
        this.skills[index]["file_url"] = URL.createObjectURL(
          document.getElementById("freshFile" + index).files[0]
        );
        this.skills[index]["resource_file_name"] = document.getElementById(
          "freshFile" + index
        ).files[0].name;
      }

      if (document.getElementById("freshFile1" + index).value) {
        console.log(
          " Look here dom",
          document.getElementById("freshFile1" + index).files[0]
        );
        this.skills[index]["resource_file_image"] = document.getElementById(
          "freshFile1" + index
        ).files[0];
        this.skills[index]["file_url1"] = URL.createObjectURL(
          document.getElementById("freshFile1" + index).files[0]
        );
        this.skills[index]["resource_file_image_name"] = document.getElementById(
          "freshFile1" + index
        ).files[0].name;
      }
    },
    showError(error) {
      return this.$toast.error(" ", error, this.notificationSystem.error);
    },

    showSuccess(message) {
      return this.$toast.success(' ', message, this.notificationSystem.success);
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
        self.student_skills = response.data.student_resources;
        console.log("self.student_skills", self.student_skills);
      });
    },
    addSkill: function () {
      var selected_course = document.getElementById("student_skill");
      var startingDateSelect = document.getElementById("resource_description");
      var completingDateSelect = document.getElementById("selected_resource_title");
      var resource_price = document.getElementById("selected_resource_price");
      // var resource_file = document.getElementById("selected_resource_file");
      // console.log("TEST", resource_file.value)
      if (
        startingDateSelect.value === "" ||
        completingDateSelect.value === "" ||
        resource_price.value == ""
      ) {
        this.showError("empty field not allow");
      } else {
        var skill_list_count = jQuery(".wt-btn")
          .parents(".wt-skillsform")
          .next(".wt-myskills")
          .find("ul#skill_list li").length;
        skill_list_count = skill_list_count - 1;
        this.skill.count = skill_list_count;
        //  this.showMessage("hello ia m in ");
          this.showSuccess("Please upload resource document before proceeding");
        // this.showError("Please upload resource document before proceeding");

        // this.selected_skill_text = skillsSelect.options[skillsSelect.selectedIndex].text;
        // this.selected_skill = document.getElementById("student_skill").value;
        this.selected_starting = document.getElementById("resource_description").value;
        this.selected_completing = document.getElementById(
          "selected_resource_title"
        ).value;
        this.resource_price = document.getElementById("selected_resource_price").value;
        // this.resource_file = document.getElementById("selected_resource_file").files[0];
        this.skills.push(
          Vue.util.extend(
            {},
            this.skill,
            this.skill.count++,
            (this.skill.course = selected_course.value),
            (this.skill.id = this.skill.id),
            // this.skill.file_url = URL.createObjectURL(this.resource_file),

            (this.skill.resource_description = this.selected_starting),
            (this.skill.resource_title = this.selected_completing),
            (this.skill.resource_price = this.resource_price),
            (this.skill.random_key = Math.random().toString(36).substring(7))
            // this.skill.resource_file=this.resource_file,
            // this.skill.resource_file_name=this.resource_file.name
          )
        );
        console.log("this.skills", this.skills);
      }
    },
    removeSkill: function (index) {
      var self = this;
      this.$swal({
        title: "Delete Resource",
        text: "Are you Sure?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: true,
        closeOnCancel: true,
        showLoaderOnConfirm: true,
      }).then((result) => {
        var self = this;
        if (result.value) {
          let option = self.skills[index];
          var select = document.getElementById("student_skill");
          // select.options[select.options.length] = new Option(option.title, option.id, false, false);
          console.log("BEFORE self.skills", self.skills);
          self.skills.splice(index, 1);
          console.log("AFTER self.skills", self.skills);
          self.$swal("Deleted", "Skill Deleted", "success");
        } else {
          this.$swal.close();
        }
      });
    },
    removeStoredSkill: function (index) {
      var self = this;
      this.$swal({
        title: "Delete Resource",
        text: "Are you Sure?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: true,
        closeOnCancel: true,
        showLoaderOnConfirm: true,
      }).then((result) => {
        var self = this;
        if (result.value) {
          let option = self.student_skills[index];
          var id = option.id;

          axios
            .post(APP_URL + "/student/delete-get-resource/" + id)
            .then(function (response) {
              console.log(response);
            });

          //let option = self.student_skills[index];
          var select = document.getElementById("student_skill");
          console.log("BEFORE self.student_skills", self.student_skills);
          // select.options[select.options.length] = new Option(option.title, option.id, false, false);
          self.student_skills.splice(index, 1);
          console.log("AFTER self.student_skills", self.student_skills);
          self.$swal("Deleted", "Resource Deleted", "success");
        } else {
          this.$swal.close();
        }
      });
    },
    editInput: function (index) {
      var x = document.getElementsByClassName("wt-skillsactive");
      setTimeout(() => {
        console.log("World!", x[0]);
        if (x[0] == undefined) {
          this.skill.is_save_course = 1;
          var abc = document.getElementById("hiding-saved-icon" + index);
          console.log("I am x in 1", abc);
          abc.style.display = "block";
          // this.student_skills[index]['pivot']['isSaveCourse'];
        } else {
          this.skill.is_save_course = 0;
          var abc = document.getElementById("hiding-saved-icon" + index);
          console.log("I am x", abc);
          abc.style.display = "none";
        }
      }, 300);
      console.log("FOUND 3.0!", x);
      console.log("FOUND 4.0!", x[0]);

      console.log("EDITINPUT FUNCTION");
      console.log("index", index);
      this.student_skills[index]["description"] = document.getElementById(
        "savedDescription" + index
      ).value;
      this.student_skills[index]["title"] = document.getElementById(
        "savedTitle" + index
      ).value;
      this.student_skills[index]["price"] = document.getElementById(
        "savedPrice" + index
      ).value;

      if (document.getElementById("storedFile" + index).value) {
        this.student_skills[index]["resource_file"] = document.getElementById(
          "storedFile" + index
        ).files[0];
        // this.skills[index]['file_url'] = URL.createObjectURL(document.getElementById("storedFile" + index).files[0])
        this.student_skills[index]["resource_file_name"] = document.getElementById(
          "storedFile" + index
        ).files[0].name;
        this.student_skills[index]["resource_file_image_name"] = document.getElementById(
          "storedFile1" + index
        ).files[0].name;
        this.student_skills[index]["display_file"] = true;
      }

      this.edit_class = true;
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
  },
};
</script>
