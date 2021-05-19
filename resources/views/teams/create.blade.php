@extends('layout')

@section('title', 'Nuevo equipo')

@section('content')
    @card
    <div>Crear nueva profesion</div>
    @slot('header', 'Crear nueva equipo')
    @include('shared._errors')

    <form method="post" action="{{ route('teams.store') }}">
        @include('teams._fields')

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Crear el nuevo equipo</button>
            <a href="{{ route('teams.index') }}" class="btn btn-link">Regresar al listado de equipos</a>
        </div>
    </form>
    @endcard
@endsection

