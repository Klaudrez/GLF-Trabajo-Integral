<template>
  <div>
    <h1 class="title">Página no encontrada</h1>
    <h4 class="subtitle-1">
      {{ this.mensaje }}
    </h4>
  </div>
</template>

<script>
export default {
  name: "NotFound",
  data() {
    return {
      mensaje: null,
    };
  },
  mounted() {
    this.mensaje =
      "La ruta " + window.location.pathname + window.location.hash + " no ha sido encontrada";
    this.$store.commit("writeLog", { level: "error", message: this.mensaje });
  },
};
</script>