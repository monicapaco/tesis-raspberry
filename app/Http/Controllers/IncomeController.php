<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeFormRequest;
use App\Http\Requests\UpdIncomeFormRequest;
use App\Models\DetailIncome;
use App\Models\Income;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = trim($request->get("searchText"));

        $ingresos = DB::table('incomes as i')
            ->join('entities as p', 'i.provider_id', '=', 'p.id')
            ->join('detail_incomes as di', 'i.id', '=', 'di.income_id')
            ->select(
                'i.id',
                DB::raw("DATE_FORMAT(i.created_at,'%d/%m/%Y') as created_at"),
                'p.name',
                'i.type_voucher',
                'i.serial_voucher',
                'i.number_voucher',
                'i.status',
                DB::raw('sum(di.quantity * di.purchase_price) as total')
            )
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('i.number_voucher', 'LIKE', "%$query%")
                        ->orWhere('p.name', 'LIKE', "%$query%");
                });
            })
            ->orderBy('i.id', 'desc')
            ->groupBy(
                'i.id',
                'i.created_at',
                'p.name',
                'i.type_voucher',
                'i.serial_voucher',
                'i.number_voucher',
                'i.status'
            )
            ->paginate(7);

        return view('compras.ingreso.index', [
            "ingresos" => $ingresos,
            "searchText" => $query
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $personas = DB::table('entities')
            ->where('type', 'Proveedor')
            ->where('status', 'Activo')
            ->get();

        $articulos = DB::table('items as art')
            ->select(DB::raw('CONCAT(art.codevar," ",art.name) as articulo'), 'art.id')
            ->where('art.status', '1')
            ->get();

        return view("compras.ingreso.create", [
            "personas" => $personas,
            "articulos" => $articulos
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(IncomeFormRequest $request)
    {
        try {

            DB::beginTransaction();

            $ingreso = new Income();
            $ingreso->provider_id = $request->provider_id;
            $ingreso->type_voucher = $request->type_voucher;
            $ingreso->serial_voucher = $request->serial_voucher;
            $ingreso->number_voucher = $request->number_voucher;
            $ingreso->status = $request->status;
            $ingreso->save();

            $items = $request->item_id;
            $cantidad = $request->quantity;
            $precioCompra = $request->purchase_price;
            $precioVenta = $request->sale_price;

            foreach ($items as $index => $item) {

                DetailIncome::create([
                    'income_id' => $ingreso->id,
                    'item_id' => $item,
                    'quantity' => $cantidad[$index],
                    'purchase_price' => $precioCompra[$index],
                    'sale_price' => $precioVenta[$index],
                ]);
            }

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Error al guardar ingreso: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Error al guardar ingreso'
            ]);
        }

        return redirect('compras/ingreso');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $ingreso = $this->getIngresoWithTotal($id);
        $detalles = $this->getDetalles($id);

        return view("compras.ingreso.show", [
            "ingreso" => $ingreso,
            "detalles" => $detalles
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT  (BUG FIX AQUÍ)
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $ingreso = $this->getIngresoWithTotal($id);
        $detalles = $this->getDetalles($id);

        if (!$ingreso) {
            abort(404, 'Ingreso no encontrado');
        }

        return view('compras.ingreso.edit', [
            "ingreso" => $ingreso,
            "detalles" => $detalles
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(UpdIncomeFormRequest $request, $id)
    {
        $ingreso = Income::findOrFail($id);
        $ingreso->status = $request->status;
        $ingreso->save();

        return redirect('compras/ingreso');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY (ANULAR)
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $ingreso = Income::findOrFail($id);
        $ingreso->status = 'Anulado';
        $ingreso->save();

        return redirect('compras/ingreso');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS PRIVADOS (EVITA DUPLICACIÓN)
    |--------------------------------------------------------------------------
    */

    private function getIngresoWithTotal($id)
    {
        return DB::table('incomes as i')
            ->join('entities as p', 'i.provider_id', '=', 'p.id')
            ->join('detail_incomes as di', 'di.income_id', '=', 'i.id')
            ->select(
                'i.id',
                'i.created_at',
                'p.name',
                'i.type_voucher',
                'i.serial_voucher',
                'i.number_voucher',
                'i.status',
                DB::raw('sum(di.quantity * di.purchase_price) as total')
            )
            ->where('i.id', $id)
            ->groupBy(
                'i.id',
                'i.created_at',
                'p.name',
                'i.type_voucher',
                'i.serial_voucher',
                'i.number_voucher',
                'i.status'
            )
            ->first();
    }

    private function getDetalles($id)
    {
        return DB::table('detail_incomes as d')
            ->join('items as a', 'd.item_id', '=', 'a.id')
            ->select(
                'a.name as articulo',
                'd.quantity',
                'd.purchase_price',
                'd.sale_price'
            )
            ->where('d.income_id', $id)
            ->get();
    }
}
