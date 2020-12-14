<template>
  <div class="home">
    <v-parallax dark style="background-color: #02182b">
      <v-row align="center" justify="center">
        <v-col class="" cols="12">
          <h2 class="headline font-weight mb-4">Grupo 1 — Trabajo Integral</h2>
          <h4 class="subtitle-1 font-weight-thin">
            <ul style="margin: 0">
              - Ignacio Sebastián Delgado Vargas
            </ul>
            <ul style="margin: 0">
              - Mariam Valeria Maldonado Marín
            </ul>
            <ul style="margin: 0">
              - Andrés Antonio Parada Claussen
            </ul>
            <ul style="margin: 0">
              - Marcelo Alberto Silva Escala
            </ul>
            <ul style="margin: 0">
              - Marcelo Ignacio Tapia Riquelme
            </ul>
          </h4>
        </v-col>
      </v-row>
    </v-parallax>
    <br />
    <h1 class="display-1 font-weight mb-4">Calcular hoja de ruta</h1>
    <v-row class="ma-10">
      <v-file-input
        :rules="rulesTxt"
        v-model="file"
        dense
        outlined
        accept=".txt"
        label="Cargar documento"
        prepend-icon="mdi-file-upload"
        @click:clear="limpiar"
      ></v-file-input>
      <v-btn color="primary" :disabled="bloquearCarga" @click="procesarArchivo">
        Cargar
      </v-btn>
    </v-row>
    <div v-if="mostrarCarga">
      <h1 class="display-1 font-weight mb-4">Asignar centros y puntos</h1>
      <v-row class="mr-10 ml-10">
        <v-col cols="4">
          <v-select
            :items="puntosAux"
            v-model="nuevoPunto"
            label="Punto de venta"
            no-data-text="No hay puntos por seleccionar"
            dense
            outlined
          ></v-select>
        </v-col>

        <v-col cols="2">
          <v-text-field
            v-model="numProductos"
            label="Número productos"
            dense
            outlined
            @keypress="esNum($event)"
            @keyup="validarNum($event)"
            @blur="minMax()"
            type="number"
          />
        </v-col>

        <v-col cols="4">
          <v-select
            :items="centros"
            v-model="nuevoCentro"
            label="Centro de distribución"
            dense
            outlined
          ></v-select>
        </v-col>

        <v-col cols="2">
          <v-btn color="primary" block depressed raised @click="agregarRuta"
            >+ Agregar</v-btn
          >
        </v-col>
      </v-row>

      <v-row class="ma-5" align="center" style="justify-content: center">
        <v-simple-table fixed-header v-if="rutas.length > 0">
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Centro de distribución</th>
                <th class="text-left">Punto de venta</th>
                <th class="text-left">N° productos</th>
                <th class="text-left"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in rutas" :key="rutas.indexOf(item)">
                <td>{{ item.centro.id }}</td>
                <td>{{ item.punto.id }}</td>
                <td>{{ item.num }}</td>
                <td>
                  <v-btn
                    color="error"
                    depressed
                    raised
                    @click="eliminarItem(rutas.indexOf(item))"
                    >Eliminar</v-btn
                  >
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-row>
      <v-row style="justify-content: center">
        <v-btn color="primary" depressed raised @click="boton()"
          >Ver ruta optimizada</v-btn
        >
        <!-- <v-btn color="primary" depressed raised @click="verGrafo = false"
        >AAAAAn't</v-btn
      > -->
      </v-row>
      <div v-if="verGrafo">
        <Grafo :key="recargarGrafo" :grafo="grafo" />
      </div>
    </div>
  </div>
</template>

<script>
import Grafo from "../components/Grafo.vue";

export default {
  name: "Home",
  components: { Grafo },
  data: () => ({
    file: null,
    bloquearCarga: true,
    rulesTxt: [
      (value) =>
        !value ||
        value.type == "text/plain" ||
        "El archivo debe ser de texto plano (.txt)",
    ],
    puntos: [],
    puntosAux: [],
    centros: [],
    rutas: [],
    nuevoPunto: {},
    nuevoCentro: {},
    numProductos: 0,
    numValidado: 0,
    mostrarCarga: false,
    grafo: {},
    recargarGrafo: false,
    verGrafo: false,
    texto: [],
    txt: [
      ["C", "c1", 1, 1],
      ["P", "p1", 3, 2],
      ["P", "p2", 4, 1],
      ["P", "p3", 3, 0],
      ["P", "p4", -1, 3],
      ["P", "p5", -2, 2],
    ],
    user: [
      ["p1", 200, "c1"],
      ["p2", 200, "c1"],
      ["p3", 200, "c1"],
      ["p4", 200, "c1"],
      ["p5", 200, "c1"],
    ],
  }),
  mounted() {
    this.$store.commit("writeLog", {
      level: "info",
      message: "Se accede a " + window.location.pathname + window.location.hash,
    });
  },
  watch: {
    file: function () {
      this.centros = [];
      this.puntos = [];
      this.puntosAux = [];
      this.rutas = [];
      this.mostrarCarga = false;
      if (this.file != undefined) {
        if (this.file.type != "text/plain") {
          this.$store.commit("writeLog", {
            level: "info",
            message:
              "El archivo " +
              this.file.name +
              " no es del tipo MIME text/plain",
          });
          this.bloquearCarga = true;
        } else {
          this.bloquearCarga = false;
        }
      } else {
        this.bloquearCarga = true;
      }
    },
  },
  methods: {
    eliminarItem(item) {
      this.puntosAux.push(this.rutas[item].punto);
      this.rutas.splice(item, 1);
    },
    boton() {
      this.crearGrafo(this.rutas);
      console.log("rutas", this.rutas);
      console.log("puntos", this.puntos);
      console.log("texto", this.texto);
      this.recargarGrafo = !this.recargarGrafo;
      this.verGrafo = true;
      var pedidos = [];
      for (var i = 0; i < this.rutas.length; i++) {
        pedidos.push([this.rutas[i].punto.id, this.rutas[i].num, this.rutas[i].centro.id]);
      }
      console.log("pedidos", pedidos);
      // console.log("---------------------");
      // this.optimizarR(JSON.parse(JSON.stringify(this.txt)), JSON.parse(JSON.stringify(this.user)));
      // console.log("---------------------");
      // this.optimizarR(JSON.parse(JSON.stringify(this.texto)), pedidos);
      console.log("ruta optimizada", this.optimizarR(JSON.parse(JSON.stringify(this.texto)), pedidos))
      console.log("grafo", this.grafo);
    },
    async procesarArchivo() {
      if (this.file != null) {
        this.file.text().then((text) => {
          if (this.validarTexto(text)) {
            var texto = [];
            var aux = this.formatoCoord(text.split("\n"));
            this.centros = [];
            this.puntos = [];
            for (var i = 0; i < aux.length; i++) {
              console.log("aux", aux[i]);
              aux[i]["id"] = aux[i][0] + aux[i][1];
              if (aux[i][0] == "C") {
                this.centros.push(aux[i]);
              } else if (aux[i][0] == "P") {
                this.puntos.push(aux[i]);
                this.puntosAux.push(aux[i]);
              }
              texto.push([aux[i][0], aux[i]['id'], parseInt(aux[i][2]), parseInt(aux[i][3])]);
            }
            console.log("textoooo", texto);
            this.texto =[...texto];
            this.mostrarCarga = true;
          }
        });
      }
    },
    formatoCoord(array) {
      var resultado = [];
      for (let i = 0; i < array.length; i++) {
        var aux = array[i].split(";");
        resultado.push(this.añadirT(aux[0], aux[1], aux[2].split(",")));
      }
      return resultado;
    },
    añadirT(tipo, id, coord) {
      var resultado = [];
      resultado.push(tipo);
      resultado.push(id);
      for (let i = 0; i < coord.length; i++) resultado.push(coord[i]);
      return resultado;
    },
    limpiar() {
      this.file = null;
      this.mostrarCarga = false;
      this.nuevoPunto = null;
      this.nuevoCentro = null;
      this.numProductos = 0;
      this.centros = [];
      this.puntos = [];
      this.puntosAux = [];
      this.rutas = [];
    },
    esNum(e) {
      if (isNaN(parseInt(e.key))) {
        e.preventDefault();
      }
    },
    validarNum(e) {
      if (!isNaN(parseInt(e.key))) {
        this.numProductos =
          this.numProductos <= 1000 && this.numProductos >= 0
            ? this.numProductos
            : this.numValidado;
        this.numValidado = this.numProductos;
      }
    },
    agregarRuta() {
      if (
        Object.keys(this.nuevoPunto).length != 0 &&
        Object.keys(this.nuevoCentro).length != 0 &&
        this.numProductos >= 0
      ) {
        this.rutas.push({
          punto: this.nuevoPunto,
          centro: this.nuevoCentro,
          num: parseInt(this.numProductos),
        });
        this.puntosAux.splice(this.puntosAux.indexOf(this.nuevoPunto), 1);
        this.nuevoCentro = null;
        this.nuevoPunto = null;
        this.numProductos = 0;
      }
    },
    validarTexto(strtxt) {
      let valtxt = /^(C|P){1};[a-zA-Z0-9]+;-?[0-9]+,-?[0-9]+(\n(C|P){1};[a-zA-Z0-9]+;-?[0-9]+,-?[0-9]+)*$/gm;
      if (valtxt.test(strtxt)) return true;
      return false;
    },
    minMax() {
      if (this.numProductos > 1000) {
        this.numProductos = 1000;
      } else if (this.numProductos < 0) {
        this.numProductos = 0;
      }
    },
    Centros(txt) {
      var centros = [];
      for (let i = 0; i < txt.length; i++) {
        if (txt[i][0] == "C") {
          centros.push(txt[i].splice(1)); //no guarda el tipo de dato, ya que el arreglo solo tendria un tipo (C o P)
        }
      }
      return centros;
    },
    PuntosV(txt) {
      var puntosv = [];
      for (let i = 0; i < txt.length; i++) {
        if (txt[i][0] == "P") {
          puntosv.push(txt[i].splice(1)); //no guarda el tipo de dato, ya que el arreglo solo tendria un tipo (C o P)
        }
      }
      return puntosv;
    },
    AsociarCtoP(pedido, puntosv) {
      for (let i = 0; i < pedido.length; i++) {
        for (let j = 0; j < puntosv.length; j++) {
          if (pedido[i][0] == puntosv[j][0]) {
            puntosv[j].push(pedido[i][2]); //Asocia el centro y la cantidad a despachar
            puntosv[j].push(pedido[i][1]);
            puntosv[j].push(0);
          }
        }
      }
      return puntosv;
    },
    ValuePedidos(distribucion, centro) {
      var P = [];
      for (let i = 0; i < distribucion.length; i++) {
        if (centro == distribucion[i][2]) {
          P.push(distribucion[i][1]);
        }
      }
      return P;
    },
    camionesmin(pedidos) {
      var camiones = 0;
      while (pedidos.length > 0) {
        var max = Math.max(...pedidos);
        pedidos.splice(pedidos.indexOf(max), 1);
        var psumas = [];
        for (let i = 0; i < pedidos.length; i++) {
          if (max + pedidos[i] <= 1000) {
            psumas.push(pedidos[i]);
          }
        }
        if (psumas.length > 0) {
          pedidos.splice(pedidos.indexOf(Math.max(...psumas)), 1);
          max += Math.max(...psumas);
          psumas.splice(psumas.indexOf(Math.max(...psumas)), 1);
        }
        for (let j = 0; j < psumas.length; j++) {
          if (max + psumas[j] <= 1000) {
            max += psumas[j];
            pedidos.splice(pedidos.indexOf(psumas[j]), 1);
            psumas.splice(j, 1);
            j = -1;
          }
        }
        camiones++;
      }
      return camiones;
    },
    camion(numero, centro) {
      let camiones = [];
      for (let i = 0; i < numero; i++) {
        var camion = [];
        camion.push(this.DistanciaPyP(centro, ["E", 0, 0]));
        camion.push(1000);
        camion.push([centro[0]]);
        camiones.push(camion);
      }
      return camiones;
    },
    PuntosVdeC(centro, puntosv) {
      var np = 0;
      for (let i = 0; i < puntosv.length; i++) {
        if (puntosv[i].includes(centro)) {
          np++;
        }
      }
      return np;
    },
    DistanciaPyP(punto1, punto2) {
      return Math.sqrt(
        Math.pow(punto1[1] - punto2[1], 2) + Math.pow(punto1[2] - punto2[2], 2)
      );
    },
    Pmaslejano(punto, centro, puntosv) {
      var lejania = 0;
      var indice = "";

      for (let i = 0; i < puntosv.length; i++) {
        if (puntosv[i].length > 3) {
          if (
            centro[0] == puntosv[i][3] &&
            punto[0] != puntosv[i][0] &&
            puntosv[i][5] == 0
          ) {
            var distancia = this.DistanciaPyP(punto, puntosv[i]);
            if (lejania < distancia) {
              lejania = distancia;
              indice = i;
            }
          }
        }
      }
      if (indice != "") return puntosv[indice];
      return 0;
    },
    Pmascercano(punto, centro, puntosv) {
      var cercania = Number.MAX_SAFE_INTEGER;
      var indice = "";

      for (let i = 0; i < puntosv.length; i++) {
        if (puntosv[i].length > 3) {
          if (
            centro[0] == puntosv[i][3] &&
            punto[0] != puntosv[i][0] &&
            puntosv[i][5] == 0
          ) {
            var distancia = this.DistanciaPyP(punto, puntosv[i]);
            if (cercania > distancia) {
              cercania = distancia;
              indice = i;
            }
          }
        }
      }
      return puntosv[indice];
    },
    PuntoporID(id, puntosv, centros) {
      for (let i = 0; i < puntosv.length; i++)
        if (id == puntosv[i][0]) return puntosv[i];
      for (let j = 0; j < centros.length; j++)
        if (id == centros[j][0]) return centros[j];
    },
    DespachosPendientes(centro, puntosv) {
      for (let i = 0; i < puntosv.length; i++)
        if (
          puntosv[i].length > 3 &&
          puntosv[i][3] == centro[0] &&
          puntosv[i][5] == 0
        )
          return true;
      return false;
    },
    EntregasPosibles(camion, centro, puntosv) {
      for (let i = 0; i < puntosv.length; i++)
        if (
          puntosv[i].length > 3 &&
          puntosv[i][3] == centro[0] &&
          puntosv[i][5] == 0 &&
          camion[1] >= puntosv[i][4]
        )
          return true;
      return false;
    },
    CambiarEstado(punto, puntosv) {
      for (let i = 0; i < puntosv.length; i++)
        if (puntosv[i].length > 3 && punto[0] == puntosv[i][0])
          puntosv[i][5] = 1;
    },
    copiarpvs(pvs) {
      var copia = [];
      for (let i = 0; i < pvs.length; i++) {
        var elemento = [];
        for (let j = 0; j < pvs[i].length; j++) elemento.push(pvs[i][j]);
        copia.push(elemento);
      }
      return copia;
    },
    DistanciaT(camiones, puntosv, centro) {
      var posactual = this.PuntoporID(
        camiones[2][camiones[2].length - 1],
        puntosv,
        centro
      );
      var distanciadirecta = this.DistanciaPyP(posactual, ["E", 0, 0]) * 2;
      var desviacion = this.Pmascercano(posactual, centro, puntosv);
      var distanciadesviacion = this.DistanciaPyP(posactual, desviacion);
      distanciadesviacion += this.DistanciaPyP(desviacion, ["E", 0, 0]);
      if (distanciadesviacion <= distanciadirecta) return true;
      return false;
    },
    pedidospendientes(camiones, puntosv, centro, centros) {
      for (let i = 0; i < camiones.length; i++) {
        if (this.EntregasPosibles(camiones[i], centro, puntosv)) {
          while (this.EntregasPosibles(camiones[i], centro, puntosv)) {
            var posactual = this.PuntoporID(
              camiones[i][2][camiones[i][2].length - 1],
              puntosv,
              centros
            );
            var pcercano = this.Pmascercano(posactual, centro, puntosv);
            camiones[i][0] += this.DistanciaPyP(posactual, pcercano);
            camiones[i][1] -= pcercano[4];
            camiones[i][2].push(pcercano[0]);
            this.CambiarEstado(pcercano, puntosv);
          }
          camiones[i][0] += this.DistanciaPyP(
            this.PuntoporID(
              camiones[i][2][camiones[i][2].length - 1],
              puntosv,
              centros
            ),
            ["E", 0, 0]
          );
          camiones[i][2].push("E");
        }
        if (camiones[i][2][camiones[i][2].length - 1] != "E") {
          camiones[i][0] += this.DistanciaPyP(
            this.PuntoporID(
              camiones[i][2][camiones[i][2].length - 1],
              puntosv,
              centros
            ),
            ["E", 0, 0]
          );
          camiones[i][2].push("E");
        }
      }
    },
    SumarDCamiones(camiones) {
      var distanciatotal = 0;
      for (let i = 0; i < camiones.length; i++)
        distanciatotal += camiones[i][0];
      return distanciatotal;
    },
    RutaOptima(rutas) {
      var distanciaoptima = Number.MAX_SAFE_INTEGER;
      var indice = "";
      for (let i = 0; i < rutas.length; i++) {
        if (this.SumarDCamiones(rutas[i]) < distanciaoptima) {
          distanciaoptima = this.SumarDCamiones(rutas[i]);
          indice = i;
        }
      }
      return rutas[indice];
    },
    optimizarR(datos, pedidos) {
      var centros = this.Centros(datos);
      var puntosv = this.AsociarCtoP(pedidos, this.PuntosV(datos));
      var RoptimaGlobal = [];
      console.log("centros", centros);
      console.log("puntosv", puntosv);
      for (let i = 0; i < centros.length; i++) {
        var min = this.camionesmin(this.ValuePedidos(pedidos, centros[i]));
        var max = this.PuntosVdeC(centros[i][0], puntosv);
        var RoptimaCentro = [];

        for (let j = min; j <= max; j++) {
          var camiones = this.camion(j, centros[i]);
          var pvs = this.copiarpvs(puntosv);
          for (let k = 0; k < camiones.length; k++) {
            if (camiones.length == 1) {
              while (
                this.DespachosPendientes(centros[i], pvs) &&
                this.EntregasPosibles(camiones[k], centros[i], pvs)
              ) {
                var posactual = this.PuntoporID(
                  camiones[k][2][camiones[k][2].length - 1],
                  pvs,
                  centros
                );
                var pcercano = this.Pmascercano(posactual, centros[i], pvs);
                camiones[k][0] += this.DistanciaPyP(posactual, pcercano);
                camiones[k][1] -= pcercano[4];
                camiones[k][2].push(pcercano[0]);
                this.CambiarEstado(pcercano, pvs);
              }
              camiones[k][0] += this.DistanciaPyP(
                this.PuntoporID(
                  camiones[k][2][camiones[k][2].length - 1],
                  pvs,
                  centros
                ),
                ["E", 0, 0]
              );
              camiones[k][2].push("E");
            }
            if (camiones.length == max) {
              posactual = this.PuntoporID(
                camiones[k][2][camiones[k][2].length - 1],
                pvs,
                centros
              );
              pcercano = this.Pmascercano(posactual, centros[i], pvs);
              camiones[k][0] += this.DistanciaPyP(posactual, pcercano);
              camiones[k][1] -= pcercano[4];
              camiones[k][2].push(pcercano[0]);
              this.CambiarEstado(pcercano, pvs);
              camiones[k][0] += this.DistanciaPyP(
                this.PuntoporID(
                  camiones[k][2][camiones[k][2].length - 1],
                  pvs,
                  centros
                ),
                ["E", 0, 0]
              );
              camiones[k][2].push("E");
            }
            if (camiones.length != 1 && camiones.length != max) {
              if (this.Pmaslejano(["E", 0, 0], centros[i], pvs) != 0) {
                posactual = this.PuntoporID(
                  camiones[k][2][camiones[k][2].length - 1],
                  pvs,
                  centros
                );
                var plejano = this.Pmaslejano(["E", 0, 0], centros[i], pvs);
                camiones[k][0] += this.DistanciaPyP(posactual, plejano);
                camiones[k][1] -= plejano[4];
                camiones[k][2].push(plejano[0]);
                this.CambiarEstado(plejano, pvs);
              }
              while (
                this.DespachosPendientes(centros[i], pvs) &&
                this.EntregasPosibles(camiones[k], centros[i], pvs) &&
                this.DistanciaT(camiones[k], pvs, centros[i])
              ) {
                posactual = this.PuntoporID(
                  camiones[k][2][camiones[k][2].length - 1],
                  pvs,
                  centros
                );
                pcercano = this.Pmascercano(posactual, centros[i], pvs);
                camiones[k][0] += this.DistanciaPyP(posactual, pcercano);
                camiones[k][1] -= pcercano[4];
                camiones[k][2].push(pcercano[0]);
                this.CambiarEstado(pcercano, pvs);
              }
            }
          }
          this.pedidospendientes(camiones, pvs, centros[i], centros);
          //console.log(camiones.length+": ",camiones)
          if (!this.DespachosPendientes(centros[i], pvs)) {
            RoptimaCentro.push(camiones);
          }
          // console.log("ruta optima centro", RoptimaCentro);
        }
        RoptimaGlobal.push(this.RutaOptima(RoptimaCentro));
        // console.log("ruta optima global", RoptimaGlobal);
      }
      return RoptimaGlobal;
    },
    crearGrafo(listaPuntos) {
      var aristas = [];
      var nodos = [{ id: "D", ejeX: 0, ejeY: 0 }];
      var finales = [];
      for (var j = 0; j < this.centros.length; j++) {
        nodos.push({
          id: this.centros[j].id,
          ejeX: parseInt(this.centros[j][2]),
          ejeY: parseInt(this.centros[j][3]),
        });
      }
      for (var k = 0; k < this.puntos.length; k++) {
        nodos.push({
          id: this.puntos[k].id,
          ejeX: parseInt(this.puntos[k][2]),
          ejeY: parseInt(this.puntos[k][3]),
        });
        finales.push(this.puntos[k].id);
      }
      for (var i = 0; i < listaPuntos.length; i++) {
        var punto = listaPuntos[i];
        // var origen = [punto.centro.id, punto.centro[2], punto.centro[3]];
        // var destino = [punto.punto.id, punto.punto[2], punto.punto[3]];
        aristas.push({
          origen: punto.centro.id,
          destino: punto.punto.id,
          // peso: this.distanciaEntrePuntos(origen, destino),
          peso: 0,
        });
      }
      this.grafo = {
        nodos: nodos,
        aristas: aristas,
        inicial: "D",
        finales: finales,
      };
    },
  },
};
</script>

<style>
table td,
table th {
  vertical-align: middle !important;
}
</style>