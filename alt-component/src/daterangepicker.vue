<template>
  <input :value="value" />
</template>

<script>
export default {
  name: "daterangepicker",
  props: {
    options: {
      default: Object
    },
    value: {}
  },
  mounted() {
    var d = {
      locale: {
        format: "YYYY-MM-DD"
      }
    };
    console.log(this.value);
    let options = { ...d, ...this.options };
    let dp = window.$(this.$el).daterangepicker(options);

    if (!this.value) {
      $(this.$el).val("");
    }

    window.$(this.$el).on("apply.daterangepicker", (e, picker) => {
      var value =
        picker.startDate.format(d.locale.format) +
        " - " +
        picker.endDate.format(d.locale.format);

      this.$emit("change", value, picker);
      this.$emit("input", value, picker);
    });
  }
};
</script>