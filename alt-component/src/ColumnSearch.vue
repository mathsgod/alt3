<template>
  <td>
    <input
      v-if="searchable && searchType=='text'"
      type="search"
      class="form-control form-control-sm search"
      v-model="search"
      @keyup.enter="doSearch()"
    />
    <input
      v-if="searchable && searchType=='equal'"
      type="search"
      class="form-control form-control-sm search"
      v-model="search"
      @keyup.enter="doSearch()"
    />
    <el-date-picker
      style="max-width:220px"
      v-if="searchable && searchType=='date'"
      v-model="search"
      type="daterange"
      unlink-panels
      range-separator="~"
      start-placeholder="Start date"
      end-placeholder="End date"
      :picker-options="pickerOptions"
      @change="doSearch()"
      format="yyyy-MM-dd"
      value-format="yyyy-MM-dd"
    ></el-date-picker>

    <select
      v-if="searchable && searchType=='select' && !searchOptGroup"
      class="form-control form-control-sm search"
      ref="search"
      v-model="search"
      v-on:change="doSearch()"
    >
      <option></option>
      <option v-for="(o,key) in searchOption" v-bind:value="o.value" v-text="o.label" :key="key"></option>
    </select>
  </td>
</template>
<script>
//var moment = window.moment;
var $ = window.$;
export default {
  name: "alt-column-search",
  props: {
    name: String,
    data: String,
    searchOption: Array,
    searchType: String,
    searchable: Boolean,
    searchOptGroup: Array,
    searchMultiple: Boolean
  },
  data() {
    return {
      search: "",
      pickerOptions: {
        shortcuts: [
          {
            text: "Last week",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "Last month",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "Last 3 months",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit("pick", [start, end]);
            }
          }
        ]
      }
    };
  },
  mounted() {
    if (this.searchType == "multiselect") {
      var search = $(this.$refs.search);
      search.multiselect({
        enableFiltering: true,
        buttonWidth: "100%"
      });

      search.on("change", () => {
        this.$emit("search", this.name, search.val());
      });
    }
  },
  methods: {
    doSearch() {
      this.$emit("search", [this.name, this.search]);
    }
  }
};
</script>
