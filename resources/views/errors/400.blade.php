@extends('layout')

@section('title', 'Error 400')

@section('content')
    <h1>Error 400</h1>
    <h2> Detalles: {{ $exception->getMessage() }}</h2>
@endsection
