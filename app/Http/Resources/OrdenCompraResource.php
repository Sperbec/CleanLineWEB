<?php

namespace App\Http\Resources;

use App\Models\Detalle_orden_compra;
use App\Models\Opciones_definidas;
use App\Models\Orden_compra;
use App\Models\Persona;
use App\Models\Proveedor;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdenCompraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $orden_compra = Orden_compra::all()->find($this->id_orden);

        $detalles = Detalle_orden_compra::where('id_orden', $this->id_orden)->get();

        return [
            'id_orden' => $this->id_orden,
            'codigo' => $this->codigo,
            'fecha' => $this->fecha,
            'total' => number_format($orden_compra->total, 2, ',', '.'),
            'valor_iva' => number_format($orden_compra->valor_iva, 2, ',', '.'),
            'subtotal' => number_format($orden_compra->subtotal, 2, ',', '.'),
            'comentario' => $this->comentario,
            'estado' => (int) $orden_compra->estado,
            'proveedor' => new ProveedorResource(Proveedor::all()->find($orden_compra->id_proveedor)),
            'detalle_orden' => DetalleOrdenCompraResource::collection($detalles),
        ];
    }

    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->header('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
