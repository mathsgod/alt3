<template>
  <tbody>
    <template v-for="(d,index) in data">
      <tr
        ref="row"
        is="rt2-row"
        :index="index"
        :key="'row_'+index"
        :data="d"
        :columns="visibleColumns"
        :storage="storage"
        :has-hide-column="hasHideColumn"
        :show-child="showIndex[index]"
        :editColumn="editColumn"
        :editIndex="editIndex"
        :selectable="selectable"
        @toggle-row-child="toggleRowChild(index)"
        @mouse-enter-row="mouseEnterRow(index)"
        @mouse-leave-row="mouseLeaveRow(index)"
        @cell-clicked="onClickCell(index,$event)"
        @update-data="updateData"
        @toggle-sub-row="toggleSubRow(index,$event)"
        @data-deleted="$emit('data-deleted')"
      ></tr>

      <tr class="child" v-show="showChild(index)" :key="'child'+index">
        <td v-bind:colspan="showColumnCount">
          <ul>
            <li v-for="(column,key) in hideColumns" :key="'hide_col'+key">
              <b v-html="column.title"></b>&nbsp;&nbsp;
              <span v-if="column.cell(d).type=='html'" v-html="column.getContent(d)"></span>
              <span v-if="column.cell(d).type=='text'" v-text="column.getContent(d)"></span>
            </li>
          </ul>
        </td>
      </tr>

      <tr v-show="subRow[index]" :key="'subrow'+index">
        <td v-html="subRowContent[index]" :colspan="visibleColumns.length" class="p-1"></td>
      </tr>
    </template>
  </tbody>
</template>

<script>
import Vue from "vue";
import Rt2Row from "./RT2Row";
export default {
  name: "rt2-tbody",
  props: {
    data: Array,
    columns: Array,
    selectable: Boolean,
    storage: Object,
  },
  components: {
    "rt2-row": Rt2Row,
  },
  data() {
    return {
      subRow: [],
      subRowContent: [],
      subRowColumn: null,
      hoverChild: [],
      showIndex: [],
      editColumn: null,
      editIndex: null,
    };
  },
  computed: {
    selectedData() {
      return this.$children
        .filter((child) => {
          return child.selected;
        })
        .map((child) => {
          return child.data;
        });
    },
    hideColumns() {
      return this.columns.filter((column) => {
        return column.hide;
      });
    },
    visibleColumns() {
      return this.columns.filter((column) => {
        return column.isDisplay();
      });
    },
    hasHideColumn() {
      return this.columns.some((o) => o.hide);
    },
    showColumnCount() {
      return (
        this.columns.filter((c) => {
          return !c.hide;
        }).length + 1
      );
    },
  },
  mounted() {
    this.$parent.$on("reset-local-storage", () => {
      this.$emit("reset-local-storage");
    });
  },
  methods: {
    checkAll(column, value) {
      var cells = this.$children.map(row => {
        return row.getCell(column);
      });
      cells.forEach((cell) => {
        cell.setCheckbox(value);
      });
    },
    toggleSubRow(index, content) {
      if (this.subRow[index]) {
        this.subRow[index] = false;
      } else {
        this.subRow[index] = true;

        Vue.http
          .get(content.url, {
            params: content.params,
            headers: {
              Accept: "text/html",
            },
          })
          .then((resp) => {
            this.subRowContent[index] = resp.body;
            this.$forceUpdate();
          });
      }
      this.$forceUpdate();
    },
    onClickCell(index, column) {
      if (!column.editable) return false;
      this.editColumn = column;
      this.editIndex = index;
    },
    showAllChild() {
      this.data.forEach((o, i) => {
        this.showIndex[i] = true;
      });
      this.$forceUpdate();
    },
    hideAllChild() {
      this.data.forEach((o, i) => {
        this.showIndex[i] = false;
      });
      this.$forceUpdate();
    },
    toggleRowChild(index) {
      this.showIndex[index] = !this.showIndex[index];
      this.$forceUpdate();
    },
    showChild(index) {
      if (this.hoverChild[index]) return true;
      return this.showIndex[index];
    },
    mouseLeaveRow(index) {
      this.hoverChild[index] = false;
      this.$forceUpdate();
    },
    mouseEnterRow(index) {
      this.hoverChild[index] = true;
      this.$forceUpdate();
    },
    updateData(key, field, value) {
      this.editColumn = null;
      this.editIndex = null;
      this.$emit("update-data", {
        key: key,
        field: field,
        value: value,
      });
    },
  },
};
</script>
