<template>
  <section
    class="wt-haslayout wt-main-section"
    v-bind:style="{ background: category.sectionColor }"
  >
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-xs-12 col-sm-12 col-md-8 push-md-2 col-lg-6 push-lg-3">
          <div class="wt-sectionhead wt-textcenter">
            <div class="wt-sectiontitle" style="padding-bottom: 30px;position: relative;margin: 0 0 50px;margin-top: 0px;margin-right: 0px;margin-bottom: 20px;margin-left: 0px;width: 100%;float: left;">
              <h2 v-if="category.title">Explore Jobs</h2>
            </div>
            <div class="wt-description"><p><span>Start your search for student jobs within Australia’s best businesses</span></p></div>
          </div>
        </div>
        <div class="wt-categoryexpl">
          <div
            class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 float-left"
            v-for="(cat, index) in categoryList"
            :key="index"
          >
            <div class="wt-categorycontent">
              <figure>
                <img
                  :src="baseUrl + '/uploads/categories/' + cat.image"
                  :alt="cat.title"
                />
              </figure>
              <div class="wt-cattitle">
                <h3>
                  <a :href="baseUrl + '/find-facultyjob/' + cat.id">{{ cat.title }}</a>
                </h3>
              </div>
              <div class="wt-categoryslidup">
                <p v-if="cat.abstract">{{ cat.abstract }}</p>
                <a :href="baseUrl + '/find-facultyjob/' + cat.id"
                  >{{ trans("lang.explore") }} <i class="fa fa-arrow-right"></i
                ></a>
              </div>
            </div>
          </div>

          <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 float-left"></div>
          <div class="col-4"></div>
          <div class="col-lg-6 wt-btnarea mt-3 col-xs-12">
            <!-- <div
              style="display: block;
  width: 100%;
  border: none;
  background:linear-gradient(180deg, #d3398a 0%, #7a52d1 100%;
  color: #fff;
  padding: 14px 28px;
  font-size: 35px;
  cursor: pointer;
  text-align: center;
"
            >
              <a :href="baseUrl + '/view-allfaculty/'" style="color: #fff"
                >View All Faculties</a
              >
            </div> -->
            <a :href="baseUrl + '/view-allfaculty/'" class="wt-btn">Explore All Faculties</a>
            <!-- <div class="wt-btnarea"><a href="#" class="wt-btn">JOIN NOW</a></div> -->
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
export default {
  props: ["parent_index", "element_id", "categories", "type"],
  data() {
    return {
      category: {},
      categoryList: [],
      baseUrl: APP_URL,
    };
  },
  methods: {
    getCategories: function () {
      var self = this;
      axios
        .get(APP_URL + "/get-faculties")
        .then(function (response) {
          console.log(response);

          if (response.data.type == "success") {
            self.categoryList = response.data.categories;
          }
        })
        .catch(function (error) {});
    },
  },
  created: function () {
    var index = this.getArrayIndex(this.categories, "id", this.element_id);
    if (this.categories[index]) {
      this.category = this.categories[index];
    }
    this.category.parentIndex = this.parent_index;
    this.getCategories();
  },
};
</script>
