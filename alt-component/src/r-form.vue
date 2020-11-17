<template>
  <el-form ref="form1" :action="action" :model="form" label-width="auto">
    <slot v-bind:form="form"> </slot>
    <el-form-item>
      <el-button @click="onSubmit()" icon="el-icon-check" type="success"
        >Submit</el-button
      >
    </el-form-item>
  </el-form>
</template>
<script>
export default {
  props: {
    action: String,
    data: {
      type: Object,
      default() {
        return {};
      },
    },
  },
  data() {
    return {
      form: this.data,
    };
  },
  mounted() {},
  methods: {
    onSubmit() {
      this.$refs.form1.validate((valid) => {
        if (valid) {
          this.$refs.form1.$el.submit();
        }
      });
    },
  },
};
</script>