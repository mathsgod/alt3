<template>
  <td @click="onClick">
    <template v-if="editMode">
      <template v-if="column.editType == 'text'">
        <el-input
          ref="edit_element"
          v-model="localValue"
          size="mini"
          @blur="updateData()"
        ></el-input>
      </template>

      <template v-if="column.editType == 'date'">
        <el-date-picker
          ref="edit_element"
          v-model="localValue"
          size="mini"
          value-format="yyyy-MM-dd"
          @change="updateData()"
        ></el-date-picker>
      </template>
    </template>
    <template v-else>
      <template v-if="type == 'html'">
        <div v-html="value"></div>
      </template>
      <template v-else>
        {{ value }}
      </template>
    </template>
  </td>
</template>
<script>
export default {
  props: {
    column: Object,
    data: Object,
  },
  data() {
    return {
      editMode: false,
      localValue: null,
    };
  },
  computed: {
    type() {
      var o = this.data[this.column.prop];
      if (o === null) return "text";

      if (typeof this.data[this.column.prop] == "object") {
        return this.data[this.column.prop].type;
      }
      return "text";
    },
    value() {
      if (this.type == "html") {
        return this.data[this.column.prop].content;
      } else {
        return this.data[this.column.prop];
      }
    },
  },
  mounted() {
    this.localValue = this.value;
  },
  methods: {
    onClick() {
      if (!this.column.editable) return;
      if (this.editMode) return;
      this.$emit("edit-started");
      this.editMode = true;
      this.$nextTick(() => {
        this.$refs.edit_element.focus();
      });
    },
    updateData() {
      this.editMode = false;

      if (this.value != this.localValue) {
        this.$emit("update-data", this.localValue);
      }
    },
  },
};
</script>