<template>
  <textarea v-model="localValue" :id="id"></textarea>
</template>
<script>
export default {
  name: "tinymce",
  data() {
    return {
      id: "",
      localValue: this.value,
    };
  },
  props: {
    value: {
      type: String,
      default: "",
    },
    init: {
      type: Object,
      default() {
        return {
          height: 600,
          apply_source_formatting: true,
          convert_urls: false,
          plugins: [
            "fileman ace",
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table paste imagetools wordcount",
          ],
          toolbar:
            "fileman ace insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
          images_upload_url: "postAcceptor.php",
          automatic_uploads: false,
        };
      },
    },
  },
  created() {
    if (this.$attrs.id) {
      this.id = this.$attrs.id;
    } else {
      this.id =
        "tinymce-" +
        new Date().getTime() +
        "-" +
        Math.random().toString(36).substr(2, 9);
    }
  },
  mounted() {
    var id = this.id;
    window.tinymce.PluginManager.add("fileman", function (editor) {
      var openDialog = function () {
        return editor.windowManager.openUrl({
          title: "HostLink Fileman",
          url: "Fileman?source=tinymce",
        });
      };

      // Add a button that opens a window
      editor.ui.registry.addButton("fileman", {
        text: "File manager",
        onAction: function () {
          // Open window
          openDialog();
        },
      });

      // Adds a menu item, which can then be included in any menu via the menu/menubar configuration
      editor.ui.registry.addMenuItem("fileman", {
        text: "File manager",
        onAction: function () {
          // Open window
          openDialog();
        },
      });

      return {
        getMetadata: function () {
          return {
            name: "File manager",
            url: "https://www.hostlink.com.hk",
          };
        },
      };
    });

    window.tinymce.PluginManager.add("ace", (editor) => {
      let openDialog = () => {
        localStorage.setItem(id, editor.getContent());
        editor.windowManager.openUrl({
          title: "Ace code editor",
          url: "tinymce_code?source=tinymce&id=" + id,
          buttons: [
            {
              type: "custom",
              name: "action",
              text: "Submit",
              primary: true,
            },
            {
              type: "cancel",
              name: "cancel",
              text: "Close",
            },
          ],
          onAction(instance) {
            let content = localStorage.getItem(id);
            editor.setContent(content ?? "");
            localStorage.removeItem(id);
            // close the dialog
            instance.close();
          },
          onClose() {
            localStorage.removeItem(id);
          },
        });
      };

      // Add a button that opens a window
      editor.ui.registry.addButton("ace", {
        text: "Code",
        onAction() {
          // Open window
          openDialog();
        },
      });
    });

    this.init.selector = "#" + this.id;
    this.init.setup = (editor) => {
      editor.on("init", () => {
        editor.setContent(this.value);
      });
    };

    window.tinymce.init(this.init);
  },
};
</script>

