@extends('layout')

@section('title', 'Equipos')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{trans('teams.title.index')}}</h1>
    </div>
    <p class="text-right">
     -  <a href="{{ route('teams.create') }}" class="btn btn-primary">Nuevo equipo</a>
    </p>

    {{--@include('teams._filters')--}}

    @if ($teams->isNotEmpty())

        <div class="table-responsive-lg">
            <table class="table table-sm">
                <thead class="thead-dark">
                <tr>
                    <th scope="col"># <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Trabajadores</th>
                    <th scope="col">Número de profesiones</th>
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

