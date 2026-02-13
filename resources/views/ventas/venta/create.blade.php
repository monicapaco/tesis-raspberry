@extends('layouts.admin')

@section('contenido')
    <h3 class="mb-4 text-center fw-semibold">
        Nueva Venta
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

    <form action="{{ url('ventas/venta') }}" method="POST">
        @csrf

        {{-- ================= INFO VENTA ================= --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">

                <h5 class="mb-4 text-muted fw-semibold">
                    Información de la venta
                </h5>

                <div class="row g-3">

                    <div class="col-lg-12">
                        <label class="form-label">Cliente</label>
                        <select name="client_id" class="form-control selectpicker" data-live-search="true">
                            @foreach ($personas as $persona)
                                <option value="{{ $persona->id }}">{{ $persona->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-12">
                        <label class="form-label">Transporte</label>
                        <select name="carrier_id" class="form-control selectpicker" data-live-search="true">
                            @foreach ($transportes as $persona)
                                <option value="{{ $persona->id }}">{{ $persona->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label">Tipo comprobante</label>
                        <select name="type_voucher" class="form-control" required>
                            <option>Boleta</option>
                            <option>Factura</option>
                            <option>Ticket</option>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label">Serie</label>
                        <input type="text" name="serial_voucher" value="{{ old('serial_voucher') }}"
                            class="form-control">
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label">Número comprobante</label>
                        <input type="text" name="number_voucher" value="{{ old('number_voucher') }}"
                            class="form-control">
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-control" required>
                            <option value="Credito">Credito</option>
                            <option value="Al contado">Al contado</option>
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
                                <option value="{{ $art->id }}_{{ $art->stock }}_{{ $art->precio_promedio }}">
                                    {{ $art->articulo }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" id="pquantity" class="form-control">
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label">Stock</label>
                        <input type="number" id="pstock" class="form-control" disabled>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label">Precio venta</label>
                        <input type="number" id="ppurchase_sale" step="0.10" class="form-control">
                    </div>

                    <div class="col-lg-3">
                        <button type="button" id="bt_add" class="btn btn-dark w-100">
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
                    Detalle de la venta
                </h5>

                <div class="table-responsive">
                    <table id="detalles" class="table table-hover align-middle rounded-table">

                        <thead class="table-header-soft">
                            <th></th>
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </thead>

                        <tfoot>
                            <tr class="fw-semibold">
                                <td colspan="4" class="text-end">TOTAL</td>
                                <td>
                                    <h5 id="total" class="mb-0">S/. 0.00</h5>
                                    <input type="hidden" name="total" id="total_venta">
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
                Guardar venta
            </button>

            <a href="{{ url('ventas/venta') }}" class="btn btn-outline-danger px-4">
                Cancelar
            </a>
        </div>

    </form>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener("DOMContentLoaded", () => {

                let total = 0;
                let index = 0;

                const btnAdd = document.getElementById("bt_add");
                const table = document.getElementById("detalles");
                const totalLabel = document.getElementById("total");
                const totalInput = document.getElementById("total_venta");
                const btnGuardar = document.querySelector("#guardar button");

                btnGuardar.disabled = true;

                document.getElementById("pidarticulo").addEventListener("change", mostrarValores);
                mostrarValores();

                btnAdd.addEventListener("click", agregarArticulo);

                function mostrarValores() {

                    const datos = document.getElementById("pidarticulo").value.split('_');

                    document.getElementById("pstock").value = datos[1];
                    document.getElementById("ppurchase_sale").value = datos[2];

                }

                function agregarArticulo() {

                    const datos = document.getElementById("pidarticulo").value.split('_');

                    const idArticulo = datos[0];
                    const stock = parseFloat(datos[1]);

                    const articuloText =
                        document.getElementById("pidarticulo")
                        .options[document.getElementById("pidarticulo").selectedIndex].text;

                    const cantidad = parseFloat(document.getElementById("pquantity").value);
                    const precio = parseFloat(document.getElementById("ppurchase_sale").value);

                    if (!idArticulo || cantidad <= 0 || !precio) {
                        Swal.fire("Error", "Verifique los datos del artículo", "error");
                        return;
                    }

                    if (cantidad > stock) {
                        Swal.fire("Stock insuficiente",
                            "Solo hay " + stock + " unidades disponibles", "warning");
                        return;
                    }

                    const subtotal = cantidad * precio;
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
                        <input type="number" name="quantity[]" value="${cantidad}"
                        class="form-control form-control-sm quantity">
                        </td>

                        <td>
                        <input type="number" name="sale_price[]" value="${precio}"
                        class="form-control form-control-sm price">
                        </td>

                        <td class="subtotal">
                        S/. ${subtotal.toFixed(2)}
                        </td>
                    `;

                    fila.querySelector("button").addEventListener("click", () => {

                        const sub = parseFloat(
                            fila.querySelector(".subtotal").textContent.replace("S/.", "")
                        );

                        total -= sub;
                        fila.remove();
                        actualizarTotal();

                    });

                    fila.querySelectorAll(".quantity, .price").forEach(input => {
                        input.addEventListener("input", () => recalcularFila(fila));
                    });

                    table.appendChild(fila);

                    limpiar();
                    actualizarTotal();

                    index++;

                }

                function recalcularFila(fila) {

                    const cantidad = parseFloat(fila.querySelector(".quantity").value) || 0;
                    const precio = parseFloat(fila.querySelector(".price").value) || 0;

                    const subtotalCell = fila.querySelector(".subtotal");

                    const oldSubtotal = parseFloat(subtotalCell.textContent.replace("S/.", ""));

                    const newSubtotal = cantidad * precio;

                    total = total - oldSubtotal + newSubtotal;

                    subtotalCell.textContent = "S/. " + newSubtotal.toFixed(2);

                    actualizarTotal();

                }

                function limpiar() {
                    document.getElementById("pquantity").value = "";
                }

                function actualizarTotal() {

                    totalLabel.textContent = "S/. " + total.toFixed(2);
                    totalInput.value = total.toFixed(2);

                    btnGuardar.disabled = total <= 0;

                }

            });
        </script>
    @endpush
@endsection
