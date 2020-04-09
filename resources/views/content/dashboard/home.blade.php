@extends('layouts.dashboard')

@section('content')
    <h1>Welcome back, {{Auth::user()->name}}</h1>
    @role('sadmin')
        <p>test admin</p>
    @endrole

@endsection
