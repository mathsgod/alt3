<template>
  <div>
    Testing
    <el-input-number v-model="num" @change="handleChange"></el-input-number>
  </div>
</template>
<script>
export default {
  data() {
    return {
      num: 1,
    };
  },
  methods: {
    handleChange(value) {
      console.log(value);
    },
  },
};
</script>