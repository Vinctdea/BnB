<script>
import ApartmentCard from "./components/ApartmentCard.vue";
import { store } from "../store/store";
export default {
  name: "Home",
  components: {
    ApartmentCard,
  },
  data() {
    return {
      store: store,

      toastMessage: "",
    };
  },
  methods: {
    showToast(message, type = "success") {
      //metodo che permette di mostrare il toast

      this.toastMessage = message;
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
  },
  mounted() {
    //   all apartment
    sessionStorage.setItem("access", true);
    axios
      .get("/api/home", {
        headers: {
          Accept: "application/json",
          "Cache-Control": "no-cache", // Disabilita il caching
        },
        params: {
          t: Date.now(), // Aggiungi un timestamp per evitare il caching
        },
      })
      .then((res) => {
        console.log("app- all apartment:ok", res.data);
        store.allApartments = res.data;
        console.log(this.$route.query.toastMessage);

        if (this.$route.query.toastMessage) {
          this.showToast(
            this.$route.query.toastMessage,
            this.$route.query.toastType || "success"
          );
        }
        //evita che al refresh della pagina continui ad apparire la notifica toast
        this.$router.replace({
          name: this.$route.name,
          params: this.$route.params,
          query: {},
        });
        store.isLoading = false;
      })
      .catch((err) => {
        console.log("app- all apartment: ", err.message);
      });
  },
  computed: {
    sortedApartment() {
      return this.store.allApartments.sort((a, b) => {
        if (a.sponsors.length > 0 && b.sponsors.length === 0) {
          return -1;
        }

        if (a.sponsors.length === 0 && b.sponsors.length > 0) {
          return 1;
        }

        return 0;
      });
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
  <!-- <h1>Home dell'utente senza login</h1> -->

  <!-- <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-6"></div>
    </div>
  </div> -->

  <!-- card -->
  <div class="container-fluid mx-auto" v-if="!isLoading">
    <div class="row pt-5">
      <router-link
        class="col-lg-4 col-xl-3 col-md-6 col-sm-6 mb-4"
        :to="{
          name: 'showApartment',
          params: { slug: apartment.slug, id: apartment.id },
        }"
        v-for="(apartment, index) in sortedApartment"
        :key="index"
      >
        <div v-if="apartment.sponsors.length">
          <div class="sponsored-card">
            <ApartmentCard :apartment="apartment" />
            <div class="sponsored-box">
              <div class="text-sponsored">
                <span>Sponsorizzato</span>
              </div>
              <div class="sponsored-icon">
                <i class="fa-solid fa-award"></i>
              </div>
            </div>
          </div>
        </div>
        <div v-else>
          <ApartmentCard :apartment="apartment" />
        </div>
      </router-link>
    </div>
  </div>
  <div class="contLoader" v-else>
    <div class="loader"></div>
  </div>
  <!-- codice del toast per registrazione a buon fine -->
  <div
    ref="liveToast"
    class="toast align-items-center text-bg-success position-fixed bottom-0 end-0 p-2 m-3"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    style="z-index: 1050"
  >
    <div class="d-flex">
      <div class="toast-body">
        {{ toastMessage }}
      </div>
      <button
        type="button"
        class="btn-close btn-close-white me-2 m-auto"
        @click="hideToast"
        aria-label="Close"
      ></button>
    </div>
  </div>
</template>
<style lang='scss' scoped>
// @use 'path' as *;
.card {
  max-width: 100%;
  margin: 10px auto;
}

.toast {
  position: fixed;
  bottom: 0;
  right: 0;
  z-index: 1050;
}
</style>
