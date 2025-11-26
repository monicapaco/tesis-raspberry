@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nuevo Ingreso</h3>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <form action="{{route('ingreso.update',$ingreso->id)}}" method="post" >
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="form-group">
                    <label for="provider">Proveedor</label>
                
                    <p>{{$ingreso->name}}</p>
                    
                </div>
            </div>
            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                <div class="form-group">
                    <label for="type_voucher">Tipo comprobante</label>
                    <p>{{$ingreso->type_voucher}}</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                <div class="form-group">
                    <label for="serial_voucher">Comprobante:</label>
                    <p>{{$ingreso->serial_voucher}}-{{$ingreso->number_voucher}}</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                <div class="form-group">
                    <label for="status">Estado del pedido</label>
                    <select name="status" id="" value="{{ old('status') }}" class="form-control">
                        <option value="Credito">Credito</option>
                        <option value="Al contado">Al contado</option>
                    </select>
                    
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
                                <th>Precio compra</th>
                                <th>Precio venta</th>
                                <th>Subtotal</th>
                            </thead>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><h4 id="total">S/. {{$ingreso->total}}</h4></th>
                            </tfoot>
                            <tbody>
                                @foreach ($detalles as $det)
                                    <tr>
                                        <td>{{$det->articulo}}</td>
                                        <td>{{$det->quantity}}</td>
                                        <td>{{$det->purchase_price}}</td>
                                        <td>{{$det->sale_price}}</td>
                                        <td>{{$det->purchase_price*$det->quantity}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" id="guardar">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="reset" class="btn btn-danger">Cancelar</button>
                </div>
            </div>
        </div>
        
    </form>
@endsection