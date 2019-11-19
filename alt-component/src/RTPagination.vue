<template>
  <div class="btn-group pull-left">
    <button
      data-toggle="tooltip"
      title="最前一頁"
      class="btn btn-default btn-sm"
      type="button"
      @click.prevent="$emit('first-page')"
      :disabled="firstPageDisabled"
    >
      <i class="fa fa-fw fa-step-backward"></i>
    </button>
    <button
      data-toggle="tooltip"
      title="上一頁"
      class="btn btn-default btn-sm"
      type="button"
      @click.prevent="$emit('prev-page')"
      :disabled="prevPageDisabled"
    >
      <i class="fa fa-fw fa-chevron-left"></i>
    </button>
    <div class="pull-left" style="user-select: none;">
      <input
        style="width:60px"
        min="1"
        :max="pageCount"
        type="number"
        class="form-control form-control-sm"
        v-model="p"
        @change="changePage"
      />
    </div>
    <button
      data-toggle="tooltip"
      title="下一頁"
      class="btn btn-default btn-sm"
      type="button"
      @click.prevent="$emit('next-page')"
      :disabled="nextPageDisabled"
    >
      <i class="fa fa-fw fa-chevron-right"></i>
    </button>
    <button
      data-toggle="tooltip"
      title="最後一頁"
      class="btn btn-default btn-sm"
      type="button"
      @click.prevent="$emit('last-page')"
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
    page: function(v) {
      //   console.log(v);
      this.p = v;
    }
  },
  data: function() {
    return {
      p: this.page
    };
  },
  methods: {
    changePage: function() {
      this.$emit("change-page", this.p);
    }
  }
};
</script>
