<template>
  <input
    :value="value"
    :id="local_id"
    :data-target="`#${local_id}`"
    data-toggle="datetimepicker"
    autocomplete="off"
  />
</template>
<script>
export default {
  props: {
    value: String,
    id: {
      type: String
    },
    sideBySide: {
      type: Boolean,
      default: true
    },
    format: {
      type: String,
      default: "YYYY-MM-DD HH:mm"
    },
    minDate: {
      default: false
    },
    maxDate: {
      default: false
    },
    stepping: {
      default: 1
    },
    buttons: {
      default() {
        return {
          showClose: true
        };
      }
    }
  },
  data() {
    return {
      local_id: this.id
    };
  },
  created() {
    if (this.format == "YYYY-MM-DD") {
      this.buttons.showToday = true;
      this.buttons.showClose = false;
    } else {
      this.buttons.showToday = false;
    }
    if (!this.local_id) {
      this.local_id = "dt_" + Math.floor(Math.random() * 10000000000000);
    }
  },
  mounted() {
    var $ = window.$;
    if (this.required) {
      $(this.$el)
        .closest(".form-group")
        .addClass("has-feedback");
      if ($(this.$el).closest(".form-group").length == 0) {
        $(this.$el).css("margin-bottom", "0px");
        $(this.$el).addClass("form-group has-feedback");
      }
    }

    $(this.$el).datetimepicker({
      icons: {
        time: "far fa-clock",
        date: "far fa-calendar",
        previous: "fa fa-chevron-left",
        next: "fa fa-chevron-right",
        clear: "far fa-trash",
        close: "fa fa-times",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down",
        today: "fa fa-calendar-check"
      },
      sideBySide: this.sideBySide,
      format: this.format,
      minDate: this.minDate,
      maxDate: this.maxDate,
      stepping: this.stepping,
      buttons: this.buttons
    });

    $(this.$el).on("change.datetimepicker", e => {
      this.$emit("input", e.date.format(this.format));
    });
  }
};
</script>
