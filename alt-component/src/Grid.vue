<template>
  <div>
    <slot></slot>
  </div>
</template>
<script>
export default {
  props: {
    dataUrl: String,
    sortable: Boolean
  },
  mounted() {
    this.$children.forEach(c => {
      c.$on("pinned", v => {
        if (v) {
          this.$children.forEach(c => c.endSort());
        } else {
          this.$children.forEach(c => c.startSort());
        }
      });

      c.$on("sortstop", this.sortStop);
    });
  },
  computed: {
    isDragDrop() {
      return this.$children.some(c => {
        return !c.isPinned();
      });
    }
  },
  methods: {
    sortStop() {
      var grid = $(this.$el);

      var data = [];
      grid.children(".row").each(function(i, row) {
        data[i] = [];
        $(row)
          .children(".connectedSortable")
          .each(function(j, section) {
            data[i][j] = [];

            $(section)
              .children("[grid-item]")
              .each(function(k, item) {
                data[i][j].push($(item).attr("grid-item"));
              });
          });
      });
      console.log(data);

      this.$http
        .post("UI/save", {
          type: "grid",
          layout: data,
          uri: this.$attrs["data-uri"]
        })
        .then(resp => {});
    }
  }
};
</script>
