@extends('layouts.dashboard')

@section('content')
    <div class="ui container" style="margin-top: 80px;">
        <h1>New GIS Entry (Step 1)</h1>
        <h3>Fill Wilderness Data</h3>
        <form action="{{ route('wilderness.store') }}" method="post" class="ui form">
            {{ csrf_field() }}
            <div class="field">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Wilderness name" required>
            </div>
            <div class="field">
                <label for="boundary_status">Boundary Status</label>
                <select name="boundary_status" id="boundary_status">
                    <option value="Final">Final</option>
                    <option value="Provisional, Subject to Change">Provisional, Subject to Change</option>
                </select>
            </div>
            <button class="ui primary button" type="submit">Next</button>
            <a href="{{ route('dashboard.gisindex') }}" class="ui button">Cancel</a>
        </form>
    </div>
@endsection
