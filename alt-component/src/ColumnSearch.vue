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
    <input
      v-if="searchable && searchType=='date'"
      class="form-control form-control-sm search"
      value
      ref="search"
    />

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

    <select
      v-if="searchable && searchType=='select' && searchOptGroup"
      class="form-control form-control-sm search"
      ref="search"
      v-model="search"
      v-on:change="doSearch()"
    >
      <option></option>
      <optgroup v-for="(label,group) in searchOptGroup" v-bind:label="label">
        <option
          v-for="(o,key) in searchOption"
          v-bind:value="o.value"
          v-text="o.label"
          v-if="o.group==group"
          :key="key"
        ></option>
      </optgroup>
    </select>

    <select
      v-if="searchable && searchType=='multiselect'"
      v-bind:multiple="searchMultiple"
      class="form-control form-control-sm search"
      ref="search"
    >
      <option v-if="!searchMultiple" value>None selected</option>

      <option
        v-if="!searchOptGroup"
        v-for="o in searchOption"
        v-bind:value="o.value"
        v-text="o.label"
      ></option>

      <optgroup v-if="searchOptGroup" v-for="(label,group) in searchOptGroup" v-bind:label="label">
        <option
          v-for="o in searchOption"
          v-bind:value="o.value"
          v-text="o.label"
          v-if="o.group==group"
        ></option>
      </optgroup>
    </select>
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
      search: ""
    };
  },
  mounted() {
    if (this.searchType == "text") {
      //console.log(this.$refs.search);
    }
    if (this.searchType == "multiselect") {
      var search = $(this.$refs.search);
      search.multiselect({
        enableFiltering: true,
        buttonWidth: "100%"
      });

      search.on("change", () => {
        this.$emit("search", this.name, search.val());
      });

      return;
    }
    if (this.searchType == "date") {
      var search = $(this.$refs.search);
      search.keypress(e => {
        if (e.which == 13) {
          let v = this.$refs.search.value;
          if (v == "") {
            this.search = "";
            this.doSearch();
          } else if (v.indexOf("to") >= 0) {
            var s = {};
            //range
            let a = v.split("to");
            s.from = a[0].trim();
            s.to = a[1].trim();
            this.search = s;
            this.doSearch();
          } else {
            var s = {};
            s.from = v;
            s.to = v;
            this.search = s;
            this.doSearch();
          }
        }
      });

      search.daterangepicker({
        //singleDatePicker: true,
        opens: "center",
        showDropdowns: true,
        //"autoApply": true,
        autoUpdateInput: false,
        locale: {
          format: "YYYY-MM-DD",
          cancelLabel: "Clear"
        },
        ranges: {
          Today: [moment(), moment()],
          Yesterday: [
            moment().subtract(1, "days"),
            moment().subtract(1, "days")
          ],
          "Last 7 Days": [moment().subtract(6, "days"), moment()],
          "Last 30 Days": [moment().subtract(29, "days"), moment()],
          "This Month": [moment().startOf("month"), moment().endOf("month")],
          "Last Month": [
            moment()
              .subtract(1, "month")
              .startOf("month"),
            moment()
              .subtract(1, "month")
              .endOf("month")
          ],
          "This Year": [moment().startOf("year"), moment().endOf("year")],
          "Last Year": [
            moment()
              .subtract(1, "year")
              .startOf("year"),
            moment()
              .subtract(1, "year")
              .endOf("year")
          ]
        }
      });

      search.on("apply.daterangepicker", (ev, picker) => {
        console.log("update");

        if (
          picker.startDate.format("YYYY-MM-DD") ==
          picker.endDate.format("YYYY-MM-DD")
        ) {
          search.val(picker.startDate.format("YYYY-MM-DD"));
        } else {
          search.val(
            picker.startDate.format("YYYY-MM-DD") +
              " to " +
              picker.endDate.format("YYYY-MM-DD")
          );
        }

        var s = {};
        s.from = picker.startDate.format("YYYY-MM-DD");
        s.to = picker.endDate.format("YYYY-MM-DD");

        this.search = s;
        this.doSearch();
      });

      search.on("cancel.daterangepicker", (ev, picker) => {
        search.val("");
        this.search = "";
        this.doSearch();
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
