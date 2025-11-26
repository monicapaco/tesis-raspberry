@extends('layouts.admin')
@section('contenido')

        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="form-group">
                    <label for="provider">Cliente</label>
                    <p>{{$venta->name}}</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                    <label for="type_voucher">Tipo comprobante</label>
                    <p>{{$venta->type_voucher}}</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                    <label for="serial_voucher">Comprobante</label>
                    <p>{{$venta->serial_voucher}}-{{$venta->number_voucher}}</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                    <label for="status">Estado del pedido</label>
                    <p>{{$venta->status}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="col-lg-12 col-sm-12 col-md-2 col-xs-12">
                        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                            <thead style="background-color: aqua">
                                <th>Artículo</th>
                                <th>Cantidad</th>
                                <th>Precio venta</th>
                                <th>Subtotal</th>
                            </thead>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><h4 id="total">S/. {{$venta->total}}</h4></th>
                            </tfoot>
                            <tbody>
                                @foreach ($detalles as $det)
                                    <tr>
                                        <td>{{$det->articulo}}</td>
                                        <td>{{$det->quantity}}</td>
                                        <td>{{$det->sale_price}}</td>
                                        <td>{{$det->sale_price*$det->quantity}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        

@endsection
