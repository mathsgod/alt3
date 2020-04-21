<template>
  <div class="row">
    <div
      class="card col-2 m-2 p-0"
      v-for="(f,key) in files"
      :key="key"
      @dblclick.prevent="$emit('select-file',f)"
    >
      <img class="card-img-top" :src="f.url" v-if="isImage(f)" />
      <div class="card-img-top p-2 text-center" v-else>
        <i class="fa fa-fw fa-6x" :class="getFileIcon(f)"></i>
      </div>

      <div class="card-body">
        <h6 class="card-title" v-text="f.filename"></h6>
      </div>
      <div class="card-footer p-1">
        <div class="btn-group">
          <button class="btn btn-sm btn-info" @click.prevent="$emit('download-file',f)">
            <i class="fa fa-fw fa-download"></i>
          </button>
          <button class="btn btn-sm btn-danger" @click.prevent="$emit('remove-file', f)">
            <i class="fa fa-fw fa-times"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import path from "path";
export default {
  props: {
    api: {},
    files: {
      default: []
    }
  },
  methods: {
    getFileIcon(f) {
      var extname = path.extname(f.filename);
      switch (extname) {
        case "pdf":
          return ["fa-file-pdf"];
      }

      return ["fa-file"];
    },
    isImage(f) {
      var extname = path.extname(f.filename);
      if (extname == ".jpg" || extname == ".png" || extname == ".gif") {
        return true;
      }
      return false;
    }
  }
};
</script>