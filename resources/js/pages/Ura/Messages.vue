<script>
import axios from "axios";
import Avatar from "./partials/Avatar.vue";
import { store } from "../../store/store";

export default {
  name: "Messages",
  components: {
    Avatar
  },
  data() {
    return {
      message: "",
      selectedImage: null,
      showSidenav: true,
      showContent: false,
    //   images: [
        // "https://picsum.photos/seed/profile1/80/80",
        // "https://picsum.photos/seed/profile2/80/80",
        // "https://picsum.photos/seed/profile3/80/80",
        // "https://picsum.photos/seed/profile4/80/80",
        // "https://picsum.photos/seed/profile5/80/80",
        // "https://picsum.photos/seed/profile6/80/80",
        // "https://picsum.photos/seed/profile7/80/80",
        // "https://picsum.photos/seed/profile8/80/80",
        // "https://picsum.photos/seed/profile9/80/80",
        // "https://picsum.photos/seed/profile10/80/80",
    //   ],
      searchQuery: "",
    };
  },
  methods: {
    showMessage(message, index) {
      this.message = message;
      this.selectedImage = index;
      this.showSidenav = false;
      this.showContent = true;
    },
    goBackToMessages() {
      this.showSidenav = true;
      this.showContent = false;
    },
    formatDate(dateStr) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      const date = new Date(dateStr);
      return date.toLocaleDateString("it-IT", options);
    },
    formatDateTime(dateStr) {
      const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      };
      const date = new Date(dateStr);
      return date.toLocaleDateString("it-IT", options);
    },
    // getImage(index) {
    //   return this.images[index % this.images.length] || null;
    // },
  },
  computed: {
    filteredApartment() {
      return store.userApartment.filter(
        (apartment) => apartment.id == this.$route.params.id
      )[0];
    },
    filteredNameAp() {
      return store.userApartment.filter(
        (apartment) => apartment.title == this.$route.params.id
      )[0];
    },
    filteredMessages() {
      return this.filteredApartment.messages
        .filter((message) => {
          const name = `${message.name} ${message.surname}`.toLowerCase();
          return name.includes(this.searchQuery.toLowerCase());
        })
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at)); // Ordina dal più recente, crea oggetti data in base a created at e li sottrae per vedere il più recente
    },
  },
  mounted() {
    //console.log(store.userApartment.filter(apartment => apartment.id == this.$route.params.id)[0])
  },
};
</script>

<template>
  <div class="wrapper-message d-flex">
    <aside class="sidenav-message" :class="{ 'show-sidenav': showSidenav }">
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mx-4 my-3">
          <h5>{{ filteredApartment.title }}</h5>
          <div class="d-flex gap-2">
                <ul>
                    <li class="btn btn-menu">
                        <router-link :to="{name: 'dashboard'}">
                            <i class="fa-solid fa-gauge"></i>
                        </router-link>
                    </li>
                    <li class="btn btn-menu">
                        <router-link :to="{name: 'apartments'}">
                            <i class="fa-solid fa-list"></i>
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>

        <div class="d-flex mx-4 mt-3">
          <input
            type="search"
            class="form-control"
            id="box-search"
            placeholder="Cerca un messaggio"
            v-model="searchQuery"
          />
          <button type="submit" class="search-button">
            <i class="fa-solid fa-search"></i>
          </button>
        </div>

        <!-- lista messaggi -->
        <div class="message-list mt-2" v-if="filteredApartment">
          <ul class="list-group list-group-flush">
            <li
              class="list-group-item d-flex align-items-center w-100"
              v-for="(message, index) in filteredMessages"
              :key="index"
              @click="showMessage(message, index)"
            >
              <div class="image me-3">
                <Avatar :name="message.name" :surname="message.surname" :index="index" />
              </div>
              <div class="text-box my-2 w-100">
                <div class="d-flex justify-content-between">
                  <h6>{{ message.name }} {{ message.surname }}</h6>
                  <small>{{ formatDate(message.created_at) }}</small>
                </div>
                <!-- <small>{{ message.email }}</small> -->
                <small class="message-email">{{ message.message }}</small>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </aside>

    <!-- dettaglio messaggio -->
    <div class="content-box" :class="{ 'show-content': showContent }">
      <div class="container-fluid mt-5">
        <div class="row" v-if="message">
          <div class="col-xs-8 col-sm-12 col-md-12 col-lg-12">
            <div
              class="d-flex justify-content-between container container-details"
            >
              <div class="d-flex align-items-center box-user gap-2">
                <div class="main-image">
                  <Avatar :name="message.name" :surname="message.surname" :index="selectedImage" />
                </div>
                <div class="text-box my-3 ms-2">
                  <div class="d-flex flex-column">
                    <h6>{{ message.name }} {{ message.surname }}</h6>
                    <small>{{ formatDateTime(message.created_at) }}</small>
                  </div>
                  <small
                    ><a :href="'mailto:' + message.email">{{
                      message.email
                    }}</a>
                  </small>
                </div>
              </div>
              <button
                v-if="showContent"
                @click="goBackToMessages"
                class="btn btn-back"
              >
              <i class="fa-solid fa-xmark"></i>
              </button>
            </div>
            <div class="container container-message mt-3 message-details">
              <p>{{ message.message }}</p>
            </div>
          </div>
        </div>

        <div v-else class="block-else">
            <div class="block-select d-flex align-items-center justify-content-center flex-column">
                <div class="icon-comments d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <p>Seleziona un messaggio dalla lista</p>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@use "../../../scss/variables" as *;
@use "../../../scss/message" as *;

ul{
    padding:0;
    margin-bottom: 0;
}

li{
    cursor:pointer;
}

li:hover {
  background-color: #f1f1f1;
}

.active {
  background-color: #e0e0e0;
  color: black;
}

input {
  border-radius: 50px;
}

#box-search {
  position: relative;
}

.search-button {
  position: absolute;
  right: 2.5rem;
  padding: 6px;
  background-color: transparent;
  border: none;
}

small {
  color: #555;
}

.block-else{
    position:relative;
}

.block-select{
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    text-align:center;
    margin-top: 3rem;

    .fa-comments{
        font-size: 2rem;
        padding: 1rem 0;

        @media (max-width: 1024px){
            font-size: 1.5rem;
        }
    }
}

.icon-comments{
    background-color:#f1f1f1;
    border-radius: 100%;
    width: 80px;
    height: 80px;
    margin-bottom: 1rem;

    @media (max-width: 1024px){
        width: 65px;
        height: 65px;
    }
}

.btn-menu{
    border: none;
    background-color: transparent;

    a{
        color: black;
    }

    &:hover{
        a{
            color: #FF5757;
        }
    }
}

</style>
