<template>
  <el-dialog
    v-bind="$attrs"
    v-on="$listeners"
    :fullscreen="isMobile()"
    :width="getCalcWidth()"
    ><slot></slot
  ></el-dialog>
</template>
<script>
export default {
  created() {
    window.addEventListener("resize", () => {
      this.$forceUpdate();
    });
  },
  methods: {
    getCalcWidth() {
      switch (this.getScreen()) {
        case "lg":
          return "50%";
        case "md":
          return "75%";
        case "sm":
          return "90%";
      }
      return "100%";
    },

    getScreen() {
      if (window.innerWidth >= 1200) {
        return "lg";
      } else if (window.innerWidth >= 992) {
        return "md";
      } else if (window.innerWidth >= 768) {
        return "sm";
      }
      return "xs";
    },

    isMobile() {
      return this.getScreen() == "xs";
    },
  },
};
</script>
