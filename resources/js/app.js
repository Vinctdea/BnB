// Importazioni Bootstrap e CSS personalizzati
import './bootstrap';
import '../scss/_general.scss';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'bootstrap';
import '../scss/_variables.scss';
import '../scss/_dashboard.scss';

// Importazioni Vue e altre dipendenze
import router from './router/index';
import App from './App.vue';
import BraintreePlugin from './plugins/braintree';

// Importazioni core di FontAwesome
import { library } from '@fortawesome/fontawesome-svg-core';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    BarController
  } from 'chart.js';

  // Registra gli elementi necessari di Chart.js globalmente
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, BarController);
// Importazioni del componente FontAwesome
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// Importazioni delle icone specifiche
import { faUser, faMessage } from '@fortawesome/free-regular-svg-icons'; // icone regular
import { faMagnifyingGlass, faPlaneDeparture, faArrowRightFromBracket, faCaretDown, fas  } from '@fortawesome/free-solid-svg-icons'; // icone solid

// Aggiungi le icone alla libreria di FontAwesome
library.add(faUser, faMessage, faMagnifyingGlass, faPlaneDeparture, faArrowRightFromBracket, faCaretDown, fas);

// Creazione dell'app Vue
import { createApp } from 'vue';
const app = createApp(App);

// Registrazione di Braintree Plugin
app.use(BraintreePlugin);

// Registrazione del componente globale FontAwesomeIcon
app.component('font-awesome-icon', FontAwesomeIcon);

// Registrazione del router
app.use(router);

// Monta l'app Vue
app.mount('#app');

