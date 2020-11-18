<template>
  <td>
    <template v-if="type == 'html'">
      <div v-html="value"></div>
    </template>
    <template v-else>
      {{ value }}
    </template>
  </td>
</template>
<script>
export default {
  props: {
    column: Object,
    data: Object,
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
};
</script>