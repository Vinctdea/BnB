<script>
import PaymentBrain from "./PaymentBrain.vue";
import axios from "axios";
import { store } from "../../store/store";

export default {
  name: "CheckoutPayment",
  data() {
    return {
      userApartments: store.userApartment,
      tokenApi: "",

      form: {
        token: "",
        sponsor: "",
        apartment: this.$route.params.id,
      },
      countDown: 5,
    };
  },
  methods: {
    payment(id) {
      axios
        .post("api/order/make/payment", {
          token: "fake-valid-nonce",
          sponsor: id,
        })
        .then((res) => {
          console.log(res);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    paymentOnSuccess(nonce) {
      store.isLoading = true;
      this.form.token = nonce;
      this.buy();
    },
    paymentOnError(error) {
      //   store.isLoading = false;
      //   store.paymentError = true;
      //   let countDownTimer = setInterval(() => {
      //     this.countDown -= 1;
      //     if (this.countDown === 0) {
      //       clearInterval(countDownTimer);
      //       setTimeout(() => {
      //         this.$router.push({ name: "apartments" });
      //       }, 1000);
      //     }
      //   }, 1000);
    },

    // beforeBuy() {
    //   this.$refs.paymentRef.$refs.paymentBtnRef.click();
    // },
    async buy() {
      try {
        axios
          .post(
            "http://127.0.0.1:8000/api/order/make/payment",
            {
              ...this.form,
            },
            { withCredentials: true }
          )
          .then((res) => {
            console.log("pagamento effettuato inizio coun down");
            store.isLoading = false;
            store.successPayment = true;
            let countDownTimer = setInterval(() => {
              this.countDown -= 1;
              if (this.countDown === 0) {
                clearInterval(countDownTimer);
                setTimeout(() => {
                  this.$router.push({ name: "apartments" });
                }, 1000);
              }
            }, 1000);
          })
          .catch((err) => {
            store.isLoading = false;
            store.paymentError = true;
            let countDownTimer = setInterval(() => {
              this.countDown -= 1;
              if (this.countDown === 0) {
                clearInterval(countDownTimer);
                setTimeout(() => {
                  this.$router.push({ name: "apartments" });
                }, 1000);
              }
            }, 1000);
            console.log(err);
          });
      } catch {
        // disabilita i bottoni
      }
    },
    // Invoca il metodo submitPayment del componente figlio
    procediAlPagamento() {
      this.$refs.paymentRef.submitPayment(); // Richiama il metodo submitPayment
    },
  },
  components: {
    PaymentBrain,
  },

  mounted() {
    // primo evento token

    axios
      .get("http://localhost:8000/api/order/generate")
      .then((res) => {
        this.tokenApi = res.data.token;
        this.form.token = res.data.token;
        setTimeout(() => {
          store.isLoading = false;
        }, 3000);
      })
      .catch((err) => {
        store.paymentError = true;
        store.isLoading = false;
        setTimeout(() => {
          this.$router.push({ name: "apartments" });
        }, 2500);
        console.log("1 fallisce il primo token per inizializzare tutto");
        console.log("fallisce il token per creare la pagina");
        console.log(err);
      });
    this.form.sponsor = this.$route.params.sponsor;
  },
  computed: {
    token() {
      return this.tokenApi;
    },
    userApartment() {
      return this.userApartments.filter(
        (apartment) => apartment.id == this.$route.params.id
      )[0];
    },
    sponsorSelect() {
      switch (this.$route.params.sponsor) {
        case "1":
          return "Bronzo";
        case "2":
          return "Argento";
        case "3":
          return "Oro";
      }
    },
    price() {
      switch (this.$route.params.sponsor) {
        case "1":
          return "2.99";
        case "2":
          return "5.99";
        case "3":
          return "9.99";
      }
    },
    isLoading() {
      return store.isLoading;
    },
    paymentError() {
      return store.paymentError;
    },
    paymentSuccess() {
      return store.successPayment;
    },
    countDownUpdate() {
      return this.countDown;
    },
    sponsorName() {
      return this.$route.params.name;
    },
    apartmentTitle() {
      return this.$route.params.title.split("-").join(" ");
    },
  },
};
</script>
<template>
  <div
    class="container pb-4 my-4 d-flex flex-column justify-content-center align-items-center"
    v-if="!isLoading"
  >
    <div v-if="!isLoading && !paymentError && !paymentSuccess">
      <div class="container-fluid d-flex flex-column gap-1">
        <h2>Pagamento per abbonamento {{ sponsorName.toUpperCase() }}</h2>

        <ul class="w-75 p-1">
          <li class="row d-flex">
            <div class="col">
              <strong> APPARTAMENTO: </strong>
              <span> {{ apartmentTitle.toUpperCase() }}</span>
            </div>
          </li>

          <li class="row d-flex">
            <div class="col">
              <strong>TOTALE: </strong>
              <span> {{ price }} €</span>
            </div>
          </li>
        </ul>
      </div>

      <!-- <table class="table my-3"> -->
      <!-- <thead> -->
      <!-- <tr> -->
      <!-- <th scope="col">Appartamento</th> -->
      <!-- <th scope="col">Sponsor</th> -->
      <!-- <th scope="col">Totale abbonamento</th> -->
      <!-- </tr> -->
      <!-- </thead> -->
      <!-- <tbody> -->
      <!-- <tr> -->
      <!-- <td>{{ apartmentTitle }}</td> -->
      <!-- <td>{{ sponsorName }}</td> -->
      <!-- <td>{{ price }} €</td> -->
      <!-- </tr> -->
      <!-- </tbody> -->
      <!-- </table> -->

      <!-- <h2>Appartamento: {{ apartmentTitle }}</h2> -->
      <!-- <h2>Totale abbonamento {{ sponsorName }}: {{ price }} €</h2> -->
      <PaymentBrain
        ref="paymentRef"
        :authorization="token"
        @onSuccess="paymentOnSuccess"
        @onError="paymentOnError"
      />
      <button
        class="btn btn-dark"
        @click="procediAlPagamento"
        v-if="!successPayment"
      >
        Procedi al pagamento
      </button>
    </div>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="message-box _success" v-if="paymentSuccess">
            <i class="fa fa-check-circle" aria-hidden="true"></i>
            <h2>Il tuo pagamento è stato eseguito con successo</h2>
            <p class="countDown">{{ countDownUpdate }}</p>
          </div>
        </div>
      </div>
      <hr />

      <div class="row justify-content-center" v-if="paymentError">
        <div class="col-md-5">
          <div class="message-box _success _failed">
            <i class="fa fa-times-circle" aria-hidden="true"></i>
            <h2>Il tuo pagamento è fallito!!!</h2>
            <p class="countDown">{{ countDownUpdate }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="contLoader" v-else-if="isLoading && !paymentError">
    <div class="loader"></div>
  </div>
</template>
<style lang='scss' scoped>
// @use 'path' as *;
/* HTML: <div class="loader"></div> */

._failed {
  border-bottom: solid 4px red !important;
}
._failed i {
  color: red !important;
}

._success {
  box-shadow: 0 15px 25px #00000019;
  padding: 45px;
  width: 100%;
  text-align: center;
  margin: 40px auto;
  border-bottom: solid 4px #28a745;
}

._success i {
  font-size: 55px;
  color: #28a745;
}

._success h2 {
  margin-bottom: 12px;
  font-size: 40px;
  font-weight: 500;
  line-height: 1.2;
  margin-top: 10px;
}

._success p {
  margin-bottom: 0px;
  font-size: 18px;
  color: #495057;
  font-weight: 500;
}
p.countDown {
  font-size: 3rem;
}

ul {
  padding-left: 0;
  padding-right: 0;
}

@media (max-width: 500px) {
  ul {
    padding: 0 0.8rem;
    font-size: 0.85rem;
  }
}
</style>
