<script>
import { RouterLink } from "vue-router";
import RoutinglistApartment from "./partials/RoutinglistApartment.vue";
import { store } from "../../store/store";

export default {
  name: "IndexApartment",
  components: {
    RoutinglistApartment,
  },
  data() {
    return {
      name: "",
      toastMessage: "",
      sponsorType: "nulla",
      isSidebarCollapsed: false,
    };
  },
  methods: {
    toggleSidebar() {
      this.isSidebarCollapsed = !this.isSidebarCollapsed;
    },
    getMessages(apartment) {
      localStorage.setItem(`apartment${apartment.id}Count`, 0);
      localStorage.setItem("firstEnter", true);
      // const readMessages =
      //   JSON.parse(localStorage.getItem("readMessages")) || {};

      // readMessages[apartmentId] = apartment.messages.map((msg) => msg.id);

      // localStorage.setItem("readMessages", JSON.stringify(readMessages));

      // apartment.messages.forEach((message) => {
      //   message.read = true;
      // });
    },

    getUnreadMessages(apartment) {
      let firstEnter = localStorage.getItem("firstEnter");
      if (!firstEnter) {
        console.log(firstEnter);
        localStorage.setItem(
          `apartment${apartment.id}Count`,
          apartment.unreadMessages
        );
        return apartment.unreadMessages;
      } else {
        return localStorage.getItem(`apartment${apartment.id}Count`);
      }
    },
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
    deleteApartment(apartment) {
      axios
        .delete(`/api/user/utente/dashboard/${apartment.id}`) //chiamata API al backend con DELETE
        .then((res) => {
          axios
            .get("api/user/utente/dashboard")
            .then((response) => {
              store.userApartment = response.data.map((apartment) => ({
                ...apartment,
                unreadMessages: apartment.messages.filter((m) => !m.read)
                  .length,
              }));
            })
            .catch((err) => {
              console.log(err);
            });

          this.showToast(
            `Appartamento "${apartment.title}" eliminato con successo`,
            "success"
          );
          axios
            .get("api/user/utente/dashboard")
            .then((response) => {
              store.userApartment = response.data;
            })
            .catch((err) => {
              console.log(err);
            });
        })
        .catch((err) => {
          console.error(err);
          this.showToast(
            `Errore durante l'eliminazione dell'appartamento "${apartment.title}"`,
            "error"
          );
        });
    },

    imageUrl(path) {
      return `http://127.0.0.1:8000/${path}`; // URL completo dell'immagine
    },
    formatDate(dateCarbon) {
      let date = dateCarbon.split(" ")[0].split("-").reverse().join("/");
      let hour = dateCarbon.split(" ")[1].split(":").slice(0, -1).join(":");
      let string = date + " " + hour;
      return string;
    },
    counterApartment(index) {
      let length = store.userApartment.length;
      for (let i = 0; i < length; i++) {
        return length - index;
      }
    },
  },
  //   detailApartment(id) {},

  mounted() {
    // console.log("user apartment", store.userApartment[0].sponsors[0].category);
    axios
      .get("/api/user")
      .then((res) => {
        this.name = res.data.name;
      })
      .catch((err) => {
        console.log(err);
      });
    // axios
    //   .get("api/user/utente/dashboard")
    //   .then((response) => {
    //
    //     this.apartments = response.data;
    //   })
    //   .catch((err) => {
    //     console.log(err);
    //   });

    axios
      .get("api/user/utente/dashboard")
      .then((response) => {
        store.userApartment = response.data.map((apartment) => ({
          ...apartment,

          //   unreadMessages: apartment.messages.filter((m) => !m.read).length,
          unreadMessages: apartment.messages.length,
        }));
      })
      .catch((err) => {
        console.log(err);
      });

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
  },
  computed: {
    isPremium() {
      return store.userApartment.filter(
        (apartment) => apartment.sponsors.length > 0
      ).length;
    },
    apartmentFiltred() {
      return store.userApartment;
    },
    lengthArrayApartment() {
      return store.userApartment.length;
    },
  },
};
</script>
<template>
  <div class="wrapper d-flex">
    <RoutinglistApartment />

    <div class="dashboard-box dashboard-color px-2">
      <!-- <router-link :to="{ name: 'dashboard' }"> -->
      <!-- <font-awesome-icon -->
      <!-- class="ms-2 iconArrow" -->
      <!-- :icon="['fas', 'circle-arrow-left']" -->
      <!-- /> -->
      <!-- </router-link> -->
      <div class="container-fluid my-3">
        <div class="row" v-if="lengthArrayApartment">
          <div class="col-lg-12 col-md-12">
            <h4 class="mb-4 mt-2">
              <strong>Hai {{ lengthArrayApartment }} appartamenti</strong>
            </h4>

            <!-- <h4 class="my-5">Appartamenti totali: {{ apartments.length }}</h4> -->

            <div class="dashboard-card table-responsive px-2">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col" class="d-none d-lg-table-cell">#id</th>
                    <th scope="col" class="d-none d-lg-table-cell">Immagine</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Sponsor</th>
                    <th scope="col" class="d-none d-sm-table-cell">
                      Disponibilità
                    </th>
                    <th scope="col">Messaggi</th>
                    <th scope="col">Statistiche</th>
                    <th scope="col">Azioni</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(apartment, index) in apartmentFiltred"
                    :key="index"
                  >
                    <td scope="row" class="align-middle d-none d-lg-table-cell">
                      {{ counterApartment(index) }}
                    </td>
                    <td
                      class="img-container align-middle d-none d-lg-table-cell"
                      scope="row"
                    >
                      <img
                        class="img-fluid thumbnail"
                        :src="imageUrl(apartment.image)"
                        alt=""
                      />
                    </td>
                    <td scope="row" class="align-middle">
                      {{ apartment.title }}
                    </td>
                    <td scope="row" class="align-middle">
                      <router-link
                        v-if="!isPremium"
                        class="sponsor"
                        :to="{
                          name: 'payment',
                          params: { id: apartment.id, title: apartment.slug },
                        }"
                        >Sponsorizza</router-link
                      >
                      <span class="sponsor" v-if="apartment?.sponsors[0]">{{
                        formatDate(apartment.sponsors[0].pivot.end_at)
                      }}</span>
                      <span v-if="!apartment?.sponsors[0] && isPremium">-</span>
                    </td>
                    <td
                      scope="row"
                      class="align-middle d-none d-md-table-cell text-center"
                    >
                      <span
                        class="badge text-bg-success"
                        v-if="apartment.is_visible === 1"
                      >
                        Visibile
                      </span>
                      <span class="badge text-bg-danger" v-else>
                        Non visibile
                      </span>
                    </td>

                    <td scope="row" class="align-middle text-center">
                      <router-link
                        :to="{
                          name: 'messages',
                          params: {
                            id: apartment.id,
                          },
                        }"
                        @click="getMessages(apartment)"
                      >
                        <span class="position-relative">
                          <span
                            v-if="getUnreadMessages(apartment) > 0"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          >
                            {{ getUnreadMessages(apartment) }}
                          </span>
                          <i class="fa-solid fa-envelope"> </i>
                        </span>
                      </router-link>
                    </td>
                    <td scope="row" class="align-middle text-center">
                      <router-link
                        :to="{
                          name: 'statistic',
                          params: { id: apartment.id, title: apartment.slug },
                        }"
                      >
                        <i class="fa-solid fa-chart-simple"></i>
                      </router-link>
                    </td>

                    <td scope="row" class="actions align-middle">
                      <!-- td grandezza normale -->
                      <div class="d-none d-sm-table-cell">
                        <router-link
                          :to="{
                            name: 'showApartment',
                            params: { slug: apartment.slug, id: apartment.id },
                          }"
                        >
                          <div class="btn btn-dark">
                            <i class="fa-solid fa-eye"></i>
                          </div>
                        </router-link>
                        <router-link
                          class="link"
                          :to="{
                            name: 'EditApartment',
                            params: { id: apartment.id },
                          }"
                        >
                          <div class="btn btn-warning">
                            <i class="fa-solid fa-pencil"></i>
                          </div>
                        </router-link>

                        <div class="d-inline-block">
                          <button
                            type="button"
                            class="btn btn-danger"
                            id="liveToastBtn"
                            data-bs-toggle="modal"
                            data-bs-target="#mioModale"
                          >
                            <i class="fa-solid fa-trash"></i>
                          </button>
                        </div>
                      </div>
                      <!-- Modale -->
                      <div
                        class="modal fade"
                        id="mioModale"
                        tabindex="-1"
                        aria-labelledby="mioModaleLabel"
                        aria-hidden="true"
                      >
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="mioModaleLabel">
                                Sei sicuro di voler eliminare l'appartamento?
                              </h5>
                              <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Chiudi"
                              ></button>
                            </div>
                            <div class="modal-body">
                              In questo modo
                              <strong>{{ apartment.title }}</strong> non sarà
                              più disponibile
                            </div>
                            <div class="modal-footer">
                              <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                              >
                                Chiudi
                              </button>
                              <button
                                type="button"
                                @click="deleteApartment(apartment)"
                                data-bs-dismiss="modal"
                                class="btn btn-danger"
                              >
                                Elimina
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- td responsive -->
                      <div class="d-block d-sm-none">
                        <div class="btn-group dropup">
                          <button
                            type="button"
                            class="btn btn-light dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                          >
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li>
                              <router-link
                                :to="{
                                  name: 'showApartment',
                                  params: {
                                    slug: apartment.slug,
                                    id: apartment.id,
                                  },
                                }"
                              >
                                <div class="btn btn-dark m-1">
                                  <i class="fa-solid fa-eye"></i>
                                </div>
                              </router-link>
                            </li>
                            <li>
                              <router-link
                                class="link"
                                :to="{
                                  name: 'EditApartment',
                                  params: { id: apartment.id },
                                }"
                              >
                                <div class="btn btn-warning m-1">
                                  <i class="fa-solid fa-pencil"></i>
                                </div>
                              </router-link>
                            </li>

                            <li>
                              <div class="d-inline-block">
                                <button
                                  type="button"
                                  class="btn btn-danger m-1"
                                  id="liveToastBtn"
                                  data-bs-toggle="modal"
                                  data-bs-target="#mioModale"
                                >
                                  <i class="fa-solid fa-trash"></i>
                                </button>
                              </div>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div v-else>
          <h2 class="pt-5">
            Non hai appartamenti, torna indietro nella
            <router-link
              class="link badge text-bg-dark"
              :to="{ name: 'dashboard' }"
              >dashboard</router-link
            >
          </h2>
        </div>
      </div>
    </div>

    <!-- codice del toast -->
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
  </div>
</template>
<style lang='scss' scoped>
@use "../../../scss/variables" as *;

td {
  height: 100px;
}

.img-container {
  width: 5%;
}

.thumbnail {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
  aspect-ratio: 4/4;
}

.btn {
  margin-right: 10px;
}

.toast {
  position: fixed;
  bottom: 0;
  right: 0;
  z-index: 1050;
}

a {
  color: black;
}
.sponsor {
  height: 0.3rem;
  width: 0.3rem;
  padding: 0.2rem;
  border-radius: 20px;
  margin-left: 0.5rem;
  background-color: rgb(255, 215, 0);
}
.sponsor.bronzo {
  background-color: rgb(205, 127, 50);
}
.sponsor.bronzo {
  background-color: rgb(192, 192, 192);
}

.fa-envelope,
.fa-chart-simple {
  font-size: 1.5rem;
}
.disable {
  display: none;
}
.iconArrow {
  font-size: 2rem;
}

.dropdown-menu {
  --bs-dropdown-min-width: none;
}

@media (max-width: 1024px) {
  .thumbnail {
    width: 60px;
    height: 60px;
  }

  .fa-envelope,
  .fa-chart-simple {
    font-size: 1rem;
  }

  table {
    font-size: 0.8rem;
  }
}

@media (max-width: 768px) {
  table {
    font-size: 0.7rem;
  }
}

// @use 'path' as *;
</style>




