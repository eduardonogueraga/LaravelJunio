@extends('layout')

@section('title', 'Nueva profesión')

@section('content')
    @card
    <div>Crear nueva profesion</div>
    @slot('header', 'Crear nueva profesion')
    @include('shared._errors')

    <form method="post" action="{{ route('profession.store') }}">
        @include('professions._fields')

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Crear la nueva profesion</button>
            <a href="{{ route('professions.index') }}" class="btn btn-link">Regresar al listado de profesiones</a>
        </div>
    </form>
    @endcard
@endsection
