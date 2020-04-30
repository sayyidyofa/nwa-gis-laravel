@extends('layouts.dashboard')

@section('content')
    <h1>Welcome back, {{Auth::user()->name}}</h1>
    @role('sadmin')
        <p>Hello, high-privilege user</p>
    @endrole

@endsection
