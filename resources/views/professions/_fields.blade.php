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
    <label for="workday">Jornada laboral:</label>
    <input type="text" name="workday" placeholder="Jornada" value="{{old('workday', $profession->workday)}}" class="form-control">
</div>

<div class="form-group">
    <label for="academic_level">Nivel academico:</label>
    <input type="text" name="academic_level" placeholder="Nivel academico" value="{{old('academic_level', $profession->academic_level)}}" class="form-control">
</div>

<div class="form-group">
    <label for="experience">Años de experiencia requeridos:</label>
    <input type="text" name="experience" placeholder="Experiecia" value="{{old('experience', $profession->experience)}}" class="form-control">
</div>

<h5 class="mt-3">Requiere idiomas</h5>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="language" value="1" {{ old('language', $profession->language)==1?'checked':'' }}>
    <label class="form-check-label" for="">Si</label>
    <input class="form-check-input" type="radio" name="language" value="0" {{ (old('language', $profession->language))==0?'checked':'' }}>
    <label class="form-check-label" for="">No</label>
</div>

<h5 class="mt-3">Requiere transporte</h5>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="vehicle" value="1" {{old('vehicle', $profession->vehicle)==1?'checked':'' }}>
    <label class="form-check-label" for="">Si</label>
    <input class="form-check-input" type="radio" name="vehicle" value="0" {{old('vehicle', $profession->vehicle)==0?'checked':'' }}>
    <label class="form-check-label" for="">No</label>
</div>
