<template>
  <thead>
    <tr>
      <th
        v-for="(column,key) in visibleColumns"
        :key="'col_'+key"
        is="rt2-column"
        v-bind="column.$data"
        @order="$emit('order',$event)"
        @draw="$emit('draw')"
        @check-all="$emit('check-all',[column,$event])"
        ref="column"
      ></th>
    </tr>

    <tr v-if="isSearchable">
      <td
        is="alt-column-search"
        v-for="(column,key) in visibleColumns"
        :key="'search_'+key"
        v-bind="column.$data"
        @search="$emit('search',$event)"
      ></td>
    </tr>
  </thead>
</template>
<style scoped>
td {
  padding: 1px 2px 1px 2px !important;
}
</style>
<script>
import AltColumnSearch from "./ColumnSearch";
import Rt2Column from "./RT2Column";
export default {
  name: "rt2-thead",
  props: {
    columns: Array
  },
  components: {
    Rt2Column,
    AltColumnSearch
  },
  data() {
    return {
      showChild: false
    };
  },
  computed: {
    visibleColumns() {
      return this.columns.filter(column => {
        return column.isDisplay();
      });
    },
    isSearchable() {
      return this.columns.some(o => o.searchable);
    }
  },
  methods: {
    toggleChild() {
      this.showChild = !this.showChild;
      this.$emit("toggle-child", this.showChild);
    }
  }
};
</script>

