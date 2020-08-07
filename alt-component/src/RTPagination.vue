<template>
  <div class="btn-group">
    <button
      data-toggle="tooltip"
      title="最前一頁"
      class="btn btn-default btn-sm"
      type="button"
      @click.prevent="$emit('change-page',1)"
      :disabled="firstPageDisabled"
    >
      <i class="fa fa-fw fa-step-backward"></i>
    </button>
    <button
      data-toggle="tooltip"
      title="上一頁"
      class="btn btn-default btn-sm"
      type="button"
      @click.prevent="$emit('change-page',p-1)"
      :disabled="prevPageDisabled"
    >
      <i class="fa fa-fw fa-chevron-left"></i>
    </button>
    <el-input-number v-model="p" controls-position="right" @change="changePage" :min="1" :max="pageCount"></el-input-number>
    <button
      data-toggle="tooltip"
      title="下一頁"
      class="btn btn-default btn-sm"
      type="button"
      @click.prevent="$emit('change-page',p+1)"
      :disabled="nextPageDisabled"
    >
      <i class="fa fa-fw fa-chevron-right"></i>
    </button>
    <button
      data-toggle="tooltip"
      title="最後一頁"
      class="btn btn-default btn-sm"
      type="button"
      @click.prevent="$emit('change-page',pageCount)"
      :disabled="lastPageDisabled"
    >
      <i class="fa fa-fw fa-step-forward"></i>
    </button>
  </div>
</template>
<script>
export default {
  name: "rt-pagination",
  props: {
    page: {
      type: Number,
      require: true,
      default: 1
    },
    pageCount: {
      type: Number,
      default: 1
    }
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
    }
  },
  watch: {
    page(v) {
      this.p = v;
    }
  },
  data() {
    return {
      p: this.page
    };
  },
  methods: {
    changePage() {
      this.$emit("change-page", this.p);
    }
  }
};
</script>
