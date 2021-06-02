{{ csrf_field() }}
<div class="form-group">
    <label for="name">Nombre del equipo:</label>
    <input type="text" name="name" placeholder="Nombre" value="{{old('name', $team->name)}}" class="form-control">
</div>

<div class="form-group">
    <label for="leader">Lider del equipo: </label>
    <select name="leader" id="leader" class="form-control">
        <option value="">Selecciona un usuario</option>
        @foreach($leaders as $leader)
            <option value="{{ $leader->id }}" {{(old('leader', $team->leader->id) == $leader->id ) ? ' selected' : '' }}>{{$leader->first_name . ' ' . $leader->last_name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    @foreach(range(0,2) as $x)
        <label for="headquarter-{{$x}}">Sede#{{$x}}</label>
        <input type="text" name="headquarters[]" id="headquarter-{{$x}}" class="form-control"
       @if (isset($team->headquarters[$x]) && is_object($team->headquarters[$x]))
             value="{{old('headquarters.'. $x, $team->headquarters[$x]->name)}}">
        @else
            value="{{old('headquarters.'. $x)}}">
        @endif
    @endforeach
</div>


<h5 class="mt-3">Profesiones</h5>
@foreach($professions as $profession)
    <div class="form-check form-check-inline">
        <input name="professions[{{ $profession->id }}]" class="form-check-input" type="checkbox"
               id="profession_{{ $profession->id }}" value="{{ $profession->id }}"
                {{ ($errors->any() ? old('professions.'.$profession->id) : $team->professions->contains($profession)) ? 'checked' : '' }}>
        <label class="form-check-label" for="skill_{{ $profession->id }}">{{ $profession->title }}</label>
    </div>
@endforeach