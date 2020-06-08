<template>
  <div>
    aaa
    <el-row>
      <el-table
        ref="multipleTable"
        :data="tableData"
        tooltip-effect="dark"
        style="width: 100%"
      >
        <el-table-column type="selection" width="55"></el-table-column>
        <el-table-column label="日期" width="120">
          <template slot-scope="scope">{{ scope.row.date }}</template>
        </el-table-column>
        <el-table-column prop="name" label="姓名" width="120"></el-table-column>
        <el-table-column prop="address" label="地址" show-overflow-tooltip></el-table-column>
      </el-table>
    </el-row>bbb
    <table class="table table-sm table-hover">
      <thead>
        <tr>
          <td>
            <el-checkbox v-model="selectedAllFile"></el-checkbox>
          </td>
          <td></td>
          <td class="col-9 unselectable">Name</td>
          <td class="unselectable">Size</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(f,key) in files" :key="key" @dblclick.prevent="$emit('select-file',f)">
          <td>
            <el-checkbox v-model="localSelectedFiles" :label="f">{{ }}</el-checkbox>
          </td>
          <td>
            <el-dropdown trigger="click">
              <el-button size="small">
                Action
                <i class="el-icon-arrow-down el-icon--right"></i>
              </el-button>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item
                  @click.native="$emit('download-file',f)"
                  icon="el-icon-download"
                >Download</el-dropdown-item>
                <el-dropdown-item @click.native="$emit('rename-file',f)" icon="el-icon-edit">Rename</el-dropdown-item>
                <el-dropdown-item
                  @click.native="$emit('remove-file',f)"
                  icon="el-icon-delete"
                >Delete</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </td>
          <td v-text="f.filename" class="unselectable" @mouseover.self="onMouseover(f,$event)"></td>
          <td v-text="f.size" class="unselectable"></td>
        </tr>
      </tbody>
    </table>
  </div>
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
      tableData: [
        {
          date: "2016-05-03",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1518 弄"
        },
        {
          date: "2016-05-02",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1518 弄"
        },
        {
          date: "2016-05-04",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1518 弄"
        },
        {
          date: "2016-05-01",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1518 弄"
        },
        {
          date: "2016-05-08",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1518 弄"
        },
        {
          date: "2016-05-06",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1518 弄"
        },
        {
          date: "2016-05-07",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1518 弄"
        }
      ],
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
