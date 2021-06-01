<form method="get" action="{{ route('projects.index') }}">

    <div class="row row-filters">
        <div class="col-12">
            @foreach(trans('projects.filters.status') as $value => $text)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="status_{{ $value }}"
                           value="{{ $value }}" {{ $value === request('status') ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_{{ $value }}">{{ $text }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row row-filters">
        <div class="col-12">
            @foreach(trans('projects.filters.deadline') as $value => $text)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="deadline" id="deadline_{{ $value }}"
                           value="{{ $value }}" {{ $value === request('deadline') ? 'checked' : '' }}>
                    <label class="form-check-label" for="deadline_{{ $value }}">{{ $text }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row row-filters">
        <div class="col-12">
            <label for="budget" class="form-label">Presupuesto</label>
            <input type="range" name="budget" class="form-range" min="1" max="10" step="0.5" id="budget" value="{{ request('budget') }}">
            @if( request('budget'))
                <span>{{ request('budget')*1000 }} €</span>
            @endif

        </div>
    </div>

    <div class="row row-filters">
        <div class="col-12">
            <label for="teams">Número de equipos:</label>
            <input type="number" id="teams" name="teams" min="1" max="10" value="{{request('teams')}}">
            <label for="workers">Número de trabajadores:</label>
            <input type="number" id="workers" name="workers" min="1" max="15" value="{{request('workers')}}">
        </div>
    </div>


    <div class="row row-filters">
        <div class="col-md-3">
            <div class="form-inline form-search">
                <div class="input-group">
                    <input type="search" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm" placeholder="Buscar...">
                </div>
            </div>
        </div>

        <div class="col-md-6 text-right">
            <div class="form-inline form-dates">
                <label for="date_start" class="form-label-sm">Fecha</label>&nbsp;
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" name="from" id="from" placeholder="Desde" value="{{ request('from') }}">
                </div>
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" name="to" id="to" placeholder="Hasta" value="{{ request('to') }}">
                </div>
            </div>
        </div>

        <div class="col text-right">
            <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
        </div>
    </div>
</form>

