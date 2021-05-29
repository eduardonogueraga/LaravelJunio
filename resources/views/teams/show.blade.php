@extends('layout')

@section('title', "Equipo {$team->name}")

@section('content')
    <h1>{{ $team->name }}</h1>

    <p>Nombre del equipo: {{ $team->name }}</p>

    <h3>Oficina central</h3>
    <p>{{$team->mainHeadquarter->name}}</p>

    @if(count($team->headquarters) > 1)
    <h3>Subsedes</h3>
    <ul>
        @foreach($team->headquarters
                    ->filter(function($field) {return $field->is_central == false;})
                     as $headquarter)
        <li>{{$headquarter->name}}</li>
        @endforeach
    </ul>
    @endif


    <h3>Perfiles profesionales</h3>
    @forelse($team->professions as $profession)
        <ul>
        <li>{{$profession->title}}</li>
        </ul>
    @empty
        <p>Sin un perfil definido</p>
    @endforelse

    <p>
        @if(url()->previous() == route('teams.edit',  ['team' =>  intval($team->id)]))
            <a href="{{  route('teams.index') }}">Regresar al listado de equipos</a>
        @else
            <a href="{{ url()->previous() }}">Regresar</a>
        @endif
    </p>
@endsection



