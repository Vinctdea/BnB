import { createRouter, createWebHistory } from "vue-router";


import Home from '../pages/Home.vue'
import Login from '../pages/Login.vue'
import NotFoundComponent from '../pages/NotFoundComponent.vue'
import Register from '../pages/Register.vue'
import Dashboard from '../pages/Ura/Dashboard.vue'
import CreateApartment from '../pages/Ura/CreateApartment.vue'
import Statistic from '../pages/Ura/Statistic.vue'
import IndexApartment from "../pages/Ura/IndexApartment.vue";
import Messages from "../pages/Ura/Messages.vue";
import Sponsor from "../pages/Ura/Sponsor.vue";
import ShowApartment from "../pages/Ura/ShowApartment.vue";
import EditApartment from "../pages/Ura/EditApartment.vue";
import Apartments from "../pages/Apartments.vue";
import PaymentComponent from "../pages/Components/PaymentComponent.vue";
import CheckoutPayment from "../pages/Components/CheckoutPayment.vue";
import PageProfile from "../pages/Ur/PageProfile.vue";
import PageMessage from "../pages/Ur/PageMessage.vue";
import About from "../pages/About.vue";
import PrivacyPolicy from "../pages/PrivacyPolicy.vue"
import Company from "../pages/Company.vue";

const router = createRouter({
    history: createWebHistory(),
    routes:
        [
            {
                path: '/',
                name: 'home',
                component: Home
            },
            {
                path: '/login',
                name: 'login',
                component: Login
            },
            {
                path: '/registrati',
                name: 'register',
                component: Register
            },
            {
                path: '/home-page',
                name: 'pageHome',
                component: PageProfile
            },
            {
                path: '/dashboard',
                name: 'dashboard',
                component: Dashboard
            },
            {
                path: '/appartamenti',
                name: 'apartments',
                component: IndexApartment
            },
            {
                path: '/statistiche/:id/:title',
                name: 'statistic',
                component: Statistic
            },
            {
                path: '/lista-messaggi/:id/messaggi',
                name: 'messages',
                component: Messages
            },
            {
                path: '/sponsor',
                name: 'sponsor',
                component: Sponsor
            },
            {
                path: '/nuovo-appartamento',
                name: 'createApartment',
                component: CreateApartment
            },
            {
                // path: '/dettaglio-appartamento',
                path: '/appartamento-singolo/:slug/:id',
                name: 'showApartment',
                component: ShowApartment
            },{
                path: '/modifica-appartamento/:id',
                name: 'EditApartment',
                component: EditApartment
            },
            {
                path: '/appartamenti-ricerca',
                name: 'apartmentsMap',
                component: Apartments //route temporanea per visualizzare gli appartamenti + mappa
            },
            {
                path: '/payment/:id/:title',
                name: 'payment',
                component: PaymentComponent //route temporanea per visualizzare gli appartamenti + mappa
            },
            {
                path: '/payment/:id/:title/sponsor/:sponsor/:name',
                name: 'checkout',
                component: CheckoutPayment //route temporanea per visualizzare gli appartamenti + mappa
            },
            {
                path: '/messaggi-utente',
                name: 'messagePageUr',
                component: PageMessage //route temporanea per visualizzare gli appartamenti + mappa
            },

            {
                path: '/:pathMatch(.*)*',
                name: 'NotFound',
                component: NotFoundComponent,
            },
            {
                path: '/chi-siamo',
                name: 'About',
                component: About //route footer
            },
            {
                path: '/privacy-policy',
                name: 'PrivacyPolicy',
                component: PrivacyPolicy //route footer
            },
            {
                path: '/company',
                name: 'Company',
                component: Company //route footer
            },
        ],
        scrollBehavior(to, from, savedPosition) {
            if (savedPosition) {
              return savedPosition;
            } else if (to.hash) {
              return {
                el: to.hash,
                behavior: "smooth",
              };
            } else {
              return { left: 0, top: 0 };
            }
          },
})
// // Condizioni per disabilitare rotte (esempio: autenticazione)
// router.beforeEach((to, from, next) => {
//     // Supponiamo di controllare se l'utente è autenticato
//     const isAuthenticated = store.user.id;

//     // Verifica se la rotta richiede autenticazione
//     if (to.matched.some(record => record.meta.requiresAuth)) {
//       if (!isAuthenticated) {
//         // Se non autenticato, reindirizza alla pagina di login
//         return next({ name: 'login' });
//       }
//     }

//     // Se non ci sono restrizioni, continua la navigazione
//     next();
//   });

export default router;
