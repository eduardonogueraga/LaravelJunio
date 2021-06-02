@extends('layout')

@section('title', "Projecto {$project->title}")

@section('content')
    <h1>{{ $project->title }}</h1>

    <h3>Datos del proyecto</h3>
    <p>Titulo: {{$project->title}}</p>
    <p>Presupuesto: {{$project->budget}}€</p>
    <p>Estado del proyecto: {{$project->status? 'Acabado' : 'Pendiente'}}</p>
    <p>Resumen: {{$project->about}}</p>

    <h3>Equipo principal</h3>
   <p> @forelse($project->teams->filter(function($field) {return $field->pivot->is_head_team == 1;}) as $team)
           {{$team->name}} <a href="{{route('teams.show',  ['team' =>  intval($team->id)])}}">Ver</a>
       @empty
           Sin equipo principal</p>
    @endforelse

    <h3>Equipos en este proyecto</h3>
    <ul>
        @foreach($project->teams as $team)
        <li> {{$team->name}} <spam class="note">Usuarios ({{$team->users->count()}})  <a href="{{route('teams.show',  ['team' =>  intval($team->id)])}}">Ver</a></spam></li>
        @endforeach
    </ul>

    <h3>Trabajadores en el proyecto</h3>
    <ul>
        @foreach($project->teams as $team)
            @foreach($team->users as $user)
                <li>{{$user->first_name .' '. $user->last_name}}  <span class="note">Equipo: {{$user->team->name}}</span></li>
            @endforeach
        @endforeach
    </ul>

    <h3>Fecha limite</h3>
    <p>{{\Carbon\Carbon::parse($project->finish_date)->format('d-m-Y')}}</p>
    <h4>Tiempo restante</h4>

    @if(\Carbon\Carbon::parse($project->finish_date)->isFuture())
        <p>{{now()->diffForHumans(\Carbon\Carbon::parse($project->finish_date))}}</p>
    @else
        <p>Se ha vencido el plazo</p>
    @endif

    <p>
        <a href="{{  route('teams.index') }}">Regresar al listado de proyectos</a>
    </p>
@endsection



