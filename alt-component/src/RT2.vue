<style>
table.rt > thead button.multiselect {
  height: 25px;
  padding: 0px;
  background-color: white;
}
</style>
<template>
  <card class="p-0 m-0" :loading="loading">
    <card-body v-if="buttons.length > 0">
      <button
        v-for="(button, index) in buttons"
        :key="index"
        v-text="button.title"
        @click="onClickButton(button)"
        :class="button.class"
      ></button>
    </card-body>
    <card-body class="p-0 m-0">
      <div class="table-responsive">
        <rt2-table
          ref="table"
          :columns="columns"
          :data="data()"
          :selectable="selectable"
          :storage="storage"
          @search="search(...$event)"
          @order="order"
          @draw="draw"
          @update-data="updateData"
          @data-deleted="draw"
        ></rt2-table>
      </div>
    </card-body>
    <card-footer class="p-0 m-0">
      <div class="float-left">
        <rt-pagination
          :page="page"
          :page-count="pageCount"
          @change-page="page = $event"
        ></rt-pagination>
      </div>
      <div class="float-left d-flex">
        <el-tooltip content="Reload" placement="top">
          <el-button @click="draw" icon="el-icon-refresh-right"></el-button>
        </el-tooltip>

        <el-tooltip content="每頁顯示" placement="top">
          <el-select
            @change="onChangePageLength"
            v-model="local.pageLength"
            style="width: 70px"
          >
            <el-option value="10">10</el-option>
            <el-option value="25">25</el-option>
            <el-option value="50">50</el-option>
            <el-option value="100">100</el-option>
          </el-select>
        </el-tooltip>

        <el-dialog
          :visible.sync="showColumnSelector"
          title="Display columns selector"
          @close="onColumnSelectorClose"
        >
          <el-checkbox
            v-for="(column, key) in columnsHasTitle"
            :label="column.title"
            :key="key"
            v-model="column.isVisible"
            >{{ column.title }}</el-checkbox
          >
        </el-dialog>

        <el-tooltip content="Columns selector" placement="top">
          <el-button @click="showColumnSelector = true">
            <i class="fas fa-fw fa-list"></i>
          </el-button>
        </el-tooltip>

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
        </div>
      </div>

      <div class="float-right">
        <rt-info v-bind="info"></rt-info>
      </div>
    </card-footer>
  </card>
</template>
<script>
import Card from "./Card";
import CardBody from "./CardBody";
import CardFooter from "./CardFooter";
import Rt2Table from "./RT2Table";
import RtInfo from "./RTInfo";
import RtPagination from "./RTPagination";

export default {
  props: {
    ajax: {
      type: Object,
      default: () => {
        return {};
      },
    },
    pageLength: {
      type: Number,
      default: 10,
    },
    cellUrl: String,
    selectable: Boolean,
    buttons: {
      type: Array,
      default: () => {
        return [];
      },
    },
    dropdown: {
      type: Array,
      default: () => {
        return [];
      },
    },
  },
  components: {
    Card,
    CardBody,
    CardFooter,
    Rt2Table,
    RtPagination,
    RtInfo,
  },
  data() {
    var data = {
      showColumnSelector: false,
      hoverChild: [],
      total: 0,
      showIndex: [],
      local: {
        search: {},
        draw: 1,
        pageLength: this.pageLength,
        order: this.$attrs.order,
      },
      page: 1,
      remoteData: [],
      columns: [],
      loading: false,
    };
    return data;
  },
  created: function () {
    /////-------------------

    var storage = this.storage;

    storage.rows = {};

    if (storage.pageLength) {
      this.local.pageLength = parseInt(storage.pageLength);
    }

    if (storage.order) {
      this.local.order = storage.order;
    }

    this.columns = this.$attrs.columns.map((o) => {
      o.hide = false;
      o.orderDir = "";
      o.isVisible = true;

      storage.columns = Object.assign({}, storage.columns);
      if (!storage.rows) storage.rows = {};
      var s;
      if ((s = storage.columns[o.name])) {
        if (s.isVisible === false) {
          o.isVisible = false;
        }
      }

      this.local.order.forEach((ord) => {
        if (ord.name == o.name) {
          o.orderDir = ord.dir;
        }
      });

      return new window.Vue({
        data: o,
        methods: {
          cell(d) {
            var cell = {
              type: "text",
              column: this,
            };

            if (this.type == "checkbox") {
              cell.type = "checkbox";
              var id = d[this.name];
              if (!storage.rows[id]) storage.rows[id] = {};
              cell.checked = storage.rows[id].checked;
            }

            if (this.cellStyle) {
              cell.style = { ...cell.style, ...this.cellStyle };
            }

            if (this.wrap) {
              cell.divStyle = {
                "word-wrap": "break-word",
                "white-space": "pre-wrap",
              };
            }

            if (d[this.name] == null) {
              return cell;
            } else {
              for (var i in d[this.name]) {
                cell[i] = d[this.name][i];
              }
            }

            return cell;
          },
          isDisplay() {
            return this.isVisible && !this.hide;
          },
          getContent(d) {
            var o = d[this.name];

            if (o === null) return "";

            if (Array.isArray(o)) {
              return o.join(" ");
            }

            if (typeof o == "object") {
              return o.content;
            }

            return o;
          },
          getValue(d) {
            var o = d[this.name];
            if (!o) return "";

            if (typeof o == "object") {
              if (o.type == "raw") {
                return o.content;
              } else {
                return o.value;
              }
            }
            return o;
          },
          toggleVisible() {
            this.isVisible = !this.isVisible;
            this.$emit("toggleVisible");
          },
        },
      });
    });
  },
  mounted() {
    if (this.ajax.url) {
      this.draw();
    }

    window.addEventListener("resize", this.resize);
  },
  watch: {
    page() {
      this.draw();
    },
  },
  computed: {
    bottomButtons() {
      return this.buttons.filter((button) => {
        return button.text;
      });
    },
    storage() {
      var storage = JSON.parse(localStorage.getItem(this.id)) || {};
      storage.save = () => {
        var data = {};
        for (var i in storage) {
          if (typeof storage[i] == "function") continue;
          data[i] = storage[i];
        }

        localStorage.setItem(this.id, JSON.stringify(data));
      };

      storage.clear = () => {
        localStorage.removeItem(this.id);
        for (var i in storage) {
          if (typeof storage[i] == "function") continue;
          delete storage[i];
        }
      };

      return storage;
    },

    columnsHasTitle() {
      return this.columns.filter((column) => {
        return column.title;
      });
    },
    id() {
      return this.ajax.url
        .split("/")
        .filter((s) => !Number(s))
        .join("/");
    },
    pageCount() {
      return Math.ceil(this.total / this.local.pageLength);
    },
    info() {
      return {
        from: (this.page - 1) * this.local.pageLength + 1,
        to: Math.min(this.page * this.local.pageLength, this.total),
        total: this.total,
      };
    },
  },
  methods: {
    onColumnSelectorClose() {
      //save the result
      this.columns.forEach((column) => {
        let storage = this.storage;
        storage.columns = storage.columns || {};
        storage.columns[column.name] = storage.columns[column.name] || {};
        storage.columns[column.name] = Object.assign(
          storage.columns[column.name],
          { isVisible: column.isVisible }
        );
        storage.save();
      });
      this.draw();
    },
    toggleVisible(column) {
      column.toggleVisible();
    },
    clickExport(xlsx) {
      var filter = [];
      for (var col of this.columns) {
        if (!this.local.search[col.name]) {
          continue;
        }

        filter.push({
          name: col.name,
          value: this.local.search[col.name],
          method: col.searchMethod,
        });
      }

      var url = xlsx.url;
      if (url.indexOf("?") === -1) {
        url += "?";
      } else {
        url += "&";
      }

      const params = window.$.param({
        _rt_request: 1,
        filter,
      });
      url += params;

      window.open(url, "_blank");
    },
    clearChecked(name) {
      //var d = [];
      this.storage.rows[name] = {};
      this.storage.save();
    },
    getChecked(name) {
      var d = [];
      var rows = this.storage.rows[name];
      for (var r in rows) {
        d.push(r);
      }
      return d;
    },
    onClickButton(button) {
      var e = button.action + "(this);";
      eval(e);
    },
    hasExport() {
      return this.export.count > 0;
    },
    exportFile(type) {
      this.local.draw++;
      this.$http
        .get(this.ajax.url, {
          params: {
            _rt: 1,
            draw: this.local.draw,
            columns: this.columns.map((o) => {
              return {
                name: o.name,
                search: {
                  value: this.local.search[o.name],
                },
                searchMethod: o.searchMethod,
              };
            }),
            order: this.local.order,
            search: this.searchData,
            type: type,
          },
          responseType: "arraybuffer",
        })
        .then(function (response) {
          //console.log(response);
          var headers = response.headers;
          var blob = new Blob([response.data], {
            type: headers["content-type"],
          });
          var link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          if (type == "xlsx") {
            link.download = "export.xlsx";
          } else if (type == "csv") {
            link.download = "export.csv";
          }

          link.click();
        });
    },
    async updateData(data) {
      if (!this.cellUrl) {
        console.log("cell-url not found");
        return;
      }
      if (!data.key) {
        console.log("key cannot be null");
        return;
      }
      if (!data.field) {
        console.log("field cannot be null");
      }

      var d = {};
      d[data.field] = data.value;

      let resp = await this.$http.post(this.cellUrl + "/" + data.key, d);
      resp = resp.data;
      if (resp.error) {
        alert(resp.error.message);
        return;
      }
    },
    resetLocalStorage() {
      this.storage.clear();
      this.$emit("reset-local-storage");
      this.draw();
    },
    getColumn(index) {
      return this.columns[index];
    },
    onChangePageLength() {
      //save
      var storage = this.storage;
      storage.pageLength = this.local.pageLength;
      storage.save();

      this.page = 1;
      this.draw();
    },
    data() {
      return this.remoteData;
    },
    order(o) {
      var storage = this.storage;

      this.local.order = [
        {
          name: o[0],
          dir: o[1],
        },
      ];
      this.columns.forEach((c) => {
        if (c.name != o[0]) {
          c.orderDir = "";
        } else {
          c.orderDir = o[1];
        }
      });

      storage.order = this.local.order;

      storage.save();

      return this;
    },
    async draw() {
      this.loading = true;
      this.local.draw++;

      let data = (
        await this.$http.get(this.ajax.url, {
          params: {
            _rt: 1,
            draw: this.local.draw,
            columns: this.columns.map((o) => {
              return {
                name: o.name,
                search: {
                  value: this.local.search[o.name],
                },
                searchMethod: o.searchMethod,
              };
            }),
            order: this.local.order,
            page: this.page,
            length: this.local.pageLength,
          },
        })
      ).data;

      this.loading = false;
      try {
        if (data.draw < this.local.draw) {
          return;
        }

        this.remoteData = data.data;
        this.total = data.total;

        this.resize();

        this.$nextTick(() => {
          this.columns.forEach((column) => {
            if (column.type == "checkbox") {
              this.$refs.table.reloadCell(column);
            }
          });
        });
      } catch (e) {
        alert(e.message);
      }
    },
    search(name, value) {
      this.page = 1;
      this.local.search[name] = value;
      this.$emit("search", this.local.search);

      this.draw();
      return this;
    },
    resize() {
      this.columns.forEach((c) => {
        c.hide = false;
      });

      if (!this.responsive) return;

      this.$nextTick(() => {
        //console.log("--");
        var parentWidth = this.$refs.table.$el.parentElement.offsetWidth;
        //console.log("parentWidth", parentWidth);

        var total = () => {
          let total = 0;

          this.columns.forEach((c, i) => {
            let c_el = this.$refs.table.$refs.thead.$refs.column[i];
            if (c_el) {
              total += c_el.$el.offsetWidth;
            }
          });
          if (
            this.columns.some((c) => {
              return c.hide;
            })
          ) {
            total += 32;
          }
          return total;
        };

        var hideLastColumn = () => {
          let columns = this.columns.filter((c) => {
            if (c.noHide) {
              return false;
            }
            return !c.hide;
          });
          columns = columns.reverse();
          if (columns.length > 0) {
            columns[0].hide = true; //hide last column
            return true;
          } else {
            return false; //nothing can hide
          }
        };

        var check = () => {
          let t = total();
          //console.log(t);
          if (t > parentWidth) {
            var r = hideLastColumn();

            if (r) {
              this.$nextTick(() => {
                check();
              });
            }
          }
        };

        check();
      });
    },
  },
};
</script>
