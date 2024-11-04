<script>
export default {
  name: "Avatar",
  props: {
    name: {
      type: String,
      required: true,
    },
    surname: {
      type: String,
      required: true,
    },
    index: {
      type: Number,
      default: 0,
    },
  },
  computed: {
    initials() {
      return `${this.name[0] || ""}${this.surname[0] || ""}`.toUpperCase();
    },
    backgroundColor() {
      const colors = ["#FFB6C1", "#8A2BE2", "#7FFF00", "#FF4500", "#1E90FF"];
      return colors[this.index % colors.length];
    },
  },
};
</script>

<template>
  <div class="avatar" :style="{ backgroundColor: backgroundColor }">
    <span class="initials">{{ initials }}</span>
  </div>
</template>

<style lang="scss" scoped>

.avatar {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2em;
  font-weight: bold;
  color: white;

}
</style>
