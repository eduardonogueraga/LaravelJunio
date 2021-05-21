<form method="get" action="{{ route('teams.index') }}">
    <div class="row row-filters">
        <div class="col-md-3">
            <div class="form-inline form-search">
                <div class="input-group">
                    <input type="search" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm" placeholder="Buscar...">
                </div>
            </div>
        </div>
        <div class="col text-right">
            <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
        </div>
    </div>
</form>
