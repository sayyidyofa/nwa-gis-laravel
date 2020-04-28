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
            {{--<th>Coordinates</th>--}}
            <th>Action</th>
        </tr>
        @foreach($geodata as $data)
            <tr class="center aligned">
                <td>{{ $data->w_id }}</td>
                <td>{{ $data->g_id }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->boundary_status }}</td>
                <td>{{ $data->geotype }}</td>
                {{--<td>
                    @isset($data->g_id)
                        <a href="{{ route('gis.show', ['id' => $data->g_id]) }}">Show on Map</a>
                    @endisset
                </td>--}}
                <td>@empty($data->g_id)
                        <a href="/wilderness/{{ $data->w_id }}/addgeometry" data-tooltip="Add Geometry Data to Wilderness Data"><i class="map outline icon"></i></a>&nbsp;|&nbsp;
                    @endempty
                    @isset($data->g_id)
                        <a href="/geometry/{{$data->g_id}}/edit" data-tooltip="Edit Geometry Coordinates"><i class="map icon"></i></a> |
                    @endisset
                    <a href="/wilderness/{{$data->w_id}}/edit" data-tooltip="Edit Wilderness Data"><i class="tree icon"></i></a> |
                    <a href="/wilderness/{{$data->w_id}}" data-tooltip="Remove Geodata" class="delete-confirm"><i class="x icon"></i></a>
                </td>
            </tr>
        @endforeach
    </table>
    {{ $geodata->links() }}
@endsection()
