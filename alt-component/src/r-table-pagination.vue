<template>
  <div>
    <el-tooltip content="最前一頁" placement="top">
      <el-button
        @click="$emit('input', 1)"
        :disabled="firstPageDisabled"
        size="mini"
      >
        <i class="fa fa-fw fa-step-backward"></i>
      </el-button>
    </el-tooltip>

    <el-tooltip content="上一頁" placement="top" class="m-0">
      <el-button
        @click.prevent="$emit('input', value - 1)"
        :disabled="prevPageDisabled"
        size="mini"
      >
        <i class="fa fa-fw fa-chevron-left"></i>
      </el-button>
    </el-tooltip>

    <el-input
      :value="value"
      @change="$emit('input', $event)"
      :min="1"
      :max="pageCount"
      size="mini"
      style="width: 70px"
    ></el-input>

    <el-tooltip content="下一頁" placement="top" class="m-0">
      <el-button
        @click.prevent="$emit('input', value + 1)"
        :disabled="nextPageDisabled"
        size="mini"
      >
        <i class="fa fa-fw fa-chevron-right"></i>
      </el-button>
    </el-tooltip>

    <el-tooltip content="最後一頁" placement="top" class="m-0">
      <el-button
        @click.prevent="$emit('input', pageCount)"
        :disabled="lastPageDisabled"
        size="mini"
      >
        <i class="fa fa-fw fa-step-forward"></i>
      </el-button>
    </el-tooltip>
  </div>
</template>
<script>
export default {
  props: {
    value: {
      type: Number,
      require: true,
    },
    pageCount: {
      type: Number,
      default: 1,
    },
  },
  computed: {
    firstPageDisabled() {
      return this.value <= 1;
    },
    prevPageDisabled() {
      return this.value <= 1;
    },
    nextPageDisabled() {
      return this.pageCount == this.value;
    },
    lastPageDisabled() {
      return this.pageCount == this.value;
    },
  },
};
</script>