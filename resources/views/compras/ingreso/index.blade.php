@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <h3>Listado de Compras <a href="ingreso/create"><button class="btn btn-success">Nuevo</button></a></h3>
            @include('compras.ingreso.search')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <th>Id</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Comprobante</th>
                        <th>Total</th>
                        <th>Estatus</th>
                        <th>Opciones</th>
                    </thead>
                    @foreach ($ingresos as $in)
                        <tr>
                            <td>{{$in->id}}</td>
                            <td>{{$in->created_at}}</td>
                            <td>{{$in->name}}</td>
                            <td>{{$in->type_voucher}}:{{$in->serial_voucher}}-{{$in->number_voucher}}</td>
                            <td>{{$in->total}}</td>
                            <td>{{$in->status}}
                                
                            </td>
                            <td>
                                @if ($in->status=='Credito')
                                    <a href="{{route('ingreso.edit',$in->id) }}"><button class="btn btn-dark">Cambiar estatus</button></a>
                                @endif
                                
                                <a href="{{route('ingreso.show',$in->id) }}"><button class="btn btn-info">Detalles</button></a>
                                @if ($in->status!='Anulado')
                                <a href="" data-target="#modal-delete-{{$in->id}}" data-toggle="modal"><button class="btn btn-danger">Anular</button></a>
                                @endif
                            </td>
                        </tr>
                        @include('compras.ingreso.modal')
                    @endforeach
                </table>
            </div>
            {{$ingresos->render()}}
        </div>
    </div>
@endsection