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

    public function store(Request $request, Lead $lead)
    {
        $validated = $request->validate($this->validation, $this->messages);
        //$lead = Lead::where('id', $id)->firstOrFail();

        $validated['lead_id'] = $lead->id;

        $pago = Pago::create($validated);

        if (!empty($request->monto_total)) {
            $lead->update([
                'monto_inscripcion' => $request->monto_total
            ]);
        }

        return response()->json($pago);
    }
}
