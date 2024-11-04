<script>
import { store } from "../../store/store";
export default {
  name: "ShowApartment",

  data() {
    return {
      apartment: [],
      src: "",
      alt: "",
      name: "",

      //   dati messaggio
      name: "",
      surname: "",
      email: "",
      message: "",

      errors: {
        name: "",
        surname: "",
        email: "",
        message: "",
      },

      toastMessage: "",

      buttonMessage: "Invia messaggio",

      disableCheck: false,

      // Icone dei servizi
      defaultIcon: "fas fa-question-circle", // Icona di default
      servicesIcons: {
        "wi-fi": "fas fa-wifi",
        piscina: "fas fa-water",
        sauna: "fas fa-snowflake",
        navetta: "fas fa-bus",
        massaggio: "fas fa-spa",
      },
    };
  },
  methods: {
    // imageUrl(path) {
    //   return `http://127.0.0.1:8000/${path}`; // URL completo dell'immagine
    // },
    getMap() {
      const tt = window.tt; //accesso alla libreria TomTom
      let center = [this.apartment.lon, this.apartment.lat]; //centro della mappa in base alle coordinate dell'appartamento
      let size = 50; //dimensioni del popup

      const map = tt.map({
        key: "qNjsW3gGJOBNhFoXhBzsGRJAk5RJMJhI",
        center: center,
        container: "map",
        zoom: 12,
      });

      //accesso alle coordinate nel JSON generato dall'API

      const lat = this.apartment.lat; //valore della latitudine di ogni appartamento
      const lon = this.apartment.lon; //valore dalla longitudine di ogni appartamento

      if (!lat || !lon) {
        console.error("Coordinate non valide per l'appartamento");
        return;
      }

      let boxContent = document.createElement("div");
      boxContent.innerHTML = `
                    <div class="card-body">
                        <h5 class="title-popup"><strong>${this.apartment.title}</strong></h5>
                        <p class="title-popup">${this.apartment.address}</p>
                        <small>8000 euro</small>
                    </div>`;

      let popup = new tt.Popup({
        closeButton: true, //permettere la chiusura il popup
        closeOnClick: true, //chiudere il popup al click su un'altra parte della mappa
        offset: size,
        // anchor: 'none'
      }).setDOMContent(boxContent); //contenuto dinamico del popup in base alle cards degli appartamenti

      //creare il marker per l'appartamento
      let marker = new tt.Marker().setLngLat([lon, lat]).setPopup(popup); //collegare il popup al marker

      marker.addTo(map);

      //   const bounds = [
      //     [10.501, 40.7994], //estremi sud-ovest (longitudine, latitudine)
      //     [13.9894, 42.8995], //estremi nord-est (longitudine, latitudine)
      //   ];

      //   map.setMaxBounds(bounds);

      map.addControl(new tt.FullscreenControl());
      map.addControl(new tt.NavigationControl());
    },

    validateName() {
      if (!this.name) {
        this.buttonMessage = "Invia messaggio";
        this.errors.name = "";
      } else if (!/^[a-zA-Z]+$/.test(this.name)) {
        this.buttonMessage = "Invia messaggio";
        this.errors.name = "Il nome deve contenere solo lettere";
      } else if (this.name.length < 4) {
        this.buttonMessage = "Invia messaggio";
        this.errors.name =
          "il nome non può avere una lunghezza inferiore di 4 caratteri";
      } else {
        this.errors.name = "";
      }
    },

    validateSurname() {
      if (!this.surname) {
        this.buttonMessage = "Invia messaggio";
        this.errors.surname = "";
      } else if (!/^[a-zA-Z\s]+$/.test(this.surname)) {
        this.buttonMessage = "Invia messaggio";
        this.errors.surname = "Il cognome deve contenere solo lettere";
      } else if (this.surname.length < 4) {
        this.buttonMessage = "Invia messaggio";
        this.errors.surname =
          "Il cognome non può avere una lunghezza inferiore di 4 caratteri";
      } else {
        this.errors.surname = "";
      }
    },

    validateEmail() {
      const emailRegex = /^[a-zA-Z0-9.]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

      if (!this.email) {
        this.buttonMessage = "Invia messaggio";
        this.errors.email = "L'email è obbligatoria.";
      } else if (!emailRegex.test(this.email)) {
        this.buttonMessage = "Invia messaggio";
        this.errors.email =
          "Inserisci una email valida, ad esempio: nome.cognome@mail.com";
      } else {
        this.errors.email = "";
      }
    },

    validateMessage() {
      if (!this.message) {
        this.buttonMessage = "Invia messaggio";
        this.errors.message = "";
      } else if (this.message.length < 10) {
        this.buttonMessage = "Invia messaggio";
        this.errors.message =
          "Il messaggio non può avere una lunghezza inferiore a 10 caratteri";
      } else {
        this.errors.message = "";
      }
    },

    showToast(text, type = "success") {
      //metodo che permette di mostrare il toast

      this.toastMessage = text;
      const toastEl = this.$refs.liveToast;

      toastEl.classList.remove("text-bg-success", "text-bg-danger");

      if (type === "success") {
        toastEl.classList.add("text-bg-success");
      } else {
        toastEl.classList.add("text-bg-danger");
      }
      const toast = new bootstrap.Toast(toastEl);

      toast.show();

      setTimeout(() => {
        toast.hide();
      }, 5000);
    },

    hideToast() {
      //metodo che permette di nascondere il toast

      const toastEl = this.$refs.liveToast;

      const toast = new bootstrap.Toast(toastEl);

      toast.hide();
    },

    changeDisable() {
      this.disableCheck = !this.disableCheck;
    },

    submitMessage() {
      const messageData = {
        name: this.name,
        surname: this.surname,
        email: this.email,
        message: this.message,
      };

      axios
        .post(
          `http://127.0.0.1:8000/api/apartments/${this.apartment.id}/send-message`,
          messageData
        )
        .then((res) => {
          console.log(res.data);
          if (res.data.success) {
            this.showToast("Messaggio inviato correttamente", "success");
            this.errors = {
              name: "",
              surname: "",
              email: "",
              message: "",
            };
            localStorage.setItem(`apartment${12}Count`, 1);
            this.buttonMessage = "Messaggio inviato";

            this.changeDisable();
          } else {
            this.showToast("Errore durante l'invio del messaggio", "error");
            this.errors = res.data.errors;
            console.log(res.data.errors);
          }
        })
        .catch((er) => {
          console.log(er.message);
          this.showToast("Errore durante l'invio del messaggio", "error");
        });
      console.log(messageData);
    },

    submitForm() {
      this.buttonMessage = "Invio in corso...";
      this.validateName();
      this.validateSurname();
      this.validateEmail();
      this.validateMessage();

      this.submitMessage();
    },
    async findApartment(id) {
      await axios
        .get("http://127.0.0.1:8000/api/show-apartment", {
          params: {
            id: id,
          },
        })
        .then((res) => {
          this.apartment = res.data;
          console.log(res.data);
          this.getMap();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    countView(id) {
      console.log("view");
      axios
        .post("http://127.0.0.1:8000/api/view", { apartment: id })
        .then((res) => {
          console.log("view ok", res);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    imageUrl(path) {
      return `http://127.0.0.1:8000/${path}`;
    },
  },

  computed: {
    apartmentFiltred() {
      return this.apartment;
    },
    is_logged() {
      return store.is_logged;
    },
    // Computed  per verificare la presenza dell'appartamento in userApartment
    isApartmentInUserList() {
      // Verifica se l'id dell'appartamento è incluso nell'array userApartment
      return store.userApartment.some(
        (userApt) => userApt.id === this.apartment.id
      );
    },
    haveServices() {
      return this.apartment?.services?.length;
    },
    ifSponsored() {
      return this.apartment.sponsors && this.apartment.sponsors.length > 0;
    },
    isLoading() {
      return store.isLoading;
    },
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (to.name === "showApartment" && from.name !== "apartments") {
        vm.countView(to.params.id);
      }
    });
  },

  beforeRouteLeave() {
    store.isLoading = true;
  },
  mounted() {
    console.log(store);

    this.findApartment(this.$route.params.id);
    // console.log(store.allApartments[this.$route.params.id - 1]);
    // nextTick fa in modo che il DOM sia completamente pronto

    console.log(store.user.email);
    // autocomplete mail se logged
    if (!store.user.email) {
      this.email = "";
    } else {
      this.email = store.user.email;
    }
  },
};
</script>


<template>
  <div v-if="apartmentFiltred">
    <div class="container mt-5">
      <!-- Titolo dell'appartamento -->
      <div class="row">
        <div class="col-12 text-start">
          <div class="box-title">
            <h1 class="apartment-title">{{ apartment.title }}</h1>
            <div
              v-if="ifSponsored"
              class="d-flex align-items-center gap-3 box-sponsor"
            >
              Sponsorizzato
              <i class="fa solid fa-award"></i>
            </div>
          </div>
          <p class="apartment-description">
            Scopri il comfort e la bellezza di questo appartamento unico.
          </p>
          <router-link
            v-if="is_logged && isApartmentInUserList"
            class="btn btn-dark"
            :to="{
              name: 'EditApartment',
              params: { id: apartment.id },
            }"
            >modifica
          </router-link>
        </div>
      </div>

      <!-- Sezione Immagini -->
      <div class="row my-4">
        <div class="col-12">
          <div v-if="apartment.image" class="image-container mb-4">
            <img
              :src="imageUrl(apartment.image)"
              :alt="apartment.name"
              class="img-fluid rounded shadow-sm"
            />
          </div>
          <div v-else class="image-container mb-4">
            <img
              src="img/no-image.png"
              alt="Immagine non disponibile"
              class="img-fluid rounded shadow-sm"
            />
          </div>
        </div>
      </div>

      <!-- Indirizzo e Informazioni Aggiuntive -->
      <div class="row mb-4">
        <div class="col-12 text-start">
          <h3 class="apartment-address">Indirizzo: {{ apartment.address }}</h3>
          {{ apartment.number_beds }} letti &bull;
          {{ apartment.number_bathrooms }} bagni &bull;
          {{ apartment.number_rooms }} stanze &bull;
          {{ apartment.square_meters }} m²
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6 col-md-12 mb-4">
          <div class="row">
            <div class="col-6 mb-3 services-section py-3">
              <h3 class="section-title my-2">Cosa troverai</h3>
              <ul class="services-list my-3" v-if="!haveServices">
                <li class="py-2 d-flex align-items-center">
                  <i :class="defaultIcon"></i> Nessun servizio disponibile
                </li>
              </ul>
              <ul v-else class="my-3 d-flex flex-column">
                <li
                  v-for="(service, index) in apartment.services"
                  :key="index"
                  class="py-2 d-flex align-items-center gap-3"
                >
                  <i
                    :class="servicesIcons[service.name] || defaultIcon"
                    class="service-icon"
                  ></i>
                  <span>{{ service.name }}</span>
                </li>
              </ul>
            </div>

            <!-- Stato di Disponibilità -->
            <div class="col-6 availability-section py-3">
              <h3 class="section-title my-2">Disponibilità</h3>
              <p class="py-3 is-visible">
                {{ apartment.is_visible ? "Sì" : "No" }}
              </p>
            </div>
          </div>

          <!-- Descrizione fittizia -->
          <div class="col">
            <h3 class="section-title my-3">Descrizione</h3>
            <p class="description">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam
              iste explicabo nihil, ex totam debitis adipisci ullam! Sunt
              obcaecati fugiat magni, exercitationem ab aliquid voluptates
              corrupti earum debitis iusto nam porro dignissimos dolor dolore
              illum quidem ipsum perspiciatis magnam alias veritatis voluptate
              ipsam labore aspernatur. Sunt voluptate maiores qui asperiores
              deleniti quidem iste aperiam, vel et eveniet?
            </p>
          </div>
        </div>

        <!-- Colonna Destra per il Modulo Messaggi -->
        <div class="col-lg-6 col-md-12 d-flex justify-content-end box-message">
          <div class="message-form-container mt-4 rounded p-4">
            <h4 class="form-title text-center my-3">
              <strong>Invia un messaggio al proprietario</strong>
            </h4>
            <form class="message-form" @submit.prevent="submitForm">
              <div class="mb-3">
                <label for="name" class="form-label">Il tuo nome</label>
                <input
                  type="text"
                  id="name"
                  class="form-control"
                  placeholder="Inserisci il tuo nome"
                  v-model="name"
                  @input="validateName"
                  :disabled="disableCheck"
                />
                <small v-if="errors.name" class="error-message">{{
                  errors.name.toString()
                }}</small>
              </div>
              <div class="mb-3">
                <label for="surname" class="form-label">Il tuo cognome</label>
                <input
                  type="text"
                  id="surname"
                  class="form-control"
                  placeholder="Inserisci il tuo cognome"
                  v-model="surname"
                  @input="validateSurname"
                  :disabled="disableCheck"
                />
                <small v-if="errors.surname" class="error-message">{{
                  errors.surname.toString()
                }}</small>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">La tua email</label>
                <input
                  type="email"
                  id="email"
                  class="form-control"
                  placeholder="Inserisci la tua email"
                  v-model="email"
                  @input="validateEmail"
                  :disabled="disableCheck"
                />
                <small v-if="errors.email" class="error-message">{{
                  errors.email.toString()
                }}</small>
              </div>
              <div class="mb-3">
                <label for="message" class="form-label">Il tuo messaggio</label>
                <textarea
                  id="message"
                  class="form-control"
                  rows="3"
                  placeholder="Scrivi il tuo messaggio..."
                  v-model="message"
                  @input="validateMessage"
                  :disabled="disableCheck"
                ></textarea>
                <small v-if="errors.message" class="error-message">{{
                  errors.message.toString()
                }}</small>
              </div>
              <button
                type="submit"
                class="btn btn-dark btn-lg w-100 btn-text"
                :disabled="disableCheck"
              >
                <!-- Invia il messaggio -->
                {{ buttonMessage }}
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Mappa per geolocalizzazione dell'appartamento -->
      <div class="row g-2">
        <div class="col-12 my-4">
          <h4 class="map-title my-3">Dove sarai</h4>
          <div id="map"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="contLoader" v-else>
    <div class="loader"></div>
  </div>
  <!-- <div v-else>Appartamento non trovato</div> -->

  <!-- Toast per notifiche -->
  <div
    ref="liveToast"
    class="toast align-items-center text-bg-success position-fixed bottom-0 end-0 p-2 m-3"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    style="z-index: 1050"
  >
    <div class="d-flex">
      <div class="toast-body">{{ toastMessage }}</div>
      <button
        type="button"
        class="btn-close btn-close-white me-2 m-auto"
        @click="hideToast"
        aria-label="Close"
      ></button>
    </div>
  </div>
</template>


<style scoped>
ul {
  padding-left: 0;
}

i {
  width: 20px;
}
.fa-award {
  font-size: 2rem;
}
.apartment-title {
  font-size: 2rem;
  font-weight: bold;
}

.apartment-description {
  font-size: 1.1rem;
  color: #6c757d;
  margin-top: 0.5rem;
}

.image-container img {
  width: 100%;
  max-height: 400px;
  object-fit: cover;
}

.additional-info {
  font-size: 1rem;
  color: #6c757d;
}

.section-title {
  font-size: 1.25rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.services-list {
  list-style-type: none;
}

.service-icon {
  font-size: 1.5rem;
  margin-right: 8px;
  color: #656565;
}

.error-message {
  color: #e74c3c;
  font-size: 0.9rem;
}

.message-form-container {
  background-color: #f8f9fa;
  border-radius: 8px;
  margin-top: -3rem;
  width: 100%;
  border: 1px solid #ddd;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.message-form .btn {
  background-color: #ff5a5f;
  border: none;
}

#map {
  height: 400px;
  border-radius: 8px;
  overflow: hidden;
}

.map-title {
  font-weight: bold;
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

.btn-dark {
  background-color: #ff5a5f;
  border: none;
}

.shadow-sm {
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.box-sponsor {
  padding: 10px 0;
  /* background-color: rgb(255, 255, 200); */
  border-radius: 50px;
}

.box-title {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
}

@media (max-width: 768px) {
  .box-title {
    display: block;
  }

  .fa-award {
    font-size: 1.5rem;
  }

  .box-sponsor {
    font-size: 0.9rem;
    width: 40%;
  }

  .box-message {
    justify-content: center;
  }
}

@media (max-width: 500px) {
  .section-title,
  .services-list,
  span,
  p.is-visible,
  p.description,
  .form-title,
  label,
  .btn-text,
  .map-title {
    font-size: 0.9rem;
  }

  .box-sponsor {
    font-size: 0.7rem;
    padding: 0.8rem 0;
  }
}

@media (max-width: 400px) {
  .apartment-title {
    font-size: 1.5rem;
  }
}
</style>
