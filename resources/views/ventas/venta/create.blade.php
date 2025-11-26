@extends('layouts.admin')
@section('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nueva Venta</h3>
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

    <form action="{{url('ventas/venta')}}" method="post" autocomplete="off">
        @csrf
        <pre>{{ print_r($articulos, true) }}</pre>
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="form-group">
                    <label for="provider">Cliente</label>
                    <select name="client_id" id="client_id" class="form-control selectpicker" data-live-search="true">
                        @foreach ($personas as $persona)
                            <option value="{{$persona->id}}">{{$persona->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="form-group">
                    <label for="provider">Transporte</label>
                    <select name="carrier_id" id="carrier_id" class="form-control selectpicker" data-live-search="true">
                        @foreach ($transportes as $persona)
                            <option value="{{$persona->id}}">{{$persona->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                <div class="form-group">
                    <label for="type_voucher">Tipo comprobante</label>
                    <select name="type_voucher" id="" class="form-control">
                        <option value="Boleta">Boleta</option>
                        <option value="Factura">Factura</option>
                        <option value="Ticket">Ticket</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                <div class="form-group">
                    <label for="serial_voucher">Serie comprobante</label>
                    <input type="text" name="serial_voucher"  value="{{ old('serial_voucher') }}" id="" class="form-control" placeholder="Serie comprobante...">
                </div>
            </div>
            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                <div class="form-group">
                    <label for="number_voucher">Número de comprobante</label>
                    <input type="text" name="number_voucher" required value="{{ old('number_voucher') }}" id="" class="form-control" placeholder="Número de comprobante...">
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
                    <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                        <div class="form-group">
                            <label for="">Articulo</label>
                            
                            <select name="pidarticulo" id="pidarticulo" class="form-control selectpicker" data-live-search="true">
                                @foreach ($articulos as $art)
                                    <option value="{{$art->id}}_{{$art->stock}}_{{$art->precio_promedio}}">{{$art->articulo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                        <div class="form-group">
                            <label for="pquantity">Cantidad</label>
                            <input type="number" name="pquantity" id="pquantity"  class="form-control" placeholder="Cantidad...">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                        <div class="form-group">
                            <label for="pstock">Stock</label>
                            <input type="number" disabled name="pstock" id="pstock" class="form-control" placeholder="Stock...">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                        <div class="form-group">
                            <label for="ppurchase_sale">Precio de venta</label>
                            <input type="number" name="ppurchase_sale" id="ppurchase_sale" min="0" step="0.10" class="form-control" placeholder="P. venta...">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                        <div class="form-group">
                            <button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-2 col-xs-12">
                        <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                            <thead style="background-color: aqua">
                                <th>Opciones</th>
                                <th>Artículo</th>
                                <th>Cantidad</th>
                                <th>Precio venta</th>
                                <th>Subtotal</th>
                            </thead>
                            <tfoot>
                                <th>TOTAL</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><h4 id="total">S/. 0.00</h4><input type="hidden" name="total" id="total_venta"></th>
                            </tfoot>
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
@push('scripts')
    <script>
        $(document).ready(function(){
            $('#bt_add').click(function(){
                agregar();
            });
        });

        let cont=0;
        total=0;
        subtotal=[];
        $("#guardar").hide();
        $("#pidarticulo").change(mostrarValores);
        mostrarValores()

        function mostrarValores(){
            datosArticulo=document.getElementById('pidarticulo').value.split('_');
            $("#ppurchase_sale").val(datosArticulo[2]);
            $("#pstock").val(datosArticulo[1]);
        }

        function agregar(){
            datosArticulo=document.getElementById('pidarticulo').value.split('_');
            
            idarticulo=datosArticulo[0];
            articulo=$("#pidarticulo option:selected").text();
            cantidad=$("#pquantity").val();
            precio_venta=$("#ppurchase_sale").val();
            stock=$("#pstock").val();


            if (idarticulo!="" && cantidad!="" && cantidad>0  && precio_venta!=""){
                if (parseInt(stock)>=parseInt(cantidad)){
                    subtotal[cont]=(cantidad*precio_venta);
                    total=total+subtotal[cont];
                    
                    let fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="item_id[]" value="'+idarticulo+'">'+articulo+'</td><td><input type="number" name="quantity[]" value="'+cantidad+'"></td><td><input type="number" name="sale_price[]" value="'+precio_venta+'"></td><td>'+subtotal[cont]+'</td></tr>';
                    cont++;
                    limpiar();
                    $("#total").html("S/. "+total);
                    $("#total_venta").val(total);
                    evaluar();
                    $("#detalles").append(fila);
                }
                else{
                    Swal.fire({
                    icon: 'warning',
                    title: 'Stock insuficiente',
                    text: 'La cantidad a vender supera el stock disponible (' + stock + ' disponibles, intentaste vender ' + cantidad + ').',
                });
                }
            }
            else{
                Swal.fire({
                icon: 'error',
                title: 'Error en el detalle de venta',
                text: 'Revisa los datos del artículo antes de continuar.',
                });
            }
        }
        function limpiar(){
            $("#pquantity").val("");
            $("#ppurchase_sale").val("");
        }
        function evaluar(){
            if(total>0){
                $("#guardar").show();
            }
            else{
                $("#guardar").hide();
            }
        }
        function eliminar(index){
            total=total-subtotal[index];
            $("#total").html("S/."+total);
            $("#total_venta").val(total);
            $("#fila"+index).remove();
            evaluar();
        }
    </script>
@endpush