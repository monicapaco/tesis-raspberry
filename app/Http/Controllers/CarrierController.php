<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarrierFormRequest;
use App\Models\Carrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarrierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todos los transportes
     */
    public function index(Request $request)
    {
        $query = trim($request->get('searchText'));

        $transportes = DB::table('carriers')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('n_document', 'like', '%' . $query . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(8);

        return view('ventas.transporte.index', [
            "transportes" => $transportes,
            "searchText" => $query
        ]);
    }

    /**
     * Formulario crear
     */
    public function create()
    {
        return view('ventas.transporte.create');
    }

    /**
     * Guardar transporte
     */
    public function store(CarrierFormRequest $request)
    {
        $transporte = new Carrier();

        $transporte->name = $request->get('name');
        $transporte->type_document = $request->get('type_document');
        $transporte->n_document = $request->get('n_document');

        $transporte->status = '1'; // Activo

        $transporte->save();

        return redirect('ventas/transporte');
    }

    /**
     * Mostrar transporte
     */
    public function show($id)
    {
        return view('ventas.transporte.show', [
            'transporte' => Carrier::findOrFail($id)
        ]);
    }

    /**
     * Editar transporte
     */
    public function edit($id)
    {
        $transporte = Carrier::findOrFail($id);

        return view('ventas.transporte.edit', [
            'transporte' => $transporte
        ]);
    }

    /**
     * Actualizar transporte
     */
    public function update(CarrierFormRequest $request, $id)
    {
        $transporte = Carrier::findOrFail($id);

        $transporte->name = $request->get('name');
        $transporte->type_document = $request->get('type_document');
        $transporte->n_document = $request->get('n_document');

        $transporte->save();

        return redirect('ventas/transporte');
    }

    /**
     * Dar de baja transporte
     */
    public function destroy($id)
    {
        $transporte = Carrier::findOrFail($id);

        $transporte->status = '0'; // Inactivo
        $transporte->save();

        return redirect('ventas/transporte');
    }

    /**
     * Reactivar transporte
     */
    public function activate($id)
    {
        $transporte = Carrier::findOrFail($id);

        $transporte->status = '1'; // Activo
        $transporte->save();

        return redirect('ventas/transporte');
    }
}
