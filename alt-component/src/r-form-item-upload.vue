<template>
  <el-upload
    action=""
    :auto-upload="false"
    :limit="1"
    :on-change="onChange"
    ref="upload"
  >
    <slot></slot>
  </el-upload>
</template>
<script>
export default {
  props: {
    name: String,
  },
  methods: {
    onChange(file, fileList) {
      console.log(file, fileList);

    },
  },
};
</script>