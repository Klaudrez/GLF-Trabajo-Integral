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
      <v-row class="ma-10">
        <v-col cols="4">
          <v-select
            :items="puntos"
            v-model="nuevoPunto"
            label="Punto de venta"
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
          <v-btn
            color="primary"
            block
            depressed
            raised
            @click="agregarRuta"
            >+ Ruta</v-btn
          >
        </v-col>
      </v-row>

      <v-row class="ma-10" align="center" style="justify-content: center;">
        <!-- <v-col cols="6">
          <v-select
            v-model="rutas"
            :items="rutas"
            chips
            no-data-text="Debe agregar rutas"
            multiple
            outlined
            @change="changed()"
          >
            <template v-slot:selection="{ item }">
              <v-chip close @click:close="eliminarRuta(item)">
                <strong>{{ item }}</strong>
              </v-chip>
            </template>
          </v-select>
        </v-col> -->
        <v-simple-table fixed-header v-if="rutas.length > 0">
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">
            Centro de distribución
          </th>
          <th class="text-left">
            Punto de venta
          </th>
          <th class="text-left">
            N° productos
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="item in rutas"
          :key="rutas.indexOf(item)"
        >
          <td>{{ item.centro.id }}</td>
          <td>{{ item.punto.id }}</td>
          <td>{{ item.num }}</td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
      </v-row>

    </div>
  </div>
</template>

<script>
export default {
  name: "Home",
  components: {},
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
    centros: [],
    rutas: [],
    nuevoPunto: {},
    nuevoCentro: {},
    numProductos: 0,
    numValidado: 0,
    mostrarCarga: false,
  }),
  mounted() {
    this.$store.commit("writeLog", {
      level: "info",
      message: "Se accede a " + window.location.pathname + window.location.hash,
    });
  },
  watch: {
    file: function () {
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
    procesarArchivo() {
      if (this.file != null) {
        console.log("file", this.file);
        this.file.text().then((text) => {
          var weas = this.formatoCoord(text.split("\n"));
          console.log("filetext", weas);
          this.centros = [];
          this.puntos = [];
          for (var i = 0; i < weas.length; i++) {
            weas[i]["id"] = weas[i][0] + weas[i][1];
            if (weas[i][0] == "C") {
              this.centros.push(weas[i]);
            } else if (weas[i][0] == "P") {
              this.puntos.push(weas[i]);
            }
          }
          console.log("centros", this.centros);
          console.log("puntos", this.puntos);
          this.mostrarCarga = true;
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
      this.rutas = [];
    },
    esNum(e) {
      if (isNaN(parseInt(e.key))) {
        e.preventDefault();
      } 
      // else {
      //   console.log(isNaN(parseInt(e.key)));
      // }
      // console.log("actual", this.numProductos);
      // console.log("nuevo", e);
    },
    validarNum(e) {
      if (!isNaN(parseInt(e.key))) {
        this.numProductos = this.numProductos <= 1000 && this.numProductos >= 0 ? this.numProductos : this.numValidado;
        this.numValidado = this.numProductos;
      }
    },
    agregarRuta() {
      if (Object.keys(this.nuevoPunto).length != 0 && Object.keys(this.nuevoCentro).length != 0 && this.numProductos >= 0) {
        this.rutas.push({punto: this.nuevoPunto, centro: this.nuevoCentro, num: this.numProductos});
        this.puntos.splice(this.puntos.indexOf(this.nuevoPunto), 1);
        this.nuevoCentro = null;
        this.nuevoPunto = null;
        this.numProductos = 0;
      }
      console.log("rutas", this.rutas);
    }
  },
};
</script>

<style>
table td, table th {
    vertical-align: middle !important;
}
</style>