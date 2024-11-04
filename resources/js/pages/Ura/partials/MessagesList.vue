<script>
import { store } from '../../../store/store';
export default {
  name: 'MessagesList',
  props: {
    apartment: {
      type: Array,
      required: true,
    },
  },
  methods: {
    formatDate(dateStr) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      const date = new Date(dateStr);
      return date.toLocaleDateString('it-IT', options);
    },

    getImage(index) {
      const images = [
         "https://picsum.photos/seed/profile1/80/80",
         "https://picsum.photos/seed/profile2/80/80",
         "https://picsum.photos/seed/profile3/80/80",
         "https://picsum.photos/seed/profile4/80/80",
         "https://picsum.photos/seed/profile5/80/80",
         "https://picsum.photos/seed/profile6/80/80",
         "https://picsum.photos/seed/profile7/80/80",
         "https://picsum.photos/seed/profile8/80/80",
         "https://picsum.photos/seed/profile9/80/80",
         "https://picsum.photos/seed/profile10/80/80"
       ];
       return images[index % images.length];
     },

    handleSelectMessage(message) {
        console.log('Messaggio:', message);
        console.log('Index:', index);
      this.$emit('message', message);
    },
  },
  mounted(){
    console.log('messaggi', this.messages);
  }
};
</script>

<template>
  <ul class="list-group list-group-flush">
    <li class="list-group-item d-flex align-items-center" v-for="message in apartment.messages" :key="message.id" @click="handleSelectMessage(message)">

        <div class="image me-3">
            <img src="" alt="utente">
        </div>

        <div class="text-box my-2">
            <div class="d-flex justify-content-between">
                <h6>{{ message.name }} {{ message.surname }}</h6>
                <small>{{ formatDate(message.created_at) }}</small>
            </div>
            <small>{{ message.email }}</small>
            <small class="message-email">{{ message.message }}</small>
        </div>

    </li>
  </ul>
</template>

<style lang="scss">
@use '../../../../scss/message' as*;

li{
    cursor:pointer;
}
</style>
