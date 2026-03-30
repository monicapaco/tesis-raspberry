<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntityFormRequest;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request) {

            $query = trim($request->get('searchText'));

            $personas = DB::table('entities')
                ->where('type', 'Cliente')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', '%' . $query . '%')
                    ->orWhere('n_document', 'LIKE', '%' . $query . '%');
                })
                ->orderBy('id', 'desc')
                ->paginate(7);

            return view('ventas.cliente.index', [
                "personas" => $personas,
                "searchText" => $query
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('ventas.cliente.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EntityFormRequest $request)
    {
        $persona = new Entity;

        $persona->type = 'Cliente';
        $persona->status = 'Activo';

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

        return redirect('ventas/cliente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        return view("ventas.cliente.show",["persona"=>Entity::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        /* return view("ventas.cliente.edit",["persona"=>Entity::findOrFail($id)]); */
        $persona = Entity::where('id',$id)
                        ->where('type','Cliente')
                        ->first();
        if (!$persona){
            abort(404,'Cliente no encontrada o inactivo');
        }
        return view("ventas.cliente.edit",[
            'persona'=> $persona
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EntityFormRequest $request, $id)
    {
        //
        $persona=Entity::findOrFail($id);
        $persona->name=$request->get('name');
        $persona->type_document=$request->get('type_document');
        $persona->n_document=$request->get('n_document');
        $persona->address=$request->get('address');
        $persona->region=$request->get('region');
        $persona->province=$request->get('province');
        $persona->district=$request->get('district');
        $persona->phone=$request->get('phone');
        $persona->email=$request->get('email');
        $persona->update();
        return redirect('ventas/cliente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $persona = Entity::findOrFail($id);

        $persona->status = 'Inactivo';
        $persona->save();

        return redirect('ventas/cliente');
    }

    public function activate($id)
    {
        $persona = Entity::findOrFail($id);

        $persona->status = 'Activo';
        $persona->save();

        return redirect('ventas/cliente');
    }

}
