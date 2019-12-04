<template>
  <input class="form-control" ref="input" :value="value" />
</template>
<script>
export default {
  props: {
    value: String,
    sideBySide: {
      type: Boolean,
      default: true
    },
    format: {
      type: String,
      default: "YYYY-MM-DD HH:mm"
    },
    icon: {
      type: String,
      default: "far fa-calendar-alt"
    },
    minDate: {
      default: false
    },
    maxDate: {
      default: false
    }
  },
  mounted() {
    if (this.required) {
      window
        .$(this.$el)
        .closest(".form-group")
        .addClass("has-feedback");
      if ($(this.$el).closest(".form-group").length == 0) {
        $(this.$el).css("margin-bottom", "0px");
        $(this.$el).addClass("form-group has-feedback");
      }
    }
    window.$(this.$refs.input).datetimepicker({
      sideBySide: this.sideBySide,
      format: this.format,
      minDate: this.minDate,
      maxDate: this.maxDate
    });
  }
};
</script>
