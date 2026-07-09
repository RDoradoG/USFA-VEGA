<?php

namespace App\Http\Controllers;

use App\Models\Promocion;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ConfigLead;
use Illuminate\Support\Facades\Auth;

class PromocionController extends Controller
{
    private $validation = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'beca' => 'required|integer',
        //'activo' => 'required|boolean'
    ];

    private $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'descripcion.required' => 'La descrición es obligatorio.',
        'beca.required' => 'La beca es obligatorio.'
    ];

    public function list(Request $request)
    {
  
        // Parámetros con valores por defecto
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $orderBy = $request->get('order_by', 'id');
        $orderDirection = $request->get('order_direction', 'asc');

        $filter = $request->get('filter', '');

        $estado_query = $request->get('estado', null);

        switch ($estado_query) {
            case 'true': $estado = true; break;
            case 'false': $estado = false; break;
            default: $estado = null; break;
        }

        $user = Auth::user();

        $query = Promocion::query();

        if (!empty($filter)) {
            $filterStr = '%' . $filter . '%';

            $query->where(function ($q) use ($filterStr) {
                $q->where('nombre', 'like', $filterStr)
                ->orWhere('beca', 'like', $filterStr);
            });
        }

        if ($estado !== null) $query->where('activo', $estado);

        $query->orderBy($orderBy, $orderDirection);

        $promos = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($promos);
    }

    public function index()
    {   
        return Inertia::render('Promos/Index', [
            'config' => ConfigLead::where('id', 1)->firstOrFail()
        ]);
    }

    public function create()
    {
        return Inertia::render('Promos/Create', []);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validation, $this->messages);
        $validated['activo'] = true;

        $promo = Promocion::create($validated);

        return redirect()->route('promos.index')->with('success', 'Promoción creada');
    }

    public function edit(Promocion $promo)
    {
        return Inertia::render('Promos/Edit', [
            'promo' => $promo
        ]);
    }

    public function update(Request $request, Promocion $promo)
    {
        $user = Auth::user();

        //abort(403);

        $validated = $request->validate($this->validation, $this->messages);
        $validated['activo'] = true;
    
        $promo->update($validated);

        return redirect()->route('promos.index')->with('success', 'Promoción actualizada');
    }

    public function inactivate(Request $request, $id)
    {
        $promo = Promocion::where('id', $id)->firstOrFail();

        $promo->update([
            'activo' => false
        ]);

        return response()->json(['success' => true]);
    }

    public function activate(Request $request, $id)
    {
        $promo = Promocion::where('id', $id)->firstOrFail();

        $promo->update([
            'activo' => true
        ]);

        return response()->json(['success' => true]);
    }

    public function changeConfigurations(Request $request)
    {
        $validated = $request->validate([
            'dias_inactivo' => 'required|integer',
        ], [
            'dias_inactivo.required' => 'Los días son obligatorios.'
        ]);

        ConfigLead::where('id', 1)->firstOrFail()->update($validated);

        return redirect()->route('promos.index')->with('success', 'Configuración actualizada');
    }
}