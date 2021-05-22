@extends('layout')

@section('title', 'Equipos')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{trans('teams.title.index')}}</h1>
        <p>
            @if ($view == 'index')
                <a href="{{ route('teams.trashed') }}" class="btn btn-outline-dark">Ver papelera</a>
                <a href="{{ route('teams.create') }}" class="btn btn-primary">Nuevo equipo</a>
            @else
                <a href="{{ route('teams.index') }}" class="btn btn-outline-dark">Regresar al listado de equipo</a>
            @endif
        </p>
    </div>

    @includeWhen($view=='index','teams._filters')

    @if ($teams->isNotEmpty())

        <div class="table-responsive-lg">
            <table class="table table-sm">
                <thead class="thead-dark">
                <tr>
                    <th scope="col"># <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
                    <th scope="col"><a href="{{ $sortable->url('nombre_empresa') }}" class="{{ $sortable->classes('nombre_empresa') }}">Nombre</a></th>
                    <th scope="col"><a href="{{ $sortable->url('trabajadores') }}" class="{{ $sortable->classes('trabajadores') }}">Trabajadores</a></th>
                    <th scope="col"><a href="{{ $sortable->url('numero_profesiones') }}" class="{{ $sortable->classes('numero_profesiones') }}">Número de profesiones</a></th>
                    <th scope="col" class="text-right th-actions">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @each('teams._row', $teams, 'team')
                </tbody>
            </table>
            {{ $teams->links() }}
        </div>
    @else
        <p>No hay equipos para listar</p>
    @endif
@endsection

@section('sidebar')
    @parent
@endsection

