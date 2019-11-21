<template>
  <input :value="value" />
</template>
<script>
export default {
  props: {
    value: {
      type: String
    },
    format: {
      type: String,
      default: "yyyy-mm-dd"
    },
    todayHighlight: {
      type: Boolean,
      default: true
    },
    todayBtn: {
      type: String,
      default: "linked"
    },
    autoclose: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      datepicker: null
    };
  },
  mounted() {
    this.datepicker = window.$(this.$el).datepicker(this.$props);
    this.datepicker.on("change", o => {
      this.$emit("input", o.target.value);
      this.$emit("change", o.target.value);
    });
  }
};
</script>

