@extends('layout')

@section('title', 'Profesiones')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">Listado de profesiones</h1>
    </div>
    <p class="text-right">
        <a href="{{ route('profession.create') }}" class="btn btn-primary">Nueva profesion</a>
    </p>

    {{--    @includeWhen($view == 'index', 'profession._filters')--}}

    @if ($professions->isNotEmpty())

        <div class="table-responsive-lg">
            <table class="table table-sm">
                <thead class="thead-dark">
                <tr>
                    <th scope="col"># <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
                    <th scope="col">Título</th>
                    <th scope="col">Jornada</th>
                    <th scope="col">Nivel academico</th>
                    <th scope="col">Salario anual</th>
                    <th scope="col">Perfiles</th>
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
