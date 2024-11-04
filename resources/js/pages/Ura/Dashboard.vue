<script>
import IndexApartment from "./IndexApartment.vue";
import Messages from "./Messages.vue";
import Sponsor from "./Sponsor.vue";
import Routinglist from "./partials/Routinglist.vue";
import { store } from "../../store/store.js";

export default {
  name: "Dashboard",
  components: {
    IndexApartment,
    Messages,
    Sponsor,
    Routinglist,
  },
  data() {
    return {
      name: "",
      apartments: store.userApartment,
    };
  },
  methods: {},

  mounted() {
    this.apartments = store.userApartment;
    axios
      .get("api/user/utente/dashboard")
      .then((response) => {
        console.log("dash- user apartment", response.data);
        store.userApartment = response.data;
        store.isLoading = false;
      })
      .catch((err) => {
        console.log(err);
      });
  },
  computed: {
    lengthApartment() {
      return store.userApartment.length;
    },
    allMessages() {
      return store.userApartment.reduce(
        (total, apartment) =>
          total + (apartment.messages ? apartment.messages.length : 0),
        0
      );
    },
    allViews() {
      return store.userApartment.reduce(
        (total, apartment) =>
          total + (apartment.views ? apartment.views.length : 0),
        0
      );
    },
    isLoading() {
      return store.isLoading;
    },
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
  <div class="wrapper d-flex" v-if="!isLoading">
    <routinglist :visible="lengthApartment" />

    <div class="dashboard-box dashboard-color flex-grow-1 px-2">
      <div class="container-fluid my-5">
        <div class="row">
          <div class="col-12">
            <h4>
              <strong>Benvenuto in BoolBnb! {{ name }}</strong>
            </h4>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-12 col-lg-4 my-3 d-flex gap-3 flex-grow-1">
            <div class="dashboard-card d-flex flex-wrap">
              <div
                class="apartments col-12 col-sm-4 col-md-4 col-lg d-flex flex-column align-items-center justify-content-center gap-3"
              >
                <div
                  class="icon-box d-flex align-items-center justify-content-center"
                >
                  <i class="fa-regular fa-building"></i>
                </div>
                <h5 class="number">
                  {{ lengthApartment }}
                </h5>
                <h5 class="text-center">Appartamanenti</h5>
              </div>
              <div
                class="apartments col-12 col-sm-4 col-md-4 col-lg d-flex flex-column align-items-center justify-content-center gap-3"
              >
                <div
                  class="icon-box d-flex align-items-center justify-content-center"
                >
                  <i class="fa-regular fa-message"></i>
                </div>
                <h5>
                  {{ allMessages }}
                </h5>
                <h5 class="text-center">Messaggi ricevuti</h5>
              </div>
              <div
                class="apartments col-12 col-sm-4 col-md-4 col-lg d-flex flex-column align-items-center justify-content-center gap-3"
              >
                <div
                  class="icon-box d-flex align-items-center justify-content-center"
                >
                  <i class="fa-regular fa-eye"></i>
                </div>
                <h5>
                  {{ allViews }}
                </h5>
                <h5 class="text-center">Visite totali</h5>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4 my-3 d-flex gap-3 box-routes">
            <div class="dashboard-card">
              <div class="routes">
                <router-link
                  :to="{ name: 'apartments' }"
                  class="d-flex align-items-center justify-content-center gap-3"
                >
                  <i class="fa-solid fa-list"></i>
                  <span>Gestisci appartamenti</span>
                </router-link>
              </div>
            </div>
            <div class="dashboard-card">
              <div class="routes">
                <router-link
                  :to="{ name: 'createApartment' }"
                  class="d-flex align-items-center justify-content-center gap-3"
                >
                  <i class="fa-solid fa-circle-plus"></i>
                  <span>Nuovo appartamento</span>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="contLoader" v-else>
    <div class="loader"></div>
  </div>
</template>
<style lang='scss' scoped>
@use "../../../scss/variables" as *;

a {
  color: black;
  &:hover {
    color: red;
  }
}

i {
  font-size: 3rem;
  color: #ff5757;
}

span {
  font-size: 1rem;
}

.apartments {
  padding: 1rem 0;
}

.icon-box {
  border-radius: 100%;
  width: 100px;
  height: 100px;
  background-color: rgb(239, 239, 239);
}

.box-routes {
  flex-direction: column;
}

.routes {
  padding: 2rem 0;
}

.fa-circle-plus,
.fa-list {
  font-size: 2rem;
}

@media (max-width: 1024px) {
  .apartments {
    padding: 0.7rem;
  }

  .icon-box {
    height: 4rem;
    width: 4rem;
  }

  i {
    font-size: 2rem;
  }

  h5 {
    font-size: 0.9rem;
  }

  span {
    font-size: 0.9rem;
  }
}

@media (max-width: 990px) {
  .box-routes {
    flex-direction: row;
    flex-grow: 1;
  }
}

@media (max-width: 600px) {
  .box-routes {
    flex-direction: column;
  }
}

// @use 'path' as *;
</style>
