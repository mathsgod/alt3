<template>
    <div>{{from}} - {{to}} of {{total}}</div>
</template>

<script>
export default {
  name: "rt-info",
  props: ["from", "to", "total"]
};
</script>
