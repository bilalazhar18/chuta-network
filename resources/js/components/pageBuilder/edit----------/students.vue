<template>
    <div class="la-section-settings">
        <div class="wt-location wt-tabsinfo">
            <div class="form-group form-group-half toolip-wrapo">
                <input placeholder="Title" :name="'meta[student'+parent_index+'][title]'" type="text" :value="student.title" class="form-control" v-if="student.title">
                <input placeholder="Title" :name="'meta[student'+parent_index+'][title]'" type="text" value="" class="form-control" v-else>
            </div> 
            <div class="form-group form-group-half toolip-wrapo">
                <input placeholder="Subtitle" :name="'meta[student'+parent_index+'][subtitle]'" type="text" :value="student.subtitle" class="form-control" v-if="student.subtitle">
                <input placeholder="Subtitle" :name="'meta[student'+parent_index+'][subtitle]'" type="text" value="" class="form-control" v-else>
            </div>
            <div class="form-group">
                <textarea placeholder="Description" :name="'meta[student'+parent_index+'][description]'" class="form-control" v-if="student.description">{{student.description}}</textarea>
                <textarea placeholder="Description" :name="'meta[student'+parent_index+'][description]'" class="form-control" v-else></textarea>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:['parent_index', 'element_id', 'students', 'student_data'],
    data() {
        return {
            student:{},
        }
    },
    methods:{
        getArrayIndex (array, attr, value) {
            for (var i = 0; i < array.length; i += 1) {
                if (array[i][attr] == value) {
                return i
                }
            }
            return -1
        },
        removeSection: function() {
            this.$emit("removeElement", 'remove-section');
        },
    },
    created: function() {
        var index = this.getArrayIndex(this.students, 'id', this.element_id)
        if (this.students[index]) {
            this.student = this.students[index]
        }
        this.student.parentIndex = this.parent_index
    }
};
</script>
