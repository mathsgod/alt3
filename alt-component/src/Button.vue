<template>
  <button @click="onClick" :type="type">
    <i v-if="iconClass" :class="displayIcon"></i>
    <slot></slot>
  </button>
</template>
<script>
export default {
  name: "alt-button",
  props: {
    icon: String,
    type: String,
    submitCheck: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      submitting: false,
      iconClass: this.icon
    };
  },
  computed: {
    displayIcon() {
      if (this.submitting && this.submitCheck) {
        return "fa fa-spinner fa-spin";
      }
      return this.iconClass;
    }
  },
  methods: {
    onClick(event) {
      var $=window.$;
      if (this.type == "submit") {
        var form = this.$el.form;
        if (!$(form).valid()) {
          return false;
        }
        if (this.submitting && this.submitCheck) {
          event.preventDefault();
          return;
        }

        this.submitting = true;
      }
    }
  }
};
</script>
