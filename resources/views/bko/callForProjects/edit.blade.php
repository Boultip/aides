@extends('layouts.bko')

@section('heading', "Edition du dispositif : ".$callForProjects->name)
@section('menu-item-call')
    <li class="menu-item active"><a href="{{ route('bko.call.edit', $callForProjects) }}">Edition de {{ $callForProjects->name }}</a></li>
    <li class="menu-item"><a href="{{ route('bko.call.show', $callForProjects) }}">{{ $callForProjects->name }}</a></li>
@endsection

@section('content')
    @include('bko.callForProjects._form', [
        'callForProjects' => $callForProjects,
        'options' => [ 'method' => 'PUT', 'url' => action('Bko\CallForProjectsController@update', $callForProjects) ]
    ])
@endsection