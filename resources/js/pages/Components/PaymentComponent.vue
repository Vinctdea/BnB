<script>
import { store } from "../../store/store";
export default {
  name: "PaymentComponent",
  data() {
    return {};
  },
  methods: {},
  mounted() {
    store.isLoading = true;
    store.paymentError = false;
    store.successPayment = false;

    console.log(this.$route.params);
  },
  computed: {
    apartmentTitle() {
      return this.$route.params.title.split("-").join(" ");
    },
  },
};
</script>
<template>
  <div class="container">
    <h2 class="main-text">
      Scegli il piano Premium che preferisci per
      <strong> {{ apartmentTitle }} </strong>
    </h2>
    <div class="wrapper my-5">
      <div class="cardT">
        <div class="icon icon-bronze">
          <i class="fa-solid fa-award"></i>
        </div>
        <h3 class="card-title">Bronzo</h3>
        <p class="card-content">
          Lavora di più con noi! Con l'abbonamento Bronzo, avrai maggiore
          visibilità e più opportunità di essere scelto rispetto alla
          concorrenza. Sponsorizza un appartamento alla volta e fai crescere il
          tuo fatturato.
        </p>

        <div class="line"></div>

        <div class="contInfo">
          <p>Vantaggi</p>
          <ul>
            <li class="sponsor-info">
              <i class="fa-solid fa-eye"></i>
              Aumento delle visualizzazioni
            </li>
            <li class="sponsor-info">
              <i class="fa-solid fa-chart-line"></i>
              Aumento delle prenotazioni
            </li>
          </ul>

          <div class="line"></div>

          <p>Costi e dettagli:</p>
          <ul>
            <li class="sponsor-info">
              <i class="fa-solid fa-credit-card"></i>
              Costo: 2.99€
            </li>
            <li class="sponsor-info">
              <i class="fa-solid fa-clock"></i>
              24 ore di sponsorizzazione
            </li>
            <li class="sponsor-info">
              <i class="fa-solid fa-user"></i>
              Un abbonamento per profilo
            </li>
          </ul>
        </div>
        <router-link
          :to="{ name: 'checkout', params: { sponsor: 1, name: 'bronzo' } }"
        >
          <button class="btn card-btn">Vai al pagamento</button>
        </router-link>
      </div>
      <div class="cardT">
        <div class="icon icon-silver">
          <i class="fa-solid fa-award"></i>
        </div>
        <h3 class="card-title">Argento</h3>
        <p class="card-content">
          Lavora di più con noi! Con l'abbonamento Argento, avrai maggiore
          visibilità e più opportunità di essere scelto rispetto alla
          concorrenza. Sponsorizza un appartamento alla volta e fai crescere il
          tuo fatturato.
        </p>

        <div class="line"></div>

        <div class="contInfo">
          <p>Vantaggi</p>
          <ul>
            <li class="sponsor-info">
              <i class="fa-solid fa-eye"></i>
              Aumento delle visualizzazioni
            </li>
            <li class="sponsor-info">
              <i class="fa-solid fa-chart-line"></i>
              Aumento delle prenotazioni
            </li>
          </ul>

          <div class="line"></div>

          <p>Costi e dettagli:</p>
          <ul>
            <li class="sponsor-info">
              <i class="fa-solid fa-credit-card"></i>
              Costo: 5.99€
            </li>
            <li class="sponsor-info">
              <i class="fa-solid fa-clock"></i>
              72 ore di sponsorizzazione
            </li>
            <li class="sponsor-info">
              <i class="fa-solid fa-user"></i>
              Un abbonamento per profilo
            </li>
          </ul>
        </div>
        <router-link
          :to="{ name: 'checkout', params: { sponsor: 2, name: 'argento' } }"
        >
          <button class="btn card-btn">Vai al pagamento</button>
        </router-link>
      </div>
      <div class="cardT">
        <div class="icon icon-gold">
          <i class="fa-solid fa-award"></i>
        </div>
        <h3 class="card-title">Oro</h3>
        <p class="card-content">
          Lavora di più con noi! Con l'abbonamento Oro, avrai maggiore
          visibilità e più opportunità di essere scelto rispetto alla
          concorrenza. Sponsorizza un appartamento alla volta e fai crescere il
          tuo fatturato.
        </p>

        <div class="line"></div>

        <div class="contInfo">
          <p>Vantaggi</p>
          <ul>
            <li class="sponsor-info">
              <i class="fa-solid fa-eye"></i>
              Aumento delle visualizzazioni
            </li>
            <li class="sponsor-info">
              <i class="fa-solid fa-chart-line"></i>
              Aumento delle prenotazioni
            </li>
          </ul>

          <div class="line"></div>

          <p>Costi e dettagli:</p>
          <ul>
            <li class="sponsor-info">
              <i class="fa-solid fa-credit-card"></i>
              Costo: 9.99€
            </li>
            <li class="sponsor-info">
              <i class="fa-solid fa-clock"></i>
              144 ore di sponsorizzazione
            </li>
            <li class="sponsor-info">
              <i class="fa-solid fa-user"></i>
              Un abbonamento per profilo
            </li>
          </ul>
        </div>
        <router-link
          :to="{ name: 'checkout', params: { sponsor: 3, name: 'oro' } }"
        >
          <button class="btn card-btn">Vai al pagamento</button>
        </router-link>
      </div>
    </div>
  </div>
</template>
<style lang='scss' scoped>
@use "../../../scss/variables" as *;
// @use 'path' as *;

.main-text {
  text-align: center;
  color: #000000;
  margin: 40px 0;
}
.wrapper {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
}
.card-title {
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

.card-content {
  font-size: 1rem;
}

.cardT {
  max-width: 300px;
  min-height: 250px;
  padding: 30px;
  border-radius: 3px;
  box-sizing: border-box;
  color: rgb(63, 63, 63);
  margin: 20px;
  border-radius: 20px;
  box-shadow: $shadow-color 0px 5px 10px;
}

.icon {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 3rem 0;
}

.fa-solid.fa-award {
  font-size: 90px;
}

.fa-solid.fa-chart-line,
.fa-solid.fa-credit-card,
.fa-solid.fa-eye,
.fa-solid.fa-clock,
.fa-solid.fa-user {
  text-align: center;
}

.icon-bronze {
  .fa-award {
    color: #cd7f32;
  }
}

.icon-silver {
  .fa-award {
    color: rgb(192, 192, 192);
  }
}

.icon-gold {
  .fa-award {
    color: rgb(255, 215, 0);
  }
}

.card-btn {
  margin-top: 3rem;
  width: 100%;
  display: block;
  padding: 8px 16px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  //   letter-spacing: 1.2px;
  background-color: $color-logo;
  color: white;
}

.card-btn:hover {
  color: white;
  background-color: $color-logo-dark;
}

.contInfo {
  padding: 0;
  p {
    font-size: 1rem;
    font-weight: 600;
  }
  ul {
    padding: 0;
    padding-left: 0;
    list-style-type: none;
    font-size: 0.9rem;
    color: rgb(63, 63, 63);
  }

  .sponsor-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.3rem 0;

    i {
      min-width: 20px;
    }
  }
}

.line {
  border-top: 1px solid $hover-list;
  width: 100%;
  margin: 1.5rem 0;
  display: flex;
  align-self: center;
}

@media (max-width: 768px) {
  .main-text {
    text-align: left;
    margin: 2rem;
    line-height: 40px;
  }
}

@media (max-width: 450px) {
  p {
    font-size: 0.9rem;
  }
  ul li {
    font-size: 0.85rem;
  }
}

@media (max-width: 321px) {
  .cardT {
    padding: 25px;
  }
  ul li {
    font-size: 0.8rem;
  }
}
</style>
