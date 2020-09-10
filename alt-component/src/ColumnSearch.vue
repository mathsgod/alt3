<template>
  <td>
    <el-input
      v-if="searchable && (searchType=='text' || searchType=='equal')"
      class="search"
      v-model="search"
      @keyup.enter.native="doSearch"
      clearable
      @clear="search='';doSearch()"
    ></el-input>

    <el-date-picker
      style="max-width:250px"
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

    <el-select
      style="width:100%"
      v-if="searchable && searchType=='multiselect'"
      v-model="search"
      clearable
      filterable
      multiple
      collapse-tags
      @change="doSearch()"
    >
      <el-option v-for="(o,key) in searchOption" :key="key" :label="o.label" :value="o.value"></el-option>
    </el-select>

    <el-select
      style="width:100%"
      v-if="searchable && searchType=='select' && !searchOptGroup"
      v-model="search"
      clearable
      filterable
      @change="doSearch()"
    >
      <el-option v-for="(o,key) in searchOption" :key="key" :label="o.label" :value="o.value"></el-option>
    </el-select>
  </td>
</template>
<script>
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
    /*  if (this.searchType == "multiselect") {
      var search = $(this.$refs.search);
      search.multiselect({
        enableFiltering: true,
        buttonWidth: "100%"
      });

      search.on("change", () => {
        this.$emit("search", this.name, search.val());
      });
    }*/
  },
  methods: {
    doSearch() {
      this.$emit("search", [this.name, this.search]);
    }
  }
};
</script>
