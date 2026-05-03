<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos</title>

<style>
body{font-family:sans-serif;background:#f1f5f9;margin:0;padding:20px}

h1{margin-bottom:10px}

.top{display:flex;justify-content:space-between;margin-bottom:10px}
input,select{padding:8px;border-radius:8px;border:1px solid #ccc}
button{padding:8px 12px;border:none;border-radius:8px;cursor:pointer}

.primary{background:#6366f1;color:#fff}
.card{background:#fff;padding:15px;border-radius:12px}

table{width:100%;border-collapse:collapse}
th,td{padding:10px;text-align:left}
tr{border-top:1px solid #eee}

.badge{padding:3px 8px;border-radius:12px;font-size:12px}
.activo{background:#dcfce7;color:#16a34a}
.inactivo{background:#eee}

.modal{position:fixed;inset:0;background:#0005;display:none;align-items:center;justify-content:center}
.modal.show{display:flex}
.box{background:#fff;padding:20px;border-radius:12px;width:300px}
</style>
</head>

<body>

<h1>📦 Productos</h1>

<div class="top">
  <input id="buscar" placeholder="Buscar...">
  <button class="primary" onclick="nuevo()">+ Nuevo</button>
</div>

<div class="card">
<table>
<thead>
<tr><th>#</th><th>Nombre</th><th>Precio</th><th>Estado</th><th></th></tr>
</thead>
<tbody id="tb"></tbody>
</table>
</div>

<!-- MODAL -->
<div class="modal" id="modal">
<div class="box">

<input type="hidden" id="id">
<input placeholder="Nombre" id="nombre"><br><br>
<input type="number" placeholder="Precio" id="precio"><br><br>

<select id="estado">
<option value="1">Activo</option>
<option value="0">Inactivo</option>
</select><br><br>

<button onclick="guardar()" class="primary">Guardar</button>
<button onclick="cerrar()">Cancelar</button>

</div>
</div>

<script>
const URL='../controladores/ProductoController.php';
let data=[];

const $=id=>document.getElementById(id);

async function listar(){
  data=await (await fetch(URL+'?op=listar')).json();
  pintar(data);
}

function pintar(arr){
  $('tb').innerHTML=arr.map(p=>`
  <tr>
    <td>${p.id}</td>
    <td>${p.nombre}</td>
    <td>S/ ${p.precio}</td>
    <td><span class="badge ${p.estado==1?'activo':'inactivo'}">
      ${p.estado==1?'Activo':'Inactivo'}
    </span></td>
    <td>
      <button onclick="edit(${p.id})">✏️</button>
      <button onclick="del(${p.id})">🗑️</button>
    </td>
  </tr>`).join('');
}

$('buscar').oninput=e=>{
  pintar(data.filter(p=>p.nombre.toLowerCase().includes(e.target.value.toLowerCase())));
}

function nuevo(){
  $('id').value='';
  $('nombre').value='';
  $('precio').value='';
  $('estado').value='1';
  abrir();
}

function abrir(){ $('modal').classList.add('show') }
function cerrar(){ $('modal').classList.remove('show') }

async function edit(id){
  let p=await (await fetch(URL+`?op=obtener&id=${id}`)).json();
  $('id').value=p.id;
  $('nombre').value=p.nombre;
  $('precio').value=p.precio;
  $('estado').value=p.estado;
  abrir();
}

async function guardar(){
  let f=new FormData();
  f.append('id',$('id').value);
  f.append('nombre',$('nombre').value);
  f.append('precio',$('precio').value);
  f.append('estado',$('estado').value);

  await fetch(URL+'?op=guardar',{method:'POST',body:f});
  cerrar(); listar();
}

async function del(id){
  if(!confirm('Eliminar?'))return;
  let f=new FormData(); f.append('id',id);
  await fetch(URL+'?op=eliminar',{method:'POST',body:f});
  listar();
}

listar();
</script>

</body>
</html>