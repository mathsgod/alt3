<template>
  <el-select v-model="localValue" placeholder="Select">
    <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value"></el-option>
  </el-select>
</template>
<script>
export default {
  props: {
    options: {
      type: Array,
      default() {
        return [];
      }
    },
    value: {}
  },
  data() {
    return {
      localValue: this.value
    };
  },
  watch: {
    value() {
      this.localValue = this.value;
    },
    localValue() {
      this.$emit("input", this.localValue);
    }
  }
};
</script>