<template>
    <textarea v-model='value'></textarea>
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
    data: String
  },
  mounted() {
    if (typeof CKEDITOR != "undefined") {
      var base = $("base").attr("href");
      CKEDITOR.config.filebrowserImageBrowseUrl =
        base + "plugins/RoxyFileman.1.4.5/fileman/index.html?type=image";
      CKEDITOR.config.filebrowserBrowseUrl =
        base + "plugins/RoxyFileman.1.4.5/fileman/index.html";
      CKEDITOR.replace(this.$el);
    }
  }
};
</script>

