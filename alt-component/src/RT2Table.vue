<template>
  <table class="table table-hover table-sm table-bordered rt m-0">
    <thead
      is="rt2-thead"
      ref="thead"
      :columns="columns"
      @search="$emit('search',$event)"
      @order="$emit('order',$event)"
      @draw="$emit('draw')"
      @check-all="checkAll(...$event)"
      @toggle-child="toggleChild($event)"
    ></thead>
    <tbody
      is="rt2-tbody"
      ref="tbody"
      :selectable="selectable"
      :data="data"
      :columns="columns"
      :storage="storage"
      @update-data="$emit('update-data',$event)"
      @data-deleted="$emit('data-deleted')"
    ></tbody>
  </table>
</template>
<script>
import Rt2Thead from "./RT2Thead";
import Rt2Tbody from "./RT2Tbody";

export default {
  props: {
    columns: Array,
    data: Array,
    selectable: Boolean,
    storage: Object,
  },
  components: {
    Rt2Thead,
    Rt2Tbody,
  },
  methods: {
    toggleChild(show) {
      if (show) {
        this.$refs.tbody.showAllChild();
      } else {
        this.$refs.tbody.hideAllChild();
      }
    },
    checkAll(column, value) {
      this.$refs.tbody.checkAll(column, value);
    },
    reloadCell(column) {
      var rows = this.$refs.tbody.rows();
      var cells = rows.map((row) => {
        return row.getCell(column);
      });
      cells.forEach((cell) => {
        cell.reloadValue();
      });
    },
  },
};
</script>

