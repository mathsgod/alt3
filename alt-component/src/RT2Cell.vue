<template>
  <td @click="$emit('click', $event)" :style="style">
    <template v-if="editMode">
      <template v-if="column.editType == 'text'">
        <input
          type="text"
          class="form-control form-control-sm"
          v-bind:value="column.getValue(data)"
          @blur="updateData($event.target.value)"
          @keyup.enter="updateData($event.target.value)"
        />
      </template>
      <template v-else-if="column.editType == 'select'">
        <el-select v-model="localValue" size="mini">
          <el-option
            v-for="(opt, opt_key) in column.editData"
            :key="`option${opt_key}`"
            :value="opt.value"
            :label="opt.label"
          ></el-option>
        </el-select>
        <br />
        <el-button
          icon="el-icon-check"
          type="success"
          size="mini"
          @click="updateData(localValue)"
        ></el-button>
        <el-button
          icon="el-icon-close"
          type="warning"
          size="mini"
          @click="cancelUpdate()"
        ></el-button>
      </template>
      <template v-else-if="column.editType == 'date'">
        <input
          type="text"
          class="form-control form-control-sm"
          v-bind:value="column.getValue(data)"
          v-on:blur="updateData($event.target.value)"
        />
      </template>
    </template>
    <template v-else>
      <div v-if="type == 'text'" v-text="content" :style="divStyle"></div>
      <runtime-template-compiler v-if="type == 'vue'" :template="content" />
      <div v-if="type == 'html'" v-html="content" :style="divStyle"></div>
      <el-checkbox v-if="type == 'checkbox'" v-model="is_checked"></el-checkbox>

      <button
        class="btn btn-xs btn-danger"
        v-else-if="type == 'delete'"
        @click="deleteRow()"
      >
        <i class="fa fa-fw fa-times"></i>
      </button>
      <button
        class="btn btn-xs btn-default"
        v-else-if="type == 'sub-row'"
        @click="toggleSubRow()"
      >
        <i v-show="showSubRow" class="fa fa-fw fa-minus"></i>
        <i v-show="!showSubRow" class="fa fa-fw fa-plus"></i>
      </button>
    </template>
  </td>
</template>

<style scoped>
td {
  white-space: nowrap;
  padding: 1px 2px 1px 2px !important;
}
</style>
<script>
export default {
  name: "rt2-cell",
  props: {
    index: Number,
    data: Object,
    column: Object,
    storage: Object,
    editMode: Boolean,
    cssStyle: {
      type: Object,
      default() {
        return {};
      },
    },
  },
  data() {
    return {
      localValue: null,
      is_checked: false,
      showSubRow: false,
      divStyle: {},
    };
  },
  mounted() {
    this.localValue = this.column.getValue(this.data);
    this.$on("reset-local-storage", () => {
      //      console.log("reset");
    });

    if (this.column.wrap) {
      this.divStyle = {
        "word-wrap": "break-word",
        "white-space": "pre-wrap",
      };
    }
  },
  watch: {
    is_checked() {
      this.storage.rows = this.storage.rows || {};

      this.storage.rows[this.column.name] =
        this.storage.rows[this.column.name] || {};

      if (this.is_checked) {
        this.storage.rows[this.column.name][this.content] = true;
      } else {
        delete this.storage.rows[this.column.name][this.content];
      }
      this.storage.save();
    },
  },
  computed: {
    style() {
      var style = this.column.cellStyle || {};
      return Object.assign(style, this.cssStyle);
    },
    type() {
      var o = this.data[this.column.name];
      var t = this.column.type;
      if (o == null) {
        return "text";
      }
      if (typeof o == "object") {
        if (o.type) {
          t = o.type;
        }
      }

      return t;
    },
    content() {
      var o = this.data[this.column.name];
      if (o === null) return "";

      if (Array.isArray(o)) {
        return o.join(" ");
      }

      if (this.column.type == "sub-row") {
        return o;
      }

      if (typeof o == "object") {
        return o.content;
      }

      return o;
    },
  },
  created() {},
  methods: {
    reloadValue() {
      this.is_checked = false;
      this.storage.rows = this.storage.rows || {};
      this.storage.rows[this.column.name] =
        this.storage.rows[this.column.name] || {};
      if (this.storage.rows[this.column.name][this.content]) {
        this.is_checked = true;
      }
    },
    getValue() {
      var o = this.data[this.column.name];
      if (!o) return "";

      if (typeof o == "object") {
        if (o.type == "raw") {
          return o.content;
        } else {
          return o.value;
        }
      }
      return o;
    },
    setCheckbox(value) {
      this.is_checked = value;
    },
    toggleCheckBox(e) {
      this.setCheckbox(e);
    },
    isEditMode() {
      return false;
    },
    deleteRow() {
      if (confirm("Are your sure?")) {
        this.$http.delete(this.content).then(() => {
          this.$emit("data-deleted");
        });
      }
    },
    toggleSubRow() {
      this.showSubRow = !this.showSubRow;

      this.$emit("toggle-sub-row", this.content);
    },
    updateData(value) {
      if (!this.column.editable) return;

      if (this.column.editType == "text") {
        if (this.getValue() != value) {
          this.$emit("update-data", value);
        }
        return;
      }

      if (this.column.editType == "select") {
        if (this.data[this.column.data].value != value) {
          this.$emit("update-data", value);
        }
        return;
      }
    },
    cancelUpdate() {
      this.localValue = this.column.getValue(this.data);
      this.$emit("cancel-edit-mode");
    },
    updateSelectData(e) {
      console.log(e);
    },
  },
};
</script>