<template>
  <a :class="getClass" @click.prevent="click" :href="href">
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
    confirm: Boolean,
    confirmMessage: {
      type: String,
      default: "Are you sure?",
    },
    href: String,
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
  methods: {
    async click() {
      if (this.confirm) {
        try {
          await this.$confirm(this.confirmMessage);
          window.self.location = this.href;
        } catch (e) {
          console.log('cancel');
        }
      } else {
        window.self.location = this.href;
      }
    },
  },
};
</script>