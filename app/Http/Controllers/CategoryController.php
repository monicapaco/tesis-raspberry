<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryFormRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
        if ($request){
            $query=trim($request->get('searchText'));
            $categorias=DB::table('categories')
                        ->where('name','like','%'.$query.'%')
                        ->where('status','=','1')
                        ->orderBy('id','desc')
                        ->paginate(8);
            return view('almacen.categoria.index',["categorias"=>$categorias,"searchText"=>$query]);
        }
    }
    public function create(){
        return view('almacen.categoria.create');
    }
    public function store(CategoryFormRequest $request){
        $categoria=new Category();
        $categoria->name=$request->get('name');
        $categoria->description=$request->get('description');
        $categoria->status='1';
        $categoria->save();
        return redirect('almacen/categoria');
    }
    public function show($id){
        return view('almacen.categoria.show',['categoria'=>Category::findOrFail($id)]);
    }
    public function edit($id){
        /* return view('almacen.categoria.edit',['categoria'=>Category::findOrFail($id)]); */
        $categoria = Category::where('id', $id)
                         ->where('status', '1') // Solo categorías activas
                         ->first();

        if (!$categoria) {
            abort(404, 'Categoría no encontrada o inactiva');
        }

        return view('almacen.categoria.edit', [
            'categoria' => $categoria
        ]);
    }
    public function update(CategoryFormRequest $request,$id){
        $categoria=Category::findOrFail($id);
        $categoria->name=$request->get('name');
        $categoria->description=$request->get('description');
        $categoria->update();
        return redirect('almacen/categoria');
    }
    public function destroy($id){
        $categoria=Category::findOrFail($id);
        $categoria->status='0';
        $categoria->update();
        return redirect('almacen/categoria');
    }
}
