<script>
import { store } from "../../../store/store";
export default {
  name: "Routinglist",
  methods: {
    logout() {
      axios.get("/sanctum/csrf-cookie").then(() => {
        axios
          .post("/api/logout")
          .then((response) => {
            store.userName = "Accedi";
            this.$router.push({ name: "login" });
          })
          .catch((err) => {
            console.log("Errore nel logout:", err);
          });
      });
    },
  },
  props: {
    visible: {
      typeof: Boolean,
    },
  },
};
</script>

<template>
  <aside>
    <div class="mt-2 container-fluid d-flex justify-content-center">
      <ul class="d-flex flex-column justify-content-center">
        <li class="py-3 d-flex align-items-center">
          <router-link
            class="link d-flex align-items-center gap-3"
            :to="{ name: 'home' }"
          >
            <i class="fa-solid fa-house"></i>
            <span class="d-none d-xl-inline d-lg-inline"
              >Pagina principale</span
            ></router-link
          >
        </li>

        <li class="py-3 d-flex align-items-center">
          <router-link
            :to="{ name: 'apartments' }"
            class="link d-flex align-items-center gap-3"
          >
            <i class="fa-solid fa-list"></i>
            <span class="d-none d-xl-inline d-lg-inline">Appartamenti</span>
          </router-link>
        </li>
        <li class="py-3 d-flex align-items-center">
          <router-link
            :to="{ name: 'createApartment' }"
            class="link d-flex align-items-center gap-3"
          >
            <i class="fa-solid fa-circle-plus"></i>
            <span class="d-none d-xl-inline d-lg-inline"
              >Nuovo appartamento</span
            >
          </router-link>
        </li>

        <li
          @click="logout"
          class="py-3 d-flex align-items-center gap-3 click link"
        >
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
          <!-- <font-awesome-icon :icon="['fas', 'arrow-right-from-bracket']" /> -->
          <span class="d-none d-xl-inline d-lg-inline">Disconnetti</span>
        </li>
        <!-- <li class="py-3 d-flex align-items-center"> -->
        <!-- <router-link -->
        <!-- class="link d-flex align-items-center gap-2" -->
        <!-- :to="{ name: 'messages' }" -->
        <!-- > -->
        <!-- <i class="fa-solid fa-envelope"></i> -->
        <!-- <span class="d-none d-xl-inline d-lg-inline" -->
        <!-- >Messaggi</span -->
        <!-- ></router-link -->
        <!-- > -->
        <!-- </li> -->
        <!-- <li class="py-3 d-flex align-items-center"> -->
        <!-- <router-link -->
        <!-- class="link d-flex align-items-center gap-2" -->
        <!-- :to="{ name: 'sponsor' }" -->
        <!-- > -->
        <!-- <i class="fa-solid fa-money-check"></i> -->
        <!-- <span class="d-none d-xl-inline d-lg-inline" -->
        <!-- >Sponsorizzazione</span -->
        <!-- ></router-link -->
        <!-- > -->
        <!-- </li> -->
      </ul>
    </div>
  </aside>
</template>

<style lang="scss" scoped>
@use "../../../../scss/dashboard" as *;

.click {
  cursor: pointer;
}

.link:hover {
  color: red;
}
</style>
