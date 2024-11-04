<script>
import { store } from "../../store/store";
export default {
  name: "PageProfile",
  data() {
    return {
      user: store.user,
    };
  },
  methods: {
    logout() {
      axios.get("/sanctum/csrf-cookie").then(() => {
        axios
          .post("/api/logout")
          .then((response) => {
            axios
              .get("/api/user")
              .then((response) => {
                store.user = response.data;
                if (response.data.name) {
                  store.userName = response.data.name;
                } else {
                  store.userName = "Profilo";
                }
                console.log("app- user e user name: ok");
              })
              .catch((err) => {
                store.is_logged = false;
                store.userName = "Accedi";
                console.log("app- user e user name:", err.message);
              });
            this.$router.push({ name: "login" });
          })
          .catch((err) => {
            console.log("Errore nel logout:", err);
          });
      });
    },
  },
  computed: {},
};
</script>
<template>
  <h2>Email: {{ user.email }}</h2>
  <h2>Nome: {{ user.name ?? "-" }}</h2>
  <h2>Cognome: {{ user.surname ?? "-" }}</h2>
  <p>Carica il tuo primo appartamento e collabora con noi</p>
  <router-link :to="{ name: 'createApartment' }">Affitta</router-link>

  <p @click="logout" class="button">logout</p>
</template>
<style lang='scss' scoped>
// @use 'path' as *;

.button {
  cursor: pointer;
}
</style>
