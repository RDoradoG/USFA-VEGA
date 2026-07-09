<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeguimientoController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $user = Auth::user();

        // Solo jefe o dueño del lead
        if ($user->rol === 'ASESOR' && $lead->usuario_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'tipo' => 'required|in:LLAMADA,WHATSAPP,EMAIL,OTRO',
            'resultado' => 'required|in:NO_RESPONDE,INTERESADO,NO_INTERESADO,SEGUIMIENTO',
            'observacion' => 'nullable|string'
        ]);

        $lead->seguimientos()->create([
            ...$validated,
            'usuario_id' => $user->id,
            'fecha_contacto' => now()
        ]);

        // actualizar ultimo contacto en lead
        $lead->update([
            'ultimo_contacto' => now()
        ]);

        return back();
    }

    public function destroy(Seguimiento $seguimiento)
    {
        if (Auth::user()->rol !== 'JEFE') {
            abort(403);
        }

        $seguimiento->delete();

        return back();
    }
}