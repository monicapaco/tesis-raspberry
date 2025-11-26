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
            $query=trim($request->get("searchText"));
            $ingresos=  DB::table('incomes as i')
                        ->join('entities as p','i.provider_id','=','p.id')
                        ->join('detail_incomes as di','i.id','=','di.income_id')
                        ->select('i.id',DB::raw("DATE_FORMAT(i.created_at,'%d/%m/%Y') as created_at"),'p.name','i.type_voucher','i.serial_voucher','i.number_voucher','i.status',DB::raw('sum(di.quantity*di.purchase_price) as total'))
                        ->where('i.number_voucher','LIKE','%'.$query.'%')
                        ->orwhere('p.name','LIKE','%'.$query.'%')
                        ->orderBy('i.id','desc')
                        ->groupBy('i.id','i.created_at','p.name','i.type_voucher','i.serial_voucher','i.number_voucher','i.status')
                        ->paginate(7);
            return view('compras.ingreso.index',["ingresos"=>$ingresos,"searchText"=>$query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $personas=  DB::table('entities')->where('type','=','Proveedor')->get();
        $articulos= DB::table('items as art')
                    ->select(DB::raw('CONCAT(art.codevar," ",art.name) as articulo'),'art.id')
                    ->where('art.status','=','1')
                    ->get();
        return view("compras.ingreso.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeFormRequest $request)
    {
        //
        try{
            DB::beginTransaction();
            $ingreso=new Income;
            $ingreso->provider_id=$request->get('provider_id');
            $ingreso->type_voucher=$request->get('type_voucher');
            $ingreso->serial_voucher=$request->get('serial_voucher');
            $ingreso->number_voucher=$request->get('number_voucher');
            $ingreso->status=$request->get('status');
            $ingreso->save();

            $idarticulo=$request->get('item_id');
            $cantidad=$request->get('quantity');
            $precio_compra=$request->get('purchase_price');
            $precio_venta=$request->get('sale_price');

            $cont=0;
            while($cont<count($idarticulo)){
                $detalle=new DetailIncome();
                $detalle->income_id=$ingreso->id;
                $detalle->item_id=$idarticulo[$cont];
                $detalle->quantity=$cantidad[$cont];
                $detalle->purchase_price=$precio_compra[$cont];
                $detalle->sale_price=$precio_venta[$cont];
                $detalle->save();
                $cont=$cont+1;
            }
            DB::commit();
            
        }
        catch (Exception $e){
            DB::rollBack();
            Log::error('Error al guardar ingreso: '.$e->getMessage());
            //return back()->withErrors(['error' => 'Error al guardar ingreso. Revisa los logs.']);
        }
        return redirect('compras/ingreso');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $ingreso=   DB::table('incomes as i')
                    ->join('entities as p','i.provider_id','=','p.id')
                    ->join('detail_incomes as di','di.income_id','=','i.id')
                    ->select('i.id','i.created_at','p.name','i.type_voucher','i.serial_voucher','i.number_voucher','i.status',DB::raw('sum(di.quantity*di.purchase_price) as total'))
                    ->where('i.id','=',$id)
                    ->groupBy('i.id', 'i.created_at', 'p.name', 'i.type_voucher', 'i.serial_voucher','i.number_voucher','i.status')
                    ->first();
        $detalles=  DB::table('detail_incomes as d')
                    ->join('items as a','d.item_id','=','a.id')
                    ->select('a.name as articulo','d.quantity','d.purchase_price','d.sale_price')
                    ->where('d.income_id','=',$id)
                    ->get();
        return view("compras.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $detalles=  DB::table('detail_incomes as d')
                    ->join('items as a','d.item_id','=','a.id')
                    ->select('a.name as articulo','d.quantity','d.purchase_price','d.sale_price')
                    ->where('d.income_id','=',$id)
                    ->get();
        $ingreso=   DB::table('incomes as i')
                    ->join('entities as p','i.provider_id','=','p.id')
                    ->join('detail_incomes as di','di.income_id','=','i.id')
                    ->select('i.id','i.created_at','p.name','i.type_voucher','i.serial_voucher','i.number_voucher','i.status',DB::raw('sum(di.quantity*di.purchase_price) as total'))
                    ->where('i.id','=',$id)
                    ->where('i.status','=','Credito')
                    ->groupBy('i.id', 'i.created_at', 'p.name', 'i.type_voucher', 'i.serial_voucher','i.number_voucher','i.status')
                    ->first();
        if (!$ingreso){
            abort(404, 'Ingreso no encontrado o inactivo');
        }
        return view('compras.ingreso.edit',["ingreso"=>$ingreso,"detalles"=>$detalles]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdIncomeFormRequest $request,$id)
    {
        //
        /*
        $ingreso=   DB::table('incomes as i')
                    ->join('entities as p','i.provider_id','=','p.id')
                    ->join('detail_incomes as di','di.income_id','=','i.id')
                    ->select('i.id','i.created_at','p.name','i.type_voucher','i.serial_voucher','i.number_voucher','i.status',DB::raw('sum(di.quantity*di.purchase_price) as total'))
                    ->where('i.id','=',$id)
                    ->groupBy('i.id', 'i.created_at', 'p.name', 'i.type_voucher', 'i.serial_voucher','i.number_voucher','i.status')
                    ->first();
        $detalles=  DB::table('detail_incomes as d')
                    ->join('items as a','d.item_id','=','a.id')
                    ->select('a.name as articulo','d.quantity','d.purchase_price','d.sale_price')
                    ->where('d.income_id','=',$id)
                    ->get();
        */
        $ingreso=Income::findOrFail($id);
        $ingreso->status=$request->get('status');
        $ingreso->update();
        return redirect('compras/ingreso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        //
        $ingreso=Income::findOrFail($id);
        $ingreso->status='Anulado';
        $ingreso->update();

        return redirect('compras/ingreso');
    }
}
