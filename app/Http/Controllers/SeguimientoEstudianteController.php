<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\SeguimientoEstudiante;
use App\Models\Estudiante;
use App\Models\SeguimientoDesercion;
use App\Models\SeguimientoEstado;
use App\Models\User;
use App\Models\SeguimientoTipo;
use App\Models\HistoryLead;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class SeguimientoEstudianteController extends Controller
{

    private $validation = [
        'seguimiento_estado_id' => 'required|exists:seguimiento_estados,id',
        'usuario_id' => 'nullable|exists:usuarios,id',
        'motivo' => 'nullable|string|max:255',
        'observaciones' => 'nullable|string|max:750'
    ];

    private $messages = [
        'seguimiento_estado_id.required' => 'El estado es obligatorio.'
    ];

    public function index()
    {
        $user = Auth::user();
        
        return Inertia::render('Dropoffs/Index', [
            'estados' => SeguimientoEstado::orderBy('orden')->get(),
            'usuarios' => ($user->rol === 'ASESOR') ? [] : User::where('rol', '!=', 'SUPERADMIN')->get(),
            'seguimientoTipos' => SeguimientoTipo::all(),
            'auth' => [
                'user' => $user
            ]
        ]);
    }

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
        $seguimientoTipo = $request->get('seguimientoTipo', '');

        $fecha_desde = $request->get('fecha_desde', '');
        $fecha_hasta = $request->get('fecha_hasta', '');

        $user = Auth::user();

        $query = SeguimientoEstudiante::with([
            //'sede',
            //'carrera',
            'abandonosDeserciones.seguimientoTipo',
            'seguimientoEstado',
            'usuario',
            'estudiante'
        ]);

        // Si es asesor, solo ve sus leads
        if ($user->rol === 'ASESOR') {
            $query->where('usuario_id', $user->id);
        }

        $query->Join('estudiantes', 'seguimiento_estudiantes.estudiante_id', '=', 'estudiantes.id');
        $query->Join('abandonos_deserciones', 'abandonos_deserciones.seguimiento_estudiante_id', '=', 'seguimiento_estudiantes.id');
        $query->Join('seguimiento_tipos', 'seguimiento_tipos.id', '=', 'abandonos_deserciones.seguimiento_tipo_id');

        if (!empty($filter)) {
            $filterStr = '%' . $filter . '%';

            $query->where(function ($q) use ($filterStr) {
                $q->where('estudiantes.nombres', 'like', $filterStr)
                ->orWhere('estudiantes.apellidos', 'like', $filterStr);
            });
        }

        if (!empty($seguimientoTipo)) {
            $query->where(function ($q) use ($seguimientoTipo) {
                $q->where('seguimiento_tipos.id', $seguimientoTipo);
            });
        }

        // 🎯 Filtros específicos
        if (!empty($estado)) $query->where('seguimiento_estado_id', $estado);

        if (!empty($usuario)) $query->where('usuario_id', $usuario);

        if (!empty($fecha_desde)) $query->where('ultimo_contacto', '>=', $fecha_desde);

        if (!empty($fecha_hasta)) $query->where('ultimo_contacto', '<=', $fecha_hasta);

        switch ($orderBy) {
            case 'carrera':
                $query->leftJoin('carreras', 'seguimiento_estudiantes.carrera_id', '=', 'carreras.id')
                    ->orderBy('carreras.nombre', $orderDirection);
                break;

            case 'usuario':
                $query->leftJoin('usuarios', 'seguimiento_estudiantes.usuario_id', '=', 'usuarios.id')
                    ->orderBy('usuarios.nombre', $orderDirection);
                break;

            case 'estado':
                $query->leftJoin('seguimiento_estados', 'seguimiento_estudiantes.seguimiento_estado_id', '=', 'seguimiento_estados.id')
                    ->orderBy('seguimiento_estados.nombre', $orderDirection);
                break;

            case 'sede':
                $query->leftJoin('sedes', 'seguimiento_estudiantes.sede_id', '=', 'sedes.id')
                    ->orderBy('sedes.nombre', $orderDirection);
                break;

            case 'tipo':
                $query->leftJoin('seguimiento_tipos', 'seguimiento_estudiantes.seguimiento_tipo_id', '=', 'seguimiento_tipos.id')
                    ->orderBy('seguimiento_tipos.nombre', $orderDirection);
                break;

            case 'nombre':
                $query->orderBy('estudiantes.nombres', $orderDirection);
                break;

            default:
                $query->orderBy($orderBy, $orderDirection);
                break;
        }

        $query->select('seguimiento_estudiantes.*');

        $dropoffs = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($dropoffs);
    }

    public function edit(int $seguimientoEstudianteId)
    {
        return Inertia::render('Dropoffs/Edit', [
            'seguimiento_estudiante' => SeguimientoEstudiante::where('id', $seguimientoEstudianteId)->with([
                'abandonosDeserciones.seguimientoTipo',
                'abandonosDeserciones.sede',
                'abandonosDeserciones.carrera',
                'abandonosDeserciones.seguimientoDeserciones.desercionTipo',
                'seguimientoEstado',
                'usuario',
                'estudiante',
                'seguimientos.usuario'
            ])->firstOrFail(),
            'estados' => SeguimientoEstado::where('id', '!=', 6)->orderBy('orden')->get(),
            'usuarios' => User::where('activo', 1)->where('rol', '!=', 'SUPERADMIN')->get(),
            'history_seguimiento_estudiante' => HistoryLead::where('seguimiento_estudiante_id', $seguimientoEstudianteId)->orderBy('created_at', 'asc')->get()
        ]);
    }

    public function update(Request $request, int $seguimientoEstudianteId)
    {
        $user = Auth::user();
        $seguimiento_estudiante= SeguimientoEstudiante::where('id', $seguimientoEstudianteId)->firstOrFail();

        if ($user->rol === 'ASESOR' && $seguimiento_estudiante->usuario_id != $user->id) abort(403);

        $validated = $request->validate($this->validation, $this->messages);
        $mensajes = [];

        if ($seguimiento_estudiante->seguimiento_estado_id != $validated['seguimiento_estado_id']) $mensajes[] = 'el estado a ' . SeguimientoEstado::where('id', $validated['seguimiento_estado_id'])->firstOrFail()->nombre;
        if ($seguimiento_estudiante->usuario_id != $validated['usuario_id']) $mensajes[] = 'el asesor a ' . User::where('id', $validated['usuario_id'])->firstOrFail()->nombre;
        
        $seguimiento_estudiante->update($validated);

        HistoryLead::create([
            'usuario_id' => Auth::user()->id,
            'seguimiento_estudiante_id' => $seguimiento_estudiante->id,
            'mensaje' => Auth::user()->nombre . ' modificó ' . implode(', ', $mensajes) . '.'
        ]);

        return redirect()->route('dropoffs.index')->with('success', 'Deserción/Abandono actualizado');
    }

    public function syncDropoffs(Request $request)
    {
        $user = Auth::user();

        if ($user->rol != 'JEFE' && $user->rol != 'SUPERADMIN') abort(403);

        return response()->json($this->executeDropoffsSync());
    }

    private function executeDropoffsSync()
    {
        $exitCode = Artisan::call('sync:seguimientos');
        return [
            'success' => $exitCode === 0,
            'output' => Artisan::output()
        ];
    }

}
