<form method="get" action="{{ route('teams.index') }}">

    <div class="row row-filters">
        <div class="col-12">
            @foreach(trans('teams.filters.workers') as $value => $text)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="worker" id="worker_{{ $value }}"
                           value="{{ $value }}" {{ $value === request('worker') ? 'checked' : '' }}>
                    <label class="form-check-label" for="worker_{{ $value }}">{{ $text }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row row-filters">
        <div class="col-12">
            @foreach(trans('teams.filters.professions') as $value => $text)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="profession" id="profession_{{ $value }}"
                           value="{{ $value }}" {{ $value === request('profession') ? 'checked' : '' }}>
                    <label class="form-check-label" for="profession_{{ $value }}">{{ $text }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row row-filters">
        <div class="col-12">
            @foreach(trans('teams.filters.projects') as $value => $text)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="projects" id="projects_{{ $value }}"
                           value="{{ $value }}" {{ $value === request('projects') ? 'checked' : '' }}>
                    <label class="form-check-label" for="projects_{{ $value }}">{{ $text }}</label>
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

                <div class="btn-group drop-skills">
                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Profesiones
                    </button>
                    <div class="drop-menu skills-list">
                        @foreach($professions as $profession)
                            <div class="form-group form-check">
                                <input type="checkbox" name="professions[]" class="form-check-input" id="profession_{{ $profession->id }}" value="{{ $profession->id }}"
                                        {{ $checkedProfessions->contains($profession->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="profession">{{ $profession->title }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="btn-group drop-skills">
                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Proyectos en curso
                    </button>
                    <div class="drop-menu skills-list">
                        @foreach($projects as $project)
                            <div class="form-group form-check">
                                <input type="checkbox" name="actives[]" class="form-check-input" id="actives_{{ $project->id }}" value="{{ $project->id }}"
                                        {{ $checkedProjects->contains($project->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actives">{{ $project->title }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-3">
            <div class="btn-group">
                <select name="headquarter" id="headquarter" class="select-field">
                    <option value="" selected disabled hidden>Filtrar por sedes</option>
                    @foreach($headquarters as $value)
                        <option value="{{ $value->name }}" {{ request('headquarter') === $value ? 'selected' : '' }}>{{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col text-right">
            <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
        </div>
    </div>
</form>
