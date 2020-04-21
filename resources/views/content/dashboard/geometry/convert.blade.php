@extends('layouts.dashboard')

@section('content')
    <h1>Convert Geometry Coordinates</h1>
    <h3>Convert Well-Known Binary Format (WKB) to GeoJSON and vice versa</h3>
    <p>After a conversion, clear the textarea to commence a new conversion</p>
    <form action="" method="post" class="ui form" style="margin-bottom: 20px">
        <div class="field">
            <label for="data">&nbsp;</label>
            <textarea name="data" id="data" cols="100" rows="50"></textarea>
        </div>
        <button id="wkbToGeoJSON" class="ui primary button">Convert WKB to GeoJSON</button>
        <button id="GeoJSONToWkb" class="ui primary button">Convert GeoJSON to WKB</button>
    </form>
    {{--<div class="ui form">
        <div class="ui left corner labeled input">
            <div class="ui left corner label">
                <i class="asterisk icon"></i>
            </div>
            <textarea name="data" id="data" cols="200" rows="100">
            </textarea>
            <button id="wkbToGeoJSON" class="ui primary button">Convert WKB to GeoJSON</button>
            <button id="GeoJSONToWkb" class="ui primary button">Convert GeoJSON to WKB</button>
        </div>
    </div>--}}
@endsection

@section('plugin_js')
    <script src="{{ asset('js/wkx.min.js') }}"></script>
    <script src="{{ asset('js/buffer.min.js') }}"></script>
@endsection

@section('inline_js')
    <script>
        $('#wkbToGeoJSON').on('click', (e) => {
            let $data = $('#data');
            let $datatext = new FormData(document.querySelector('form')).get("data");
            e.preventDefault();
            try {
                $data.val(wkx.Geometry.parse(new buffer.Buffer($datatext, 'hex')).toGeoJSON());
            } catch (e) {
                console.log($datatext);
                alertError(e);
            }
        });

        $('#GeoJSONToWkb').on('click', (e) => {
            let $data = $('#data');
            let $datatext = new FormData(document.querySelector('form')).get("data");
            e.preventDefault();
            try {
                $data.val(buf2hex(wkx.Geometry.parseGeoJSON($datatext).toWkb().buffer).toUpperCase());
            } catch (e) {
                alertError(e)
            }
        });

        function alertError(e) {
            Swal.fire(
                "Whoops!",
                `${e.name} : ${e.message}`,
                "error"
            );
        }
    </script>
@endsection
