<script>
import { store } from "../store/store.js";
import { checkAdress } from "../store/store";
// import { throttle } from "lodash";

export default {
  name: "Header",
  data() {
    return {
      //   // name user
      //   name: store.user.name,
      //   auto compiled v model input
      searchQuery: "",
      //   data of all adress
      address: [],
      //   timeout for bettere request after user write
      debounceTimeout: null,

      isThrottled: false,
      isClose: false,
      previousScrollPosition: window.scrollY,
      isScrollingDown: false,
      //   inScrollPage: true,
    };
  },
  methods: {
    // call axios for logout
    logout() {
      axios.get("/sanctum/csrf-cookie").then(() => {
        axios
          .post("/api/logout")
          .then((response) => {
            store.userName = "Accedi";
            store.is_logged = false;
            this.$router.push({ name: "login" });
          })
          .catch((err) => {
            console.log("Errore nel logout:", err);
          });
      });
    },
    openDrop() {
      store.is_open = store.is_open ? false : true;
    },
    debouncedSearch() {
      // Cancella il timeout precedente (se esiste)
      clearTimeout(this.debounceTimeout);

      // Imposta un nuovo timeout
      this.debounceTimeout = setTimeout(() => {
        this.performSearch(); // Esegui la chiamata dopo un ritardo di 300ms
      }, 300); // Cambia questo valore per regolare il ritardo
    },
    performSearch() {
      if (!this.searchQuery.trim()) {
        return;
      }
      let urlRequest = checkAdress(this.searchQuery);
      this.isClose = true;
      axios
        .get("http://127.0.0.1:8000/proxy-tomtom", {
          params: { url: urlRequest },
        })
        .then((response) => {
          this.address = response.data.results;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    sendAdress(addressObj) {
      this.isClose = false;
      store.inputValue = this.searchQuery;
      this.$router.push({
        name: "apartmentsMap",
        query: {
          input: this.searchQuery,
          lon: addressObj.position.lon,
          lat: addressObj.position.lat,
          distance: 20,
        },
      });
    },
    toggleFilter() {
      store.isFilterClose = !store.isFilterClose;
      console.log(store.isFilterClose);
    },
    profilePush() {
      if (store.is_logged) {
        if (store.user.apartments.length) {
          this.$router.push({ name: "dashboard" });
        } else {
          this.$router.push({ name: "pageHome" });
        }
      } else {
        this.$router.push({ name: "register" });
      }
    },
    messagePush() {
      if (store.is_logged) {
        if (store.user.apartments.length) {
          this.$router.push({
            name: "apartments",
          });
        } else {
          this.$router.push({ name: "messagePageUr" });
        }
      } else {
        this.$router.push({ name: "register" });
      }
    },
  },
  mounted() {
    window.addEventListener("click", () => {
      store.is_open = false;
    });
    window.addEventListener("scroll", () => {
      //   if (this.inScrollPage) {
      const currentScrollPosition = window.scrollY;
      let firstRow = document.getElementById("responsiveNavBar");

      firstRow.style.transition = "all 0.5s";
      if (currentScrollPosition > this.previousScrollPosition) {
        firstRow.classList.add("hidden");
      } else {
        firstRow.classList.remove("hidden");
      }

      // Aggiorniamo la posizione precedente per il prossimo confronto
      this.previousScrollPosition = currentScrollPosition;
      //   } else {
      //     const currentScrollPosition = window.scrollY;
      //     let firstRow = document.getElementById("firstRow");

      //     firstRow.classList.add("hidden");

      //     // Aggiorniamo la posizione precedente per il prossimo confronto
      //     this.previousScrollPosition = currentScrollPosition;
      //   }
    });
  },
  watch: {
    // Monitora i cambiamenti di rotta
    $route(to, from) {
      if (to.name === "apartmentsMap") {
        store.inApartmentPage = true;
        this.searchQuery = this.$route.query.input;
      } else {
        store.inApartmentPage = false;
        this.searchQuery = "";
      }
      //   console.log(to.name);
      //   if (to.name === "apartmentsMap" || to.name === "home") {
      //     this.inScrollPage = true;
      //   } else {
      //     this.inScrollPage = false;
      //   }

      // Puoi aggiungere altre azioni qui, ad esempio:
      // - Eseguire una richiesta dati
      // - Aggiornare lo stato dell'app
    },
  },
  computed: {
    isLogged() {
      //   boolean login control for show right link
      return store.is_logged;
    },
    isOpen() {
      //   boolean login control for show right link
      return store.is_open;
    },
    userName() {
      return store.userName;
    },
    loggedUserApartment() {
      return store.userName !== "Accedi";
    },
    suggestAdress() {
      return this.address;
    },
    urlRequest() {
      return this.urlSearchQuery;
    },
    inApartmentPage() {
      return store.inApartmentPage;
    },
    haveApartment() {
      return store.user.apartments?.length ?? false;
    },
  },
};
</script>
<template>
  <header>
    <nav>
      <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
          <div class="col-2" id="imageLogo">
            <router-link
              class="contLogo d-flex align-items-center"
              :to="{ name: 'home' }"
            >
              <!-- <img class="logo" src="Boolnb_circle.png" alt="airbnb photo" /> -->
              <strong class="text-logo"> BoolBnb </strong>
            </router-link>
          </div>
          <div class="col">
            <!-- search input for adress -->
            <div class="input-group stylish-input-group">
              <div class="contInput d-flex align-items-center gap-3">
                <input
                  type="text"
                  class="inputCustom"
                  placeholder="Cerca appartamenti per indirizzo..."
                  v-model="searchQuery"
                  @input="debouncedSearch"
                />
                <font-awesome-icon
                  class="iconFilter"
                  :icon="['fas', 'sliders']"
                  @click.stop="toggleFilter"
                  v-if="inApartmentPage"
                />

                <div class="contSuggest" v-if="isClose">
                  <ul class="suggest">
                    <li
                      v-for="(addressObj, index) in suggestAdress"
                      :key="index"
                    >
                      <div
                        class="link d-flex align-items-center gap-2"
                        @click="sendAdress(addressObj)"
                      >
                        <font-awesome-icon
                          class="icon-location me-2"
                          :icon="['fas', 'location-dot']"
                        />
                        <span class="street">
                          {{ addressObj.address.streetName }}
                        </span>
                        <span class="city"
                          >{{ addressObj.address.municipality }}
                        </span>
                        <span class="city"
                          >{{ addressObj.address.postalCode }}
                        </span>
                        <span class="city">
                          {{ addressObj.address.neighbourhood }}</span
                        >
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="col-2" id="menuItems">
            <div class="d-flex gap-2 justify-content-end">
              <!-- user click dropdown class -->
              <div class="contDropDown" @click.stop="openDrop()">
                <div
                  class="profile d-flex justify-content-between align-items-center gap-1 m-0"
                >
                  <p class="nameUser">
                    {{ userName !== "null" ? userName : "Profilo" }}
                  </p>

                  <font-awesome-icon :icon="['fas', 'caret-down']" />
                </div>
                <div class="dropDown" :class="isOpen ? 'active' : 'disactive'">
                  <router-link class="link" :to="{ name: 'home' }"
                    >Home</router-link
                  >
                  <router-link
                    v-if="!isLogged"
                    class="link"
                    :to="{ name: 'login' }"
                    >Login</router-link
                  >
                  <router-link
                    v-if="!isLogged"
                    class="link"
                    :to="{ name: 'register' }"
                    >Register</router-link
                  >
                  <router-link
                    class="link"
                    v-if="isLogged"
                    :to="{ name: 'dashboard' }"
                    >Dashboard</router-link
                  >

                  <p v-if="isLogged" class="link" @click="logout">Logout</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <nav id="responsiveNavBar">
    <div class="contIcon">
      <router-link class="link" :to="{ name: 'home' }">
        <font-awesome-icon :icon="['fas', 'magnifying-glass']" />
        <p>Cerca</p>
      </router-link>
    </div>
    <div class="contIcon">
      <font-awesome-icon :icon="['fas', 'plane-departure']" />
      <p>Viaggia</p>
    </div>
    <div class="contIcon" v-if="haveApartment">
      <router-link class="link" :to="{ name: 'apartments' }">
        <font-awesome-icon :icon="['far', 'message']" />
        <p>Messaggi</p>
      </router-link>
    </div>
    <div class="contIcon">
      <router-link class="link" :to="{ name: 'dashboard' }">
        <font-awesome-icon :icon="['far', 'user']" />
        <p>Profilo</p>
      </router-link>
    </div>
  </nav>
</template>
<style lang="scss" scoped>
/* // @use 'path' as *; */
@use "../../scss/variables" as *;
@use "../../scss/general" as *;

header {
  display: flex;
  gap: 1rem;
  z-index: 100;

  padding: 0.5rem;
  position: fixed;
  width: 100%;
  background-color: white;
  padding: 1rem;
  height: 6rem;
  box-shadow: rgba(33, 35, 38, 0.1) 0px 10px 10px -10px;

  nav {
    width: 100%;
    height: 100%;
    .profile,
    .link {
      padding: 0.5rem 1rem;
      margin-top: 0.5rem;
    }
    .contDropDown {
      position: relative;
      border-radius: 20px;
      cursor: pointer;
      display: block;
      z-index: 100;
      border: 1px solid $color;
      z-index: 101;
      &:hover {
        box-shadow: $shadow-color 0px 2px 4px;
      }
      .profile {
        position: relative;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      }

      .dropDown.active {
        background-color: white;
        position: absolute;
        margin-top: 10px;
        width: 200px;
        top: 100%;
        left: -100px;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        // align-items: center;
        border-radius: 20px;
        box-shadow: $shadow-color 0px 2px 10px;
        .link {
          border-radius: 8px;
          &:hover {
            background-color: $hover-list;
            color: black;
          }
        }
      }
      .dropDown.disactive {
        display: none;
      }
    }
    a.contLogo {
      color: #ff5757;

      img.logo {
        height: 50px;
        width: 50px;
      }

      .text-logo {
        font-size: 20px;
      }
    }
  }
}

p,
.profile,
.link {
  margin: 0;
  padding: 0;
}

.disactive {
  display: none;
}
//#inputAdress
.contInput {
  width: 70%;
  height: 4.5rem;
  padding: 0.5rem;
  border-radius: 20px;
  position: relative;
  left: 50%;
  transform: translate(-50%);
  //   width: 70%;
  //   padding: 0.5rem;
  //   border-radius: 20px;
  //   border: 1px solid black;
  //   position: relative;
}
.button {
  display: none;
  background-color: white;
  border: 1px solid grey;
  padding: 0.5rem;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
  margin-left: 5px;
  cursor: pointer;
  &:hover {
    background-color: black;
    color: white;
  }
}
.contSuggest {
  .suggest {
    position: absolute;
    width: 90%;
    top: 100%;
    left: 50%;
    transform: translate(-50%);
    background-color: white;
    border-radius: 20px;
    font-size: 0.75rem;
    box-shadow: $shadow-color 0px 2px 15px;
    padding: 0;
    z-index: 10;

    li {
      width: 100%;
      padding: 0.5rem;

      .link {
        width: 100%;
        padding: 1rem;
        cursor: pointer;
        text-decoration: none;
        &:hover {
          background-color: $hover-list;
          border-radius: 10px;
        }
        // &::before {
        //   content: "";
        //   background-image: url("/public/img/point.svg");
        //   background-size: contain;
        //   background-repeat: no-repeat;
        // }
        .icon-location {
          font-size: 0.9rem;
          padding: 1rem;
          background-color: #dddddd;
          border-radius: 10px;
        }
        .street {
          font-size: 0.9rem;
        }
        .city {
          font-size: 0.9rem;
        }
      }
    }
  }
}
.inputCustom {
  width: 100%;
  height: 100%;
  border-radius: 50px;
  border: none;
  padding: 0 1rem;
  caret-color: black; // Cambia il colore del caret
  font-size: 1.2rem; // Aumenta la dimensione del testo, e di conseguenza la lunghezza del caret
  line-height: 1.5; // Modifica la linea del testo per allungare il caret
  box-shadow: $shadow-color 0px 2px 10px;
  &:focus {
    border: none;
    outline: none;
  }
}
#responsiveNavBar {
  height: 0;
  overflow: hidden;
}

//responsive
@media (max-width: 1072px) {
  .city,
  .street {
    font-size: 0.7rem !important;
  }
}
@media (max-width: 768px) {
  .contInput {
    width: 100%;
  }

  .text-logo {
    display: none;
  }
}
@media all and (max-width: 623px) {
  #responsiveNavBar.hidden {
    height: 0;
    overflow: hidden;
  }
  #responsiveNavBar {
    height: 4rem;
    width: 100%;
    background-color: white;
    position: fixed;
    bottom: 0;
    z-index: 100;
    display: flex;
    justify-content: space-around;
    .contIcon {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      &:hover {
        color: red;
      }
      cursor: pointer;
      .link {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        &:hover {
          color: red;
        }
      }
    }
  }
  #imageLogo,
  #menuItems {
    display: none;
  }

  .button {
    display: block;
  }
}
.hidden {
  height: 0;
  transition: all 0.3s;
}
.iconFilter {
  font-size: 2rem;
  color: rgba($color: red, $alpha: 0.7);
  cursor: pointer;
}

@media (max-width: 500px) {
  .icon-location {
    padding: 0.2rem !important;
    background-color: transparent !important;
  }
}
</style>
