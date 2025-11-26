@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nueva categoria</h3>
            @if (count($errors)>0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{url('almacen/categoria')}}" method="post" autocomplete="off" >
                @csrf
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="" class="form-control" placeholder="Nombre...">
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea name="description" id="" cols="30" class="form-control" placeholder="Descripción..." rows="10"></textarea>
                    
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="reset"  class="btn btn-danger">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
@endsection