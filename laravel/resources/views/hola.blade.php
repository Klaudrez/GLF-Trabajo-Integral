{{-- @extends('template')

@section('seccion') --}}
{{-- <html>
<head>
    <script type="text/javascript" src="vis/disc">
    
    </script>
</head>
<body>
            
</body>
</html> --}}
<!doctype html>
<html>
<head>
  <title>Network</title>
  <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js">
  
  </script> 
  <style type="text/css">
    #mynetwork {
      width: 1111px;
      height: 500px;
      border: 1px solid lightgray;
    }
  </style> 
</head>
<body>
<script> 
        
</script>
<div id="mynetwork"></div>

<!-- <form method="post" action="#"> -->
<tr>
    <button onclick="ObtenerEtiqueta()">Agregar Nodo</button>
    <button onclick="Clean()">Clean </button>
</tr>
<br>
<tr>
    <input type="text" placeholder="Desde " name="Desde" id="Desde" required="" pattern="[0-9]+">
    <input type="text" placeholder="Hasta " name="Hasta" id="Hasta" required="" pattern="[0-9]+">  
    <button onclick="ObtenerConection()">Conectar </button>
    <br><label for="">Tipo: </label>
    <input type="radio" name="btnradio" id="1" >dirigido
    <input type="radio" name="btnradio" id="0" >simple
    <br>
    <div style="text-align: right">
    <input type="text" placeholder="Inicio " name="Inicio" id="Inicio">
    <input type="text" placeholder="Final " name="Final" id="Final"> 
    <button onclick="Ruta()">Mostrar camino más corto</button><br>
    <button onclick="FuncionEuler()">¿Euleriano?</button><br>
    <button onclick="FuncionHamilton()">¿Hamiltoniano?</button><br>
    <button onclick="conexo()">¿Conexo o no conexo?</button><br>
    <button onclick="Matrizadya()">Mostrar matriz de adyacencia</button></br>
    <button onclick="arbol()">Mostrar arbol</button></br>
    <input type="text" placeholder="Nodo de entrada " name="N_en" id="N_en">
    <input type="text" placeholder="Nodo de salida " name="N_sal" id="N_sal"> 
    <button onclick="FuncionFlujo()">Flujo maximo</button><br>
    </div>
</tr>

</tr> 


<table>
</table>
<script type="text/javascript">
  // create an array with nodes
  function y(nombres){
      var object =[];
      for (let index = 0; index < nombres.length; index++) {
          const element = nombres[index];
          object.push({id: index + 1, label:element})
      }
      return object;
  };
  //var nodes = new vis.DataSet(y(nombres));
  var array =[]
  var nombre=[]
  var id=1
  var node,edge,data,container,options,network
  
  function Clean()
  {
    for(let index=0;index<nombre.length-1;index++)
      {
        nombre.splice(index,nombre.length)
        array.splice(index,array.length)
        id=1
        Dibujar()
      }

  }

  function FuncionFlujo()
  {
    alert("No se implemento el metodos")
  }

  function arbol()
  {
    alert("No se implemento el metodo")
  }  
  function ObtenerEtiqueta()
  {        
     nombre.push(id.toString())
     id++
     Dibujar()
  }

  function FuncionEuler()
  {
    if(Array.isArray(CaminoE(matriz(nombre,array))))
      {
        var mensaje=imprimirR(CaminoE(matriz(nombre,array)),"Camino Euleriano")
         alert(mensaje)
      }
    else 
    {
      var respuesta=CaminoE(matriz(nombre,array))
      alert(respuesta)
    }
  }

  function FuncionHamilton()
  {
    alert("No se implemento el metodo")
  }

  function imprimirM(matriz1, titulo)
  {
    var mensaje=titulo + "\n\n"
    for(let index=0;index<matriz1.length;index++)
      for(let jdex=0;jdex<matriz1.length;jdex++)
      {
        if(jdex==matriz1.length-1)
          mensaje+= matriz1[index][jdex].toString() + " \n\n"
        else
          mensaje+= matriz1[index][jdex].toString() + "         "
      }
      return mensaje
  }

  function imprimirR(arreglo, titulo)
  {
    var mensaje=titulo + "\n["
    for(let index=0;index<arreglo.length;index++)
    {
      if(index==arreglo.length-1)
        mensaje+=arreglo[index] + "]"
      else
        mensaje+=arreglo[index] + ", "
    }
    return mensaje
  }

  function Ruta()
  {
    var inicio=parseInt(document.getElementById('Inicio').value)
    var final=document.getElementById('Final').value
    if(Array.isArray(ruta(inicio,final,matriz(nombre,array))))
      {
        var mensaje=imprimirR(ruta(inicio,final,matriz(nombre,array)),"Camino más corto")
         alert(mensaje)
      }
    else   
      alert("Los nodos ingresados no son conexos entre si")
    
  }

  function Tipo()
  { 
    var r1=document.getElementById('1')
    var r2=document.getElementById('0')

    if(r1.checked==true){
      var options = {edges:{arrows:{to:{enabled:true}}}}
      return true
    }
    else
    {
      var options = {}
      return false
    } 
  }
  function conexo()
  {
    var mensaje=""
    if(EsConexa(matriz(nombre,array)))
      mensaje="El grafo es conexo"
    else
      mensaje="El grafo no es conexo"
    var msj=imprimirM(matrizDeCaminos(matriz(nombre,array)),"Matriz de Camino")
    alert(msj+mensaje) 
  }
  
  function Matrizadya()
  {
    var tipo=Tipo()
    if(tipo)
    {
      var mensaje=imprimirM(matrizdiri(nombre,array),"Matriz de adyacencia")
    }
    else
    {
      var mensaje=imprimirM(matriz(nombre,array),"Matriz de adyacencia")      
    }
    alert(mensaje)
  }
  function ObtenerConection()
  {
    var arr=[]
    var desde = parseInt(document.getElementById('Desde').value)
    var hasta = parseInt(document.getElementById('Hasta').value)
    arr.push(desde)
    arr.push(hasta)
    array.push(arr)
    
    Dibujar()
  }
 /*  [1,2],
  [2,3],
  [3,4],
  [4,5],
  [5,2],
  [1,5] 
  ];*/
  function x (array){
      var object =[];
      array.forEach(element => {
          object.push({from: element[0], to: element[1]})
      });
      return object;
  }
  // create an array with edges
  //var edges = new vis.DataSet(x(array)
    /* {from: 1, to: 3},
    {from: 1, to: 2},
    {from: 2, to: 4},
    {from: 2, to: 5},
    {from: 3, to: 6},
    {from: 2, to: 6} */
  
 /*  console.log(array);
  console.log(x(array)); */
  //console.log(nodes);
 
 
  // create a network

  function Dibujar()
  {
    var opcion=document.getElementById('1')
    node=new vis.DataSet(y(nombre))
    edge = new vis.DataSet(x(array))
    container = document.getElementById('mynetwork');
    data = {
      nodes: node,
      edges: edge}
  

     if(opcion.checked==true)
      options = {edges:{arrows:{to:{enabled:true}}}}
    else
      options = {}; 
    network = new vis.Network(container, data, options);
  }
  

/////////////////////////////
///////////////////////////////INTEGRAL///////////////////////////////////////

var Texto = [
    ['C','1','3','4'],
    ['C','2','-4','1'],
    ['P','3','10','9'],
    ['P','4','-7','5'],
    ['P','5','25','15'],
    ['P','6','-12','7'],
    ['P','7','15','11']
]

function separarCP(Texto){
    var CentroDeDistribución = []
    var PuntoDeVenta = []
    for (let index = 0; index < Texto.length; index++) {
        const element = Texto[index];
        element[1]=parseInt(element[1])
        element[2]=parseInt(element[2])
        element[3]=parseInt(element[3])
        if(element[0]=='C'){
            let dato =[]
            dato.push(element[1])
            dato.push(element[2])
            dato.push(element[3])
            CentroDeDistribución.push(dato)
        }
        else{
            let dato =[]
            dato.push(element[1])
            dato.push(element[2])
            dato.push(element[3])
            PuntoDeVenta.push(dato)
        }
    }
    var Resultado = []
    Resultado.push(CentroDeDistribución)
    Resultado.push(PuntoDeVenta)
    return Resultado
}
var CentrosDeDistribución = separarCP(Texto)[0]
var PuntosDeVenta = separarCP(Texto)[1]
//var PuntosDeVenta = agregarYaPaso(PuntosDeVenta)

/*                                     // PuntosDeVenta debe venir MODIFICADO tal que: PuntosDeVenta > element = [Id, X, Y, YaPaso, CD, Demanda]
function Rutas(CentrosDeDistribución, PuntosDeVenta ) { // PuntosDeVenta > element = [Id, X, Y, YaPaso, CD] ; Return = array(rutas de cada centro)
    let Respuesta = []
    for (let index = 0; index < CentrosDeDistribución.length; index++) {
        const element = CentrosDeDistribución[index];
        Respuesta.push(rutaOptimaCD(element,PuntosDeVenta))
        
    }
}
function rutaOptimaCD(CD, PuntosDeVenta){ //CD = [ID, X, Y]; PuntosDeVenta > element = [Id, X, Y, YaPaso, CD] ; Return = array(rutas Camiones)
    let PVdeCD = []
    for (let index = 0; index < PuntosDeVenta.length; index++) {
        const element = PuntosDeVenta[index];
        if(element[4]==CD[0]){
            PVdeCD.push(element)
        }
    }

}
function rutaCamion()
 */

/* function agregarYaPaso(PuntoDeVenta){ 
    for (let index = 0; index < PuntoDeVenta.length; index++) {
        const element = PuntoDeVenta[index];
        element.push(false)
    }
    return PuntoDeVenta
} */

/* function CentrosDeDistribuciónAsignado(PV,CD){
    PV.push(CD)
    return PV
} */

function distanciaEntrePuntos(origen,destino){  // origen y destino son arrays [id,X,Y]
    let x=Math.pow(origen[1] - destino[1], 2) + Math.pow(origen[2] - destino[2], 2)
    let resultado = Math.sqrt(x)
    //return Number.parseFloat(resultado).toFixed(5)
    resultado = parseFloat(Number.parseFloat(resultado).toFixed(5))
    console.log(typeof(resultado))
    return resultado
}

function puntoMasCercano(puntoActual, Puntos ){ //Punto = [Id, X, Y, YaPaso, CD] Puntos > element = [Id, X, Y, YaPaso, CD] 
    /* if(puntoActual.length==)
    puntoActual[3]=True
    for() */
}

console.log(PuntosDeVenta)

</script> 
</body>
</html>
{{-- @endsection --}}