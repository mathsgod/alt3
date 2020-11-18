<template>
  <el-card v-loading="loading" :body-style="{ padding: '0px' }">
    <table class="table table-hover table-sm table-bordered m-0">
      <thead>
        <tr>
          <slot></slot>
        </tr>
        <tr>
          <r-table-column-search
            v-for="(c, i) in columns"
            :key="`column-search-${i}`"
            :column="c"
            @search="search(...$event)"
          ></r-table-column-search>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(d, k) in localData" :key="k">
          <r-table-cell
            v-for="(c, i) in columns"
            :key="i"
            :column="c"
            :data="d"
          ></r-table-cell>
        </tr>
      </tbody>
    </table>

    <div class="float-left d-flex">
      <r-table-pagination
        v-model="page"
        :page-count="pageCount"
      ></r-table-pagination>

      <el-tooltip content="Reload" placement="top">
        <el-button
          @click="reload"
          icon="el-icon-refresh-right"
          size="mini"
        ></el-button>
      </el-tooltip>

      <el-tooltip content="每頁顯示" placement="top">
        <el-select v-model="localPageLength" style="width: 70px" size="mini">
          <el-option :value="2">2</el-option>
          <el-option :value="10">10</el-option>
          <el-option :value="25">25</el-option>
          <el-option :value="50">50</el-option>
          <el-option :value="100">100</el-option>
        </el-select>
      </el-tooltip>

      <el-dialog
        :visible.sync="showColumnSelector"
        title="Display columns selector"
        @close="onColumnSelectorClose"
      >
        <el-checkbox
          v-for="(column, key) in columnsHasLabel"
          :label="column.prop"
          :key="key"
          v-model="column.isVisible"
          >{{ column.label }}</el-checkbox
        >
      </el-dialog>

      <el-tooltip content="Columns selector" placement="top">
        <el-button @click="showColumnSelector = true" size="mini">
          <i class="fas fa-fw fa-list"></i>
        </el-button>
      </el-tooltip>
      <!-- 

      <el-tooltip content="Clear cache" placement="top-start">
        <el-button @click="resetLocalStorage">
          <i class="fa fa-times-circle"></i>
        </el-button>
      </el-tooltip>

      <div class="dropdown" v-if="dropdown.length > 0">
        <el-dropdown>
          <el-button>
            Export
            <i class="el-icon-arrow-down el-icon--right"></i>
          </el-button>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item
              v-for="(x, index) in dropdown"
              :key="index"
              v-text="x.label"
              @click.native="clickExport(x)"
            ></el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>

      <div class="btn-group">
        <button
          v-for="(button, index) in bottomButtons"
          :key="index"
          @click="onClickButton(button)"
          class="btn btn-default btn-sm"
          type="button"
          v-text="button.text"
        ></button>
      </div> -->
    </div>
  </el-card>
</template>

<script>
export default {
  props: {
    data: Array,
    remote: String,
    pageLength: {
      type: Number,
      default: 25,
    },
  },
  components: {
    "r-table-pagination": () => import("./r-table-pagination"),
  },
  data() {
    return {
      loading: false,
      columns: [],
      localData: this.data,
      draw: 0,
      page: 1,
      order: [],
      searchData: {},
      localPageLength: this.pageLength,
      showColumnSelector: false,
      total: 0,
    };
  },
  computed: {
    pageCount() {
      return Math.ceil(this.total / this.localPageLength);
    },
    columnsHasLabel() {
      return this.columns.filter((column) => {
        return column.label;
      });
    },
  },
  watch: {
    async localPageLength() {
      this.page = 1;
      await this.reload();
    },
    async page() {
      await this.reload();
    },
  },
  async created() {},
  async mounted() {
    this.columns = this.$slots.default
      .filter((v) => v.componentOptions?.tag == "r-table-column")
      .map((v) => v.componentInstance);

    this.columns.forEach((column) => {
      column.$on("order-changed", async () => {
        this.order = [];
        this.order.push({
          name: column.prop,
          dir: column.localOrder,
        });

        this.reload();
      });
    });

    if (this.remote) {
      await this.reload();
    }
  },
  methods: {
    onColumnSelectorClose() {},
    loadData() {},
    search(name, value, method) {
      this.page = 1;

      this.searchData[name] = {
        name,
        value,
        method,
      };

      this.reload();
    },
    async reload() {
      this.loading = true;
      this.draw++;

      let params = {
        _rtable: 1,
        draw: this.draw,
        page: this.page,
        columns: this.columns.map((o) => {
          return o.prop;
        }),
        order: this.order,
        length: this.localPageLength,
      };

      params.search = Object.values(this.searchData);

      console.log(params);

      let resp = await this.$http.get(this.remote, { params });
      this.loading = false;
      resp = resp.data;
      this.localData = resp.data;
      this.total = resp.total;
    },
  },
};
</script>