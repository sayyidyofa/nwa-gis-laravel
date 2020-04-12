@extends('layouts.dashboard')

@section('content')
    <p>There exists {{ $count }} geodata in this system</p>

    <p>Data list: </p>
    <table class="ui selectable sortable celled table">
        <tr class="center aligned">
            <th class="sorted ascending">Wilderness ID</th>
            <th>Geometry ID</th>
            <th>Name</th>
            <th>Boundary Status</th>
            <th>GeoType</th>
            <th>Coordinates</th>
            <th>Action</th>
        </tr>
        @foreach($geodata as $data)
            <tr class="center aligned">
                <td>{{ $data->w_id }}</td>
                <td>{{ $data->g_id }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->boundary_status }}</td>
                <td>{{ $data->geotype }}</td>
                <td>Data is big. <a href="/geometry/{{$data->id}}/coordinates">Show me</a></td>
                <td><a href="/geometry/{{$data->id}}/edit" data-tooltip="Edit Geometry Data"><i class="map icon"></i></a> |
                    <a href="/wilderness/{{$data->id}}/edit" data-tooltip="Edit Wilderness Data"><i class="tree icon"></i></a> |
                    <a href="/wilderness/{{$data->id}}" data-tooltip="Remove Data" class="delete-confirm"><i class="x icon"></i></a></td>
            </tr>
        @endforeach
    </table>
    {{ $geodata->links() }}
@endsection()
