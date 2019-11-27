<template>
  <div :class="c">
    <div class="overlay" v-if="myLoading">
      <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: "card",
  props: {
    primary: Boolean,
    secondary: Boolean,
    success: Boolean,
    info: Boolean,
    warning: Boolean,
    danger: Boolean,
    dark: Boolean,
    outline: Boolean,
    loading: Boolean
  },
  data() {
    return {
      myLoading: this.loading
    };
  },
  watch: {
    loading(value) {
      this.myLoading = value;
    }
  },
  computed: {
    c() {
      let c = ["card"];

      if (this.outline) {
        c.push("card-outline");
      }

      if (this.primary) {
        c.push("card-primary");
      } else if (this.secondary) {
        c.push("card-secondary");
      } else if (this.success) {
        c.push("card-success");
      } else if (this.info) {
        c.push("card-info");
      } else if (this.warning) {
        c.push("card-warning");
      } else if (this.danger) {
        c.push("card-danger");
      } else if (this.dark) {
        c.push("card-dark");
      }

      return c;
    }
  },
  methods: {
    showLoading() {
      this.myLoading = true;
    },
    hideLoading() {
      this.myLoading = false;
    }
  }
};
</script>