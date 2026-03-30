<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemFormRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ItemController extends Controller
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
        if($request){

            $query=trim($request->get('searchText'));
            $articulos= DB::table('items as i')
                        ->join('categories as c','i.category_id','=','c.id')
                        ->select('i.id','i.name','i.codevar','i.stock','c.name as categoria','i.description','i.img','i.condition')

                        ->where('i.name','LIKE','%'.$query.'%')
                        ->where('i.status','=','1')
                        ->orderBy('i.id','desc')
                        ->paginate(5);
            return view('almacen.articulo.index',["articulos"=>$articulos,"searchText"=>$query]);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categorias=DB::table('categories')->where('status','=','1')->get();
        return view('almacen.articulo.create',["categorias"=>$categorias]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemFormRequest $request)
    {
        //
        $articulo=new Item();
        $articulo->category_id=$request->get('category_id');
        $articulo->codevar=$request->get('codevar');
        $articulo->name=$request->get('name');
        $articulo->stock=$request->get('stock');
        $articulo->description=$request->get('description');
        $articulo->condition='Activo';
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $file->move(public_path().'/imagenes/articulos',$file->getClientOriginalName());
            $articulo->img=$file->getClientOriginalName();
        }

        $articulo->status='1';
        $articulo->save();
        return redirect('almacen/articulo');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        return view('almacen.articulo.show',['articulo'=>Item::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        /* $articulo=Item::findOrFail($id);
        $categorias=DB::table('categories')->where('status','=','1')->get();
        return view('almacen.articulo.edit',["articulo"=>$articulo,"categorias"=>$categorias]); */
    // Buscar el artículo solo si está activo (condicion = 1)
        $articulo = Item::where('id', $id)
                        ->where('status', '1') // Asegúrate que este sea el campo correcto
                        ->first();

        if (!$articulo) {
            abort(404, 'Artículo no encontrado o inactivo');
        }

        $categorias = DB::table('categories')
                        ->where('status', '1')
                        ->get();

        return view('almacen.articulo.edit', [
            'articulo' => $articulo,
            'categorias' => $categorias
        ]);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemFormRequest $request, $id)
    {
        //

        $articulo=Item::findOrFail($id);
        $articulo->category_id=$request->get('category_id');
        $articulo->codevar=$request->get('codevar');
        $articulo->name=$request->get('name');
        $articulo->stock=$request->get('stock');
        $articulo->description=$request->get('description');
        $articulo->condition='Activo';

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $file->move(public_path().'/imagenes/articulos',$file->getClientOriginalName());
            $articulo->img=$file->getClientOriginalName();
        }

        $articulo->status='1';
        $articulo->update();
        return redirect('almacen/articulo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $articulo=Item::findOrFail($id);
        $articulo->status='0';
        $articulo->update();
        return redirect('almacen/articulo');

    }
    /**
     * Print
     */
    public function imprimir()
    {
        $articulos = DB::table('items as i')
            ->select(
                'i.id',
                'i.name',
                'i.codevar',
                'i.stock',
                'i.description'
            )
            ->where('i.status','=','1')
            ->orderBy('i.id','asc')
            ->get();

        $data = [
            "articulos" => $articulos,
            "fechaGeneracion" => Carbon::now()
        ];

        $pdf = Pdf::loadView("almacen.articulo.pdf", $data)
            ->setPaper("A4");

        return $pdf->stream("inventario.pdf");
    }
}
