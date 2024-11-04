<script>
import { store } from "../store/store";
import { isCloser } from "../store/store";
import { distanceOfCenter } from "../store/store";

export default {
  name: "Apartments",

  data() {
    return {
      filter: {
        number_rooms: "",
        number_beds: "",
        square_meters: "",
        distance: "",
        latCenter: "",
        lonCenter: "",
        distance: 20,
      },

      filtredApartment: "",
      services: "",
      activeItems: [],
      isReady: false,
      src: "",
      isLoadingAp: true,
    };
  },
  methods: {
    getMap() {
      const tt = window.tt; //accesso alla libreria TomTom
      let center = [this.filter.lonCenter, this.filter.latCenter]; //centro della mappa
      let size = 50; //dimensioni del popup
      const map = tt.map({
        key: "qNjsW3gGJOBNhFoXhBzsGRJAk5RJMJhI",
        center: center,
        container: "map",
        zoom: 10,
      });
      //estrapolazione delle proprietà che si riferiscono a "position" nel JSON generato dall'API
      this.filtredApartment.forEach((apartment) => {
        const lat = apartment.lat; //valore della latitudine di ogni appartamento
        const lon = apartment.lon; //valore dalla longitudine di ogni appartamento
        let boxContent = document.createElement("div");
        boxContent.innerHTML = `
                <div class="card-body">
                    <h5 class="title-popup"><strong>${apartment.title}</strong></h5>
                    <p class="title-popup">${apartment.address}</p>
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
      });
      //   const bounds = [
      //     [10.501, 40.7994], //estremi sud-ovest (longitudine, latitudine)
      //     [13.9894, 42.8995], //estremi nord-est (longitudine, latitudine)
      //   ];
      //   map.setMaxBounds(bounds);
      map.addControl(new tt.FullscreenControl());
      map.addControl(new tt.NavigationControl());
    },
    updateFilter() {
      this.isLoading = true;
      store.isFilterClose = false;
      this.$router
        .push({
          query: {
            input: store.inputValue,
            lon: this.filter.lonCenter,
            lat: this.filter.latCenter,
            distance: this.filter.distance,
            number_rooms: this.filter.number_rooms || undefined,
            number_beds: this.filter.number_beds || undefined,
            square_meters: this.filter.square_meters || undefined,
            services: this.activeItems,
          },
        })
        .then(() => {
          // Chiamata alla funzione di filtro con i nuovi parametri
          this.filterApartment(this.$route.query);
        });
    },

    createFilterData() {
      const urlParams = new URLSearchParams(this.$route.query);
      store.inputValue = urlParams.get("input");
      this.filter.lonCenter = urlParams.get("lon");
      this.filter.latCenter = urlParams.get("lat");
      this.filter.distance = urlParams.get("distance");
      this.filter.number_rooms = urlParams.get("number_rooms") || "";
      this.filter.number_beds = urlParams.get("number_beds") || "";
      this.filter.square_meters = urlParams.get("square_meters") || "";
      this.activeItems = urlParams.get("services")
        ? urlParams.get("services").split(",")
        : [];
      console.log(
        store.inputValue,
        this.filter.lonCenter,
        this.filter.latCenter,
        this.filter.distance
      );
      this.isReady = true;
    },
    createDistanceData() {
      this.filtredApartment.forEach((apartment) => {
        apartment["distanceOfCenter"] = distanceOfCenter(
          this.filter.latCenter,
          this.filter.lonCenter,
          apartment.lat,
          apartment.lon
        );
      });
    },

    async filterApartment(query) {
      await axios
        .get("api/filtred-apartment", {
          params: {
            query,
          },
        })
        .then((res) => {
          this.filtredApartment = res.data;
          console.log("filtro", this.filtredApartment);
          this.createFilterData();
          this.createDistanceData();
          this.isLoadingAp = false;
          // wait of dom after is loading
          this.$nextTick(() => {
            this.getMap();
          });
        })
        .catch((err) => console.log(err.message));
    },
    toggleActive(i) {
      let id = String(i);
      if (this.activeItems.includes(id)) {
        // Se l'elemento è già attivo, rimuovilo
        this.activeItems = this.activeItems.filter((i) => i !== id);
      } else {
        // Se l'elemento non è attivo, aggiungilo
        this.activeItems.push(id);
      }
    },
    isActive(id) {
      return this.activeItems.includes(String(id));
    },
    adressShorter(adress) {
      return adress
        .split("")
        .map((letter, index) => {
          if (index <= 36) {
            return letter;
          } else if (index > 36 && index < 39) {
            return ".";
          } else {
            return;
          }
        })
        .join("");
    },
  },

  mounted() {
    axios
      .get("api/services")
      .then((res) => {
        this.services = res.data;
      })
      .catch((err) => console.log(err.message));

    this.filterApartment(this.$route.query);
    window.addEventListener("click", function () {
      store.isFilterClose = false;
    });
  },
  watch: {
    "$route.query.input": {
      handler(newQuery, oldQuery) {
        if (newQuery) {
          this.isLoading = true;
          this.input = newQuery;
          this.filterApartment(this.$route.query);
        }
      },
    },
  },
  computed: {
    apartmensFiltred() {
      return this.filtredApartment.sort((a, b) => {
        if (a.sponsors.length > 0 && b.sponsors.length === 0) {
          return -1;
        }
        if (a.sponsors.length === 0 && b.sponsors.length > 0) {
          return 1;
        }

        return a.distanceOfCenter - b.distanceOfCenter;
      });
    },
    isOpenFilter() {
      return store.isFilterClose;
    },
    isLoading() {
      return this.isLoadingAp;
    },
    input() {
      return this.$route.query.input.toUpperCase();
    },
  },
};
</script>

<template>
  <div class="filterClass" @click.stop="" :class="{ open: isOpenFilter }">
    <label for="number_rooms">Camere</label>
    <input
      type="number"
      placeholder="inserisci numero"
      id="number_rooms"
      v-model.trim="filter.number_rooms"
      class="form-control mb-1"
    />
    <label for="number_beds">Letti</label>
    <input
      type="number"
      placeholder="inserisci numero"
      id="number_beds"
      v-model.trim="filter.number_beds"
      class="form-control mb-1"
    />
    <label for="square_meters">Superficie</label>
    <input
      type="number"
      placeholder="inserisci numero"
      id="square_meters"
      v-model.trim="filter.square_meters"
      class="form-control mb-1"
    />

    <span class="">{{ filter.distance }} km dal punto scelto</span>
    <input
      class="dark mb-1"
      type="range"
      min="1"
      max="200"
      v-model.trim="filter.distance"
    />
    <label for="square_meters ">Servizi</label>
    <div class="contServices mb-1" v-if="isReady">
      <span
        class="btn service"
        v-for="(service, index) in services"
        :key="index"
        :class="{ active: isActive(service.id) }"
        @click="toggleActive(service.id)"
        >{{ service.name }}</span
      >
    </div>

    <button @click="updateFilter" class="btn btn-filter">Filtra</button>
  </div>
  <div class="container-fluid contResponsive" v-if="!isLoading">
    <div id="rowContainer">
      <div class="leftCol">
        <h5 class="text-search">
          <strong>
            Trovati {{ apartmensFiltred.length }} alloggi in questa località:
            {{ input }}
          </strong>
        </h5>
        <div class="row" v-if="apartmensFiltred.length">
          <div
            class="col-lg-6 col-md-6 col-sm-12 mb-4"
            v-for="(apartment, index) in apartmensFiltred"
            :key="index"
          >
            <div class="sponsored-card">
              <div class="card-container">
                <router-link
                  :to="{
                    name: 'showApartment',
                    params: { slug: apartment.slug, id: apartment.id },
                  }"
                >
                  <div class="card-image-wrapper">
                    <img
                      :src="apartment.image"
                      class="card-image"
                      alt="Appart-Img"
                    />
                  </div>
                  <div class="card-info">
                    <h5 class="card-title">{{ apartment.title }}</h5>
                    <p class="card-text adress">
                      <strong>{{ adressShorter(apartment.address) }}</strong>
                    </p>
                    <p class="card-text adress2">
                      <strong>{{ apartment.address }}</strong>
                    </p>
                    <p class="card-text">
                      Camere: {{ apartment.number_rooms }}
                    </p>
                    <p class="card-text">Letti: {{ apartment.number_beds }}</p>
                    <p class="card-text">
                      Superficie: {{ apartment.square_meters }} m²
                    </p>
                    <p class="card-text">
                      Distanza:
                      {{
                        apartment.distanceOfCenter
                          ? apartment.distanceOfCenter.toFixed(2)
                          : 0
                      }}
                      Km
                    </p>
                  </div>

                  <div class="sponsored-box" v-if="apartment.sponsors.length">
                    <div class="text-sponsored">
                      <span>Sponsorizzato</span>
                    </div>
                    <div class="sponsored-icon">
                      <i class="fa-solid fa-award"></i>
                    </div>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="mapCont">
        <div id="map"></div>
      </div>
    </div>
  </div>
  <div class="contLoader" v-else>
    <div class="loader"></div>
  </div>
</template>

<style lang="scss">
@use "../../scss/variables" as *;
@use "../../scss/cardApartments" as *;

#map {
  width: 100%;
  //   height: auto;
  height: 100%;
}

.title-popup {
  font-size: 10px;
}

a {
  text-decoration: none;
}

input[type="range"].dark {
  background: black;
}

.contServices {
  display: flex;
  //   justify-content: center;
  flex-wrap: nowrap;
  gap: 1rem;
  .service {
    width: calc(50% - 0.2rem);
    padding: 0.3rem 0.6rem;
    border: 1px solid black;
    border-radius: 8px;
    cursor: pointer;
    &:hover {
      background: black;
      color: white;
    }
  }
  .service.active {
    background: black;
    // background: rgba($color: red, $alpha: 0.8);
    color: white;
  }
}
.filterClass {
  display: none;
}
.filterClass.open {
  display: block;
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
  width: 50%;
  margin: auto;
  position: fixed;
  background-color: white;
  z-index: 10;
  top: 6rem;
  left: 50%;
  transform: translate(-50%);
  padding: 1.5rem;
  border-radius: 20px;
  box-shadow: $shadow-color 0px 2px 10px;
}

.btn-filter {
  background-color: $color-logo;
  color: white;
  &:hover {
    background-color: #e54b4b;
    color: white;
  }
}

#rowContainer {
  display: flex;
  justify-content: center;
  gap: 2rem;
}
.leftCol {
  width: 50%;
  padding-left: 1rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-top: 1rem;
}
#mapCont {
  height: calc(100vh - 6rem);
  width: 50%;

  position: -webkit-sticky;
  position: sticky;
  top: 6rem; /* Posiziona la colonna fissa in cima allo schermo */
  right: 0;
  overflow-y: auto; /* Permette lo scrolling interno se necessario */
  background-color: #f8f9fa; /* Facoltativo: Imposta un colore di sfondo */
}

.card-text.adress2 {
  display: none;
}
@media (max-width: 991px) {
  .card-text.adress2 {
    display: block;
  }
  .card-text.adress {
    display: none;
  }
  #rowContainer {
    display: flex;
    flex-direction: column-reverse; /* Le colonne vengono visualizzate in verticale e invertite */
  }
  #mapCont {
    width: 100%;
    height: 50vh;
    position: relative;
    top: 0;
    left: 0;

    overflow-y: visible; /* Disattiva lo scroll interno */
  }
  .leftCol {
    width: 100%;
    padding-left: 0;
  }
  .filterClass.open {
    width: 70%;
  }
}
@media (max-width: 686px) {
  .filterClass.open {
    width: 95%;
    padding: 1rem;
    overflow: auto;
  }
  .text-search {
    font-size: 1rem;
    margin-bottom: 1rem;
  }
}

@media (max-width: 486px) {
  .service {
    flex-wrap: wrap;
    width: 20%;
  }
  .contResponsive {
    margin-top: 1rem;
  }
}

@media (max-width: 375px) {
  //   .text-search {
  //     font-size: 1rem;
  //     margin-bottom: 1rem;
  //   }
}
</style>
