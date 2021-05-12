@extends('layout')

@section('title', 'Editar profesion')

@section('content')
    @card
    <div>Editar profesion</div>
    @slot('header', 'Editar profesion')
    @include('shared._errors')

    <form method="post" action="{{ route('profession.update', $profession) }}">
        {{ method_field('PUT') }}
        @include('professions._fields')

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Actualizar profesion</button>
            <a href="{{ route('professions.index') }}" class="btn btn-link">Regresar al listado de profesiones</a>
        </div>
    </form>
    @endcard
@endsection
