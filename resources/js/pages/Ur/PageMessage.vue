<script>
import { store } from "../../store/store";
export default {
  name: "PageMessage",
  data() {
    return {
      user: store.user,
    };
  },
  methods: {},
  computed: {},
};
</script>
<template>
  <h2>non hai inviato nessun messaggio</h2>
</template>
<style lang='scss' scoped>
// @use 'path' as *;

.button {
  cursor: pointer;
}
</style>
