<template>
  <cytoscape
    ref="cy"
    :config="config"
    :afterCreated="afterCreated"
    style="height: 100%"
  >
    <cy-element
      v-for="def in elementos"
      :key="`${def.data.id}`"
      sync
      :definition="def"
    />
  </cytoscape>
</template>

<script>
import nodoFinal from "../assets/nodo-final.png";
import nodoInicialFinal from "../assets/nodo-inicial-final.png";
import nodoInicial from "../assets/nodo-inicial.png";
import nodoNormal from "../assets/nodo.png";

export default {
  name: "Grafo",
  props: {
    grafo: Object,
  },
  data: () => ({
    config: {
      style: [
        {
          selector: "node",
          style: {
            "background-color": "white",
            label: "data(id)",
            shape: "square",
            "background-opacity": "0",
          },
        },
        {
          selector: ".inicial.final",
          style: {
            "background-image": nodoInicialFinal,
            "background-fit": "contain",
          },
        },
        {
          selector: ".inicial.noFinal",
          style: {
            "background-image": nodoInicial,
            "background-fit": "contain contain",
          },
        },
        {
          selector: ".noInicial.final",
          style: {
            "background-image": nodoFinal,
            "background-fit": "cover cover",
          },
        },
        {
          selector: ".noInicial.noFinal",
          style: {
            "background-image": nodoNormal,
            "background-fit": "cover cover",
          },
        },
        {
          selector: "edge",
          style: {
            "curve-style": "bezier",
            "line-color": "#CCCCCC",
            "target-arrow-color": "#CCCCCC",
            "target-arrow-shape": "triangle",
            label: "data(label)",
          },
        },
      ],
      layout: { name: "circle" },
      userZoomingEnabled: false,
    },
  }),
  watch: {
    elementos() {
      this.$nextTick(() => {
        const cy = this.$refs.cy.instance;
        this.afterCreated(cy);
      });
    },
  },
  mounted() {},
  computed: {
    elementos() {
      var elementos = [];
      if (this.grafo.nodos) {
        for (let j = 0; j < this.grafo.nodos.length; j++) {
          var nodo = this.grafo.nodos[j];
          if (nodo != "") {
            elementos.push({
              classes: [
                this.grafo.inicial.indexOf(nodo) != -1
                  ? "inicial"
                  : "noInicial",
                this.grafo.finales.indexOf(nodo) != -1 ? "final" : "noFinal",
              ],
              data: { id: nodo },
              position: {
                x: 1,
                y: 1,
              },
              group: "nodes",
            });
          }
        }
      }
      if (this.grafo.aristas) {
        for (let i = 0; i < this.grafo.aristas.length; i++) {
          var arista = this.grafo.aristas[i];
          elementos.push({
            data: {
              id: i,
              source: arista.origen,
              target: arista.destino,
              label: arista.alfabeto,
              type: "loop",
            },
            group: "edges",
          });
        }
      }
      return elementos;
    },
  },
  methods: {
    async afterCreated(cy) {
      await cy;
      cy.layout(this.config.layout).run();
    },
  },
};
</script>

<style>
#cytoscape-div {
  min-height: 100px;
  height: 100%;
}
#cytoscape-div,
#cytoscape-div > div,
#cytoscape-div > div > canvas {
  min-height: 300px !important;
  max-width: 100% !important;
  height: 100% !important;
}
</style>