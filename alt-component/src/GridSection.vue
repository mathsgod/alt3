<template>
  <section class="connectedSortable ui-sortable" :style="getStyle()">
    <slot ref="s"></slot>
  </section>
</template>
<script>
export default {
  computed: {
    card() {
      return this.$children.filter(o => {
        return o.$vnode.componentOptions.tag == "card";
      });
    }
  },
  data() {
    return {
      isDragDrop: false
    };
  },
  mounted() {
    this.$children.forEach(c => {
      c.$on("pinned", v => {
        this.$emit("pinned", v);
      });
    });
  },
  methods: {
    getStyle() {
      if (this.isDragDrop) {
        return { "min-height": "100px" };
      } else {
        return { "min-height": "0px" };
      }
    },
    isPinned() {
      //no children
      if (this.card.length == 0) return true;
      return this.card.every(b => {
        return b.isPinned;
      });
    },
    startSort() {
      var $ = window.$;
      this.isDragDrop = true;
      this.card.forEach(b => b.unpin());
      $(this.$el)
        .sortable({
          placeholder: "sort-highlight",
          connectWith: ".connectedSortable",
          handle: ".card-header, .nav-tabs",
          forcePlaceholderSize: true,
          zIndex: 999999
        })
        .on("sortstop", (/*event, ui*/) => {
          this.$emit("sortstop");
        });
    },
    endSort() {
      var $ = window.$;
      this.isDragDrop = false;
      this.card.forEach(b => b.pin());
      $(this.$el).sortable("destroy");
    }
  }
};
</script>
