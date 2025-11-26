@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <h3>Listado de transporte <a href="transporte/create"><button class="btn btn-success">Nuevo</button></a></h3>
            @include('ventas.transporte.search')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Tipo Doc</th>
                        <th>Número Documento</th>
                        <th>Opciones</th>
                    </thead>
                    @foreach ($transportes as $tr)
                        <tr>
                            <td>{{$tr->id}}</td>
                            <td>{{$tr->name}}</td>
                            <td>{{$tr->type_document}}</td>
                            <td>{{$tr->n_document}}</td>
                            <td>
                                <a href="{{route('transporte.edit',$tr->id)}}"><button class="btn btn-info">Editar</button></a>
                                <a href="" data-target="#modal-delete-{{$tr->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
                            </td>
                        </tr>
                        @include('ventas.transporte.modal')
                    @endforeach
                </table>
            </div>
            {{$transportes->render()}}
        </div>
    </div>
@endsection