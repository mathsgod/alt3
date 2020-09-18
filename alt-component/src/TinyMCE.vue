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
      this.id = "tinymce-" + new Date().getTime();
    }
  },
  mounted() {
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

    window.tinymce.PluginManager.add("ace", function (editor) {
      var openDialog = function () {
        localStorage.setItem("tinymce_content", editor.getContent());
        editor.windowManager.openUrl({
          title: "HostLink Fileman",
          url: "System/code?source=tinymce",
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
          onAction: function (instance, trigger) {
            editor.setContent(localStorage.getItem("tinymce_content"));
            // close the dialog
            instance.close();
          },
        });
      };

      /*         window.ace.edit("code", {
            mode: "ace/mode/html",
            wrap: true,
          });
        setTimeout(() => {
             var textarea = document
            .querySelector(".tox-dialog__body-content")
            .querySelector("textarea");
          textarea.style.height = "600px";

          var e=window.ace.edit("code", {
            mode: "ace/mode/html",
            wrap: true,
          });
          e.session.setOptions({ tabSize: 4, useSoftTabs: false });
        });  
      };*/

      // Add a button that opens a window
      editor.ui.registry.addButton("ace", {
        text: "Code",
        onAction: function () {
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

