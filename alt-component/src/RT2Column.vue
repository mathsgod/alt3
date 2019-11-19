<template>
  <th
    v-on:click="click"
    class="unselectable"
    v-bind:style="getStyle()"
    v-bind:class="{
        sortable:orderable,
        sorting_desc:(orderDir=='desc'),
        sorting_asc:(orderDir=='asc')
    }"
  >
    <input v-if="type=='checkbox'" type="checkbox" is="icheck" v-on:change="checkboxChange">
    <div v-else v-text="title"></div>
  </th>
</template>
<script>
export default {
  name: "rt2-column",
  props: {
    name: String,
    data: String,
    title: String,
    orderable: Boolean,
    orderDir: String,
    isVisible: {
      type: Boolean,
      default: true
    },
    width: String,
    minWidth: String,
    maxWidth: String,
    overflow: String,
    type: String
  },
  data() {
    return {
      hide: false,
      local: {
        orderDir: this.orderDir
      }
    };
  },
  methods: {
    checkboxChange(e) {
      this.$emit("check-all",e.target.checked);
    },
    click() {
      if (!this.orderable) return;
      if (this.local.orderDir == "desc") {
        this.order("asc");
      } else {
        this.order("desc");
      }
      this.draw();
    },
    search(search) {
      this.local.search = search;
      return this;
    },
    order(dir) {
      if (!this.orderable) return this;
      this.local.orderDir = dir;
      this.$emit("order", [this.name, dir]);
      return this;
    },
    draw() {
      this.$emit("draw");
      return this;
    },
    getStyle() {
      let style = {};
      if (this.width) {
        style.width = this.width;
      }

      if (this.minWidth) {
        style["min-width"] = this.minWidth;
      }

      if (this.maxWidth) {
        style["max-width"] = this.maxWidth;
      }

      if (this.overflow) {
        style["overflow"] = this.overflow;
      }

      return style;
    }
  }
};
</script>
