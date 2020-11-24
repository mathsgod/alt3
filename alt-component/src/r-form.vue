<template>
  <card :type="type" :outline="outline">
    <card-body>
      <el-form
        ref="form1"
        :action="action"
        :model="form"
        label-width="auto"
        :method="method"
        :enctype="enctype"
      >
        <slot v-bind:form="form"> </slot>
      </el-form>
    </card-body>
    <card-footer>
      <el-button @click="onSubmit()" icon="el-icon-check" type="success"
        >Submit</el-button
      >
      <el-button @click="onBack()" type="warning">Back</el-button>
    </card-footer>
  </card>
</template>
<script>
export default {
  props: {
    type: String,
    action: String,
    outline: Boolean,
    method: String,
    enctype: String,
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
  provide: {
    rForm: this,
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
    onBack() {
      window.history.go(-1);
    },
  },
};
</script>