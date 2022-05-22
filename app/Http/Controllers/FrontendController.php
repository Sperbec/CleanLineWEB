<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Facturas;
use App\Models\Opciones_definidas;
class FrontendController extends Controller
{

    
    public function nuevos_productos()
    {
        $producto=Producto::orderBy('id_producto','desc')->paginate(5);
        
        return view('frontend.inicio',compact('producto'));
    }

    public function categoria_aseo_personal()
    {
        $producto_aseo_personal = Producto::where('id_categoria','2')->paginate(12);

        return view('frontend.aseo_personal',compact('producto_aseo_personal'));
    }

    public function categoria_aseo_general()
    {
        $producto_aseo_general = Producto::where('id_categoria','1')->paginate(12);

        return view('frontend.aseo_general',compact('producto_aseo_general'));
    }

    public function detalle(Producto $producto)
    {
        //dd($producto);
        return view('frontend.detalle',compact('producto'));
    }

    public function crear()
    {
        return view('frontend.crear');
    }

    public function store(Request $request)
    {
        //$producto =Producto::create($request->all());

        $request->validate([
            'nombre'=> 'required',
            'descripcion' => 'required',
            'sku'=> 'required',
            'estado' => 'required',
            'precio' => 'required',
            'cantidad_existencia'=> 'required',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg,jfif|max:2048',
            'id_categoria'=> 'required',
        ]);
       //dd('ok');
        
        $salidaimagen =$request->all();

        if($imagen=$request->file('imagen'))
        {
            $rutaGuardarImg = 'imagen/';
            $imagenProducto = date('YmdHis'). "." .$imagen->getClientOriginalExtension();
            $imagen->move($rutaGuardarImg, $imagenProducto);
            $salidaimagen['imagen'] = "$imagenProducto";
        }

        Producto::create($salidaimagen);
        
        return redirect()->route('inicio');
    }

    //carrito de Compras
     /**
     * Write code on Method
     *
     * @return response()
     */
     public function carrito()
    {
        $carrito = session()->get('carrito');
        
        return view('frontend.carrito',compact('carrito'));
    } 
  
    /**
     * Write code on Method
     *
     * @return response()
     */
     public function añadir_carrito($id)
    {
        $producto = Producto::findOrFail($id);
        $carrito = session()->get('carrito', []);
        //si el carrito tiene un producto con el mismo id
        if(isset($carrito[$id])) {
            $carrito[$id]['quantity']++;
        } else {
            $carrito[$id] = [
                "id" => $producto->id_producto,
                "nombre" => $producto->nombre,
                "quantity" => 1,
                "precio" => $producto->precio,
                "imagen" => $producto->imagen,
                "descripcion" => $producto->descripcion,
                "sku" => $producto->sku,
                "estado" => $producto->estado,
                "cantidad_existencia" => $producto->cantidad_existencia,
                "id_categoria" => $producto->id_categoria
                
            ];
        }
        session()->put('carrito', $carrito);
        
        return redirect()->back()->with('success', 'Producto Añadido al carrito!');
    } 
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $carrito = session()->get('carrito');
            $carrito[$request->id]["quantity"] = $request->quantity;
            session()->put('carrito', $carrito);
            session()->flash('success', 'Carrito actualizado');
        }
        //return redirect()->route('carrito');
    }   
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function eliminar(Request $request)
    {
        if($request->id) {
            $carrito = session()->get('carrito');
            if(isset($carrito[$request->id])) {
                unset($carrito[$request->id]);
                session()->put('carrito', $carrito);
            }
            session()->flash('success', 'Producto eliminado');
        } return redirect()->back()->with('success', 'Producto Añadido al carrito!');
    }

    public function detalle_compra(Request $request)
    {
        $comentario_facturas = Facturas::all();
        $opcion_entregas = Opciones_definidas::where('variable', '00tipoentrega')->get();
        $opcion_pagos = Opciones_definidas::where('variable', '00tipopago')->get();
        $carrito = session()->get('carrito');
        session()->put('carrito', $carrito);
       return view('facturas/detalle',compact('opcion_entregas','opcion_pagos','comentario_facturas'));
    }  

    
    
}   