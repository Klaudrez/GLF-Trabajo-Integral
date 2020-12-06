<template>
  <div>
    <v-row justify="center">
    <v-dialog
      v-model="dialog"
    >
      <v-card>
        <v-card-title class="headline">
          Registros de la aplicación
        </v-card-title>
        <LogViewer class="auto-scroll" :auto-scroll="true" :log="this.$store.state.log" :key="log"/>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            text
            @click="dialog = false"
          >
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
    <b-navbar fixed-top>
      <template slot="start">
        <router-link to="/">
          <b-navbar-item>
            <b-icon icon="graph"></b-icon>
            <span>Grafos y Lenguajes Formales — Trabajo Integral</span>
          </b-navbar-item>
        </router-link>
      </template>
      <template slot="end" >
        <v-btn
          color="primary"
          dark
         @click="onLog"
        >
          Logs
        </v-btn>
      </template>
    </b-navbar>
  </div>
</template>
<script>
import LogViewer from '@femessage/log-viewer';

export default {
  name: "Navbar",
  components: {
    LogViewer
  },
  data () {
      return {
        log: "",
        dialog: false,
      }
    },
    mounted () {
      this.log = this.$store.state.log;
    },
    watch: {
   dialog: function () {
      this.log = this.$store.state.log;
    },
    },
    methods: {
      onLog() {
        this.dialog = true;
      }
    }
};
</script>
<style>
.navbar-end {
  align-items: center !important;
  padding: 0 5% 0 0;
}
.navbar-start {
  padding: 0 0 0 5%;
}
.navbar {
  background-color: #02182b !important;
}
a.navbar-item {
  color: #ffffff !important;
  background-color: #02182b;
}
a.navbar-item:hover {
  color: #02182b !important;
}
.router-link-active .navbar-item {
  height: 100%;
}
a .navbar-item {
  height: 100%;
}
</style>