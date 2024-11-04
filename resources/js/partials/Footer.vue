<script>
export default {
  name: "Footer",
  data() {
    return {};
  },
  methods: {},
};
</script>
<template>
    <div class="container-fluid d-flex flex-wrap gap-3  ms_container">

        <div class="copy-right">&copy; BolBnb 2024</div>
        <router-link class="link" to="/">Home</router-link>
        <router-link class="link" to="/chi-siamo"> chi siamo</router-link>
        <router-link class="link" to="/privacy-policy"> Privacy policy</router-link>
        <router-link class="link" to="/company"> La nostra azienda</router-link>




    </div>
</template>
<style lang='scss' scoped>
// @use 'path' as *;

.ms_container{
    // position: absolute;
    // bottom: 0;
    font-size: 1rem;
    padding:20px;
    height: auto;
    background-color: rgb(103, 100, 100);
    color: white;
    display: flex;
    flex-direction: row;
    align-items: center;

    a{

       color: white;
       text-decoration: none;
    //    align-items: center;
       &:hover{
        text-decoration: underline;
        color: red;
        // transform: scale(1.3);
        // transition: transform 1s cubic-bezier(0.25, 0.1, 0.25, 1);

       }

    }

        @media (max-width: 768px) {
            flex-direction: column;
            justify-content: center;
            text-align: center;

        .link {
            font-size: 0.9rem;
            padding: 5px 0;
        }

        .copy-right {
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
    }

    @media (max-width: 550px) {
        font-size: 0.8rem;

        .link {
            font-size: 0.8rem;
        }

        .copy-right {
            font-size: 0.8rem;
        }
  }


}
</style>
