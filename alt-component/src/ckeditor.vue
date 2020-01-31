<template>
  <textarea v-model="value"></textarea>
</template>
<script>
export default {
  name: "ckeditor",
  data() {
    return {
      value: this.data
    };
  },
  props: {
    data: String,
    config: {
      type: Object,
      default() {
        return {};
      }
    }
  },
  mounted() {
    if (typeof window.CKEDITOR != "undefined") {
      var base = $("base").attr("href");
      window.CKEDITOR.config = { ...CKEDITOR.config, ...this.config };
      window.CKEDITOR.replace(this.$el);
    }
  }
};
</script>

