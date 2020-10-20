<template>
  <div>
    <el-tooltip content="最前一頁" placement="top">
      <el-button @click="$emit('change-page', 1)" :disabled="firstPageDisabled">
        <i class="fa fa-fw fa-step-backward"></i>
      </el-button>
    </el-tooltip>

    <el-tooltip content="上一頁" placement="top" class="m-0">
      <el-button
        @click.prevent="$emit('change-page', p - 1)"
        :disabled="prevPageDisabled"
      >
        <i class="fa fa-fw fa-chevron-left"></i>
      </el-button>
    </el-tooltip>

    <el-input-number
      v-model="p"
      controls-position="right"
      @change="changePage"
      :min="1"
      :max="pageCount"
    ></el-input-number>

    <el-tooltip content="下一頁" placement="top" class="m-0">
      <el-button
        @click.prevent="$emit('change-page', p + 1)"
        :disabled="nextPageDisabled"
      >
        <i class="fa fa-fw fa-chevron-right"></i>
      </el-button>
    </el-tooltip>

    <el-tooltip content="最後一頁" placement="top" class="m-0">
      <el-button
        @click.prevent="$emit('change-page', pageCount)"
        :disabled="lastPageDisabled"
      >
        <i class="fa fa-fw fa-step-forward"></i>
      </el-button>
    </el-tooltip>
  </div>
</template>
<script>
export default {
  name: "rt-pagination",
  props: {
    page: {
      type: Number,
      require: true,
      default: 1,
    },
    pageCount: {
      type: Number,
      default: 1,
    },
  },
  computed: {
    firstPageDisabled() {
      return this.page <= 1;
    },
    prevPageDisabled() {
      return this.page <= 1;
    },
    nextPageDisabled() {
      return this.pageCount == this.page;
    },
    lastPageDisabled() {
      return this.pageCount == this.page;
    },
  },
  watch: {
    page(v) {
      this.p = v;
    },
  },
  data() {
    return {
      p: this.page,
    };
  },
  methods: {
    changePage() {
      this.$emit("change-page", this.p);
    },
  },
};
</script>
