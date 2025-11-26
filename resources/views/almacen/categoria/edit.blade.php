@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Editar categoria {{ $categoria->name}}</h3>
            @if (count($errors)>0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('categoria.update', $categoria->id) }}" method="post" >
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="" class="form-control" placeholder="Nombre..." value="{{$categoria->name}}">
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <input type="text" name="description" id="" class="form-control" placeholder="Descripción..." value="{{$categoria->description}}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="reset"  class="btn btn-danger">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
@endsection