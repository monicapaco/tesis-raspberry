<div class="modal fade" id="modalDelete{{ $in->id }}" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content rounded-4 border-0 shadow">

            <form action="{{ route('venta.destroy', $in->id) }}" method="POST">

                @csrf
                @method('DELETE')

                {{-- HEADER --}}
                <div class="modal-header border-0 pb-0">

                    <h5 class="modal-title fw-semibold">
                        Anular venta
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                {{-- BODY --}}
                <div class="modal-body text-center">

                    <i class="bi bi-x-circle text-danger fs-1"></i>

                    <p class="mt-3 mb-0">
                        ¿Seguro que deseas anular esta venta?
                    </p>

                    <small class="text-muted">
                        La venta pasará a estado anulado
                    </small>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 justify-content-center">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-danger">
                        Sí, anular
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
