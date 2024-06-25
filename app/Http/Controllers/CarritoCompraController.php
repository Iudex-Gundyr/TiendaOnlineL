<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CarritoMaterial;
use App\Models\CarritoOferta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Materiales; // Asegúrate de importar el modelo correcto

class CarritoCompraController extends Controller
{
    public function verCarrito()
    {
        $clienteId = Auth::guard('cliente')->id();
        if (!$clienteId) {
            return back()->with('error','Debes iniciar sesión para armar y ver tu carrito ♥');
        }
        $materiales = $this->tablaCarrito($clienteId);
        $totalPagar = $this->pagarCarrito($clienteId);
        $materialesOferta = $this->tablaCarritoOferta($clienteId);
        $totalPagarOferta = $this->pagarCarritoOferta($clienteId);

        return view('Tienda/Carrito/Carrito',compact('materiales','totalPagar','materialesOferta','totalPagarOferta'));
    }
    public function tablaCarritoOferta($clienteId)
    {
        $ofertas = DB::table('material_oferta AS mo')
        ->select(
            'mo.*',
            'mo.id AS idmatof',
            'o.*',
            'o.fotografia', // Incluir o.fotografia en la selección
            'ma.*',
            'cof.cantidad AS cantof',
            DB::raw('ma.valorm * (1 - o.porcentajeof / 100) AS valor_con_descuento'),
            DB::raw('(ma.valorm * (1 - o.porcentajeof / 100)) * cof.cantidad AS totalPagar'),
            'cof.id AS carrito_id',
            DB::raw('mo.cantidad - COALESCE(suma_cantidad.total_compra, 0) AS cantidad_en_compra')
        )
        ->join('ofertas AS o', 'o.id', '=', 'mo.fk_id_oferta')
        ->join('materiales AS ma', 'ma.id', '=', 'mo.fk_id_material')
        ->join('carrito_oferta AS cof', 'mo.id', '=', 'cof.fk_id_materialoferta')
        ->leftJoin(DB::raw('(SELECT
                                 fk_id_Moferta,
                                 SUM(CASE 
                                         WHEN fk_id_estadoel = 2 AND TIMESTAMPDIFF(HOUR, created_at, NOW()) < 1 THEN cantidad
                                         WHEN fk_id_estadoel = 1 THEN cantidad
                                         ELSE 0
                                     END) AS total_compra
                             FROM compra_oferta
                             GROUP BY fk_id_Moferta) AS suma_cantidad'), 'suma_cantidad.fk_id_Moferta', '=', 'mo.id')
        ->where('mo.fk_id_estadoel', 1)
        ->where('cof.fk_id_cliente', $clienteId)
        ->groupBy(
            'mo.id',
            'mo.fk_id_oferta',
            'o.id',
            'o.nombreof',
            'o.fotografia', // Agregar o.fotografia al GROUP BY
            'mo.fk_id_material',
            'o.porcentajeof',
            'ma.valorm',
            'cof.cantidad',
            'cof.id',
            'suma_cantidad.total_compra',
            'mo.cantidad',
            'mo.fk_id_estadoel',
            'o.fechaexp',
            'o.fk_id_estadoel',
            'ma.id',
            'ma.nombrem',
            'ma.codigob',
            'ma.fechac',
            'ma.fechaupd',
            'ma.fk_id_categorias',
            'ma.fk_id_marcas',
            'ma.fk_id_usuariocre',
            'ma.fk_id_usuarioupd',
            'ma.fk_id_estadoel'
        )
        ->get();
    
    
    
        
    
            return $ofertas;

    }
    
    

    public function tablaCarrito($clienteId)
    {
        try {
            // Construir la consulta utilizando Query Builder
            $resultados = DB::table('materiales')
                ->select(
                    'materiales.id',
                    'materiales.codigob',
                    'materiales.nombrem',
                    'materiales.valorm',
                    'cm.id as carrito_id',
                    'cm.cantidad',
                    'cm.fk_id_material',
                    'cm.fk_id_cliente',
                    DB::raw('materiales.valorm * cm.cantidad AS valor_total'),
                    DB::raw('SUM(COALESCE(c.total_cantidad, 0) - COALESCE(co.total_cantidad, 0) - COALESCE(cm2.total_cantidad, 0)) AS cantidad_restante')
                )
                ->join('carrito_material AS cm', 'cm.fk_id_material', '=', 'materiales.id')
                ->leftJoin(DB::raw('(SELECT fk_id_materiales, SUM(cantidad) AS total_cantidad FROM cantidadmat GROUP BY fk_id_materiales) AS c'), 'materiales.id', '=', 'c.fk_id_materiales')
                ->leftJoin(DB::raw('(SELECT fk_id_material, SUM(cantidad) AS total_cantidad FROM material_oferta WHERE fk_id_estadoel = 1 GROUP BY fk_id_material) AS co'), 'materiales.id', '=', 'co.fk_id_material')
                ->leftJoin(DB::raw('(SELECT fk_id_materiales, SUM(CASE WHEN (created_at >= NOW() - INTERVAL 1 HOUR OR fk_id_estadoel = 1) THEN cantidad ELSE 0 END) AS total_cantidad FROM compra_materiales GROUP BY fk_id_materiales) AS cm2'), 'materiales.id', '=', 'cm2.fk_id_materiales')
                ->where('materiales.fk_id_estadoel', 1)
                ->where('cm.fk_id_cliente', $clienteId)
                ->groupBy('materiales.id', 'materiales.codigob', 'materiales.nombrem', 'materiales.valorm', 'cm.id', 'cm.cantidad', 'cm.fk_id_material', 'cm.fk_id_cliente')
                ->orderByDesc('materiales.id')
                ->get();
            
    
            return $resultados;
        } catch (\Exception $e) {
            // Registra el error en los logs
            Log::error('Error al obtener tabla de carrito: ' . $e->getMessage());
            // Devuelve un array vacío o null en caso de error
            return [];
        }
    }

    public function pagarCarritoOferta($clienteId)
    {
        try {
            // Realizar la consulta para obtener el total a pagar
            $totalPagar =  DB::table('material_oferta')
            ->selectRaw('COALESCE(SUM((ma.valorm * (1 - o.porcentajeof / 100)) * cof.cantidad), 0) AS totalPagar_sumado')
            ->join('ofertas AS o', 'o.id', '=', 'material_oferta.fk_id_oferta')
            ->join('materiales AS ma', 'ma.id', '=', 'material_oferta.fk_id_material')
            ->join('carrito_oferta AS cof', 'material_oferta.id', '=', 'cof.fk_id_materialoferta')
            ->where('material_oferta.fk_id_estadoel', 1)
                ->where('cof.fk_id_cliente', $clienteId)
                ->first();
    
            // Si se encuentra el resultado, devuelve el totalPagar sumado como número
            if ($totalPagar) {
                return $totalPagar->totalPagar_sumado;
            } else {
                // Si totalPagar es NULL, devuelve 0
                return 0;
            }
        } catch (\Exception $e) {
            // Registra el error en los logs
            Log::error('Error al calcular el total a pagar del carrito: ' . $e->getMessage());
    
            // Devuelve null en caso de error
            return null;
        }
    }
    
    

    public function pagarCarrito($clienteId)
    {
        try {
            $totalPagar = DB::table('materiales')
                ->selectRaw('SUM(materiales.valorm * cm.cantidad) AS total_valor_total')
                ->join('carrito_material AS cm', 'cm.fk_id_material', '=', 'materiales.id')
                ->leftJoin(DB::raw('(SELECT fk_id_materiales, SUM(cantidad) AS total_cantidad FROM cantidadmat GROUP BY fk_id_materiales) AS c'), 'materiales.id', '=', 'c.fk_id_materiales')
                ->leftJoin(DB::raw('(SELECT fk_id_material, SUM(cantidad) AS total_cantidad FROM material_oferta WHERE fk_id_estadoel = 1 GROUP BY fk_id_material) AS co'), 'materiales.id', '=', 'co.fk_id_material')
                ->leftJoin(DB::raw('(SELECT fk_id_materiales, SUM(CASE WHEN (created_at >= NOW() - INTERVAL 1 HOUR OR fk_id_estadoel = 1) THEN cantidad ELSE 0 END) AS total_cantidad FROM compra_materiales GROUP BY fk_id_materiales) AS cm2'), 'materiales.id', '=', 'cm2.fk_id_materiales')
                ->where('materiales.fk_id_estadoel', 1)
                ->where('cm.fk_id_cliente', $clienteId)
                ->groupBy('cm.fk_id_cliente')
                ->first();
    
            return $totalPagar->total_valor_total ?? 0; // Si no se encuentra ningún resultado, retornar cero
        } catch (\Exception $e) {
            // Manejar cualquier excepción que ocurra
            Log::error('Error al calcular el total a pagar del carrito: ' . $e->getMessage());
            return 0; // En caso de error, retornar cero
        }
    }
    public function actualizarCantidad(Request $request)
    {
        try {
            // Obtener el ID del cliente autenticado
            $clienteId = Auth::guard('cliente')->id();

            $carritoId = $request->input('carrito_id');
            $nuevaCantidad = $request->input('nueva_cantidad');

            // Buscar el carrito que se quiere actualizar
            $carrito = CarritoMaterial::where('id', $carritoId)
                                      ->where('fk_id_cliente', $clienteId)
                                      ->first();

            if ($carrito) {
                // Actualizar la cantidad
                $carrito->cantidad = $nuevaCantidad;
                $carrito->save();

                return response()->json(['success' => true], 200);
            } else {
                return response()->json(['error' => 'Carrito no encontrado o no autorizado'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la cantidad: ' . $e->getMessage()], 500);
        }
    }

    public function actualizarCantidadOferta(Request $request)
    {
        try {
            $carritoId = $request->input('carrito_id');
            $nuevaCantidad = $request->input('nueva_cantidad');
    
            // Obtener el ID del cliente autenticado
            $clienteId = auth()->guard('cliente')->id();
    
            // Validar si el carrito existe y pertenece al usuario autenticado
            $carritoOferta = CarritoOferta::where('id', $carritoId)
                                          ->where('fk_id_cliente', $clienteId)
                                          ->first();
            if (!$carritoOferta) {
                return response()->json(['error' => 'El carrito de oferta no existe o no pertenece al usuario'], 404);
            }
    
            // Actualizar la cantidad del carrito de oferta
            $carritoOferta->cantidad = $nuevaCantidad;
            $carritoOferta->save();
    
            // Devolver una respuesta exitosa
            return response()->json(['message' => 'Cantidad actualizada correctamente'], 200);
        } catch (\Exception $e) {
            // Capturar y manejar cualquier error
            return response()->json(['error' => 'Error al actualizar la cantidad del carrito de oferta'], 500);
        }
    }
    public function agregarCarrito(Request $request)
    {
        try {
            // Validar los datos recibidos
            $request->validate([
                'fk_id_material' => 'required|integer|exists:materiales,id',
                'cantidad' => 'required|integer|min:1'
            ]);
    
            // Obtener el ID del cliente autenticado
            $clienteId = Auth::guard('cliente')->id();
    
            // Verificar si ya existe un registro para el cliente y el material
            $carritoExistente = CarritoMaterial::where('fk_id_cliente', $clienteId)
                ->where('fk_id_material', $request->fk_id_material)
                ->first();
    
            if ($carritoExistente) {
                // Si ya existe, actualiza la cantidad en lugar de crear uno nuevo
                $carritoExistente->cantidad = $request->cantidad;
                $carritoExistente->save();
            } else {
                // Si no existe, crea un nuevo registro en carrito_material
                CarritoMaterial::create([
                    'fk_id_cliente' => $clienteId,
                    'fk_id_material' => $request->fk_id_material,
                    'cantidad' => $request->cantidad
                ]);
            }
    
            // Responder con un mensaje de éxito
            return response()->json(['message' => 'Producto agregado al carrito.'], 200);
    
        } catch (\Exception $e) {
            // Capturar cualquier excepción y devolver un mensaje de error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function agregarCarritoOferta(Request $request)
    {
        try {
            // Validar los datos del formulario si es necesario
            $request->validate([
                'cantidad' => 'required|integer|min:1',
                'fk_id_Moferta' => 'required|exists:material_oferta,id',
            ]);
    
            // Obtener el ID del cliente autenticado
            $clienteId = Auth::guard('cliente')->id();
    
            // Verificar si ya existe una entrada para este cliente y esta oferta en el carrito
            $existente = CarritoOferta::where('fk_id_cliente', $clienteId)
                                      ->where('fk_id_materialoferta', $request->fk_id_Moferta)
                                      ->exists();
    
            if ($existente) {
                // Si ya existe una entrada para esta oferta y cliente, devolver un mensaje de error
                return response()->json(['error' => 'Ya tienes este producto en tu carrito'], 400);
            }
    
            // Crear una nueva instancia de CarritoOferta y asignar los valores
            $carritoOferta = new CarritoOferta();
            $carritoOferta->cantidad = $request->cantidad;
            $carritoOferta->fk_id_cliente = $clienteId;
            $carritoOferta->fk_id_materialoferta = $request->fk_id_Moferta;
            $carritoOferta->save();
    
            // Puedes devolver una respuesta JSON si lo prefieres
            return response()->json(['message' => 'Producto agregado al carrito correctamente'], 200);
        } catch (\Exception $e) {
            // Registra el error en los logs
            Log::error('Error al agregar producto al carrito: ' . $e->getMessage());
    
            // Devuelve una respuesta de error
            return response()->json(['error' => 'Error al agregar producto al carrito. Por favor, inténtalo de nuevo más tarde.'], 500);
        }
    }
    
    public function eliminarCarrito($id)
    {
        try {
            $clienteId = Auth::guard('cliente')->id(); // Obtener el ID del cliente autenticado
    
            // Buscar el registro del carrito
            $carrito = CarritoMaterial::where('id', $id)
                ->where('fk_id_cliente', $clienteId)
                ->first();
    
            if ($carrito) {
                // Eliminar el registro si se encuentra
                $carrito->delete();
                return redirect()->back()->with('success_message', 'Producto eliminado del carrito correctamente.');
            } else {
                return redirect()->back()->with('error', 'Producto no encontrado.');
            }
        } catch (\Exception $e) {
            // Registra el error en los logs
            Log::error('Error al eliminar producto del carrito: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar producto del carrito. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function eliminarCarritoOferta($id)
    {
        try {
            $clienteId = Auth::guard('cliente')->id(); // Obtener el ID del cliente autenticado
    
            // Buscar el registro del carrito
            $carrito = CarritoOferta::where('id', $id)
                ->where('fk_id_cliente', $clienteId)
                ->first();
    
            if ($carrito) {
                // Eliminar el registro si se encuentra
                $carrito->delete();
                return redirect()->back()->with('success_message', 'Producto eliminado del carrito correctamente.');
            } else {
                return redirect()->back()->with('error', 'Producto no encontrado.');
            }
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Error al eliminar producto del carrito. Por favor, inténtalo de nuevo más tarde.');
        }
    }
    
    
}
