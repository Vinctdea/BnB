<script>
import { store } from "../../../store/store";
export default {
  name: "RoutinglistApartment",
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
          </router-link>
        </li>
        <li class="py-3 d-flex align-items-center">
          <router-link
            class="link d-flex align-items-center gap-3"
            :to="{ name: 'dashboard' }"
          >
            <i class="fa-solid fa-gauge"></i>
          </router-link>
        </li>

        <li class="py-3 d-flex align-items-center">
          <router-link
            class="link d-flex align-items-center gap-3"
            :to="{ name: 'createApartment' }"
          >
            <i class="fa-solid fa-circle-plus"></i>
          </router-link>
        </li>

        <li
          @click="logout"
          class="py-3 d-flex align-items-center gap-3 click link"
        >
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
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
aside {
  height: calc(100vh - 6rem);
  overflow: hidden;
  width: 50px;
  position: sticky;
  top: 6rem;
  background-color: white;
  .link:hover{
    color: red;
  }

  ul {
    padding: 0 0.5rem;

    }

}


.dashboard-box {
  padding: 15px;
  width: calc(100% - 50px);
}

table {
  font-size: 10px;
}

ul {
  padding: 0;
}

.click {
  cursor: pointer;
}
</style>
