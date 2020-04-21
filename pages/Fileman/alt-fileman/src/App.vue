<template>
  <div id="app">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <div>
            <button class="btn btn-info btn-sm" @click.prevent="onClickCreateFolder">
              <i class="fa fa-fw fa-plus"></i> Create
            </button>
            <button class="btn btn-warning btn-sm" @click.prevent="onClickRenameFolder">
              <i class="fa fa-fw fa-pen"></i> Rename
            </button>
            <button
              class="btn btn-danger btn-sm"
              @click.prevent="onClickRemoveFolder"
              :disabled="!canDeleteFolder()"
            >
              <i class="fa fa-fw fa-times"></i> Delete
            </button>
            <button class="btn btn-info btn-sm" @click.prevent="onClickRefreshFolder">
              <i class="fa fa-fw fa-sync"></i> Refresh
            </button>
          </div>
          <div style="overflow: auto">
            <v-jstree
              ref="tree1"
              :data="items1"
              show-checkbox
              allow-batch
              whole-row
              @item-click="onClickDirectory"
              :async="asyncLoad"
            ></v-jstree>
          </div>
        </div>
        <div class="col">
          <div>
            <button class="btn btn-info btn-sm" @click="onClickAddFile()">
              <i class="fa fa-fw fa-plus"></i> Add files
            </button>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label
                class="btn btn-info btn-sm"
                @click="fileViewMode='list'"
                :class="{active:fileViewMode=='list'}"
              >
                <input type="radio" name="fileViewMode" />
                <i class="fa fa-fw fa-list"></i>
              </label>
              <label
                class="btn btn-info btn-sm"
                @click="fileViewMode='grid'"
                :class="{active:fileViewMode=='grid'}"
              >
                <input type="radio" name="fileViewMode" />
                <i class="fa fa-fw fa-th"></i>
              </label>
            </div>
            <button
              class="btn btn-info btn-sm"
              @click="onClickDownloadFiles()"
              :disabled="selectedFiles.length==0"
            >
              <i class="fa fa-fw fa-download"></i> Download files
            </button>
            <button
              class="btn btn-danger btn-sm"
              @click="onClickDeleteFiles()"
              :disabled="selectedFiles.length==0"
            >
              <i class="fa fa-fw fa-times"></i> Delete files
            </button>
          </div>
          <file-list-view
            ref="fileListView"
            v-if="fileViewMode=='list'"
            :api="api"
            :files="files"
            @select-file="onSelectFile"
            @download-file="downloadFile"
            @remove-file="removeFile"
            @selected-files="onSelectedFiles"
            @rename-file="renameFile"
          ></file-list-view>
          <file-grid-view
            v-if="fileViewMode=='grid'"
            :api="api"
            :files="files"
            @select-file="onSelectFile"
            @download-file="downloadFile"
            @remove-file="removeFile"
          ></file-grid-view>
        </div>
      </div>
      <footer class="main-footer unselectable">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">1.0.0</div>
        <!-- Default to the left -->
        <strong>
          Hostlink fileman 著作權 © 2020
          <a href="https://www.hostlink.com.hk" target="_blank">HostLink</a>.
        </strong> 版權所有.
      </footer>
    </div>

    <upload-file-dialog ref="upload_file_dialog" :api="api" @files-uploaded="reloadFilesContent"></upload-file-dialog>
  </div>
</template>

<script>
import VJstree from "vue-jstree";
import UploadFileDialog from "./UploadFileDialog";
import API from "./api";
import FileListView from "./FileListView";
import jwt_decode from "jwt-decode";
import FileGridView from "./FileGridView";
import path from "path";
import Swal from "sweetalert2/dist/sweetalert2.js";

export default {
  name: "app",
  components: {
    //  HelloWorld,
    VJstree: VJstree,
    "upload-file-dialog": UploadFileDialog,
    "file-list-view": FileListView,
    "file-grid-view": FileGridView
  },
  data() {
    return {
      token: "",
      payload: {},
      files: [],
      items1: [],
      uploadFiles: [],
      selectedFolder: {
        value: "/"
      },
      elementId: "",
      fileViewMode: "list",
      init_value: "",
      source: "",
      selectedFiles: [],
      type: ""
    };
  },
  async created() {
    var url = new URL(window.location);
    this.token = url.searchParams.get("token");
    this.source = url.searchParams.get("source");
    this.type = url.searchParams.get("type");

    if (!this.token) {
      this.token = prompt("please input token");
      if (!this.token) {
        return;
      }
    }

    try {
      this.payload = jwt_decode(this.token);
    } catch (e) {
      alert(e);
      return;
    }

    //this.api = new API(`http://127.0.0.1/hostlink-fileman/`);
    this.api = new API(this.payload.api, this.token);
    this.elementId = url.searchParams.get("id");

    this.init_value = url.searchParams.get("value");
  },
  computed: {
    selectedNode() {
      return this.getSelectedNode();
    }
  },
  methods: {
    canDeleteFolder() {
      var node = this.getSelectedNode();
      if (!node) return false;
      if (node.data.value == "/") {
        return false;
      }
      return true;
    },
    async renameFile(f) {
      var ret = prompt("Please input file name", f.filename);
      if (ret !== false) {
        let dir = path.dirname(f.pathname);
        try {
          await this.api.renameFile(f.pathname, dir + ret);
          this.files = await this.api.listFile(this.selectedNode.model.value);
        } catch (e) {
          Swal.fire("Error", e.message, "error");
        }
      }
    },
    async onClickDeleteFiles() {
      if (confirm(`Are you sure remove all files?`)) {
        for (var f of this.selectedFiles) {
          await this.api.removeFile(f.pathname);
        }
        let value = this.getSelectedNode().data.value;
        await this.reloadFiles(value);
      }
    },
    onClickDownloadFiles() {
      for (var f of this.selectedFiles) {
        this.downloadFile(f);
      }
    },
    onSelectedFiles(files) {
      this.selectedFiles = files;
    },
    async reloadNode(node) {
      var model = node.model;
      model.closeChildren();
      model.children = [this.$refs.tree1.initializeLoading()];
      model.openChildren();
    },
    async onClickRenameFolder() {
      var model = this.selectedNode.model;
      if (model.value == "/") {
        return;
      }
      var new_name = prompt("Rename folder?", model.text);
      if (new_name === false) return;

      let root = path.dirname(model.value);
      if (root != "/") root += "/";
      await this.api.renameDirectory(model.value, root + new_name);

      //reload parent
      this.reloadNode(this.selectedNode.$options.parent);
    },
    async reloadFilesContent() {
      let value = this.getSelectedNode().model.value;
      await this.reloadFiles(value);
    },
    async removeFile(f) {
      if (confirm(`Are you sure remove ${f.filename}?`)) {
        await this.api.removeFile(f.pathname);
        let value = this.getSelectedNode().model.value;
        await this.reloadFiles(value);
      }
    },
    getSelectedNode() {
      if (!this.$refs.tree1) {
        return null;
      }
      var n = null;
      this.$refs.tree1.handleRecursionNodeChilds(this.$refs.tree1, node => {
        if (
          typeof node.model != "undefined" &&
          //          node.model.hasOwnProperty("selected") &&
          node.model.selected
        ) {
          n = node;
        }
      });
      return n;
    },
    async onClickRemoveFolder() {
      if (confirm(`Delete folder?`)) {
        try {
          await this.api.removeDirectory(this.selectedNode.model.value);
          var parent = this.selectedNode.$parent;
          this.reloadNode(parent);
          parent.model.selected = true;
        } catch (e) {
          Swal.fire("Error", e.message, "error");
        }
      }
    },
    async onClickCreateFolder() {
      let s = prompt("Folder name?");
      if (s) {
        let model = this.selectedNode.model;
        try {
          var p = model.value;
          if (p != "/") {
            p = p + "/";
          }
          await this.api.createDirectory(p + s);
          this.reloadNode(this.selectedNode);
        } catch (e) {
          Swal.fire("Error", e.message, "error");
        }
      }
    },
    getUrlParam(name) {
      var url = new URL(window.location);
      return url.searchParams.get(name);
    },
    onSelectFile(f) {
      console.log("select file ", f);
      if (this.source == "ckeditor") {
        if (window.opener) {
          window.opener.postMessage(
            {
              source: "ckeditor",
              CKEditorFuncNum: this.getUrlParam("CKEditorFuncNum"),
              token: this.token,
              action: "select-file",
              value: f.pathname
            },
            "*"
          );
          self.close();
        }
      } else {
        window.parent.postMessage(
          {
            token: this.token,
            action: "select-file",
            value: f.pathname,
            id: this.elementId
          },
          "*"
        );
      }
    },
    async onClickRefreshFolder() {
      this.reloadNode(this.getSelectedNode());
      this.files = await this.api.listFile(this.getSelectedNode().model.value);
    },
    onClickAddFile() {
      this.$refs.upload_file_dialog.open(
        this.getSelectedNode().model.value,
        this.type
      );
    },
    async downloadFile(f) {
      await this.api.downloadFile(f);
    },
    async reloadFiles(path) {
      this.files = await this.api.listFile(path);
    },
    async onClickDirectory(node, item) {
      this.selectedFiles = [];
      this.$refs.fileListView.selectedAllFile = false;

      this.files = [];
      console.log(node);
      let ret = await this.api.listFile(item.value);
      this.files = ret;
      this.selectedFolder = item;
    },
    getTreeItem(arr) {
      if (arr === null) {
        return [this.$refs.tree1.initializeLoading()];
      }
      var items = [];
      for (var a of arr) {
        var children = this.getTreeItem(a.children);
        let item = this.$refs.tree1.initializeDataItem({
          text: a.name,
          value: a.pathname,
          selected: this.init_value === a.pathname,
          disabled: false,
          loading: false,
          isLeaf: false,
          children: children,
          opened: a.children !== null
        });
        items.push(item);
      }
      return items;
    },
    async asyncLoad(oriNode, resolve) {
      var value = oriNode.data.value;

      console.log("async load value", value);
      if (!oriNode.data.value) {
        var path = this.init_value ?? "/";

        var children = [];
        try {
          let folders = await this.api.listAllDirecotry(path);
          console.log(folders);
          children = this.getTreeItem(folders);
        } catch (e) {
          alert(e);
        }

        resolve([
          {
            text: "/",
            value: "/",
            selected: path == "/",
            disabled: false,
            loading: false,
            isLeaf: false,
            children: children,
            opened: true
          }
        ]);

        this.files = await this.api.listFile(path);

        return;
      }

      let data;
      try {
        data = await this.api.listDirectory(value);
        console.log(data);
      } catch (e) {
        alert(e);
        return;
      }

      data = data.map(d => {
        return {
          text: d.name,
          value: d.pathname,
          selected: false,
          disabled: false,
          loading: false,
          isLeaf: false,
          children: [this.$refs.tree1.initializeLoading()]
        };
      });

      console.log(data);
      resolve(data);
    }
  }
};
</script>

<style>
.dropzone {
  padding: 20px;
  border: 2px dashed #dedede;
  border-radius: 5px;
  background: #f5f5f5;
}

.dropzone i {
  font-size: 3rem;
}

.dropzone .message {
  color: rgba(0, 0, 0, 0.54);
  font-weight: 500;
  font-size: initial;
  text-transform: uppercase;
}

#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  margin-top: 20px;
}
.unselectable {
  -webkit-user-select: none; /* Safari */
  -moz-user-select: none; /* Firefox */
  -ms-user-select: none; /* IE10+/Edge */
  user-select: none; /* Standard */
}

.main-footer {
  color: #869099;
  position: fixed;
  bottom: 0;
  display: block;
}
</style>
