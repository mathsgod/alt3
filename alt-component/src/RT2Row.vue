<template>
  <tr @click="onClick" v-bind:class="getClass">
    <td
      is="rt2-cell"
      ref="cell"
      v-for="(column, index) in columns"
      :data="data"
      :key="'col_' + index"
      :storage="storage"
      :column="column"
      :edit-mode="isEditMode(column)"
      @click="$emit('cell-clicked', column)"
      @data-deleted="$emit('data-deleted')"
      @toggle-sub-row="$emit('toggle-sub-row', $event)"
      @update-data="updateData(column, $event)"
      @cancel-edit-mode="$emit('cancel-edit-mode')"
    ></td>
  </tr>
</template>
<script>
import Rt2Cell from "./RT2Cell";
export default {
  name: "rt2-row",
  props: {
    data: Object,
    columns: Array,
    storage: Object,
    hasHideColumn: Boolean,
    showChild: Boolean,
    editMode: Boolean,
    editIndex: Number,
    editColumn: Object,
    index: Number,
    selectable: Boolean,
  },
  data() {
    return {
      selected: false,
    };
  },
  components: {
    "rt2-cell": Rt2Cell,
  },
  computed: {
    getClass() {
      var c = {};
      if (this.selected) {
        c.selected = true;
      }
      return c;
    },
    style() {
      var style = this.data.__row__.style || {};
      return style;
    },
  },
  methods: {
    /**
     * get cell by column
     */
    getCell(column) {
      var index = this.columns.indexOf(column);
      return this.$children[index];
    },
    updateData(column, value) {
      var r = this.data;
      if (column.editType == "text") {
        if (column.getValue(r) != value) {
          r[column.data] = value;
          this.$emit("update-data", r._key, column.data, value);
        }
        return;
      }

      if (column.editType == "select") {
        r[column.data].value = value;
        r[column.data].content = column.editData.find(
          (d) => d.value == value
        ).label;
        this.$emit("update-data", r._key, column.data, value);
      }
    },
    onClick() {
      if (!this.selectable) return;
      this.selected = !this.selected;
      this.$emit("click");
    },
    isEditMode(column) {
      if (this.editColumn == column && this.editIndex == this.index) {
        return true;
      }
      return false;
    },
  },
};
</script>

