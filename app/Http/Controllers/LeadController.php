<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Sede;
use App\Models\Carrera;
use App\Models\Horario;
use App\Models\Estado;
use App\Models\User;
use App\Models\Fuente;
use App\Models\Promocion;
use App\Models\HistoryLead;
use Illuminate\Http\Request;
use Inertia\Inertia;
use libphonenumber\PhoneNumberUtil;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class LeadController extends Controller
{

    private $validation = [
        'nombre' => 'required|string|max:255',
        'apellido_paterno' => 'nullable|string|max:255|required_without:apellido_materno',
        'apellido_materno' => 'nullable|string|max:255|required_without:apellido_paterno',
        'codigo_pais' => '',
        'celular' => '',
        'genero' => 'nullable|string',
        'ciudad' => 'nullable|string',

        'sede_id' => 'required|exists:sedes,id',
        'carrera_id' => 'nullable|exists:carreras,id',
        'horario_id' => 'nullable|exists:horarios,id',

        'estado_id' => 'required|exists:estados,id',
        'usuario_id' => 'nullable|exists:usuarios,id',
        'fuente_id' => 'required|exists:fuentes,id',
        'promocion_id' => 'nullable|exists:promociones,id',

        'interes_nivel' => 'required|in:Alto,Medio,Bajo',
        'observaciones' => 'nullable|string',
    ];

    private $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'apellido_paterno.required_without' => 'Debes ingresar al menos un apellido.',
        'apellido_materno.required_without' => 'Debes ingresar al menos un apellido.',
        'celular.required' => 'El celular es obligatorio.',
        'celular.unique' => 'Ya existe un lead con este número de celular.',
        'sede_id.required' => 'La sede es obligatoria.',
        'estado_id.required' => 'El estado es obligatorio.',
        'fuente_id.required' => 'La fuente es obligatoria.',
        'interes_nivel.required' => 'El interes es obligatorio.'
    ];

    private const STATE_ID = 5;

    private function getValidations(Request $request, $lead = null)
    {
        if (is_null($lead)) {
            $this->validation['celular'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('leads')
                    ->where(function ($query) use ($request) {
                        return $query->where('codigo_pais', $request->codigo_pais);
                    })
            ];
        } else {
            $this->validation['celular'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('leads')
                    ->ignore($lead->id)
                    ->where(function ($query) use ($request) {
                        return $query->where('codigo_pais', $request->codigo_pais);
                    })
            ];
        }

        return $this->validation;
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
        $interes = $request->get('interes', '');
        $fuente = $request->get('fuente', '');

        $fecha_desde = $request->get('fecha_desde', '');
        $fecha_hasta = $request->get('fecha_hasta', '');

        $user = Auth::user();

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
        $user = Auth::user();
        
        return Inertia::render('Leads/Index', [
            'estados' => Estado::orderBy('orden')->get(),
            'usuarios' => ($user->rol === 'ASESOR') ? [] : User::where('rol', '!=', 'SUPERADMIN')->get(),
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
            'estados' => Estado::where('id', '!=', 6)->orderBy('orden')->get(),
            'usuarios' => User::where('activo', 1)->where('rol', '!=', 'SUPERADMIN')->get(),
            'fuentes' => Fuente::all(),
            'codigos_pais' => $this->getListCodes(),
            'promociones' => Promocion::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->getValidations($request), $this->messages);

        if (Auth::user()->rol !== 'JEFE') $validated['usuario_id'] = Auth::user()->id;

        if (empty($validated['codigo_pais'])) $validated['codigo_pais'] = '591';

        $validated['fecha_registro'] = now();
        $validated['ultimo_contacto'] = now();

        $lead = Lead::create($validated);

        HistoryLead::create([
            'usuario_id' => Auth::user()->id,
            'lead_id' => $lead->id,
            'mensaje' => Auth::user()->nombre . ' creó el nuevo lead.'
        ]);

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
            'estados' => Estado::where('id', '!=', 6)->orderBy('orden')->get(),
            'usuarios' => User::where('activo', 1)->where('rol', '!=', 'SUPERADMIN')->get(),
            'fuentes' => Fuente::all(),
            'codigos_pais' => $this->getListCodes(),
            'promociones' => Promocion::all(),
            'history_lead' => HistoryLead::where('lead_id', $lead->id)->orderBy('created_at', 'asc')->get()
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $user = Auth::user();

        if ($user->rol === 'ASESOR' && $lead->usuario_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate($this->getValidations($request, $lead), $this->messages);

        $mensajes = [];

        if ($lead->estado_id != $validated['estado_id']) $mensajes[] = 'el estado a ' . Estado::where('id', $validated['estado_id'])->firstOrFail()->nombre;
        if ($lead->usuario_id != $validated['usuario_id']) $mensajes[] = 'el asesor a ' . User::where('id', $validated['usuario_id'])->firstOrFail()->nombre;
        if ($lead->fuente_id != $validated['fuente_id']) $mensajes[] = 'la fuente a ' . Fuente::where('id', $validated['fuente_id'])->firstOrFail()->nombre;
        if ($lead->interes_nivel != $validated['interes_nivel']) $mensajes[] = 'el nivel de interés a ' . $validated['interes_nivel'];
        if ($lead->promocion_id != $validated['promocion_id']) $mensajes[] = 'la promoción a ' . Promocion::where('id', $validated['promocion_id'])->firstOrFail()->nombre;
        if ($lead->sede_id != $validated['sede_id']) $mensajes[] = 'la sede a ' . Sede::where('id', $validated['sede_id'])->firstOrFail()->nombre;
        if ($lead->carrera_id != $validated['carrera_id']) $mensajes[] = 'la carrera a ' . Carrera::where('id', $validated['carrera_id'])->firstOrFail()->nombre;
        if ($lead->horario_id != $validated['horario_id']) $mensajes[] = 'el horario a ' . Horario::where('id', $validated['horario_id'])->firstOrFail()->nombre;
        if ($lead->ciudad != $validated['ciudad']) $mensajes[] = 'la ciudad a ' . $validated['ciudad'];
    
        $lead->update($validated);

        HistoryLead::create([
            'usuario_id' => Auth::user()->id,
            'lead_id' => $lead->id,
            'mensaje' => Auth::user()->nombre . ' modificó ' . implode(', ', $mensajes) . '.'
        ]);

        //API
        /*if ($lead->estado_id == self::STATE_ID) {
            $this->sendToPay($last_names, $names, $sex, $id_sede, $id_carrera);
        }*/

        return redirect()->route('leads.index')->with('success', 'Lead actualizado');
    }

    /*private function sendToPay($last_names, $names, $sex, $id_sede, $id_carrera) {
        $response = Http::post('https://ecovir.usfa.edu.bo:49233/api/adhesion/new', [
            'Apellidos' => $last_names,
            'Nombres' => $names,
            'Sexo' => $sex,
            'IdSede' => $id_sede,
            'IdCarrera' => $id_carrera
        ]);

        return $response->json();
    }*/

    public function destroy(Lead $lead)
    {
        if (Auth::user()->rol !== 'JEFE' && Auth::user()->rol !== 'SUPERADMIN') {
            abort(403);
        }

        $lead->delete();

        return redirect()->back()->with('success', 'Lead eliminado');
    }

    private function getListCodes()
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        $regiones = $phoneUtil->getSupportedRegions();

        $codigos = [];

        foreach ($regiones as $region) {
            $codigo = $phoneUtil->getCountryCodeForRegion($region);

            $codigos[] = [
                'value' => $region . ' (' . $codigo . ')',   // Ej: BO, AR, US
                'id' => strval($codigo)    // Ej: 591, 54, 1
            ];
        }

        // Opcional: eliminar duplicados (varios países comparten código, ej: +1)
        $codigosUnicos = collect($codigos)
            ->unique('id')
            ->values();

        return $codigosUnicos;
    }

    public function uploadCSV(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Archivo inválido',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {

            $header = fgetcsv($handle);

            while (($row = fgetcsv($handle)) !== false) {
                $sede = (empty($row[7])) ? null : Sede::where('nombre', $row[7])->first();
                $carrera = (empty($row[8])) ? null : Carrera::where('nombre', $row[8])->first();
                $horario = (empty($row[9])) ? null : Horario::where('nombre', $row[9])->first();
                $estado = (empty($row[10])) ? null : Estado::where('nombre', $row[10])->first();
                $fuente = (empty($row[11])) ? null : Fuente::where('nombre', $row[11])->first();
                $promocion = (empty($row[12])) ? null : Promocion::where('nombre', $row[12])->first();

                $fecha_registro = (empty($row[14])) ? now() : Carbon::parse($row[14])->format('Y-m-d H:i:s');
                $ultimo_contacto = (empty($row[15])) ? now() : Carbon::parse($row[15])->format('Y-m-d H:i:s');

                // Ajusta según tus columnas CSV
                Lead::create([
                    'nombre' => $row[0] ?? null,
                    'apellido_paterno' => $row[1] ?? null,
                    'apellido_materno' => $row[2] ?? null,
                    'codigo_pais' => $row[3] ?? '591',
                    'celular' => $row[4] ?? null,
                    'genero' => $row[5] ?? null,
                    'ciudad' => $row[6] ?? null,

                    'sede_id' => $sede->id ?? null,
                    'carrera_id' => $carrera->id ?? null,
                    'horario_id' => $horario->id ?? null,
                    'estado_id' => $estado->id ?? null,
                    'fuente_id' => $fuente->id ?? null,
                    'promocion_id' => $promocion->id ?? null,

                    'interes_nivel' => $row[13] ?? null,
                    'fecha_registro' => $fecha_registro,
                    'ultimo_contacto' => $ultimo_contacto,
                    'observaciones' => $row[16] ?? null,
                ]);
            }

            fclose($handle);
        }

        return redirect()->back()->with('success', 'Leads creados');
    }
}