<template>
  <el-date-picker v-model="localValue" v-bind="$attrs"></el-date-picker>
</template>
<script>
export default {
  props: {
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