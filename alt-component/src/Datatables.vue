<template>
    <table class="table table-hover table-sm table-bordered">
        <slot></slot>
    </table>
</template>
<script>
export default {
  name: "alt-datatables",
  data() {
    return {
      table: null,
      columns: [],
      searchColumns: []
    };
  },
  props: {
    buttons: {
      type: Array,
      default: () => []
    }
  },
  mounted() {
    //if(this.$attrs["data-columns"]){

    //}
    this.columns = JSON.parse(this.$attrs["data-columns"]);
    this.initTable();
  },
  methods: {
    initTable() {
      var buttons = [];

      this.buttons.forEach(o => {
        if (typeof o == "object") {
          eval("o.action=" + o.action + ";");
        }
        buttons.push(o);
      });

      this.table = $(this.$el).DataTable({
        buttons: buttons
      });

      if (this.$attrs["data-server-side"] == "true") {
        this.table.button().add(0, {
          action: function(e, dt, button, config) {
            dt.ajax.reload();
          },
          text: '<i class="fa fa-sync-alt"></i> Reload'
        });
      }

      /*
                this.table.button().add(1, {
                    action: function (e, dt, button, config) {
                        //dt.ajax.reload();
                        dt.destroy();
                        this.initTable();
                    }.bind(this),
                    text: '<i class="fa fa-sync-alt"></i> Responsive'
                });*/

      this.table.on(
        "responsive-resize",
        function(e, datatable, columns) {
          this.searchColumns.forEach((c, i) => {
            if (datatable.columns().responsiveHidden()[i]) {
              c.show();
            } else {
              c.hide();
            }
          });
        }.bind(this)
      );

      /*this.table.on("preXhr.dt",()=>{
                    $(this.$el).closest('.box').data("box").showLoading()
                });
    
                this.table.on("xhr.dt",()=>{
                    $(this.$el).closest('.box').data("box").hideLoading()
                });*/

      /*var thead = $("<thead>");
                var tr = $("<tr class='search-row'>").appendTo(thead);
                $(this.$el).find("thead").after(thead);*/

      if (this.isSearchable()) {
        var thead = $("<thead>");
        var tr = $("<tr role='row' class='search-row'>").appendTo(thead);
        $(this.$el)
          .find("thead")
          .after(thead);

        this.columns.forEach((o, i) => {
          var td = $("<td>");
          this.searchColumns.push(td);
          td.appendTo(tr);
          var column = this.table.columns(i);

          if (!o.searchable) return;

          if (o.searchType == "select2") {
            var search = $("<select class='form-control input-sm'>");
            var opt = $("<option>");
            search.append(opt);

            o.searchOption.forEach(o => {
              var opt = $("<option>");
              opt.text(o.label);
              opt.val(o.value);
              search.append(opt);
            });

            search.on("change", () => {
              column.search(search.val()).draw();
            });

            td.append(search);

            search.select2({});
          } else if (o.searchType == "text") {
            var search = $("<input class='form-control input-sm'>");
            td.append(search);

            search.keyup(e => {
              var code = e.keyCode ? e.keyCode : e.which;
              if (code == 13) {
                this.table
                  .columns(i)
                  .search(search.val())
                  .draw();
              }
            });
          } else if (o.searchType == "select") {
            var search = $("<select class='form-control input-sm'>");
            var opt = $("<option>");
            search.append(opt);

            o.searchOption.forEach(o => {
              var opt = $("<option>");
              opt.text(o.label);
              opt.val(o.value);
              search.append(opt);
            });

            search.on("change", () => {
              column.search(search.val()).draw();
            });

            td.append(search);
          } else if (o.searchType == "date") {
            var search = $('<input class="form-control input-sm" value="" />');
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
                "This Month": [
                  moment().startOf("month"),
                  moment().endOf("month")
                ],
                "Last Month": [
                  moment()
                    .subtract(1, "month")
                    .startOf("month"),
                  moment()
                    .subtract(1, "month")
                    .endOf("month")
                ]
              }
            });

            search.on("apply.daterangepicker", (ev, picker) => {
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

              column.search(JSON.stringify(s)).draw();
            });

            search.on("cancel.daterangepicker", function(ev, picker) {
              search.val("");
              column.search("").draw();
            });

            td.append(search);
          }
        });
      }
    },
    isSearchable() {
      return this.columns.some(function(c) {
        return c.searchable;
      });
    }
  }
};
</script>
