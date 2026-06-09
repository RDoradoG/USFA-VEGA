<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Sede;
use App\Models\Carrera;
use App\Models\Horario;
use App\Models\Estado;
use App\Models\User;
use App\Models\Fuente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadController extends Controller
{

    private $validation = [
        'nombre' => 'required|string|max:255',
        'apellido_paterno' => 'required|string|max:255',
        'apellido_materno' => 'required|string|max:255',
        'celular' => 'required|string|max:20',
        'genero' => 'nullable|string',
        'ciudad' => 'nullable|string',

        'sede_id' => 'required|exists:sedes,id',
        'carrera_id' => 'nullable|exists:carreras,id',
        'horario_id' => 'nullable|exists:horarios,id',

        'estado_id' => 'required|exists:estados,id',
        'usuario_id' => 'nullable|exists:usuarios,id',
        'fuente_id' => 'required|exists:fuentes,id',

        'interes_nivel' => 'required|in:Alto,Medio,Bajo',
        'observaciones' => 'nullable|string',
    ];

    private $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
        'apellido_materno.required' => 'El apellido materno es obligatorio.',
        'celular.required' => 'El celular es obligatorio.',
        'sede_id.required' => 'La sede es obligatoria.',
        'estado_id.required' => 'El estado es obligatorio.',
        'fuente_id.required' => 'La fuente es obligatoria.',
        'interes_nivel.required' => 'El interes es obligatorio.'
    ]; 

    public function index()
    {
        $user = auth()->user();

        $query = Lead::with([
            'sede',
            'carrera',
            'horario',
            'estado',
            'usuario',
            'fuente'
        ]);

        // Si es asesor, solo ve sus leads
        if ($user->rol === 'ASESOR') {
            $query->where('usuario_id', $user->id);
        }
        
        return Inertia::render('Leads/Index', [
            'leads' => $query->latest()->paginate(10),
            'estados' => Estado::orderBy('orden')->get(),
            'usuarios' => User::all(),
            'fuentes' => Fuente::all(),
            'auth' => [
                'user' => $user
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Leads/Create', [
            'sedes' => Sede::all(),
            'carreras' => Carrera::all(),
            'horarios' => Horario::all(),
            'estados' => Estado::orderBy('orden')->get(),
            'usuarios' => User::all(),
            'fuentes' => Fuente::all(),
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->rol !== 'JEFE') {
            abort(403);
        }

        $validated = $request->validate($this->validation, $this->messages);

        //unset($validated['fecha_registro']);

        Lead::create($validated);

        return redirect()->route('leads.index')->with('success', 'Lead creado');
    }

    public function edit(Lead $lead)
    {
        return Inertia::render('Leads/Edit', [
            'lead' => $lead->load([
                'sede',
                'carrera',
                'horario',
                'estado',
                'usuario',
                'fuente',
                'seguimientos.usuario'
            ]),
            'sedes' => Sede::all(),
            'carreras' => Carrera::all(),
            'horarios' => Horario::all(),
            'estados' => Estado::orderBy('orden')->get(),
            'usuarios' => User::all(),
            'fuentes' => Fuente::all(),
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $user = auth()->user();

        if ($user->rol === 'ASESOR' && $lead->usuario_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate($this->validation, $this->messages);

        $lead->update($validated);

        return redirect()->route('leads.index')->with('success', 'Lead actualizado');
    }

    public function destroy(Lead $lead)
    {
        if (auth()->user()->rol !== 'JEFE') {
            abort(403);
        }

        $lead->delete();

        return redirect()->back()->with('success', 'Lead eliminado');
    }
}