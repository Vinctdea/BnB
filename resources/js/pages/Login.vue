<script>
import { store } from "../store/store.js";
export default {
  name: "Login",
  data() {
    return {
      user: {
        email: "",
        password: "",
      },
      errors: {
        email: "",
        password: "",
      },
      toastMessage: "",
      toastType: "",
    };
  },
  methods: {
    showToast(message, type = "error") {
      //metodo che permette di mostrare il toast nel Login

      this.toastMessage = message;

      this.toastType = type;

      const toastEl = this.$refs.liveToast;

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

    submit() {
      axios
        .post("/api/login", this.user)
        .then(() => {
          store.is_logged = true;
          // get a data of user
          axios
            .get("/api/user")
            .then((response) => {
              store.user = response.data;
              store.is_logged = true;
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
          this.$router.push({ name: "home" });
        })
        .catch((err) => {
          store.is_logged = false;
          console.log(err);

          if (err.response && err.response.status === 401) {
            this.loginError = "Email o password errati.";
          } else {
            console.log("Errore durante il login:", err);
            //this.loginError = "Si è verificato un errore durante il login. L'email o la password sono errati";
            this.showToast(
              `Errore nel login. L'email o la password sono errati`,
              "error"
            );
          }
        });
    },

    validateEmail() {
      const emailRegex = /^[a-zA-Z0-9.]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

      if (!this.user.email) {
        this.errors.email = "L'email è obbligatoria.";
      } else if (!emailRegex.test(this.user.email)) {
        this.errors.email = "L'email non è valida";
      } else {
        this.errors.email = "";
      }
    },

    validatePassword() {
      if (!this.user.password) {
        this.errors.password = "La password è obbligatoria.";
      } else if (this.user.password.length < 8) {
        this.errors.password =
          "La password deve essere lunga almeno 8 caratteri.";
      } else {
        this.errors.password = "";
      }
    },

    submitLogin() {
      this.validateEmail();
      this.validatePassword();

      if (Object.values(this.errors).every((error) => error === "")) {
        this.submit();
      }
    },
  },
};
</script>


<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-8 mt-5">
        <h2>
          Effettua il login o
          <router-link :to="{ name: 'register' }">Registrati</router-link>
        </h2>
        <form class="mx-auto" @submit.prevent="submitLogin">
          <div class="input-container">
            <label class="form-label" for="email">Email*</label>
            <input
              class="form-control"
              type="email"
              id="email"
              name="email"
              v-model="user.email"
              @input="validateEmail"
            />
            <small v-if="errors.email" class="error-message">{{
              errors.email
            }}</small>
          </div>

          <div class="input-container">
            <label class="form-label" for="password">Password*</label>
            <input
              class="form-control"
              type="password"
              id="password"
              name="password"
              v-model="user.password"
              @input="validatePassword"
            />
            <small v-if="errors.password" class="error-message">{{
              errors.password
            }}</small>
          </div>
          <button class="btn btn-dark mt-3" type="submit">Login</button>

          <!-- <div v-if="loginError" class="text-danger mt-4"> -->
          <!-- {{ loginError }} -->
          <!-- </div> -->
        </form>
      </div>
    </div>

    <!-- codice del toast -->
    <div
      ref="liveToast"
      class="toast align-items-center text-bg-danger position-fixed bottom-0 end-0 p-2 m-3"
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
// @use 'path' as *;
.input-container {
  padding: 10px 0;

  .error-message {
    color: red;
    font-size: 0.9em;
  }
}

label {
  display: block;
}

.toast {
  position: fixed;
  bottom: 0;
  right: 0;
  z-index: 1050;
}

.col-8 {
  margin-bottom: 15rem;
  min-height: 60vh;
}
</style>
