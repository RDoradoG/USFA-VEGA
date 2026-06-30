<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lead;
use App\Models\Pago;

class PagoController extends Controller
{
    private $validation = [
        'monto' => 'required|numeric',
        'numero_factura' => 'required|string',
        'cuf_id' => 'string'
    ];

    private $messages = [
        'monto.required' => 'El monto es obligatorio.',
        'numero_factura.required' => 'el número de factura es obligatorio.'
    ];

    private const STATE_ID = 6;

    public function store(Request $request, Lead $lead)
    {
        $validated = $request->validate($this->validation, $this->messages);
        //$lead = Lead::where('id', $id)->firstOrFail();

        $validated['lead_id'] = $lead->id;

        $pago = Pago::create($validated);

        $new_lead = [];

        if (!empty($request->monto_total)) $new_lead['monto_inscripcion'] = $request->monto_total;
        if ($lead->estado_id != self::STATE_ID) $new_lead['estado_id'] =  self::STATE_ID;

        if (!empty($new_lead)) $lead->update($new_lead);

        return response()->json($pago);
    }
}
