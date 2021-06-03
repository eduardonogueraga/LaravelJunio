@extends('layout')

@section('title', 'Nuevo proyecto')

@section('content')
    @card
    @slot('header', 'Crear nuevo proyecto')
    @include('shared._errors')

    <form method="post" action="{{ route('projects.store') }}">
        @include('projects._fields')

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Crear el nuevo proyecto</button>
            <a href="{{ route('projects.index') }}" class="btn btn-link">Regresar al listado de proyectos</a>
        </div>
    </form>
    @endcard
@endsection

