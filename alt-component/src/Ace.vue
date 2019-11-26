<template>
     <textarea></textarea>
</template>

<script>
export default {
  mounted() {
    if (this.$slots.default) {
      this.$el.value = this.$slots.default[0].text;
    }

    var $that = $(this.$el);

    $(this.$el).addClass("hide");
    var $div = $("<div style='height:400px'></div>");
    $div.insertAfter(this.$el);

    $div.html(this.$el.value);

    this.$nextTick(() => {
      var editor = ace.edit($div[0]);
      var mode = $(this.$el).attr("ace-mode");
      if (mode) {
        editor.session.setMode("ace/mode/" + mode);
      }

      editor.getSession().setValue($(this.$el).val());

      editor.getSession().on("change", function() {
        //                console.log(editor.getSession().getValue());
        $that.val(editor.getSession().getValue());
      });
    });
  }
};
</script>
