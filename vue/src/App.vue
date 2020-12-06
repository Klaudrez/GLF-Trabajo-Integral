<template>
  <v-app>
    <div id="app">
      <Navbar />
      <v-card outlined style="margin: 1%; padding: 1%">
        <router-view />
      </v-card>
    </div>
  </v-app>
</template>

<script>
import Navbar from "@/components/Navbar.vue";

export default {
  components: {
    Navbar,
  },
};
</script>

<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Anonymous+Pro&display=swap');

html {
  background-image: url("~@/assets/Background.jpg");
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
.multi-line {
  white-space: pre-line;
}
.log-viewer {
  font-family: "Courier New", Courier, monospace;
  height: 300px !important;
  overflow: auto; 
  display: flex; 
  flex-direction: column-reverse;
}
.auto-scroll {
  margin-bottom: 8px !important;
}
.theme--light.v-application {
  background: unset !important;
}
.v-parallax {
  height: auto !important;
  padding: 2%;
}
.v-btn {
  margin: 2px;
}
.col {
  padding: 0 !important;
}
.v-text-field.v-text-field--enclosed .v-text-field__details {
  margin: 0 !important;
}
// #app {
//   font-family: Avenir, Helvetica, Arial, sans-serif;
//   -webkit-font-smoothing: antialiased;
//   -moz-osx-font-smoothing: grayscale;
//   color: #2c3e50;
// }

// #nav {
//   padding: 30px;

//   a {
//     font-weight: bold;
//     color: #2c3e50;

//     &.router-link-exact-active {
//       color: #42b983;
//     }
//   }
// }
</style>