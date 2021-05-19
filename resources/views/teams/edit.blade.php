@extends('layout')

@section('title', 'Editar equipo')

@section('content')
    @card
    <div>Editar equipo</div>
    @slot('header', 'Editar equipo')
    @include('shared._errors')

    <form method="post" action="{{ route('teams.update', $team) }}">
        {{ method_field('PUT') }}
        @include('teams._fields')

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Actualizar equipo</button>
            <a href="{{ route('teams.index') }}" class="btn btn-link">Regresar al listado de equipos</a>
        </div>
    </form>
    @endcard
@endsection

