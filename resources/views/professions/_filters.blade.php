<form method="get" action="{{ route('professions.index') }}">
    <div class="row row-filters">
        <div class="col-12">
            @foreach(trans('professions.filters.language') as $value => $text)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="language" id="language_{{ $value }}"
                           value="{{ $value }}" {{ $value === request('language') ? 'checked' : '' }}>
                    <label class="form-check-label" for="language_{{ $value }}">{{ $text }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row row-filters">
        <div class="col-12">
            @foreach(trans('professions.filters.transport') as $value => $text)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="transport" id="transport_{{ $value }}"
                           value="{{ $value }}" {{ $value === request('transport') ? 'checked' : '' }}>
                    <label class="form-check-label" for="transport_{{ $value }}">{{ $text }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row row-filters">
        <div class="col-12">
            @foreach(trans('professions.filters.experience') as $value => $text)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="experience" id="experience_{{ $value }}"
                           value="{{ $value }}" {{ $value === request('experience') ? 'checked' : '' }}>
                    <label class="form-check-label" for="experience_{{ $value }}">{{ $text }}</label>
                </div>
            @endforeach
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
            <div class="col-md-3">
                <div class="btn-group">
                    <select name="workday" id="workday" class="select-field">
                        <option value="" selected disabled hidden>Filtrar por jornada</option>
                        @foreach(trans('professions.workday') as $value)
                            <option value="{{ $value }}" {{ request('workday') === $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="btn-group">
                    <select name="academic_level" id="academic_level" class="select-field">
                        <option value="" selected disabled hidden>Filtrar por niveles academicos</option>
                        @foreach(trans('professions.academic_level') as $value)
                            <option value="{{ $value }}" {{ request('academic_level') === $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        <div class="col text-right">
            <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
        </div>
    </div>

</form>

