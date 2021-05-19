@extends('layout')

@section('title', "Equipo {$team->id}")

@section('content')
    <h1>{{ $team->name }}</h1>

    <p>Nombre del equipo: {{ $team->name }}</p>

    <h3>Perfiles profesionales</h3>
    @forelse($team->professions as $profession)
        <p>{{$profession->title}}</p>
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



