@extends('layouts.public')

@section('inline_css')
    <style>
        .last.container {
            margin-bottom: 300px !important;
        }
        h1.ui.center.header {
            margin-top: 3em;
        }
        h2.ui.center.header {
            margin: 4em 0em 2em;
        }
        h3.ui.center.header {
            margin-top: 2em;
            padding: 2em 0em;
        }
        #map{
            width: 1900px;
            height: 900px;
        }
    </style>
@endsection

@section('content')
    <h1 class="ui center aligned header">NWA - GIS</h1>

    <h3 class="ui center aligned header">Map</h3>
    <div id="map">

    </div>
@endsection

@section('inline_js')
    <script>
        var centerView = [38.8948932, -77.0365529];
        var mymap = L.map('map').setView(centerView, 13);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 20,
            id: 'mapbox/satellite-v9',
            accessToken: 'pk.eyJ1Ijoic2F5eWlkeW9mYSIsImEiOiJjazdvaHVyanUwNmF3M2dxbnMzaHJqd2hmIn0.eurgCqMjF3XR7m0oZ1Ludw'
        }).addTo(mymap);
        var marker = L.marker(centerView).addTo(mymap);
        loadDataWithPopup({
            url: '/gisdata',
            message: 'Loading GIS data...',
            callback: (data) => {
                data.forEach((item, index) => {
                    try {
                        let geoJSONObj = wkx.Geometry.parse(new buffer.Buffer(item.coordinates, 'hex')).toGeoJSON();
                        L.geoJSON({
                            "type": "Feature",
                            "properties": {
                                "name": item.name,
                                "popupContent": item.boundary_status
                            },
                            "geometry": {
                                "type": geoJSONObj.type,
                                "coordinates": geoJSONObj.coordinates
                            }
                        }).addTo(mymap);
                    }
                    catch (e) {
                        console.log(e.message);
                    }
                });
            }
        });
    </script>
@endsection
