@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nuevo articulo</h3>
            @if (count($errors)>0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <form action="{{url('almacen/articulo')}}" method="post" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" required value="{{old('name')}}" id="" class="form-control" placeholder="Nombre...">
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="name">Categoria</label>
                    <select name="category_id" id="" class="form-control">
                        @foreach ($categorias as $cat)
                            <option value="{{$cat->id}}">{{$cat->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="codevar">Código</label>
                    <input type="text" name="codevar" required value="{{old('codevar')}}" id="" class="form-control" placeholder="Codigo...">
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="text" name="stock" required value="{{old('stock')}}" id="" class="form-control" placeholder="Stock...">
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <input type="text" name="description" required value="{{old('description')}}" id="" class="form-control" placeholder="Descripción...">
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="description">Imagen</label>
                    <input type="file" name="img" class="form-control">
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="reset"  class="btn btn-danger">Cancelar</button>
                </div>
            </div>
        </div>
        
    </form>

        
@endsection