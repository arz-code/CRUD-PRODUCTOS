<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>CRUD Productos</title>

<style>
*{box-sizing:border-box;margin:0;padding:0;}

body{
  font-family:'Segoe UI',Arial;
  background:#f1f5f9;
  display:flex;
  min-height:100vh;
  font-size:14px;
  color:#0f172a;
}

/* SIDEBAR */
.sidebar{
  width:220px;
  background:linear-gradient(180deg,#16a34a,#15803d);
  color:#fff;
  position:fixed;
  height:100vh;
  box-shadow:2px 0 10px rgba(0,0,0,.08);
}

.sidebar-brand{
  padding:22px 18px;
  border-bottom:1px solid rgba(255,255,255,.15);
}
.sidebar-brand h1{
  font-size:16px;
  font-weight:600;
}

.nav-item{
  padding:12px 18px;
  color:#dcfce7;
  border:none;
  width:100%;
  text-align:left;
  cursor:pointer;
}
.nav-item.active{
  background:#fff;
  color:#16a34a;
  font-weight:600;
  border-radius:0 20px 20px 0;
}

/* MAIN */
.main{
  margin-left:220px;
  flex:1;
}

/* TOPBAR */
.topbar{
  height:60px;
  background:#fff;
  border-bottom:1px solid #e2e8f0;
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 28px;
}

.btn-add{
  background:#2563eb;
  color:#fff;
  border:none;
  padding:8px 16px;
  border-radius:8px;
  cursor:pointer;
}
.btn-add:hover{
  background:#1d4ed8;
}

/* CONTENT */
.content{
  padding:30px;
}

/* CARD */
.table-card{
  background:#fff;
  border-radius:12px;
  box-shadow:0 6px 18px rgba(0,0,0,.05);
  overflow:hidden;
}

.table-card-header{
  padding:16px 20px;
  border-bottom:1px solid #e2e8f0;
  display:flex;
  justify-content:space-between;
}

/* TABLE */
table{
  width:100%;
  border-collapse:collapse;
}

thead{
  background:#f8fafc;
}

thead th{
  padding:12px 20px;
  font-size:11px;
  text-transform:uppercase;
  color:#64748b;
}

tbody tr{
  border-bottom:1px solid #f1f5f9;
}
tbody tr:hover{
  background:#f9fafb;
}
tbody td{
  padding:14px 20px;
}

/* ESTADO */
.estado-1{color:#16a34a;font-weight:600;}
.estado-0{color:#94a3b8;}

/* BOTONES */
button{
  border:none;
  padding:5px 10px;
  border-radius:6px;
  cursor:pointer;
}

button:nth-child(1){
  background:#e0f2fe;
  color:#0369a1;
}
button:nth-child(2){
  background:#fee2e2;
  color:#b91c1c;
}

/* MODAL */
.overlay{
  display:none;
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.4);
  justify-content:center;
  align-items:center;
}
.overlay.open{
  display:flex;
}

.modal{
  background:#fff;
  border-radius:12px;
  width:400px;
  box-shadow:0 15px 40px rgba(0,0,0,.2);
}

.modal-body{
  padding:22px;
}
.modal-body input,
.modal-body select{
  width:100%;
  margin-bottom:12px;
  padding:10px;
  border-radius:6px;
  border:1px solid #e2e8f0;
}

.modal-footer{
  padding:14px;
  display:flex;
  justify-content:flex-end;
  gap:10px;
}

.modal-footer button:first-child{
  background:#e5e7eb;
}
.modal-footer button:last-child{
  background:#16a34a;
  color:#fff;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-brand">
    <h1>CRUD PRODUCTOS</h1>
  </div>
  <button class="nav-item active">📦 Productos</button>
</aside>

<!-- MAIN -->
<div class="main">
<header class="topbar">
  <span>Módulos / Productos</span>
  <button class="btn-add" onclick="abrirModalNuevo()">+ Agregar</button>
</header>

<div class="content">
<div class="table-card">
<div class="table-card-header">
  <h2>Lista de Productos</h2>
  <span id="count-productos">0 registros</span>
</div>

<table>
<thead>
<tr>
<th>#</th>
<th>Nombre</th>
<th>Precio</th>
<th>Estado</th>
<th>Acciones</th>
</tr>
</thead>

<tbody id="tbody-productos">
<tr><td colspan="5">Cargando...</td></tr>
</tbody>
</table>
</div>
</div>
</div>

<!-- MODAL -->
<div class="overlay" id="modal-producto">
<div class="modal">

<div class="modal-body">
<input type="hidden" id="mp-id">

<input type="text" id="mp-nombre" placeholder="Nombre del producto">
<input type="number" id="mp-precio" placeholder="Precio">

<select id="mp-estado">
<option value="1">Activo</option>
<option value="0">Inactivo</option>
</select>
</div>

<div class="modal-footer">
<button onclick="cerrarModal()">Cancelar</button>
<button onclick="guardarProducto()">Guardar</button>
</div>

</div>
</div>

<script>
const CTRL_P = '../controladores/ProductoController.php';

async function listarProductos(){
  try{
    const res = await fetch(`${CTRL_P}?op=listar`);
    const data = await res.json();

    document.getElementById('count-productos').textContent = data.length+" registros";

    document.getElementById('tbody-productos').innerHTML =
      data.length ? data.map(p=>`
        <tr>
          <td>${p.id}</td>
          <td>${p.nombre}</td>
          <td>S/ ${parseFloat(p.precio).toFixed(2)}</td>
          <td class="estado-${p.estado}">
            ${p.estado==1?'Activo':'Inactivo'}
          </td>
          <td>
            <button onclick="editarProducto(${p.id})">Editar</button>
            <button onclick="eliminarProducto(${p.id})">Eliminar</button>
          </td>
        </tr>
      `).join('')
      : `<tr><td colspan="5">Sin registros</td></tr>`;

  }catch(e){
    document.getElementById('tbody-productos').innerHTML =
      `<tr><td colspan="5">Error de conexión</td></tr>`;
  }
}

function abrirModalNuevo(){
  document.getElementById('mp-id').value='';
  document.getElementById('mp-nombre').value='';
  document.getElementById('mp-precio').value='';
  document.getElementById('mp-estado').value='1';
  abrirModal();
}

function abrirModal(){
  document.getElementById('modal-producto').classList.add('open');
}

function cerrarModal(){
  document.getElementById('modal-producto').classList.remove('open');
}

async function editarProducto(id){
  const res = await fetch(`${CTRL_P}?op=obtener&id=${id}`);
  const p = await res.json();

  document.getElementById('mp-id').value=p.id;
  document.getElementById('mp-nombre').value=p.nombre;
  document.getElementById('mp-precio').value=p.precio;
  document.getElementById('mp-estado').value=p.estado;

  abrirModal();
}

async function guardarProducto(){
  const id = document.getElementById('mp-id').value;
  const nombre = document.getElementById('mp-nombre').value;
  const precio = document.getElementById('mp-precio').value;
  const estado = document.getElementById('mp-estado').value;

  if(!nombre || !precio){
    alert("Completa los campos");
    return;
  }

  const fd = new FormData();
  fd.append('id',id);
  fd.append('nombre',nombre);
  fd.append('precio',precio);
  fd.append('estado',estado);

  await fetch(`${CTRL_P}?op=guardar`,{
    method:'POST',
    body:fd
  });

  cerrarModal();
  listarProductos();
}

async function eliminarProducto(id){
  if(!confirm("¿Eliminar producto?")) return;

  const fd = new FormData();
  fd.append('id',id);

  await fetch(`${CTRL_P}?op=eliminar`,{
    method:'POST',
    body:fd
  });

  listarProductos();
}

listarProductos();
</script>

</body>
</html>