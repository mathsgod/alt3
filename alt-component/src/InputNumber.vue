<template>
    <el-input-number v-model="localValue" v-bind="$attrs"></el-input-number>
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
}
</script>