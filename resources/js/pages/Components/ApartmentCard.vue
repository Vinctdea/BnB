<script>
export default {
  name: "ApartmentCard",
  props: {
    apartment: Object,
  },

  methods: {
    // imageUrl(path) {
    //   return `${image}`; // URL completo dell'immagine
    // }
  }
};
</script>


<template>
  <!-- <div class="card shadow-sm border-0 rounded">
        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Appart-Img">
        <div class="card-body">
            <h5 class="card-title">Appartamento 1</h5>
            <p class="card-text">Indirizzo: Via Roma, 123</p>
            <p class="card-text">Descrizione: Appartamento con 2 camere da letto e 1 bagno.</p>
        </div>
    </div> -->

  <!-- <div class="card shadow-sm border-0 rounded">
    <img
      :src="apartment.image"
      class="card-img-top"
      alt="Appart-Img"
    />
    <div class="card-body">
      <h5 class="card-title">{{ apartment.title }}</h5>
      <p class="card-text">{{ apartment.address }}</p>
      <p class="card-text">Stanze n° {{ apartment.number_beds }}</p>
    </div>
  </div> -->

  <div class="card-container">
    <div class="card-image-wrapper">
        <img :src="apartment.image" class="card-image" alt="Appart-Img" />
    </div>
    <div class="card-info">
      <h5 class="card-title">{{ apartment.title }}</h5>
      <p class="card-text">{{ apartment.address }}</p>
      <p class="card-text">Stanze: {{ apartment.number_beds }}</p>
    </div>
  </div>
</template>

<style scoped lang="scss">
@use '../../../scss/cardApartments' as *;

</style>
