<template>
  <el-card v-loading="loading" :body-style="{ padding: '0px' }" shadow="never">
    <table class="table table-hover table-sm table-bordered m-0">
      <thead>
        <tr>
          <th v-if="selectable"></th>
          <slot></slot>
        </tr>
        <tr v-if="isSearchable">
          <td v-if="selectable"></td>
          <r-table-column-search
            v-for="(c, i) in columns"
            :key="`column-search-${i}`"
            :column="c"
            @search="search(...$event)"
          ></r-table-column-search>
        </tr>
      </thead>
      <tbody>
        <template v-for="(d, k) in localData">
          <tr :key="`${draw}-${k}`">
            <td v-if="selectable">
              <el-checkbox-group
                v-model="selectedValue"
                class="r-table-checkbox-group"
              >
                <el-checkbox :label="d[key]"></el-checkbox>
              </el-checkbox-group>
            </td>

            <r-table-cell
              ref="cell"
              @click.native="onCellClicked()"
              v-for="(c, i) in columns"
              :key="i"
              :column="c"
              :data="d"
              @update-data="updateData(d, c.prop, $event)"
              @edit-started="onEditStarted()"
              @toggle-sub-row="toggleSubRow(k, $event)"
              @data-deleted="reload"
            ></r-table-cell>
          </tr>

          <tr v-show="subRow[k]" :key="`subrow-${k}`">
            <td v-html="subRowContent[k]" :colspan="columnsLength"></td>
          </tr>
        </template>
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
        <el-select v-model="localPageLength" style="width: 70px">
          <el-option
            v-for="(p, index) in pageLengthOption"
            :value="p"
            v-text="p"
            :key="index"
          ></el-option>
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

      <el-dropdown v-if="$slots.dropdown">
        <el-button>
          Export
          <i class="el-icon-arrow-down el-icon--right"></i>
        </el-button>
        <el-dropdown-menu slot="dropdown">
          <slot name="dropdown"> </slot>
        </el-dropdown-menu>
      </el-dropdown>

      <!-- el-tooltip content="Save search filter" placement="top">
        <el-button @click="onSaveSearchFilter()" size="mini">
          <i class="fas fa-fw fa-save"></i>
        </el-button>
      </el-tooltip -->

      <!-- 

      <el-tooltip content="Clear cache" placement="top-start">
        <el-button @click="resetLocalStorage">
          <i class="fa fa-times-circle"></i>
        </el-button>
      </el-tooltip>
 -->
    </div>
    <div class="float-right">
      {{ info.from }} - {{ info.to }} of {{ info.total }}
    </div>
  </el-card>
</template>


<script>
export default {
  props: {
    data: {
      type: Array,
      default() {
        return [];
      },
    },
    remote: String,
    pageLengthOption: {
      type: Array,
      default: [10, 25, 50, 100],
    },
    pageLength: {
      type: Number,
      default: 25,
    },
    cellUrl: String,
    selectable: Boolean,
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
      key: null,
      selectedValue: [],
      dropdowns: [],
      subRow: [],
      subRowContent: [],
    };
  },
  computed: {
    info() {
      return {
        from: (this.page - 1) * this.localPageLength + 1,
        to: Math.min(this.page * this.localPageLength, this.total),
        total: this.total,
      };
    },
    pageCount() {
      return Math.ceil(this.total / this.localPageLength);
    },
    columnsHasLabel() {
      return this.columns.filter((column) => {
        return column.label;
      });
    },
    isSearchable() {
      return this.columns.some((o) => o.searchable);
    },
    columnsLength() {
      let l = 0;
      l = this.columns.length;
      if (this.selectable) {
        l++;
      }
      return l;
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
    if (this.$slots.dropdown) {
      this.dropdowns = this.$slots.dropdown.map((v) => v.componentInstance);
      this.dropdowns.forEach((dd) => {
        dd.$on("click", () => {
          dd.clickCallback(this);
        });
      });
    }

    this.columns = this.$slots.default
      .filter((v) => v.componentOptions?.tag == "r-table-column")
      .map((v) => v.componentInstance);

    this.columns.forEach((column) => {
      column.$on("order-changed", async () => {
        this.clearEditMode();

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
    async toggleSubRow(index, e) {
      if (this.subRow[index]) {
        this.subRow[index] = false;
      } else {
        this.subRow[index] = true;

        let resp = await this.$http.get(e.url, {
          params: e.params,
          headers: {
            Accept: "text/html",
          },
        });
        resp = resp.body;
        this.subRowContent[index] = resp;
      }
      this.$forceUpdate();
    },
    onSaveSearchFilter() {},
    clearEditMode() {
      this.$refs.cell.forEach((cell) => {
        cell.editMode = false;
      });
    },
    onEditStarted() {
      this.clearEditMode();
    },
    async updateData(data, prop, value) {
      if (!this.cellUrl) {
        console.log("cell url not defined");
        return;
      }
      if (!this.key) {
        console.log("key is not defined");
        return;
      }
      let key_value = data[this.key];
      let params = {};
      params[prop] = value;
      let url = this.cellUrl + "/" + key_value;
      let resp = await this.$http.post(url, params);
      resp = resp.data;
      if (resp.error) {
        this.$alert(resp.error.message, { type: "error" });
        return;
      }
      if (resp.data) {
        await this.reload();
      }
      //should be reload
    },
    onCellClicked() {
      console.log("cell clicked");
    },
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

      let resp = await this.$http.get(this.remote, { params });
      this.loading = false;
      resp = resp.data;
      this.localData = resp.data;
      this.total = resp.total;
      this.key = resp.key;
    },
  },
};
</script>