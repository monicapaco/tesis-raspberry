@extends('layouts.admin')

@section('contenido')

<h3 class="mb-4 text-center fw-semibold">
    Editar artículo
</h3>

@if ($errors->any())
<div class="alert alert-danger rounded-3 shadow-sm">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<form action="{{ route('articulo.update',$articulo->id) }}"
      method="POST"
      enctype="multipart/form-data">

@csrf
@method('PATCH')


{{-- ================= INFO ARTICULO ================= --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
<div class="card-body">

    <h5 class="mb-4 text-muted fw-semibold">
        Información del artículo
    </h5>

    <div class="row g-3">

        {{-- Nombre --}}
        <div class="col-lg-6">
            <label class="form-label">Nombre *</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name',$articulo->name) }}"
                   required>
        </div>

        {{-- Categoria --}}
        <div class="col-lg-6">
            <label class="form-label">Categoría *</label>

            <select name="category_id"
                    class="form-control selectpicker"
                    data-live-search="true"
                    required>

                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}"
                        {{ $cat->id == $articulo->category_id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach

            </select>
        </div>

        {{-- Código --}}
        <div class="col-lg-6">
            <label class="form-label">Código *</label>
            <input type="text"
                   name="codevar"
                   class="form-control"
                   value="{{ old('codevar',$articulo->codevar) }}"
                   required>
        </div>

        {{-- Stock --}}
        <div class="col-lg-6">
            <label class="form-label">Stock *</label>
            <input type="number"
                   name="stock"
                   class="form-control"
                   value="{{ old('stock',$articulo->stock) }}"
                   required>
        </div>

        {{-- Descripción --}}
        <div class="col-lg-12">
            <label class="form-label">Descripción *</label>
            <input type="text"
                   name="description"
                   class="form-control"
                   value="{{ old('description',$articulo->description) }}"
                   required>
        </div>

    </div>

</div>
</div>


{{-- ================= IMAGEN ================= --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
<div class="card-body">

    <h5 class="mb-4 text-muted fw-semibold">
        Imagen del artículo
    </h5>

    <div id="imageDropzone"
         class="border rounded-4 p-4 text-center position-relative upload-zone">

        <input type="file"
               name="img"
               id="imageInput"
               accept="image/*"
               class="d-none">


        {{-- Placeholder --}}
        <div id="uploadPlaceholder"
             class="{{ $articulo->img ? 'd-none' : '' }}">

            <i class="bi bi-image fs-1 text-muted"></i>

            <p class="mb-1 fw-semibold">
                Arrastra una imagen aquí
            </p>

            <p class="text-muted small">
                o haz clic para seleccionar archivo
            </p>

        </div>


        {{-- Preview --}}
        <div id="previewContainer"
             class="{{ $articulo->img ? '' : 'd-none' }}">

            <img id="previewImg"
                 class="img-fluid rounded-3 shadow-sm"
                 style="max-height:200px;"
                 src="{{ $articulo->img ? asset('imagenes/articulos/'.$articulo->img) : '' }}">

            <p id="fileName"
               class="small text-muted mt-2 mb-2">
                {{ $articulo->img ?? '' }}
            </p>

            <button type="button"
                    id="removeImage"
                    class="btn btn-outline-danger btn-sm">
                Quitar imagen
            </button>

        </div>

    </div>

</div>
</div>


{{-- BOTONES --}}
<div class="text-end">

    <button type="submit"
            class="btn btn-dark px-4">
        Actualizar artículo
    </button>

    <a href="{{ url('almacen/articulo') }}"
       class="btn btn-outline-danger px-4">
        Cancelar
    </a>

</div>

</form>

@push('scripts')
<script>

document.addEventListener("DOMContentLoaded", () => {

    const dropzone = document.getElementById("imageDropzone");
    const input = document.getElementById("imageInput");
    const preview = document.getElementById("previewImg");

    const placeholder = document.getElementById("uploadPlaceholder");
    const previewContainer = document.getElementById("previewContainer");
    const fileName = document.getElementById("fileName");
    const removeBtn = document.getElementById("removeImage");


    dropzone.addEventListener("click", () => input.click());


    dropzone.addEventListener("dragover", e => {
        e.preventDefault();
        dropzone.classList.add("dragover");
    });

    dropzone.addEventListener("dragleave", () => {
        dropzone.classList.remove("dragover");
    });

    dropzone.addEventListener("drop", e => {

        e.preventDefault();
        dropzone.classList.remove("dragover");

        const file = e.dataTransfer.files[0];
        if(file){
            input.files = e.dataTransfer.files;
            mostrarPreview(file);
        }
    });


    input.addEventListener("change", e => {
        if(e.target.files[0]){
            mostrarPreview(e.target.files[0]);
        }
    });


    function mostrarPreview(file){

        preview.src = URL.createObjectURL(file);
        fileName.textContent = file.name;

        placeholder.classList.add("d-none");
        previewContainer.classList.remove("d-none");
    }


    removeBtn.addEventListener("click", e => {

        e.stopPropagation();

        input.value = "";
        preview.src = "";

        previewContainer.classList.add("d-none");
        placeholder.classList.remove("d-none");
    });

});
</script>
@endpush

@endsection
