<template>
  <table class="table table-sm table-hover">
    <thead>
      <tr>
        <td>
          <div class="icheck-info">
            <input type="checkbox" id="selected_all_file" v-model="selectedAllFile" />
            <label for="selected_all_file"></label>
          </div>
        </td>
        <td></td>
        <td class="col-9 unselectable">Name</td>
        <td class="unselectable">Size</td>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(f,key) in files" :key="key" @dblclick.prevent="$emit('select-file',f)">
        <td>
          <div class="icheck-info">
            <input type="checkbox" :id="`file${key}`" v-model="localSelectedFiles" :value="f" />
            <label :for="`file${key}`"></label>
          </div>
        </td>
        <td>
          <div class="btn-group">
            <button
              type="button"
              class="btn btn-sm dropdown-toggle"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >Action</button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="#" @click.prevent="$emit('download-file',f)">
                <i class="fa fa-fw fa-download"></i> Download
              </a>
              <a class="dropdown-item" href="#" @click.prevent="$emit('rename-file',f)">
                <i class="fa fa-fw fa-pen"></i> Rename
              </a>
              <a class="dropdown-item" href="#" @click.prevent="$emit('remove-file',f)">
                <i class="fa fa-fw fa-times"></i> Delete
              </a>
              <!-- div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Separated link</a-->
            </div>
          </div>
        </td>
        <td v-text="f.filename" class="unselectable" @mouseover.self="onMouseover(f,$event)"></td>
        <td v-text="f.size" class="unselectable"></td>
      </tr>
    </tbody>
  </table>
</template>
<script>
import tippy from "tippy.js";
//import Poppy from "poppyjs";
import path from "path";
export default {
  props: {
    api: {},
    files: {
      default() {
        return [];
      }
    },
    selectedFiles: {
      default() {
        return [];
      }
    }
  },
  data() {
    return {
      selectedAllFile: false,
      localSelectedFiles: this.selectedFiles
    };
  },
  watch: {
    selectedAllFile() {
      if (this.selectedAllFile) {
        this.localSelectedFiles = this.files;
      } else {
        this.localSelectedFiles = [];
      }
    },
    localSelectedFiles() {
      this.$emit("selected-files", this.localSelectedFiles);
    }
  },
  methods: {
    onClickFile(file, e) {
      if (e.target.checked) {
        this.$emit("selected-file", file);
      } else {
        this.$emit("unselected-file", file);
      }
    },
    onMouseover(file, event) {
      var extname = path.extname(file.filename);
      if (extname == ".jpg" || extname == ".png" || extname == ".gif") {
        let ins = tippy(event.target, {
          content: "Loading...",
          allowHTML: true,
          async onShow(instance) {
            instance.setContent(`<img src="${file.url}" width="200"/>`);
          }
        });
        if (ins) {
          ins.show();
        }
      }
    }
  }
};
</script>
