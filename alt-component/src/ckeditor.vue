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
    basepath: String,
    data: String,
    config: {
      type: Object,
      default() {
        return {};
      }
    }
  },
  created() {
    window.addEventListener(
      "message",
      event => {
        var data = event.data;
        if (data.source == "ckeditor") {
          CKEDITOR.tools.callFunction(
            data.CKEditorFuncNum,
            this.basepath + data.value
          );
        }
      },
      false
    );
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

