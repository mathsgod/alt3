<template>
  <a class="btn" :class="getClass">
    <template v-if="icon"><i :class="icon"></i></template>
    <slot></slot
  ></a>
</template>
<script>
export default {
  props: {
    type: {
      type: String,
      default: "default",
    },
    size: String,
    icon: String,
    block: Boolean,
  },
  computed: {
    getClass() {
      let c = ["btn"];
      if (this.type) {
        c.push(`btn-${this.type}`);
      }

      if (this.size) {
        c.push(`btn-${this.size}`);
      }

      if (this.block) {
        c.push("btn-block");
      }

      return c;
    },
  },
};
</script>