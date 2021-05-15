{{ csrf_field() }}
<div class="form-group">
    <label for="title">Titulo:</label>
    <input type="text" name="title" placeholder="Titulo" value="{{old('title', $profession->title)}}" class="form-control">
</div>

<div class="form-group">
    <label for="salary">Salario anual:</label>
    <input type="text" name="salary" placeholder="Salario" value="{{old('salary', $profession->salary)}}" class="form-control">
</div>

<div class="form-group">
    <label for="workday">Jornada laboral: </label>
    <select name="workday" id="workday" class="form-control">
        <option value="">Selecciona una opción</option>
        @foreach(trans('professions.workday') as $value)
            <option value="{{ $value }}" {{(old('workday', $profession->workday) == $value ) ? ' selected' : '' }}>{{$value }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="academic_level">Nivel academico: </label>
    <select name="academic_level" id="academic_level" class="form-control">
        <option value="">Selecciona una opción</option>
        @foreach(trans('professions.academic_level') as $value)
            <option value="{{ $value }}" {{(old('academic_level', $profession->academic_level) == $value ) ? ' selected' : '' }}>{{$value }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="experience">Años de experiencia requeridos:</label>
    <input type="text" name="experience" placeholder="Experiecia" value="{{old('experience', $profession->experience)}}" class="form-control">
</div>

<h5 class="mt-3">Requiere idiomas</h5>

@foreach(trans('professions.form.language') as $value => $label)
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="language" id="language_{{ $value }}" value="{{ $value }}"
                {{ old('language', $profession->language) == $value ? 'checked' : ''}}>
        <label class="form-check-label" for="language_{{ $value }}">{{ $label }}</label>
    </div>
@endforeach

<h5 class="mt-3">Requiere vehiculo privado</h5>

@foreach(trans('professions.form.transport') as $value => $label)
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="vehicle" id="vehicle_{{ $value }}" value="{{ $value }}"
                {{ old('vehicle', $profession->vehicle) == $value ? 'checked' : ''}}>
        <label class="form-check-label" for="vehicle_{{ $value }}">{{ $label }}</label>
    </div>
@endforeach

