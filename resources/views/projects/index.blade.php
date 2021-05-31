@extends('layout')

@section('title', 'Proyectos')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{trans('projects.title.index')}}</h1>
    </div>

    @if ($projects->isNotEmpty())

        <div class="table-responsive-lg">
            <table class="table table-sm">
                <thead class="thead-dark">
                <tr>
                    <th scope="col"># <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
                    <th scope="col">Titulo del proyecto</th>
                    <th scope="col">Presupuesto</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Plazo de finalización</th>
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

