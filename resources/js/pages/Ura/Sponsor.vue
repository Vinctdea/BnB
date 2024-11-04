<script>
import Routinglist from './partials/Routinglist.vue';
export default{
    name:'Sponsor',
    components:{
        Routinglist
    }
}
</script>

<template>
    <div class="wrapper d-flex">
  <aside>
      <Routinglist />
  </aside>
  <div class="dashboard-box">
    <div class="container-fluid my-3">
      <h1>Sponsor</h1>
    </div>
  </div>
</div>
</template>

<style lang="scss" scoped>
@use '../../../scss/variables' as *;
@use '../../../scss/dashboard' as *;

</style>
