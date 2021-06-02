@extends('layout')

@section('title', 'Editar proyecto')

@section('content')
    @card
    <div>Editar proyecto</div>
    @slot('header', 'Editar proyecto')
    @include('shared._errors')

    <form method="post" action="{{ route('projects.update', $project) }}">
        {{ method_field('PUT') }}
        @include('projects._fields')

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Actualizar proyecto</button>
            <a href="{{ route('projects.index') }}" class="btn btn-link">Regresar al listado de proyectos</a>
        </div>
    </form>
    @endcard
@endsection

