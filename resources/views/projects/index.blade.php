@extends('layout')

@section('title', 'Proyectos')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{trans('projects.title.index')}}</h1>
        <p> <a href="{{ route('projects.create') }}" class="btn btn-primary">Nuevo proyecto</a></p>
    </div>

    @include('projects._filters')

    @if ($projects->isNotEmpty())

        <div class="table-responsive-lg">
            <table class="table table-sm">
                <thead class="thead-dark">
                <tr>
                    <th scope="col"># <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
                    <th scope="col"><a href="{{ $sortable->url('titulo') }}" class="{{ $sortable->classes('titulo') }}">Titulo del proyecto</a></th>
                    <th scope="col"><a href="{{ $sortable->url('presupuesto') }}" class="{{ $sortable->classes('presupuesto') }}">Presupuesto</a></th>
                    <th scope="col"><a href="{{ $sortable->url('estado') }}" class="{{ $sortable->classes('estado') }}">Estado</a></th>
                    <th scope="col"><a href="{{ $sortable->url('plazo') }}" class="{{ $sortable->classes('plazo') }}">Plazo de finalización</a></th>
                    <th scope="col" class="text-right th-actions">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @each('projects._row', $projects, 'project')
                </tbody>
            </table>
            {{ $projects->links() }}
        </div>
    @else
        <p>No hay proyectos para listar</p>
    @endif
@endsection

@section('sidebar')
    @parent
@endsection

