@extends('layouts.admin')

@section('contenido')

<h3 class="mb-4 text-center fw-semibold">
    Nuevo Ingreso
</h3>

@if (count($errors) > 0)
<div class="alert alert-danger rounded-3 shadow-sm">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ url('compras/ingreso') }}" method="post">
@csrf

{{-- ================= INFO INGRESO ================= --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">

        <h5 class="mb-4 text-muted fw-semibold">
            Información del ingreso
        </h5>

        <div class="row g-3">

            <div class="col-lg-12">
                <label class="form-label">Proveedor</label>
                <select name="provider_id" class="form-control selectpicker" data-live-search="true">
                    @foreach ($personas as $persona)
                        <option value="{{$persona->id}}">
                            {{$persona->name}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3">
                <label class="form-label">Tipo comprobante</label>
                <select name="type_voucher" class="form-control">
                    <option>Boleta</option>
                    <option>Factura</option>
                    <option>Ticket</option>
                </select>
            </div>

            <div class="col-lg-3">
                <label class="form-label">Serie</label>
                <input type="text" name="serial_voucher"
                    class="form-control"
                    value="{{ old('serial_voucher') }}">
            </div>

            <div class="col-lg-3">
                <label class="form-label">Número comprobante</label>
                <input type="text" name="number_voucher"
                    class="form-control"
                    required>
            </div>

            <div class="col-lg-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-control">
                    <option>Credito</option>
                    <option>Al contado</option>
                </select>
            </div>

        </div>

    </div>
</div>

{{-- ================= AGREGAR ARTICULO ================= --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">

        <h5 class="mb-4 text-muted fw-semibold">
            Agregar artículos
        </h5>

        <div class="row g-3 align-items-end">

            <div class="col-lg-3">
                <label class="form-label">Artículo</label>
                <select id="pidarticulo" class="form-control selectpicker" data-live-search="true">
                    @foreach ($articulos as $art)
                        <option value="{{$art->id}}">
                            {{$art->articulo}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2">
                <label class="form-label">Cantidad</label>
                <input type="number" id="pquantity" class="form-control">
            </div>

            <div class="col-lg-2">
                <label class="form-label">P. Compra</label>
                <input type="number" id="ppurchase_price" class="form-control">
            </div>

            <div class="col-lg-2">
                <label class="form-label">P. Venta</label>
                <input type="number" id="ppurchase_sale" class="form-control">
            </div>

            <div class="col-lg-3">
                <button type="button"
                    id="bt_add"
                    class="btn btn-dark w-100">
                    + Agregar artículo
                </button>
            </div>

        </div>

    </div>
</div>

{{-- ================= DETALLE ================= --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">

        <h5 class="mb-4 text-muted fw-semibold">
            Detalle del ingreso
        </h5>

        <div class="table-responsive">
            <table id="detalles"
                class="table table-hover align-middle rounded-table">

                <thead class="table-header-soft">
                    <th></th>
                    <th>Artículo</th>
                    <th>Cantidad</th>
                    <th>P. Compra</th>
                    <th>P. Venta</th>
                    <th>Subtotal</th>
                </thead>

                <tfoot>
                    <tr class="fw-semibold">
                        <td colspan="5" class="text-end">
                            TOTAL
                        </td>
                        <td>
                            <h5 id="total" class="mb-0">
                                S/. 0.00
                            </h5>
                        </td>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>
</div>

{{-- BOTONES --}}
<div class="text-end" id="guardar">
    <button type="submit" class="btn btn-dark px-4">
        Guardar ingreso
    </button>

    <a href="{{ url('compras/ingreso') }}"
        class="btn btn-outline-danger px-4">
        Cancelar
    </a>
</div>

</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

document.addEventListener("DOMContentLoaded", () => {

    const btnAdd = document.getElementById("bt_add");
    const table = document.getElementById("detalles");
    const totalLabel = document.getElementById("total");

    const btnGuardar = document.querySelector("#guardar button[type='submit']");

    let total = 0;
    let rowIndex = 0;

    // Deshabilitado al inicio
    btnGuardar.disabled = true;

    btnAdd.addEventListener("click", agregarArticulo);

    function agregarArticulo() {

        const selectArticulo = document.getElementById("pidarticulo");
        const cantidadInput = document.getElementById("pquantity");
        const compraInput = document.getElementById("ppurchase_price");
        const ventaInput = document.getElementById("ppurchase_sale");

        const idArticulo = selectArticulo.value;
        const articuloText = selectArticulo.options[selectArticulo.selectedIndex].text;

        const cantidad = parseFloat(cantidadInput.value);
        const precioCompra = parseFloat(compraInput.value);
        const precioVenta = parseFloat(ventaInput.value);

        if (!idArticulo || cantidad <= 0 || !precioCompra || !precioVenta) {

            Swal.fire({
                icon: "error",
                title: "Datos incompletos",
                text: "Verifica los campos del artículo"
            });

            return;
        }

        const subtotal = cantidad * precioCompra;
        total += subtotal;

        const fila = document.createElement("tr");

        fila.innerHTML = `
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger">✕</button>
            </td>

            <td>
                <input type="hidden" name="item_id[]" value="${idArticulo}">
                ${articuloText}
            </td>

            <td>
                <input type="number" name="quantity[]" value="${cantidad}" class="form-control form-control-sm quantity">
            </td>

            <td>
                <input type="number" name="purchase_price[]" value="${precioCompra}" class="form-control form-control-sm purchase">
            </td>

            <td>
                <input type="number" name="sale_price[]" value="${precioVenta}" class="form-control form-control-sm">
            </td>

            <td class="subtotal">
                S/. ${subtotal.toFixed(2)}
            </td>
        `;

        // eliminar fila
        fila.querySelector("button").addEventListener("click", () => {

            const sub = parseFloat(
                fila.querySelector(".subtotal").textContent.replace("S/.","")
            );

            total -= sub;
            fila.remove();
            actualizarEstado();
        });

        // recalcular subtotal si editan
        fila.querySelectorAll(".quantity, .purchase").forEach(input => {
            input.addEventListener("input", () => recalcularFila(fila));
        });

        table.appendChild(fila);

        limpiarInputs();
        actualizarEstado();

        rowIndex++;
    }

    function recalcularFila(fila) {

        const cantidad = parseFloat(fila.querySelector(".quantity").value) || 0;
        const compra = parseFloat(fila.querySelector(".purchase").value) || 0;

        const subtotalCell = fila.querySelector(".subtotal");
        const oldSubtotal = parseFloat(subtotalCell.textContent.replace("S/.","")) || 0;

        const newSubtotal = cantidad * compra;

        total = total - oldSubtotal + newSubtotal;

        subtotalCell.textContent = "S/. " + newSubtotal.toFixed(2);

        actualizarEstado();
    }

    function limpiarInputs() {
        document.getElementById("pquantity").value = "";
        document.getElementById("ppurchase_price").value = "";
        document.getElementById("ppurchase_sale").value = "";
    }

    function actualizarEstado() {

        totalLabel.textContent = "S/. " + total.toFixed(2);
        btnGuardar.disabled = total <= 0;
    }

});

</script>
@endpush

@endsection
