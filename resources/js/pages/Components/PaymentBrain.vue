<script>
import { store } from "../../store/store";
export default {
  name: "PaymentBrain",
  data() {
    return {
      error: "",
      dropinInstance: null,
    };
  },
  props: {
    authorization: {
      required: true,
      type: String,
    },
  },
  methods: {
    onSuccess(payload) {
      let token = payload.nonce;
      this.$emit("onSuccess", token);
      // Do something great with the nonce...
    },
    onError(error) {
      let message = error.message;
      if (message == "No payment method is available") {
        this.error = "Seleziona un metodo di pagamento";
      } else {
        this.error = message;
      }
      this.$emit("onError", message);
      // Whoops, an error has occured while trying to get the nonce
    },
    submitPayment() {
      if (this.dropinInstance) {
        // Ottieni il metodo di pagamento e il nonce
        this.dropinInstance
          .requestPaymentMethod()
          .then(this.onSuccess)
          .catch(this.onError);
      } else {
        this.error = "Errore: il Drop-in non è stato caricato correttamente.";
      }
    },
  },
  mounted() {
    // Usa il metodo creato nel plugin per creare il Drop-in
    this.$braintree
      .createDropin({
        authorization: this.authorization,
        container: "#dropin-container",
        locale: "it_IT",
        // Altre opzioni se necessarie...
      })
      .then((dropinInstance) => {
        this.dropinInstance = dropinInstance;
      })
      .catch((error) => {
        console.log("non stato creato il template");
        this.onError(error);
      });
  },
};
</script>
<template>
  <div id="dropin-container"></div>
  <!-- <div>
    <v-btn ref="paymentBtnRef" @click="submitPayment" />
  </div> -->
  <!-- <div v-if="error">
    <p>{{ error }}</p>
  </div> -->
</template>
<style lang='scss' scoped>
// @use 'path' as *;
</style>
