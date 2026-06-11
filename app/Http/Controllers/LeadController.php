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
use Carbon\Carbon;

class LeadController extends Controller
{

    private $validation = [
        'nombre' => 'required|string|max:255',
        'apellido_paterno' => 'required|string|max:255',
        'apellido_materno' => 'required|string|max:255',
        'celular' => 'required|string|max:20|unique:leads,celular',
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
        'celular.unique' => 'Ya existe un lead con este número de celular.',
        'sede_id.required' => 'La sede es obligatoria.',
        'estado_id.required' => 'El estado es obligatorio.',
        'fuente_id.required' => 'La fuente es obligatoria.',
        'interes_nivel.required' => 'El interes es obligatorio.'
    ]; 

    public function list(Request $request)
    {
  
        // Parámetros con valores por defecto
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $orderBy = $request->get('order_by', 'id');
        $orderDirection = $request->get('order_direction', 'asc');

        $filter = $request->get('filter', '');
        $estado = $request->get('estado', '');
        $usuario = $request->get('usuario', '');
        $interes = $request->get('interes', '');
        $fuente = $request->get('fuente', '');

        $fecha_desde = $request->get('fecha_desde', '');
        $fecha_hasta = $request->get('fecha_hasta', '');

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

        if (!empty($filter)) {
            $filterStr = '%' . $filter . '%';

            $query->where(function ($q) use ($filterStr) {
                $q->where('nombre', 'like', $filterStr)
                ->orWhere('apellido_paterno', 'like', $filterStr)
                ->orWhere('apellido_materno', 'like', $filterStr)
                ->orWhere('celular', 'like', $filterStr)
                ->orWhere('ciudad', 'like', $filterStr);
            });
        }

        // 🎯 Filtros específicos
        if (!empty($estado)) $query->where('estado_id', $estado);

        if (!empty($usuario)) $query->where('usuario_id', $usuario);

        if (!empty($interes)) $query->where('interes_nivel', $interes);

        if (!empty($fuente)) $query->where('fuente_id', $fuente);

        if (!empty($fecha_desde)) $query->where('fecha_registro', '>=', $fecha_desde);

        if (!empty($fecha_hasta)) $query->where('fecha_registro', '<=', $fecha_hasta);

        switch ($orderBy) {
            case 'carrera':
                $query->leftJoin('carreras', 'leads.carrera_id', '=', 'carreras.id')
                    ->orderBy('carreras.nombre', $orderDirection);
                break;

            case 'usuario':
                $query->leftJoin('users', 'leads.usuario_id', '=', 'users.id')
                    ->orderBy('users.nombre', $orderDirection);
                break;

            case 'estado':
                $query->leftJoin('estados', 'leads.estado_id', '=', 'estados.id')
                    ->orderBy('estados.nombre', $orderDirection);
                break;

            default:
                $query->orderBy($orderBy, $orderDirection);
                break;
        }

        $query->select('leads.*');

        $leads = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($leads);
    }

    public function index()
    {
        $user = auth()->user();
        
        return Inertia::render('Leads/Index', [
            'estados' => Estado::orderBy('orden')->get(),
            'usuarios' => ($user->rol === 'ASESOR') ? [] : User::all(),
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
        $validated = $request->validate($this->validation, $this->messages);

        if (auth()->user()->rol !== 'JEFE') $validated['usuario_id'] = auth()->user()->id;

        if (empty($validated['fecha_registro'])) $validated['fecha_registro'] = Carbon::now()->toDateString();

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