<script>
import axios from "axios";
import { store } from "../../store/store";
import { checkAdress } from "../../store/store";

export default {
  name: "EditApartment",

  data() {
    return {
      apartment: "",
      errors: {},
      image: "",
      services: [],
    };
  },
  methods: {
    findApartment(id) {
      axios
        .get("http://127.0.0.1:8000/api/show-apartment", {
          params: {
            id: id,
          },
        })
        .then((res) => {
          this.apartment = res.data;
          console.log("services start", res.data.services);
          axios
            .get("http://127.0.0.1:8000/api/services")
            .then((res) => {
              this.services = res.data;
              store.isLoading = false;
            })
            .catch((err) => console.log(err));
        })
        .catch((err) => {
          console.log(err);

        });
    },
    // changeItem(id) {
    //   let isInside = this.apartment.services.filter((service) => {
    //     return service.id == id;
    //   });
    //   if (isInside) {
    //     console.log();

    //     this.apartment.services.splice(id - 1, 1);
    //   } else {

    //     this.apartment.services.push(this.services[id - 1]);
    //   }
    //   console.log(this.apartment.services);
    // },
    changeVisibility() {
      // Cambia il valore booleano di is_visible
      this.apartment.is_visible = !this.apartment.is_visible;
    },

    validate() {
      this.errors = {};

      if (!this.apartment.title) {
        this.errors.title = "Il titolo è obbligatorio.";
      }
      if (
        !this.apartment.number_rooms ||
        isNaN(this.apartment.number_rooms) ||
        this.apartment.number_rooms <= 0
      ) {
        this.errors.number_rooms = "Inserisci un numero di stanze valido.";
      }
      if (
        !this.apartment.number_beds ||
        isNaN(this.apartment.number_beds) ||
        this.apartment.number_beds <= 0
      ) {
        this.errors.number_beds = "Inserisci un numero di letti valido.";
      }
      if (
        !this.apartment.number_bathrooms ||
        isNaN(this.apartment.number_bathrooms) ||
        this.apartment.number_bathrooms <= 0
      ) {
        this.errors.number_bathrooms = "Inserisci un numero di bagni valido.";
      }
      if (!this.apartment.address) {
        this.errors.address = "L'indirizzo è obbligatorio.";
      }
      if (
        !this.apartment.square_meters ||
        isNaN(this.apartment.square_meters) ||
        this.apartment.square_meters <= 0
      ) {
        this.errors.square_meters =
          "Inserisci un valore di metri quadri valido.";
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
    createFormData(apartment) {
      let objChange = {};
      objChange["title"] = apartment.title;
      objChange["number_rooms"] = apartment.number_rooms;
      objChange["number_beds"] = apartment.number_beds;
      objChange["number_bathrooms"] = apartment.number_bathrooms;
      objChange["square_meters"] = apartment.square_meters;
      objChange["is_visible"] = apartment.is_visible;
      objChange["address"] = apartment.address;
      objChange["lat"] = apartment.lat;
      objChange["lon"] = apartment.lon;
      let serviceToSend = this.apartment.services.map((service) => {
        return service.id;
      });
      objChange["services"] = serviceToSend;
      return objChange;
    },
    async submit() {
      if (!this.validate()) {
        console.log("Errore di validazione", this.errors);
        return;
      }
      let urlRequest = checkAdress(this.apartment.address);

      await axios
        .get("http://127.0.0.1:8000/proxy-tomtom", {
          params: { url: urlRequest },
        })
        .then((response) => {
          this.apartment.lat = response.data.results[0].position.lat;
          this.apartment.lon = response.data.results[0].position.lon;
          console.log("cambiato");
        })

        .catch((error) => {
          console.error("Errore:", error.response || error.message);
        });
      let data = this.createFormData(this.apartment);
      let formData = new FormData();
      formData.append("image", this.image);

      formData.append("apartment", JSON.stringify(data));
      formData.append("id", this.apartment.id);

      axios
        .post("http://127.0.0.1:8000/api/user/update-file", formData)
        .then((res) => {
          this.$router.push({
            name: "apartments",
            query: {
                    toastMessage: `Appartamento ${this.apartment.title} modificato con successo`,
                    toastType: "success",
                },
            });
        })
        .catch((err) => {
          console.log(err);
            this.$router.push({
                name: "apartments",
                query: {
                        toastMessage: `Errore nella modifica di ${this.apartment.title}`,
                        toastType: "danger",
                    },
                });

        });
      console.log(this.changes, this.image);
    },
    isSelectedServices(id) {
      return this.apartment.services
        .map((service) => {
          return service.id;
        })
        .includes(id);
    },
    toggleChecked(serviceId) {
      // Aggiunge o rimuove l'ID del servizio all'array selectedServices
      if (this.isSelectedServices(serviceId)) {
        this.apartment.services = this.apartment.services.filter(
          (service) => service.id !== serviceId
        );
        console.log("slice", this.apartment.services);
      } else {
        this.apartment.services.push(this.services[serviceId - 1]);
        console.log("push", this.apartment.services);
      }
      console.log("new service", this.apartment.services);
    },
  },
  computed: {
    apartamentFiltred() {
      return this.apartment;
    },
    image() {
      return `http://127.0.0.1:8000/${this.apartment.image}`;
    },
    isLoading() {
      return store.isLoading;
    },
    // isSelectedServices(id) {
    //   return this.apartment.services
    //     .map((service) => {
    //       return service.id;
    //     })
    //     .includes(id);
    // },
  },
  mounted() {
    this.findApartment(this.$route.params.id);
  },
  beforeRouteEnter() {
    store.isLoading = true;
  },
  beforeRouteLeave() {
    store.isLoading = true;
  },
};
</script>

<template>
  <div class="container" v-if="!isLoading">
    <h1 class="my-5">Modifica di {{ apartamentFiltred.title }}</h1>

    <form @submit.prevent="submit" class="mb-4">
      <label for="title" class="form-label">Titolo</label>
      <input
        type="text"
        class="form-control"
        v-model="apartment.title"
        id="title"
        name="title"
      />
      <div v-if="errors.title" class="text-error">
        <small>
          {{ errors.title }}
        </small>
      </div>

      <label for="" class="form-label">Numero di stanze</label>
      <input
        type="number"
        class="form-control"
        v-model="apartment.number_rooms"
        id="number_rooms"
        name="number_rooms"
      />
      <div v-if="errors.number_rooms" class="text-error">
        <small>
          {{ errors.number_rooms }}
        </small>
      </div>

      <label for="" class="form-label">Numero di letti</label>
      <input
        type="number"
        class="form-control"
        v-model="apartment.number_beds"
        id="number_beds"
        name="number_beds"
      />
      <div v-if="errors.number_beds" class="text-error">
        <small>
          {{ errors.number_beds }}
        </small>
      </div>

      <label for="" class="form-label">Numero di bagni</label>
      <input
        type="number"
        class="form-control"
        v-model="apartment.number_bathrooms"
        id="number_bathrooms"
        name="number_bathrooms"
      />
      <div v-if="errors.number_bathrooms" class="text-error">
        <small>
          {{ errors.number_bathrooms }}
        </small>
      </div>

      <label for="" class="form-label">Indirizzo</label>
      <input
        type="text"
        class="form-control"
        v-model="apartment.address"
        id="address"
        name="address"
      />
      <div v-if="errors.address" class="text-error">
        <small>
          {{ errors.address }}
        </small>
      </div>

      <label for="" class="form-label">Metri quadri</label>
      <input
        type="text"
        class="form-control"
        v-model="apartment.square_meters"
        id="square_meters"
        name="square_meters"
      />
      <div v-if="errors.square_meters" class="text-error">
        <small>
          {{ errors.square_meters }}
        </small>
      </div>

      <label for="image" class="col-form-label">Carica un'immagine:</label>
      <input
        type="file"
        class="form-control mb-3"
        id="image"
        name="image"
        @change="savePhoto"
        v-on="apartamentFiltred.image"
      />
      <img :src="image" alt="" /><br />

      <label>Servizi:</label>
      <div class="d-flex flex-wrap gap-2" v-if="services">
        <div v-for="item in services" :key="item.id">
          <input
            type="checkbox"
            class="btn-check"
            :checked="isSelectedServices(item.id)"
            :id="item.id"
            @click="toggleChecked(item.id)"
          />
          <label class="btn btn-outline-dark w-100" :for="item.id"
            >{{ item.name }}
          </label>
        </div>
      </div>
      <label class="form-check-label" for="flexSwitchCheckDefault"
        >Visibilità</label
      >
      <div class="form-check form-switch">
        <input
          @click="changeVisibility"
          class="form-check-input"
          type="checkbox"
          role="switch"
          :checked="!apartment.is_visible"
        />

        <span v-if="apartment.is_visible">Appartamento visibile</span>
        <span v-else>Appartamento non visibile</span>
      </div>

      <button type="submit" class="btn btn-dark">Salva</button>
    </form>
  </div>
  <div class="contLoader" v-else>
    <div class="loader"></div>
  </div>
</template>



<style lang="scss" scoped>
label {
  margin-top: 20px;
}
button {
  margin-top: 30px;
}

.text-error {
  color: red;
}
</style>
