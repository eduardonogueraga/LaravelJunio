@extends('layout')

@section('title', "Profesion {$profession->id}")

@section('content')
    <h1>{{ $profession->title }}</h1>

    <p>Titulo: {{ $profession->title }}</p>
    <p>Jornada laboral: {{$profession->workday}}</p>
    <p>Nivel academico: {{ $profession->academic_level }}</p>
    <p>Salario anual: {{ number_format($profession->salary, 0, ',', '.') }}€, Salario mensual: {{round($profession->salary/12,2)}}€</p>
    <p>Años de experiencia: {{($profession->experience)?? 'Sin experiencia'}}</p>
    <p>Requiere idiomas: {{($profession->language)?'Si': 'No'}}</p>
    <p>Requiere vehiculo: {{($profession->vehicle)?'Si': 'No'}}</p>
    <p>Usuarios con esta profesion: {{count($profession->profiles)}}</p>
    <p>Empresas que solicitan esta profession: <b>{{($profession->teams->implode('name'))}}</b></p>

    <p>
        @if(url()->previous() == route('profession.edit',  ['profession' =>  intval($profession->id)]))
            <a href="{{  route('professions.index') }}">Regresar al listado de profesiones</a>
        @else
        <a href="{{ url()->previous() }}">Regresar</a>
        @endif
    </p>
@endsection



