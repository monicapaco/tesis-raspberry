<div class="modal fade"
     id="modalDelete{{ $art->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">

            <form action="{{ route('articulo.destroy',$art->id) }}" method="POST">
                @csrf
                @method('DELETE')

                {{-- HEADER --}}
                <div class="modal-header border-0 pb-0">

                    <h5 class="modal-title fw-semibold text-danger">
                        Eliminar artículo
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                {{-- BODY --}}
                <div class="modal-body text-center">

                    <p class="mb-1">
                        ¿Deseas eliminar el artículo?
                    </p>

                    <strong>{{ $art->name }}</strong>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0">

                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn btn-danger">
                        Confirmar eliminación
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
