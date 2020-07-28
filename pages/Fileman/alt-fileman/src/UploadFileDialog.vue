<template>
  <div
    class="modal"
    id="exampleModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Upload files...</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th></th>
                <th>Name</th>
                <th>Size</th>
                <th class="col-md-1"></th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(item,key) in items" :key="key">
                <td>
                  <button
                    class="btn btn-sm btn-danger"
                    @click.prevent="items=items.filter(i=>i!=item)"
                  >
                    <i class="fa fa-fw fa-times"></i>
                  </button>
                </td>
                <td v-text="item.file.name"></td>
                <td v-text="item.file.size"></td>
                <td>
                  <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped"
                      role="progressbar"
                      :class="getProgressClass(item)"
                      :style="getProgressStyle(item)"
                    ></div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <div
            class="dropzone"
            id="dropzone"
            @drop="dropFile($event)"
            @dragover.prevent
            @click="clickDrop"
          >
            <div class="message">
              <i class="fa fa-fw fa-cloud-upload-alt"></i>
              Drag &amp; Drop here or click
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-sm btn-primary" @click.prevent="uploadFile" :disabled="this.items.length==0">
            <i class="fa fa-fw fa-upload"></i> Upload
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
//import Swal from "sweetalert2/dist/sweetalert2.js";

export default {
  data() {
    return {
      items: [],
      path: "",
      progress: [],
      type: ""
    };
  },
  props: {
    api: Object
  },
  methods: {
    getProgressClass(item) {
      if (item.end) {
        return [];
      }
      return ["progress-bar-animated"];
    },
    getProgressStyle(item) {
      if (!item.start) {
        return {};
      }

      var p = (item.loaded / item.total) * 100;
      return {
        width: `${p}%`
      };
    },
    async upload(i, item) {
      let xhr = await this.api.getUploadRequest();
      xhr.addEventListener("error", e => {
        console.log("error", e);
      });

      xhr.addEventListener("loadstart", () => {
        item.start = true;
        this.$forceUpdate();
        console.log("onloadstart", item);
      });

      xhr.upload.addEventListener("progress", e => {
        if (e.lengthComputable) {
          console.log("loaded", e.loaded);
          console.log("total", e.total);
          item.loaded = e.loaded;
          item.total = e.total;
          this.$forceUpdate();
            
          console.log("onprogress", item, e);
        }
      });

      xhr.onloadend = () => {
        item.end = true;
        this.$forceUpdate();
        console.log("onloadend", item);
        this.$emit("files-uploaded");
      };

      var formData = new FormData();
      formData.append("path", this.path);
      formData.append("file", item.file);
      xhr.send(formData);
      return xhr;
    },
    async uploadFile() {
      var promise = [];
      for (var i in this.items) {
        if (this.items[i].start) continue;

        var p = this.upload(i, this.items[i]);
        promise.push(p);
      }

      await Promise.all(promise);
      this.$emit("files-uploaded");
    },
    open(path, type) {
      this.path = path;
      this.type = type;
      this.items = [];
      window.$(this.$el).modal("show");
      window.$(this.$el).on("hidden.bs.modal", () => {
        window.$(this.$el).removeData("bs.modal");
      });
    },
    async dropFile(ev) {
      console.log(ev);
      ev.preventDefault();
      if (ev.dataTransfer.items) {
        // Use DataTransferItemList interface to access the file(s)
        var files = [];
        for (let i = 0; i < ev.dataTransfer.items.length; i++) {
          // If dropped items aren't files, reject them
          if (ev.dataTransfer.items[i].kind === "file") {
            var file = ev.dataTransfer.items[i].getAsFile();
            files.push(file);
          }
        }

        for (let f of files) {
          this.items.push({
            start: false,
            end: false,
            loaded: 0,
            total: 0,
            file: f
          });
        }
      } else {
        // Use DataTransfer interface to access the file(s)
        for (var i = 0; i < ev.dataTransfer.files.length; i++) {
          console.log(
            "... file[" + i + "].name = " + ev.dataTransfer.files[i].name
          );
        }
      }
    },
    clickDrop() {
      var input = document.createElement("input");
      input.multiple = true;
      input.type = "file";
      if (this.type == "image") {
        input.accept = "image/*";
      }

      input.onchange = async event => {
        for (let i = 0; i < event.target.files.length; i++) {
          this.items.push({
            start: false,
            end: false,
            loaded: 0,
            total: 0,
            file: event.target.files[i]
          });
        }
      };

      input.click();
    }
  }
};
</script>

