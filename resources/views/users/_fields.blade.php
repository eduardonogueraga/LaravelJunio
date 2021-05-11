{{ csrf_field() }}
<div class="form-group">
    <label for="first_name">Nombre:</label>
    <input type="text" name="first_name" placeholder="Nombre" value="{{ old('first_name', $user->first_name) }}" class="form-control">
</div>
<div class="form-group">
    <label for="last_name">Apellidos:</label>
    <input type="text" name="last_name" placeholder="Apellidos" value="{{ old('last_name', $user->last_name) }}" class="form-control">
</div>
<div class="form-group">
    <label for="email">Correo electrónico:</label>
    <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email', $user->email) }}" class="form-control">
</div>
<div class="form-group">
    <label for="password">Contraseña:</label>
    <input type="password" name="password" placeholder="Al menos 6 caracteres" class="form-control">
</div>
<div class="form-group">
    <label for="profession_id">Profesión: </label>
    <select name="profession_id" id="profession_id" class="form-control">
        <option value="">Selecciona una opción</option>
        @foreach($professions as $profession)
            <option value="{{ $profession->id }}" {{ old('profession_id', $user->profile->profession_id) == $profession->id ? ' selected' : '' }}>{{ $profession->title }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="other_profession">Otra profesion:</label>
    <input type="text" name="other_profession" placeholder="Otra profesion" value="{{ old('other_profession', $user->other_profession)  }}" class="form-control">
</div>

<div class="form-group">
    <label for="region">Region o estado:</label>
    <input type="text" name="region" placeholder="Estado" value="{{ old('region', $user->address->region) }}" class="form-control">
</div>

<div class="form-group">
    <label for="city">Ciudad:</label>
    <input type="text" name="city" placeholder="Ciudad" value="{{ old('city', $user->address->city) }}" class="form-control">
</div>

<div class="form-group">
    <label for="street">Calle:</label>
    <input type="text" name="street" placeholder="Calle" value="{{ old('street', $user->address->street) }}" class="form-control">
</div>

<div class="form-group">
    <label for="country_id">País: </label>
    <select name="country_id" id="country_id" class="form-control">
        <option value="">Selecciona una opción</option>
        @foreach($countries as $country)
            <option value="{{ $country->id }}" {{(old('country_id', $user->address->country->id) == $country->id ) ? ' selected' : '' }}>{{ $country->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="zipcode">Código psotal:</label>
    <input type="text" name="zipcode" placeholder="Código psotal" value="{{ old('zipcode', $user->address->zipcode) }}" class="form-control">
</div>

<div class="form-group">
    <label for="bio">Biografía:</label>
    <textarea name="bio" placeholder="Biografía" class="form-control">{{ old('bio', $user->profile->bio) }}</textarea>
</div>
<div class="form-group">
    <label for="twitter">Twitter:</label>
    <input type="text" name="twitter" placeholder="Twitter" value="{{ old('twitter', $user->profile->twitter) }}" class="form-control">
</div>

<h5>Habilidades</h5>
@foreach($skills as $skill)
    <div class="form-check form-check-inline">
        <input name="skills[{{ $skill->id }}]" class="form-check-input" type="checkbox"
               id="skill_{{ $skill->id }}" value="{{ $skill->id }}"
                {{ ($errors->any() ? old('skills.'.$skill->id) : $user->skills->contains($skill)) ? 'checked' : '' }}>
        <label class="form-check-label" for="skill_{{ $skill->id }}">{{ $skill->name }}</label>
    </div>
@endforeach

<h5 class="mt-3">Rol</h5>
@foreach(trans('users.roles') as $role => $name)
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="role" id="role_{{ $role }}" value="{{ $role }}"
                {{ old('role', $user->role) == $role ? 'checked' : '' }}>
        <label class="form-check-label" for="role_{{ $role }}">{{ $name }}</label>
    </div>
@endforeach

<h5 class="mt-3">Estado</h5>

@foreach(trans('users.states') as $state => $label)
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="state" id="state_{{ $state }}" value="{{ $state }}"
            {{ old('state', $user->state) == $state ? 'checked' : ''}}>
        <label class="form-check-label" for="state_{{ $state }}">{{ $label }}</label>
    </div>
@endforeach
