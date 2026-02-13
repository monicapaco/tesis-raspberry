<div class="modal fade"
     id="modalDelete{{ $cat->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">

            <form action="{{ route('categoria.destroy',$cat->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-header border-0 pb-0">

                    <h5 class="modal-title fw-semibold text-danger">
                        Dar de baja categoría
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body text-center">

                    <p class="mb-1">
                        ¿Deseas desactivar la categoría?
                    </p>

                    <strong>{{ $cat->name }}</strong>

                </div>

                <div class="modal-footer border-0">

                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn btn-danger">
                        Confirmar
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
