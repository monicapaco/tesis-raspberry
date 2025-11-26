@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Editar transporte {{ $transporte->name}}</h3>
            @if (count($errors)>0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('transporte.update', $transporte->id) }}" method="post" >
                @csrf
                @method('PATCH')
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" name="name" required value="{{ $transporte->name}}" id=""
                            class="form-control" placeholder="Nombre...">
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Tipo documento</label>
                        <select name="type_document" id="" class="form-control">
                            @if ($transporte->type_document=='DNI')
                            <option value="DNI" selected>DNI</option>
                            <option value="RUC">RUC</option>
                            <option value="PAS">PAS</option>
                        @elseif ($transporte->type_document=='RUC')
                            <option value="DNI">DNI</option>
                            <option value="RUC" selected>RUC</option>
                            <option value="PAS">PAS</option>
                        @else
                            <option value="DNI">DNI</option>
                            <option value="RUC">RUC</option>
                            <option value="PAS" selected>PAS</option>
                        @endif
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Número documento</label>
                        <input type="text" name="n_document" required value="{{ $transporte->n_document }}" id="" class="form-control" placeholder="Número de documento...">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="reset"  class="btn btn-danger">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
@endsection