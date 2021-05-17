@extends('layout')

@section('title', 'Profesiones')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{trans('professions.title.index')}}</h1>
    </div>
    <p class="text-right">
        <a href="{{ route('profession.create') }}" class="btn btn-primary">Nueva profesion</a>
    </p>

    @include('professions._filters')

    @if ($professions->isNotEmpty())

        <div class="table-responsive-lg">
            <table class="table table-sm">
                <thead class="thead-dark">
                <tr>
                    <th scope="col"># <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
                    <th scope="col"><a href="{{ $sortable->url('titulo') }}" class="{{ $sortable->classes('titulo') }}">Titulo</a></th>
                    <th scope="col"><a href="{{ $sortable->url('jornada') }}" class="{{ $sortable->classes('jornada') }}">Jornada</a></th>
                    <th scope="col"><a href="{{ $sortable->url('nivel') }}" class="{{ $sortable->classes('nivel') }}">Nivel academico</a></th>
                    <th scope="col"><a href="{{ $sortable->url('salario') }}" class="{{ $sortable->classes('salario') }}">Salario anual</a></th>
                    <th scope="col"><a href="{{ $sortable->url('perfiles') }}" class="{{ $sortable->classes('perfiles') }}">Perfiles</a></th>
                    <th scope="col" class="text-right th-actions">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @each('professions._row', $professions, 'profession')
                </tbody>
            </table>
                    {{ $professions->links() }}
        </div>
    @else
        <p>No hay profesiones para listar</p>
    @endif
@endsection

@section('sidebar')
    @parent
@endsection
