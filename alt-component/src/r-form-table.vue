<template>
  <el-table :data="data">
    <el-table-column width="46px">
      <template slot="header">
        <button class="btn btn-xs btn-primary" @click.prevent="addRow()">
          <i class="fa fa-fw fa-plus"></i>
        </button>
      </template>
      <template slot-scope="scope">
        <button
          v-if="scope.row[dataKey]"
          class="btn btn-xs btn-danger"
          @click.prevent="removeRow(scope.$index, scope.row)"
        >
          <i class="fa fa-fw fa-minus"></i>
        </button>
        <button
          v-else
          class="btn btn-xs btn-warning"
          @click.prevent="removeRow(scope.$index)"
        >
          <i class="fa fa-fw fa-minus"></i>
        </button>
        <input
          type="hidden"
          :name="`${dataName}[${scope.$index}][${dataKey}]`"
          :value="scope.row[dataKey]"
        />
      </template>
    </el-table-column>
    <slot></slot>

    <input
      type="hidden"
      v-for="(i, index) in deletedId"
      :key="index"
      :value="i"
      :name="`${dataName}[${data.length + index}][__del__]`"
    />
  </el-table>
</template>

<script>
export default {
  props: {
    data: Array,
    dataKey: String,
    dataName: {
      type: String,
      default: "data",
    },
  },
  data() {
    return {
      deletedId: [],
    };
  },
  methods: {
    removeRow(index, data) {
      if (data) {
        this.deletedId.push(data[this.dataKey]);
      }

      this.data.splice(index, 1);
    },
    addRow() {
      this.data.unshift({});
    },
  },
};
</script>