<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function __construct()
    {
        // SOLO JEFE
        $this->middleware(function ($request, $next) {
            if (auth()->user()->rol !== 'JEFE') {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        return Inertia::render('Users/Index', [
            'usuarios' => User::all()
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
        $estado_query = $request->get('estado', null);
        $rol = $request->get('rol', '');     

        switch ($estado_query) {
            case 'true': $estado = true; break;
            case 'false': $estado = false; break;
            default: $estado = null; break;
        }


        $query = User::query();

        if (!empty($filter)) {
            $filterStr = '%' . $filter . '%';

            $query->where(function ($q) use ($filterStr) {
                $q->where('nombre', 'like', $filterStr)
                ->orWhere('email', 'like', $filterStr);
            });
        }

        // 🎯 Filtros específicos
        if ($estado !== null) $query->where('activo', $estado);

        if (!empty($rol)) $query->where('rol', $rol);

        $query->orderBy($orderBy, $orderDirection);

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($users);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'rol' => 'required|in:JEFE,ASESOR'
        ]);

        User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'rol' => $request->rol
        ]);

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'usuario' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => "required|email|unique:usuarios,email,{$user->id}",
            'rol' => 'required|in:JEFE,ASESOR'
        ]);

        $data = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'rol' => $request->rol
        ];

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('No puedes eliminarte');
        }

        $data = [
            'activo' => false
        ];

        $user->update($data);
        return back();
    }
}