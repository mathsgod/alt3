<template>
  <div class="card-header">
    <slot></slot>
    <slot name="title"></slot>
    <slot name="tools"></slot>
  </div>
</template>

<script>
export default {
  name: "card-header"
};
</script>