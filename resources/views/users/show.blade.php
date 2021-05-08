@extends('layout')

@section('title', "Usuario {$user->id}")

@section('content')
<h1>Usuario #{{ $user->id }}</h1>

<p>Nombre del usuario: {{ $user->name }}</p>
<p>Apellidos del usuario: {{$user->last}}</p>
<p>Correo electrónico: {{ $user->email }}</p>
<p>Twitter: {{ ($user->profile->twitter)?: 'Sin cuenta de twitter' }}</p>
<p>Profession: {{($user->profile->profession->title)?: 'Sin profesion'}}</p>
<p>Equipo: {{($user->team->name)?: 'Sin equipo'}}</p>
<p>Hablidades: {{($user->skills->implode('name', ','))?: 'Sin habilidades'}}</p>

<p>
    <a href="{{ route('users.index') }}">Regresar al listado de usuarios</a>
</p>
@endsection

