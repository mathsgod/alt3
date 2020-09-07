<template>
  <th
    v-on:click="click"
    class="unselectable text-nowrap"
    v-bind:style="getStyle()"
    v-bind:class="{
        sortable:orderable,
        sorting_desc:(orderDir=='desc'),
        sorting_asc:(orderDir=='asc')
    }"
  >
    <el-checkbox v-if="type=='checkbox'" @input="checkboxChange"  ></el-checkbox>
    <div v-else v-text="title"></div>
  </th>
</template>
<style scoped>
th {
  padding: 1px 2px 1px 2px !important;
}

.unselectable {
  user-select: none;
  -moz-user-select: none;
  -khtml-user-select: none;
  -webkit-user-select: none;
  -o-user-select: none;
}
.sortable {
  cursor: pointer;
  background: url(./assets/images/sort_both.png) no-repeat center right;
}

.sorting_desc {
  background: url(./assets/images/sort_desc.png) no-repeat center right;
}

.sorting_asc {
  background: url(./assets/images/sort_asc.png) no-repeat center right;
}
</style>
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
      local: {
        orderDir: this.orderDir
      }
    };
  },
  methods: {
    checkboxChange(e) {
      this.$emit("check-all", e[0]);
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
