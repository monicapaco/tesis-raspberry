<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarrierFormRequest;
use App\Models\Carrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Can;

class CarrierController extends Controller
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
            $query=trim($request ->get('searchText'));
            $transportes=   DB::table('carriers')
                            ->where('name','like','%'.$query.'%')
                            ->where('status','=','1')
                            ->orderBy('id','desc')
                            ->paginate(8);
            return view('ventas.transporte.index',["transportes"=>$transportes,"searchText"=>$query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('ventas.transporte.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarrierFormRequest $request)
    {
        //
        $transporte=new Carrier();
        $transporte->name=$request->get('name');
        $transporte->type_document=$request->get('type_document');
        $transporte->n_document=$request->get('n_document');
        $transporte->status='1';
        $transporte->save();
        return redirect('ventas/transporte');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        return view('ventas.transporte.show',['transporte'=>Carrier::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        /* return view('ventas.transporte.edit',['transporte'=>Carrier::findOrFail($id)]); */
        $transporte = Carrier::where('id',$id)
                        ->where('status','1')
                        ->first();
        if (!$transporte){
            abort(404,'Transporte no encontrada o inactiva');
        }
        return view('ventas.transporte.edit',[
            'transporte'=> $transporte
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarrierFormRequest $request, $id)
    {
        //
        $transporte=Carrier::findOrFail($id);
        $transporte->name=$request->get('name');
        $transporte->type_document=$request->get('type_document');
        $transporte->n_document=$request->get('n_document');
        $transporte->update();
        return redirect('ventas/transporte');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        //
        $transporte=Carrier::findOrFail($id);
        $transporte->status='0';
        $transporte->update();
        return redirect('ventas/transporte');
    }
}
