<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryFormRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* =============================
        LISTADO
    ============================= */
    public function index(Request $request)
    {
        $query = trim($request->get('searchText'));

        $categorias = Category::when($query, function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->orderBy('id', 'desc')
            ->paginate(8);

        return view('almacen.categoria.index', [
            "categorias" => $categorias,
            "searchText" => $query
        ]);
    }

    /* =============================
        CREAR
    ============================= */
    public function create()
    {
        return view('almacen.categoria.create');
    }

    public function store(CategoryFormRequest $request)
    {
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 1
        ]);

        return redirect('almacen/categoria');
    }

    /* =============================
        MOSTRAR
    ============================= */
    public function show($id)
    {
        return view('almacen.categoria.show', [
            'categoria' => Category::findOrFail($id)
        ]);
    }

    /* =============================
        EDITAR (solo activos)
    ============================= */
    public function edit($id)
    {
        $categoria = Category::where('id', $id)
            ->where('status', 1)
            ->firstOrFail();

        return view('almacen.categoria.edit', compact('categoria'));
    }

    public function update(CategoryFormRequest $request, $id)
    {
        $categoria = Category::findOrFail($id);

        $categoria->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect('almacen/categoria');
    }

    /* =============================
        DESACTIVAR
    ============================= */
    public function destroy($id)
    {
        $categoria = Category::findOrFail($id);
        $categoria->status = 0;
        $categoria->save();

        return redirect('almacen/categoria');
    }

    /* =============================
        ACTIVAR
    ============================= */
    public function activate($id)
    {
        $categoria = Category::findOrFail($id);
        $categoria->status = 1;
        $categoria->save();

        return redirect('almacen/categoria');
    }
}
