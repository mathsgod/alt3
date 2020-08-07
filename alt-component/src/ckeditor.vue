<template>
  <textarea v-model="localValue"></textarea>
</template>
<script>
export default {
  name: "ckeditor",
  data() {
    return {
      ckeditor: null,
      localValue: this.value,
    };
  },
  props: {
    value: String,
    basepath: String,
    config: {
      type: Object,
      default() {
        return {};
      },
    },
  },
  created() {
    window.addEventListener(
      "message",
      (event) => {
        var data = event.data;
        if (data.source == "ckeditor") {
          window.CKEDITOR.tools.callFunction(
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
      var CKEDITOR = window.CKEDITOR;
      this.ckeditor = CKEDITOR.replace(this.$el, this.config);

      this.ckeditor.on("change", () => {
        this.$emit("input", this.ckeditor.getData());
      });
    } else {
      console.error("ckeditor/ckeditor not included");
    }
  },
};
</script>

