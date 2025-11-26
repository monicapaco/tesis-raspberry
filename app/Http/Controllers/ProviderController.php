<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntityFormRequest;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
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
        //
        if ($request) {
            # code...
            $query=trim($request->get('searchText'));
            $personas = DB::table('entities')
                        ->where('type', 'Proveedor')
                        ->where(function($queryBuilder) use ($query) {
                            $queryBuilder->where('name', 'LIKE', '%'.$query.'%')
                                        ->orWhere('n_document', 'LIKE', '%'.$query.'%')
                                        ->orWhere('region','LIKE','%'.$query.'%')
                                        ->orWhere('province','LIKE','%'.$query.'%')
                                        ->orWhere('district','LIKE','%'.$query.'%');
                        })
                        ->paginate(7);

            return view('compras.proveedor.index',["personas"=>$personas,"searchText"=>$query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('compras.proveedor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EntityFormRequest $request)
    {
        //
        $persona=new Entity;
        $persona->type='Proveedor';
        $persona->name=$request->get('name');
        $persona->type_document=$request->get('type_document');
        $persona->n_document=$request->get('n_document');
        $persona->address=$request->get('address');
        $persona->region=$request->get('region');
        $persona->province=$request->get('province');
        $persona->district=$request->get('district');
        $persona->phone=$request->get('phone');
        $persona->email=$request->get('email');
        $persona->save();
        return redirect('compras/proveedor');
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        return view("compras.proveedor.show",["personas"=>Entity::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $persona=Entity::where('id',$id)
                        ->where('type','Proveedor')
                        ->first();
        if (!$persona){
            abort(404,'Proveedor no encontrado o inactivo');
        }
        return view("compras.proveedor.edit",["persona"=>Entity::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EntityFormRequest $request,$id)
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
        return redirect('compras/proveedor');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $persona=Entity::findOrFail($id);
        $persona->type='Inactivo';
        $persona->update();
        return redirect('compras/proveedor');
    }
}
