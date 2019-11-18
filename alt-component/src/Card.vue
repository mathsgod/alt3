<template>
  <div class="card">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: "card",
  mounted() {
    //console.log("card");
  }
};
</script>