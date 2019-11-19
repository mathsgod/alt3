<template>
  <div class="card-header">
    <h3 class="card-title">
      <slot name="title"></slot>
    </h3>
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: "card-header"
};
</script>