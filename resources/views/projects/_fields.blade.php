{{ csrf_field() }}

<div class="form-group">
    <label for="title">Nombre del proyecto:</label>
    <input type="text" name="title" placeholder="Nombre del proyecto" value="{{old('title', $project->title)}}" class="form-control">
</div>

<div class="form-group">
    <label for="about">Descripcion del proyecto:</label>
    <textarea name="about" placeholder="Descripcion" class="form-control">{{ old('about', $project->about) }}</textarea>
</div>

<div class="form-group">
    <label for="budget">Presupuesto:</label>
    <input type="number" id="budget" name="budget" min="1000" max="10000" step="0.01" value="{{old('budget', $project->budget) ?? 1000}}" class="form-control">
</div>
<h5 class="mt-3">Plazo</h5>

<div class="form-group">

    <label for="date_start" class="form-label-sm">Fecha limite</label>&nbsp;
        <div class="input-group">
            <input type="text" class="form-control form-control-sm" name="finish_date" id="finish_date"
                   placeholder="Fecha limite"
                   value="{{old('finish_date', \Carbon\Carbon::parse($project->finish_date)->format('d/m/Y'))}}">
        </div>
</div>

<h5 class="mt-3">Equipos</h5>
@foreach($teams as $team)
    <div class="form-check form-check-inline">
        <input name="teams[{{ $team->id }}]" class="form-check-input" type="checkbox"
               id="team_{{ $team->id }}" value="{{ $team->id }}"
                {{ ($errors->any() ? old('teams.'.$team->id) : $project->teams->contains($team)) ? 'checked' : '' }}>
        <label class="form-check-label" for="teams_{{ $team->id }}">{{ $team->name }}<span class="note">( Usuarios: {{$team->users_count}}, Proyectos: {{$team->projects_count}})</span></label>
    </div>
@endforeach

@if(url()->current() !== route('projects.create'))
    <h5 class="mt-3">Estado del proyecto</h5>

    @foreach(trans('projects.forms.status') as $state => $label)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="status" id="status_{{ $state }}" value="{{ $state }}"
                    {{ old('status', $project->status) == $state ? 'checked' : ''}}>
            <label class="form-check-label" for="status_{{ $state }}">{{ $label }}</label>
        </div>
    @endforeach
@endif







