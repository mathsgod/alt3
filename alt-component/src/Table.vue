<template>
    <table><slot></slot></table>
</template>
<script>
export default {
  props: {
    formCreate: Boolean,
    formName: String
  },
  data() {
    return {};
  },
  mounted() {
    if (this.formCreate) {
      var o = this.$el;

      var $table = $(o);
      if ($table.prop("tagName") != "TABLE")
        $table = $table.find("table:first");

      var form_name = this.formName;
      var form_addbottom = $table.attr("form-addbottom") == "";
      var max_row = $table.attr("max-row") ? $table.attr("max-row") : -1;

      var tr = $table.find("tr:first");
      var a = $(
        "<a href='javascript:void(0)' class='btn btn-xs btn-primary'><i class='fa fa-plus'/></a>"
      );
      var th = $(
        "<th style='text-align:center;width:36px' align='center'>"
      ).prependTo(tr);
      th.append(a);

      var del_index = [];
      $table.find("tr").each(function(i, o) {
        if (i == 0) return;
        var $tr = $(o);
        var $td = $("<td style='text-align:center' align='center'>").prependTo(
          $tr
        );
        var a = $(
          "<a href='javascript:void(0)' class='btn btn-xs btn-danger'><i class='fa fa-minus' /></a>"
        ).prependTo($td);
        a.on("click", function() {
          var del_input = $("<input type='hidden' />");
          if (form_name != undefined) {
            del_input.attr("name", form_name + "[d][]");
          } else {
            del_input.attr("name", "_d[]");
          }
          del_input.val($tr.attr("data-index"));
          if ($tr.attr("data-index")) {
            $table.after(del_input);
          }

          $tr.remove();
        });
      });

      var index = 0;
      $(a).on("click", function() {
        var $table = $(o);
        //count row
        if (max_row > 0) {
          if ($table.find("tbody tr").length >= max_row) {
            return false;
          }
        }
        var tr = $table.find("tr:first");
        var new_tr = $("<tr>");
        $(tr)
          .find("th")
          .each(function(i, o) {
            if (i == 0) {
              var a = $(
                "<a href='javascript:void(0)' class='btn btn-xs btn-warning'><i class='fa fa-minus' /></a>"
              );
              var td = $("<td>").appendTo(new_tr);
              td.attr("align", "center");
              a.appendTo(td);

              a.on("click", function() {
                new_tr.remove();
              });
            } else {
              var div = $("<div>");
              div.append($(o).attr("c-tpl"));
              div.find("input,textarea,select").each(function(i, obj) {
                var name = $(obj).attr("name");
                var index_of = name.indexOf("[]");
                if (form_name != "") {
                  if (index_of >= 0) {
                    $(obj).attr(
                      "name",
                      form_name +
                        "[c][" +
                        index +
                        "][" +
                        name.substring(0, index_of) +
                        "][]"
                    );
                  } else {
                    $(obj).attr(
                      "name",
                      form_name + "[c][" + index + "][" + name + "]"
                    );
                  }
                } else {
                  if (index_of >= 0) {
                    $(obj).attr(
                      "name",
                      "_c[" + index + "][" + name.substring(0, index_of) + "][]"
                    );
                  } else {
                    $(obj).attr("name", "_c[" + index + "][" + name + "]");
                  }
                }
              });

              new_tr.append(
                "<td><div class='form-group no-margin'>" +
                  div.html() +
                  "</div></td>"
              );
            }
          });
        if (form_addbottom) {
          $table.append(new_tr);
        } else {
          if ($table.find("tbody")) {
            $table.find("tbody").prepend(new_tr);
          } else {
            tr.after(new_tr);
          }
        }

        index++;

        $(".datetimepicker").each(function(i, o) {
          $(o).dp();
        });

        return false;
      });
    }

    var btn = $(this.$el).find(".table-childrow-btn");
    $(this.$el)
      .find(".table-childrow-btn")
      .off("click")
      .on("click", () => {
        var $this = btn;
        var tr = btn.closest("tr");
        var colspan = $(tr).find("td").length;

        if ($this.hasClass("table-childrow-close")) {
          $this
            .removeClass("table-childrow-close")
            .addClass("table-childrow-open");
          $this
            .find("i")
            .removeClass("fa-chevron-up")
            .addClass("fa-chevron-down");

          var s = new Date().getTime();
          $this.attr("data-id", s);

          var new_tr = $("<tr class='table-childrow'>");
          new_tr.attr("data-id", s);
          tr.after(new_tr);

          var new_td = $("<td>");
          new_tr.append(new_td);
          new_td.attr("colspan", colspan);

          if ($this.attr("data-url")) {
            this.$emit("loading", true);
            $.get($this.attr("data-url")).done(html => {
              this.$emit("loading", false);

              new_td.html(html);
            });
          } else {
            new_td.html($this.attr("data-child"));
          }
        } else {
          $this
            .removeClass("table-childrow-open")
            .addClass("table-childrow-close");
          $this
            .find("i")
            .removeClass("fa-chevron-down")
            .addClass("fa-chevron-up");
          let s = $this.attr("data-id");
          var $table = $this.closest("table");
          $table.find("tr.table-childrow[data-id='" + s + "']").remove();
        }
      });
  },
  methods: {}
};
</script>
