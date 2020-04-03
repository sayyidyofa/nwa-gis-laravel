@extends('layouts.dashboard')

@section('content')
    <h1>Welcome back, User</h1>
    <p>There exists {{ $geodata->count() }} geodata in this system</p>

    <p>Data list: </p>
    <table class="ui selectable sortable celled table">
        <tr class="center aligned">
            <th class="sorted ascending">ID</th>
            <th>Name</th>
            <th>Boundary Status</th>
            <th>GeoType</th>
            <th>Coordinates</th>
            <th>Action</th>
        </tr>
        @foreach($geodata as $data)
            <tr class="center aligned">
                <td>{{ $data->id }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->boundary_status }}</td>
                <td>{{ $data->geotype }}</td>
                <td>Data is big. <a href="/geometry/{{$data->id}}/coordinates">Show me</a></td>
                <td><a href="/wilderness/{{$data->id}}/edit"><i class="edit icon"></i></a> |
                    <a href="/wilderness/{{$data->id}}/destroy"><i class="x icon icon delete-confirm"></i></a></td>
            </tr>
        @endforeach
    </table>
    {{ $geodata->links() }}
@endsection()
