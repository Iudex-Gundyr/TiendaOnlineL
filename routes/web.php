<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IntranetController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\PrivilegiosController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\CiudadesController;
use App\Http\Controllers\MarcasController;
use App\Http\Controllers\MaterialesController;
use App\Http\Controllers\FotosDescripcionController;
use App\Http\Controllers\CantidadController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\Ofertascontroller;
use App\Http\Controllers\matOfertasController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarritoCompraController;
use App\Http\Controllers\pagarController;
use App\Http\Controllers\TransbankController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\EntregasController;

//Login

Route::get('/Login',[LoginController::class, 'Login'])->name('Login');
Route::get('/login', function () {
    if (!auth()->check()) {
        return redirect()->route('productos');
    }
    // Aquí puedes manejar otro comportamiento si el usuario está autenticado
})->name('login');
Route::post('/IniciarSesion',[LoginController::class,'IniciarSesion'])->name('IniciarSesion');
Route::get('/cerrarSesion',[LoginController::class,'cerrarSesion'])->name('cerrarSesion');



//Intranet
Route::get('/dashboard',[IntranetController::class,'dashboard'])->name('dashboard')->middleware('auth:usuarios');

//Usuarios
Route::get('/usuarios',[UsuariosController::class,'usuarios'])->name('usuarios')->middleware('auth:usuarios');
Route::post('/CrearUsuario',[UsuariosController::class,'CrearUsuario'])->name('CrearUsuario')->middleware('auth:usuarios');
Route::get('/usuariosfiltrar',[UsuariosController::class,'usuariosfiltrar'])->name('usuariosfiltrar')->middleware('auth:usuarios');
Route::get('/eliminarUsuario/{id}', [UsuariosController::class, 'eliminarUsuario'])->name('eliminarUsuario')->middleware('auth:usuarios');
Route::get('/modificarUsuario/{id}',[UsuariosController::class,'modificarUsuario'])->name('modificarUsuario')->middleware('auth:usuarios');
Route::post('/updateUsuario/{id}',[UsuariosController::class,'updateUsuario'])->name('updateUsuario')->middleware('auth:usuarios');

//Privilegio
Route::get('/privilegios/{id}',[PrivilegiosController::class,'privilegios'])->name('privilegios')->middleware('auth:usuarios');
Route::post('/agregarPrivilegio/{id}',[PrivilegiosController::class,'agregarPrivilegio'])->name('agregarPrivilegio')->middleware('auth:usuarios');
Route::get('/eliminarPrivilegio/{id}', [PrivilegiosController::class, 'eliminarPrivilegio'])->name('eliminarPrivilegio')->middleware('auth:usuarios');

//Categorias
Route::get('/categorias',[CategoriasController::class,'categorias'])->name('categorias')->middleware('auth:usuarios');
Route::post('/CrearCategoria',[CategoriasController::class,'CrearCategoria'])->name('CrearCategoria')->middleware('auth:usuarios');
Route::get('/categoriasfiltrar',[CategoriasController::class,'categoriasfiltrar'])->name('categoriasfiltrar')->middleware('auth:usuarios');
Route::get('/eliminarCategoria/{id}',[CategoriasController::class,'eliminarCategoria'])->name('eliminarCategoria')->middleware('auth:usuarios');

//Pais
Route::get('/paises',[PaisController::class,'paises'])->name('paises')->middleware('auth:usuarios');
Route::post('/crearPais',[PaisController::class,'crearPais'])->name('crearPais')->middleware('auth:usuarios');
Route::get('/paisesFiltrar',[PaisController::class,'paisesFiltrar'])->name('paisesFiltrar')->middleware('auth:usuarios');
Route::get('/eliminarPais/{id}',[PaisController::class,'eliminarPais'])->name('eliminarPais')->middleware('auth:usuarios');

//Region
Route::get('/Regiones/{id}',[RegionController::class,'Regiones'])->name('Regiones')->middleware('auth:usuarios');
Route::post('/crearRegion/{id}',[RegionController::class,'crearRegion'])->name('crearRegion')->middleware('auth:usuarios');
Route::get('regionesFiltrar/{id}',[RegionController::class,'regionesFiltrar'])->name('regionesFiltrar')->middleware('auth:usuarios');
Route::get('eliminarRegion/{id}',[RegionController::class,'eliminarRegion'])->name('eliminarRegion')->middleware('auth:usuarios');

//ciudades
Route::get('/ciudades/{id}',[CiudadesController::class,'ciudades'])->name('ciudades')->middleware('auth:usuarios');
Route::post('/crearCiudad/{id}',[CiudadesController::class,'crearCiudad'])->name('crearCiudad')->middleware('auth:usuarios');
Route::get('/eliminarCiudad/{id}',[CiudadesController::class,'eliminarCiudad'])->name('eliminarCiudad')->middleware('auth:usuarios');
Route::get('/ciudadesfiltrar/{id}',[CiudadesController::class,'ciudadesfiltrar'])->name('ciudadesfiltrar')->middleware('auth:usuarios');

//Marcas
Route::get('/marcas',[MarcasController::class,'marcas'])->name('marcas')->middleware('auth:usuarios');
Route::post('/CrearMarca',[MarcasController::class,'CrearMarca'])->name('CrearMarca')->middleware('auth:usuarios');
Route::get('/marcasfiltrar',[MarcasController::class,'marcasfiltrar'])->name('marcasfiltrar')->middleware('auth:usuarios');
Route::get('/eliminarMarca/{id}',[MarcasController::class,'eliminarMarca'])->name('eliminarMarca')->middleware('auth:usuarios');

//Materiales
Route::get('/materiales',[MaterialesController::class,'materiales'])->name('materiales')->middleware('auth:usuarios');
Route::post('/crearMaterial',[MaterialesController::class,'crearMaterial'])->name('crearMaterial')->middleware('auth:usuarios');
Route::get('/materialesfiltrar',[MaterialesController::class,'materialesfiltrar'])->name('materialesfiltrar')->middleware('auth:usuarios');
Route::get('/modificarMaterial/{id}',[MaterialesController::class,'modificarMaterial'])->name('modificarMaterial')->middleware('auth:usuarios');
Route::post('/updateMaterial/{id}',[MaterialesController::class,'updateMaterial'])->name('updateMaterial')->middleware('auth:usuarios');
Route::get('/eliminarMateriales/{id}',[MaterialesController::class,'eliminarMaterial'])->name('eliminarMaterial')->middleware('auth:usuarios');

//CantidadMaterial
Route::get('/cantidad/{id}',[CantidadController::class,'cantidad'])->name('cantidad')->middleware('auth:usuarios');
Route::post('/agregarCantidad/{id}',[CantidadController::class,'agregarCantidad'])->name('agregarCantidad')->middleware('auth:usuarios');
Route::post('/eliminarCantidad/{id}',[CantidadController::class,'eliminarCantidad'])->name('eliminarCantidad')->middleware('auth:usuarios');

//fotos-y-descripcion-Material
Route::get('/FotosDescripcion/{id}',[FotosDescripcionController::class,'FotosDescripcion'])->name('FotosDescripcion')->middleware('auth:usuarios');

//fotos
Route::post('/agregarFoto/{id}',[FotosDescripcionController::class,'agregarFoto'])->name('agregarFoto')->middleware('auth:usuarios');
Route::get('/eliminarFoto/{id}',[FotosDescripcionController::class,'eliminarFoto'])->name('eliminarFoto')->middleware('auth:usuarios');

//descripcion
Route::post('/agregarDescripcion/{id}',[FotosDescripcionController::class,'agregarDescripcion'])->name('agregarDescripcion')->middleware('auth:usuarios');
Route::get('/eliminarDescripcion/{id}',[FotosDescripcionController::class,'eliminarDescripcion'])->name('eliminarDescripcion')->middleware('auth:usuarios');

//Ofertas
Route::get('/Ofertas',[Ofertascontroller::class,'Ofertas'])->name('Ofertas')->middleware('auth:usuarios');
Route::post('crearOferta',[OfertasController::class,'crearOferta'])->name('crearOferta')->middleware('auth:usuarios');
Route::get('/ofertasFiltrar',[OfertasController::class,'ofertasFiltrar'])->name('ofertasFiltrar')->middleware('auth:usuarios');
Route::get('/modificarOferta/{id}',[OfertasController::class,'modificarOferta'])->name('modificarOferta')->middleware('auth:usuarios');
Route::put('/updateOferta/{id}', [OfertasController::class, 'updateOferta'])->name('updateOferta')->middleware('auth:usuarios');
Route::get('/eliminarOferta/{id}',[OfertasController::class,'eliminarOferta'])->name('eliminarOferta')->middleware('auth:usuarios');

//MaterialOferta
Route::get('/materialOferta/{id}',[matOfertasController::class,'materialOferta'])->name('materialOferta')->middleware('auth:usuarios');
Route::post('/crearMatOf/{id}',[matOfertasController::class,'crearMatOf'])->name('crearMatOf')->middleware('auth:usuarios');
Route::get('/eliminarmatOferta/{id}',[matOfertasController::class,'eliminarmatOferta'])->name('eliminarmatOferta')->middleware('auth:usuarios');

//Funcion de MaterialOferta
Route::get('/tomarCategorias/{id}', [matOfertasController::class, 'tomarCategorias'])->name('tomarCategorias')->middleware('auth:usuarios');
Route::get('/materialesPorCategoriaMarca/{idCategoria}/{idMarca}', [matOfertasController::class, 'materialesPorCategoriaMarca'])->name('materialesPorCategoriaMarca')->middleware('auth:usuarios');
Route::get('/materiales/{idCategoria}', [matOfertasController::class, 'materiales'])->name('materiales')->middleware('auth:usuarios');

//Entregas

Route::get('/entregas',[entregasController::class,'entregas'])->name('entregas')->middleware('auth:usuarios');
Route::get('/verDetallesEntrega/{id}',[entregasController::class,'verDetallesEntrega'])->name('verDetallesEntrega')->middleware('auth:usuarios');
Route::get('/realizarEntrega/{id}',[entregasController::class,'realizarEntrega'])->name('realizarEntrega')->middleware('auth:usuarios');




//Tienda

Route::get('/', [TiendaController::class, 'Tienda'])->name('home');



//Registrar Cliente

Route::get('/Registrar',[ClienteController::class,'Registrar'])->name('Registrar');
Route::get('/tomarRegiones/{id}', [ClienteController::class, 'tomarRegiones'])->name('tomarRegiones');
Route::get('/tomarCiudades/{id}', [ClienteController::class, 'tomarCiudades'])->name('tomarCiudades');
Route::post('/registrarCliente',[ClienteController::class, 'registrarCliente'])->name('registrarCliente');


//Inicio sesion Cliente

Route::post('/IniciarSesionCliente',[ClienteController::class,'IniciarSesionCliente'])->name('IniciarSesionCliente');
Route::get('/cerrarSesion',[ClienteController::class,'cerrarSesion'])->name('cerrarSesion');
Route::post('/recuperarPass',[ClienteController::class,'recuperarPass'])->name('recuperarPass');



//Datos protegidos solo para los que tienen sesión iniciada 'Cliente'


Route::middleware(['auth:cliente'])->group(function () {

    // Rutas para clientes autenticados
    Route::get('/misDatos', [ClienteController::class, 'misDatos'])->name('misDatos');
    Route::post('/actualizarCliente',[ClienteController::class,'actualizarCliente'])->name('actualizarCliente');
});


//productos

Route::get('/productos/{n?}', [TiendaController::class, 'productos'])->name('productos');
Route::post('/filtrarProductos/{n?}', [TiendaController::class, 'filtrarProductos'])->name('filtrarProductos');

Route::get('/verDetalle/{id}',[TiendaController::class,'verDetalle'])->name('verDetalle');

Route::get('/verDetalleMaterial/{id}',[TiendaController::class,'verDetalleMaterial'])->name('verDetalleMaterial');

//productos con oferta
Route::get('/verDetalle/{id}',[TiendaController::class,'verDetalle'])->name('verDetalle');


//Carrito de compra y compra
Route::get('/verCarrito',[CarritoCompraController::class,'verCarrito'])->name('verCarrito');
Route::post('/agregarCarrito', [CarritoCompraController::class, 'agregarCarrito'])->name('agregarCarrito');
Route::post('/agregarCarritoOferta', [CarritoCompraController::class, 'agregarCarritoOferta'])->name('agregarCarritoOferta');
Route::post('/eliminarCarrito/{id}', [CarritoCompraController::class, 'eliminarCarrito'])->name('eliminarCarrito');
Route::post('/actualizarCantidad', [CarritoCompraController::class, 'actualizarCantidad'])->name('actualizarCantidad');
Route::post('/actualizarCantidadOferta', [CarritoCompraController::class, 'actualizarCantidadOferta'])->name('actualizarCantidadOferta');
Route::post('/eliminarCarritoOferta/{id}',[CarritoCompraController::class,'eliminarCarritoOferta'])->name('eliminarCarritoOferta');

//Pagar

Route::get('/pagar', [pagarController::class, 'pagar'])->name('pagar');
Route::get('/confirmar_pago', [pagarController::class, 'confirmar_pago'])->name('confirmar_pago');

//Mis Compras


Route::get('/DetallesMisCompras/{id}',[ComprasController::class,'DetallesMisCompras'])->name('DetallesMisCompras');
Route::get('/MisCompras',[ComprasController::class,'MisCompras'])->name('MisCompras');














