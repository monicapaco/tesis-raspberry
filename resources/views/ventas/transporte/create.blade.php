@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nuevo transporte</h3>
            @if (count($errors)>0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{url('ventas/transporte')}}" method="post" autocomplete="off" >
                @csrf
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" name="name" required value="{{ old('name') }}" id=""
                            class="form-control" placeholder="Nombre...">
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Tipo documento</label>
                        <select name="type_document" id="" class="form-control">
                            <option value="DNI">DNI</option>
                            <option value="RUC">RUC</option>
                            <option value="PAS">PAS</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <div class="form-group">
                        <label for="name">Número documento</label>
                        <input type="text" name="n_document" required value="{{ old('n_document') }}" id="" class="form-control" placeholder="Número de documento...">
                    </div>
                </div>
                <div class="form-group col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="reset"  class="btn btn-danger">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
@endsection