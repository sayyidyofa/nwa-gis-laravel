@extends('layouts.dashboard')

@section('inline_css')
    <style>
        input[type="color"] {
            padding:initial !important;
        }
    </style>
@endsection

@section('content')
    <div class="ui container" style="margin-top: 80px;">
        <h1>New Wilderness</h1>
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
            <div class="field">
                <label for="color">Color</label>
                <input type="color" name="color" id="color" value="">
            </div>
            <button class="ui primary button" type="submit">Create</button>
            <a href="{{ route('gis.create') }}" class="ui button">Cancel</a>
        </form>
    </div>
@endsection

@section('inline_js')
    <script>
        // Generate random color
        $((e)=>{
            $('#color').attr('value', '#' + (function co(lor){   return (lor +=
                [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f'][Math.floor(Math.random()*16)])
            && (lor.length === 6) ?  lor : co(lor); })(''));
        });
    </script>
@endsection
