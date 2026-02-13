<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleFormRequest;
use App\Http\Requests\UpdSaleFormRequest;
use App\Models\DetailSale;
use App\Models\Sale;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
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
        $query = trim($request->get('searchText'));

        $ventas = Sale::with('client')
            ->when($query, function ($q) use ($query) {
                $q->where('number_voucher', 'LIKE', "%$query%")
                ->orWhereHas('client', function ($c) use ($query) {
                        $c->where('name','LIKE',"%$query%")
                        ->orWhere('n_document','LIKE',"%$query%");
                });
            })
            ->orderBy('id','desc')
            ->paginate(7);

        return view('ventas.venta.index', [
            "ventas" => $ventas,
            "searchText" => $query
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $personas=DB::table('entities')->where('type','=','Cliente')->get();
        $transportes=DB::table('carriers')->where('status','=','1')->get();
        $articulos= DB::table('items as art')
                    ->join('detail_incomes as di','art.id','=','di.item_id')
                    ->select(DB::raw('CONCAT(art.codevar," ",art.name) as articulo'),'art.id'
                    ,'art.stock',DB::raw('ROUND(avg(di.sale_price),2) as precio_promedio'))
                    ->where('art.status','=','1')
                    ->where('art.stock','>','0')
                    ->groupBy('articulo','art.id','art.stock')
                    ->get();
        return view("ventas.venta.create",["personas"=>$personas,"articulos"=>$articulos,"transportes"=>$transportes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleFormRequest $request)
    {
        try {

            DB::beginTransaction();

            $venta = new Sale();
            $venta->client_id       = $request->get('client_id');
            $venta->carrier_id      = $request->get('carrier_id');
            $venta->type_voucher    = $request->get('type_voucher');
            $venta->serial_voucher  = $request->get('serial_voucher');
            $venta->number_voucher  = $request->get('number_voucher');
            $venta->total           = $request->get('total');

            // ================================
            // ESTADO COMERCIAL
            // ================================
            $status = $request->get('status');
            $venta->status = $status;

            // ================================
            // ESTADO DE PAGO
            // ================================
            if ($status === 'Al contado') {
                $venta->payment_status = 'Pagado';
                $venta->paid_at = now();
            } else {
                $venta->payment_status = 'Pendiente';
                $venta->paid_at = null;
            }

            $venta->save();


            // ================================
            // DETALLE DE VENTA
            // ================================
            $idarticulo   = $request->get('item_id');
            $cantidad     = $request->get('quantity');
            $precio_venta = $request->get('sale_price');

            for ($cont = 0; $cont < count($idarticulo); $cont++) {

                $item = DB::table('items')
                    ->where('id', $idarticulo[$cont])
                    ->lockForUpdate()
                    ->first();

                if (!$item) {
                    throw new Exception("Artículo no encontrado.");
                }

                if ($item->stock < $cantidad[$cont]) {
                    throw new Exception("Stock insuficiente para {$item->name}");
                }

                DetailSale::create([
                    'sale_id'    => $venta->id,
                    'item_id'    => $idarticulo[$cont],
                    'quantity'   => $cantidad[$cont],
                    'sale_price' => $precio_venta[$cont],
                ]);

                DB::table('items')
                    ->where('id', $idarticulo[$cont])
                    ->decrement('stock', $cantidad[$cont]);
            }

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Error al registrar venta: ' . $e->getMessage());

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect('ventas/venta')
            ->with('success', 'Venta registrada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $venta= DB::table('sales as v')
        ->join('entities as p','v.client_id','=','p.id')
        ->join('detail_sales as di','di.sale_id','=','v.id')
        ->select('v.id','v.created_at','p.name','v.type_voucher','v.serial_voucher',
        'v.number_voucher','v.status',DB::raw('sum(di.quantity*di.sale_price) as total'))
        ->where('v.id','=',$id)
        ->groupBy('v.id', 'v.created_at', 'p.name', 'v.type_voucher', 'v.serial_voucher','v.number_voucher','v.status','total')
        ->first();

        $detalles=  DB::table('detail_sales as d')
                    ->join('items as a','d.item_id','=','a.id')
                    ->select('a.name as articulo','d.quantity','d.sale_price')
                    ->where('d.sale_id','=',$id)
                    ->get();
        return view('ventas.venta.show',['venta'=>$venta,'detalles'=>$detalles]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $venta= DB::table('sales as v')
        ->join('entities as p','v.client_id','=','p.id')
        ->join('detail_sales as di','di.sale_id','=','v.id')
        ->select('v.id','v.created_at','p.name','v.type_voucher','v.serial_voucher',
        'v.number_voucher','v.status','v.total')
        ->where('v.id','=',$id)
        ->groupBy('v.id', 'v.created_at', 'p.name', 'v.type_voucher', 'v.serial_voucher','v.number_voucher','v.status','v.total')
        ->first();

        $detalles=  DB::table('detail_sales as d')
                    ->join('items as a','d.item_id','=','a.id')
                    ->select('a.name as articulo','d.quantity','d.sale_price')
                    ->where('d.sale_id','=',$id)
                    ->get();
        return view('ventas.venta.edit',['venta'=>$venta,'detalles'=>$detalles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdSaleFormRequest $request, string $id)
    {
        $venta = Sale::findOrFail($id);

        $nuevoStatus = $request->get('status');

        // ================================
        // SI SE MARCA COMO PAGADO
        // ================================
        if ($nuevoStatus === 'Pagado' && $venta->payment_status !== 'Pagado') {
            $venta->payment_status = 'Pagado';
            $venta->paid_at = now();
        }

        $venta->status = $nuevoStatus;
        $venta->save();

        return redirect('ventas/venta')
            ->with('success', 'Venta actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            DB::beginTransaction();

            $venta = Sale::findOrFail($id);

            // ================================
            // EVITAR DOBLE ANULACIÓN
            // ================================
            if ($venta->status === 'Anulado') {
                return redirect('ventas/venta')
                    ->with('error', 'La venta ya está anulada');
            }

            // ================================
            // OBTENER DETALLES DE LA VENTA
            // ================================
            $detalles = DetailSale::where('sale_id', $id)->get();

            foreach ($detalles as $detalle) {

                DB::table('items')
                    ->where('id', $detalle->item_id)
                    ->increment('stock', $detalle->quantity);
            }

            // ================================
            // ANULAR VENTA
            // ================================
            $venta->status = 'Anulado';
            $venta->update();

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Error al anular venta: ' . $e->getMessage());

            return redirect('ventas/venta')
                ->with('error', 'No se pudo anular la venta');
        }

        return redirect('ventas/venta')
            ->with('success', 'Venta anulada y stock restaurado');
    }

    /**
     * Print a sale made by id.
     */
    public function imprimir($id)
    {
        $venta = DB::table("sales as v")
            ->join("entities as p", "v.client_id", "=", "p.id")
            ->join("detail_sales as di", "di.sale_id", "=", "v.id")
            ->select(
                "v.id",
                "v.created_at",
                "p.name",
                "p.address",
                "p.n_document",
                "p.phone",
                "v.type_voucher",
                "v.serial_voucher",
                "v.number_voucher",
                "v.status",
                DB::raw("sum(di.quantity*di.sale_price) as total"),
            )
            ->where("v.id", "=", $id)
            ->groupBy(
                "v.id",
                "v.created_at",
                "p.name",
                "p.address",
                "p.n_document",
                "p.phone",
                "v.type_voucher",
                "v.serial_voucher",
                "v.number_voucher",
                "v.status",
            )
            ->first();

        $detalles = DB::table("detail_sales as d")
            ->join("items as a", "d.item_id", "=", "a.id")
            ->select("a.name as descripcion", "d.quantity", "d.sale_price")
            ->where("d.sale_id", "=", $id)
            ->get();

        $fecha = date_parse($venta->created_at);

        $path = public_path("img/logo.jpeg");

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data_logo = file_get_contents($path);
            $logo_base64 =
                "data:image/" . $type . ";base64," . base64_encode($data_logo);
        } else {
            $logo_base64 = "";
        }

        $data = [
            "dia" => str_pad($fecha["day"], 2, "0", STR_PAD_LEFT),
            "mes" => str_pad($fecha["month"], 2, "0", STR_PAD_LEFT),
            "anio" => $fecha["year"],
            "num_nota" => "{$venta->serial_voucher}-{$venta->number_voucher}",
            "comprador" => $venta->name,
            "direccion" => $venta->address,
            "ruc" => $venta->n_document,
            "celular" => $venta->phone,
            "detalles" => $detalles,
            "status" => $venta->status,
            "pre_total" => number_format($venta->total, 2),
            "logo_base64" => $logo_base64,
        ];

        // ventas/venta/pdf -> loadView(plantilla, datos)
        $pdf = Pdf::loadView("ventas.venta.pdf", $data)->setPaper("A4");

        return $pdf->stream("nota-venta.pdf");
    }

    public function markPaid($id)
    {
        try {

            $venta = Sale::findOrFail($id);

            if ($venta->status === 'Anulado') {
                return redirect()->back()
                    ->with('error', 'No se puede pagar una venta anulada');
            }

            if ($venta->payment_status === 'Pagado') {
                return redirect()->back()
                    ->with('info', 'La venta ya está pagada');
            }

            $venta->payment_status = 'Pagado';
            $venta->paid_at = now();
            $venta->save();

            return redirect()->back()
                ->with('success', 'Venta marcada como pagada');

        } catch (Exception $e) {

            Log::error('Error al marcar pago: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'No se pudo registrar el pago');
        }
    }

}
