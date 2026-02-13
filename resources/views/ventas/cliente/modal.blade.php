<div class="modal fade" id="modalDelete{{ $per->id }}" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content rounded-4 border-0 shadow">

            <form action="{{ route('cliente.destroy', $per->id) }}" method="POST">

                @csrf
                @method('DELETE')


                <div class="modal-header border-0 pb-0">

                    <h5 class="modal-title fw-semibold">
                        Eliminar cliente
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body text-center">

                    <i class="bi bi-exclamation-triangle text-danger fs-1"></i>

                    <p class="mt-3 mb-0">
                        ¿Seguro que deseas eliminar este cliente?
                    </p>

                    <small class="text-muted">
                        Esta acción no se puede deshacer
                    </small>

                </div>


                <div class="modal-footer border-0 justify-content-center">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-danger">
                        Sí, eliminar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
