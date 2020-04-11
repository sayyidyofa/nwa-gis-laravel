@extends('layouts.dashboard')

@section('content')
    <div class="ui container" style="margin-top: 80px;">
        <h3>Edit Wilderness Data</h3>
        <form action="{{ route('wilderness.update', ['wilderness' => $w]) }}" method="post" class="ui form">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <div class="field">
                <label for="id">ID</label>
                <input type="text" name="id" id="id" disabled value="{{$w->id}}">
            </div>
            <div class="field">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Wilderness name" value="{{$w->name}}" required>
            </div>
            <div class="field">
                <label for="boundary_status">Boundary Status</label>
                <select name="boundary_status" id="boundary_status">
                    <option value="Final" {{ $w->boundary_status === "Final" ? "selected" : "" }}>Final</option>
                    <option value="Provisional, Subject to Change" {{ $w->boundary_status === "Provisional, Subject to Change" ? "selected" : "" }}>Provisional, Subject to Change</option>
                </select>
            </div>
            <button class="ui primary button" type="submit">Edit</button>
            <a href="{{ route('dashboard.gisindex') }}" class="ui button">Cancel</a>
        </form>
    </div>
@endsection
