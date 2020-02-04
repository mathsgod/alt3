<template>
  <input class="form-control" :fileman="id" v-model="value1" />
</template>

<script>
export default {
  name: "fileman",
  props: {
    url: String,
    value: String
  },
  data() {
    return {
      id: "",
      value1: this.value
    };
  },
  created() {
    this.id = new Date().getTime();

    window.addEventListener(
      "message",
      event => {
        var data = event.data;
        if (data.action == "select-file") {
          if (this.id == data.id) {
            this.$emit("input", data.value);
            this.value1 = data.value;
            window.$.fancybox.close();
          }
        }
      },
      false
    );
  },
  mounted() {
    let url = this.url + "&id=" + this.id;
    window.$(this.$el).on("focus", () => {
      window.$.fancybox.open({
        src: url,
        type: "iframe"
      });
    });
  }
};
</script>