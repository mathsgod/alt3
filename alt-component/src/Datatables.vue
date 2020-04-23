<template>
  <table class="table table-hover table-sm table-bordered">
    <slot></slot>
  </table>
</template>
<script>
//var moment = window.moment;
var $ = window.$;
export default {
  name: "datatables",
  props: {
    buttons: {
      type: Array,
      default: () => []
    },
    columns: {
      type: Array,
      default: () => []
    },
    data: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      table: null,
      searchColumns: []
    };
  },
  mounted() {
    if (this.columns.length) {
      this.table = window.$(this.$el).DataTable({
        columns: this.columns,
        data: this.data
      });
    } else {
      this.table = window.$(this.$el).DataTable();
    }
  },
  methods: {
    initTable() {
      var buttons = [];

      this.buttons.forEach(o => {
        if (typeof o == "object") {
          eval("o.action=" + o.action + ";");
        }
        buttons.push(o);
      });

      console.log(this.columns);
      this.table = $(this.$el).DataTable({
        columns: this.columns,
        buttons: buttons
      });
    },
    isSearchable() {
      return this.columns.some(function(c) {
        return c.searchable;
      });
    }
  }
};
</script>
