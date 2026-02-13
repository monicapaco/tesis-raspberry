<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntityFormRequest;
use App\Models\Entity;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * LISTADO
     * Muestra TODOS los proveedores (activos e inactivos)
     */
    public function index(Request $request)
    {
        $query = trim($request->get('searchText'));

        $personas = Entity::where('type', 'Proveedor')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                  ->orWhere('n_document', 'LIKE', '%' . $query . '%')
                  ->orWhere('phone', 'LIKE', '%'.$query.'%');
            })
            ->orderBy('id', 'desc')
            ->paginate(7);

        return view('compras.proveedor.index', [
            "personas" => $personas,
            "searchText" => $query
        ]);
    }

    /**
     * FORM CREAR
     */
    public function create()
    {
        return view('compras.proveedor.create');
    }

    /**
     * GUARDAR NUEVO PROVEEDOR
     */
    public function store(EntityFormRequest $request)
    {
        $persona = new Entity();

        $persona->type = 'Proveedor';
        $persona->status = 'ACTIVO';

        $persona->name = $request->get('name');
        $persona->type_document = $request->get('type_document');
        $persona->n_document = $request->get('n_document');
        $persona->address = $request->get('address');
        $persona->region = $request->get('region');
        $persona->province = $request->get('province');
        $persona->district = $request->get('district');
        $persona->phone = $request->get('phone');
        $persona->email = $request->get('email');

        $persona->save();

        return redirect('compras/proveedor');
    }

    /**
     * MOSTRAR DETALLE
     */
    public function show($id)
    {
        $persona = Entity::where('id', $id)
            ->where('type', 'Proveedor')
            ->firstOrFail();

        return view("compras.proveedor.show", [
            "personas" => $persona
        ]);
    }

    /**
     * FORM EDITAR
     */
    public function edit($id)
    {
        $persona = Entity::where('id', $id)
            ->where('type', 'Proveedor')
            ->firstOrFail();

        return view("compras.proveedor.edit", [
            "persona" => $persona
        ]);
    }

    /**
     * ACTUALIZAR PROVEEDOR
     */
    public function update(EntityFormRequest $request, $id)
    {
        $persona = Entity::findOrFail($id);

        $persona->name = $request->get('name');
        $persona->type_document = $request->get('type_document');
        $persona->n_document = $request->get('n_document');
        $persona->address = $request->get('address');
        $persona->region = $request->get('region');
        $persona->province = $request->get('province');
        $persona->district = $request->get('district');
        $persona->phone = $request->get('phone');
        $persona->email = $request->get('email');

        $persona->save();

        return redirect('compras/proveedor');
    }

    /**
     * ELIMINAR (BORRADO LÓGICO)
     * Cambia status a INACTIVO
     */
    public function destroy($id)
    {
        $persona = Entity::findOrFail($id);

        $persona->status = 'INACTIVO';
        $persona->save();

        return redirect('compras/proveedor');
    }

    /**
     * ACTIVAR PROVEEDOR
     */
    public function activate($id)
    {
        $persona = Entity::findOrFail($id);

        $persona->status = 'ACTIVO';
        $persona->save();

        return redirect('compras/proveedor');
    }
}
