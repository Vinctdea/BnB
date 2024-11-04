<script>
import Routinglist from "./partials/Routinglist.vue";
import { checkAdress } from "../../store/store";
export default {
  name: "CreateApartment",
  components: {
    Routinglist,
  },
  data() {
    return {
      //
      apartment: {
        title: "",
        address: "",
        lat: "",
        lon: "",
        number_rooms: "",
        number_beds: "",
        number_bathrooms: "",

        square_meters: "",
        services: [],
      },
      services: [],
      image: "",
      imagePreview: "", // Aggiunto per gestire l'anteprima dell'immagine
      isAproveStreet: false,
      errors: {},
      street: "",
      number: "",
      city: "",
      postalCode: "",
      place: "",
      resultOfSearch: "",
      activeButton: true,
      clickOfStreet: false,
    };
  },
  methods: {
    validateForm() {
      this.errors = {};

      //validazione delle informazioni geografiche
      if (!this.street) {
        this.errors.street = "La via è obbligatoria.";
      }

      if (!this.city) {
        this.errors.city = "La città è obbligatoria.";
      }

      if (!/^\d{5}$/.test(this.postalCode)) {
        this.errors.postalCode =
          "Il codice postale deve essere composto da 5 cifre.";
      }

      if (this.isAproveStreet) {
        //validazione dei campi relativi all'appartamento da inserire
        if (!this.apartment.title) {
          this.errors.title = "Il nome dell'appartamento è obbligatorio.";
        }

        if (!this.apartment.number_rooms || this.apartment.number_rooms <= 0) {
          this.errors.number_rooms =
            "Il numero di stanze deve essere maggiore di 0.";
        }

        if (!this.apartment.number_beds || this.apartment.number_beds < 0) {
          this.errors.number_beds =
            "Il numero di letti non può essere negativo.";
        }

        if (
          !this.apartment.number_bathrooms ||
          this.apartment.number_bathrooms < 0
        ) {
          this.errors.number_bathrooms =
            "Il numero di bagni non può essere negativo.";
        }

        if (
          !this.apartment.square_meters ||
          this.apartment.square_meters <= 0
        ) {
          this.errors.square_meters =
            "I metri quadri devono essere maggiori di 0.";
        }
      }

      return Object.keys(this.errors).length === 0;
    },

    savePhoto(event) {
      const file = event.target.files[0]; // Salva il file caricato

      if (file && /\.(jpe?g|png|gif)$/i.test(file.name)) {
        this.image = file; // Assegna il file a apartment.image

        let reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreview = e.target.result; // Carica l'anteprima dell'immagine
        };
        reader.readAsDataURL(file); // Converte il file immagine per l'anteprima
      } else {
        this.image = null;
        this.imagePreview = "";
        this.errors.image = "Formato immagine non valido (solo JPG, PNG, GIF).";
      }
    },

    submit() {
      if (!this.validateForm()) return;

      let streetComplete =
        this.street +
        " " +
        this.city +
        " " +
        this.postalCode +
        " " +
        this.place;
      let urlRequest = checkAdress(streetComplete);

      axios
        .get("http://127.0.0.1:8000/proxy-tomtom", {
          params: { url: urlRequest },
        })
        .then((response) => {
          this.resultOfSearch = response.data.results;
          this.activeButton = false;
        })

        .catch((error) => {
          console.error("Errore:", error.response || error.message);
        });
    },
    addServices(idService) {
      if (this.apartment.services.includes(idService)) {
        // lo tolgo
        let index = this.apartment.services.indexOf(idService);
        this.apartment.services.splice(index, 1);
      } else {
        // lo inserisco
        this.apartment.services.push(idService);
      }
      console.log(this.apartment.services);
    },
    activeForm(index) {
      this.clickOfStreet = true;
      this.apartment.address =
        this.street +
        " " +
        this.city +
        " " +
        this.postalCode +
        " " +
        this.place;
      this.apartment.lat = this.resultOfSearch[index].position.lat;
      this.apartment.lon = this.resultOfSearch[index].position.lon;
      this.isAproveStreet = true;
    },
    createApartment() {
      console.log(
        "evento finale",
        "result of search",
        this.resultOfSearch,
        "is aproved street ",
        this.isAproveStreet
      );
      if (!this.validateForm()) return;
      let formData = new FormData();
      formData.append("image", this.image);
      formData.append("apartment", JSON.stringify(this.apartment));

      axios
        .post("api/user/utente/dashboard", formData)
        .then((res) => {
          this.$router.push({
            name: "apartments",
            query: {
              toastMessage: `Appartamento ${this.apartment.title} inserito con successo`,
              toastType: "success",
            },
          });
        })
        .catch((err) => {
          console.log(err.message);
          this.$router.push({
            name: "apartments",
            query: {
              toastMessage: `Errore durante l'inserimento dell'appartamento ${this.apartment.title}`,
              toastType: "error",
            },
          });
        });
    },
  },
  mounted() {
    axios
      .get("api/services")
      .then((response) => {
        this.services = response.data;
      })
      .catch((error) => {
        console.error("Errore durante il caricamento dei dati:", error);
      });
  },
  computed: {},
};
</script>

<template>
  <div class="wrapper d-flex">
    <!-- <aside>
      <Routinglist />
    </aside> -->
    <div class="dashboard-box">
      <div class="container-fluid mt-4 mb-5">
        <div class="row justify-content-center gap-2">
          <div class="col-sm-12 col-md-10 col-lg-10">
            <h1>Inserisci un appartamento</h1>
          </div>

          <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-10">
              <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="row">
                  <div class="mb-3 col-12">
                    <label
                      v-if="isAproveStreet"
                      for="title"
                      class="col-form-label"
                      >Nome appartamento:</label
                    >
                    <input
                      v-if="isAproveStreet"
                      type="text"
                      class="form-control"
                      id="title"
                      name="title"
                      maxlength="500"
                      min="1"
                      v-model="apartment.title"
                    />
                    <span v-if="errors.title" class="text-danger">{{
                      errors.title
                    }}</span>
                  </div>
                </div>
                <div class="row">
                  <div v-if="!isAproveStreet" class="mb-3 col-12">
                    <label for="address" class="col-form-label">Via:</label>
                    <input
                      type="text"
                      class="form-control"
                      id="address"
                      name="address"
                      v-model="street"
                    />
                    <span v-if="errors.street" class="text-danger">{{
                      errors.street
                    }}</span>
                  </div>
                </div>

                <div class="row">
                  <div v-if="!isAproveStreet" class="mb-3 col-12">
                    <label for="address" class="col-form-label">Numero</label>
                    <input
                      type="number"
                      class="form-control"
                      id="address"
                      name="address"
                      v-model="number"
                    />
                    <span v-if="errors.number" class="text-danger">{{
                      errors.number
                    }}</span>
                  </div>
                </div>

                <div class="row">
                  <div v-if="!isAproveStreet" class="mb-3 col-12">
                    <label for="address" class="col-form-label">Citta</label>
                    <input
                      type="text"
                      class="form-control"
                      id="address"
                      name="address"
                      v-model="city"
                    />
                    <span v-if="errors.city" class="text-danger">{{
                      errors.city
                    }}</span>
                  </div>
                </div>
                <div class="row">
                  <div v-if="!isAproveStreet" class="mb-3 col-12">
                    <label for="address" class="col-form-label"
                      >Codice Postale</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      id="address"
                      name="address"
                      v-model="postalCode"
                      maxlength="5"
                    />
                    <span v-if="errors.postalCode" class="text-danger">{{
                      errors.postalCode
                    }}</span>
                  </div>
                </div>
                <div v-if="resultOfSearch && !isAproveStreet" class="">
                  <p>Scegli e clicca un indirizzo per andare avanti:</p>
                  <ul class="p-0 mt-2">
                    <li
                      class="badge rounded-pill text-bg-light p-2"
                      role="button"
                      v-for="(street, index) in resultOfSearch"
                      :key="index"
                      @click="activeForm(index)"
                    >
                      <font-awesome-icon :icon="['fas', 'location-dot']" />
                      {{
                        street.address.streetName +
                        " " +
                        street.address.countrySubdivisionCode +
                        " " +
                        street.address.municipality +
                        " " +
                        street.address.neighbourhood +
                        " " +
                        street.address.postalCode
                      }}
                    </li>
                  </ul>
                </div>

                <div class="row">
                  <div class="mb-3 col-12">
                    <label
                      v-if="isAproveStreet"
                      for="number_rooms"
                      class="col-form-label"
                      >numero stanze:</label
                    >
                    <input
                      v-if="isAproveStreet"
                      type="number"
                      class="form-control"
                      id="number_rooms"
                      name="number_rooms"
                      min="1"
                      max="65535"
                      v-model="apartment.number_rooms"
                    />
                    <span v-if="errors.number_rooms" class="text-danger">{{
                      errors.number_rooms
                    }}</span>
                  </div>
                </div>

                <div class="row">
                  <div class="mb-3 col-12">
                    <label
                      v-if="isAproveStreet"
                      for="number_beds"
                      class="col-form-label"
                      >numero letti:</label
                    >
                    <input
                      v-if="isAproveStreet"
                      type="number"
                      class="form-control"
                      id="number_beds"
                      name="number_beds"
                      min="0"
                      max="65535"
                      v-model="apartment.number_beds"
                    />
                    <span v-if="errors.number_beds" class="text-danger">{{
                      errors.number_beds
                    }}</span>
                  </div>
                </div>

                <div class="row">
                  <div class="mb-3 col-12">
                    <label
                      v-if="isAproveStreet"
                      for="number_bathrooms"
                      class="col-form-label"
                      >numero bagni:</label
                    >
                    <input
                      v-if="isAproveStreet"
                      type="number"
                      class="form-control"
                      id="number_bathrooms"
                      name="number_bathrooms"
                      min="0"
                      max="65535"
                      v-model="apartment.number_bathrooms"
                    />
                    <span v-if="errors.number_bathrooms" class="text-danger">{{
                      errors.number_bathrooms
                    }}</span>
                  </div>
                </div>

                <div class="row">
                  <div v-if="isAproveStreet" class="mb-3 col-12">
                    <label for="square_meters" class="col-form-label"
                      >metri quadri:</label
                    >
                    <input
                      type="number"
                      class="form-control"
                      id="square_meters"
                      name="square_meters"
                      min="0"
                      max="65535"
                      v-model="apartment.square_meters"
                    />
                    <span v-if="errors.square_meters" class="text-danger">{{
                      errors.square_meters
                    }}</span>
                  </div>
                </div>

                <div class="row">
                  <div v-if="isAproveStreet" class="mb-3 col-12">
                    <label for="image" class="col-form-label"
                      >Carica un'immagine:</label
                    >
                    <input
                      type="file"
                      class="form-control mb-3"
                      id="image"
                      name="image"
                      @change="savePhoto"
                    />
                  </div>
                </div>
                <!-- Anteprima immagine caricata -->
                <div class="row">
                  <div v-if="imagePreview && isAproveStreet">
                    <img
                      :src="imagePreview"
                      alt="Anteprima immagine"
                      class="img-fluid mb-3"
                    />
                  </div>
                </div>
                <div v-if="isAproveStreet">
                  <div class="row">
                    <div
                      v-for="item in services"
                      :key="item.id"
                      class="col-6 col-sm-4 col-md-3 col-lg-3 mb-3"
                    >
                      <input
                        type="checkbox"
                        class="btn-check"
                        :id="item.id"
                        autocomplete="off"
                      />
                      <label
                        class="btn btn-outline-dark w-100"
                        @click="addServices(item.id)"
                        :for="item.id"
                        >{{ item.name }}
                      </label>
                    </div>
                  </div>
                </div>

                <button
                  @click="submit"
                  class="btn btn-dark"
                  v-if="activeButton"
                >
                  Approva indirizzo
                </button>
                <button
                  class="btn btn-dark mt-5"
                  @click="createApartment"
                  v-if="!activeButton && clickOfStreet"
                >
                  inserisci appartamento
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang='scss' scoped>
img {
  max-height: 500px;
}
</style>
