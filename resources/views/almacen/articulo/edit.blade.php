@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
            <h3>Editar articulo <br>{{ $articulo->name}}</h3>
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


    <form action="{{ route('articulo.update', $articulo->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" required value="{{$articulo->name}}" id="" class="form-control" placeholder="Nombre...">
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="name">Categoria</label>
                    <select name="category_id" id="" class="form-control">
                        @foreach ($categorias as $cat)
                            @if ($cat->id==$articulo->category_id)
                                <option value="{{$cat->id}}" selected>{{$cat->name}}</option>
                            @else
                                <option value="{{$cat->id}}" >{{$cat->name}}</option>
                            @endif
                            
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="codevar">Código</label>
                    <input type="text" name="codevar" required value="{{$articulo->codevar}}" id="" class="form-control" placeholder="Codigo...">
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="text" name="stock" required value="{{$articulo->stock}}" id="" class="form-control" placeholder="Stock...">
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <input type="text" name="description" required value="{{$articulo->description}}" id="" class="form-control" placeholder="Descripción...">
                </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="form-group">
                    
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                        <label for="description">Imagen</label>
                        <input type="file" name="img" class="form-control ">
                    </div>
                    @if (($articulo->img)!="")
                        <img src="{{asset('imagenes/articulos/'.$articulo->img)}}" alt="">
                    @endif
                    
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