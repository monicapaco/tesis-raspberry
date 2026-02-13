<form method="GET" action="{{ url('ventas/cliente') }}" autocomplete="off" role="search">
    @csrf
    <div class="form-group">
        <div class="input-group">
            <input type="text" name="searchText" class="form-control" placeholder="Ingrese nombre o número de documento"
                value="{{ $searchText }}">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary ms-2">Buscar</button>
            </span>
        </div>
    </div>
</form>
