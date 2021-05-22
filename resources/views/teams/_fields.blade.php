{{ csrf_field() }}
<div class="form-group">
    <label for="name">Nombre del equipo:</label>
    <input type="text" name="name" placeholder="Nombre" value="{{old('name', $team->name)}}" class="form-control">
</div>

<div class="form-group">
    <label for="headquarter">Direccion y nombre de la sede:</label>
    <input type="text" name="headquarter" placeholder="Sede" value="{{old('headquarter', optional($team->headquarter)->name)}}" class="form-control">
</div>

<h5 class="mt-3">Profesiones</h5>
@foreach($professions as $profession)
    <div class="form-check form-check-inline">
        <input name="professions[{{ $profession->id }}]" class="form-check-input" type="checkbox"
               id="profession_{{ $profession->id }}" value="{{ $profession->id }}"
                {{ ($errors->any() ? old('professions['.$profession->id.']') : $team->professions->contains($profession)) ? 'checked' : '' }}>
        <label class="form-check-label" for="skill_{{ $profession->id }}">{{ $profession->title }}</label>
    </div>
@endforeach