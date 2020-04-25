@extends('layouts.dashboard')

@section('plugin_css')
    <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
          integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
          crossorigin=""/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
@endsection

@section('content')
    <h1>Map</h1>
    <div id="map" style="width: 1200px; height: 400px;"></div>
@endsection

@section('plugin_js')
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
            integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
            crossorigin=""></script>
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
    <script src="{{ asset('js/wkx.min.js') }}"></script>
    <script src="{{ asset('js/buffer.min.js') }}"></script>
@endsection

@section('inline_js')
    <script>
        "use strict";

        var url = "{{ URL::to('/') }}";
        var centerView = new L.LatLng(38.8948932, -77.0365529);
        var mymap = L.map('map', {
            fullscreenControl: true
        }).setView(centerView, 7);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            id: 'mapbox/satellite-v9',
            accessToken: 'pk.eyJ1Ijoic2F5eWlkeW9mYSIsImEiOiJjazdvaHVyanUwNmF3M2dxbnMzaHJqd2hmIn0.eurgCqMjF3XR7m0oZ1Ludw'
        }).addTo(mymap);

        /**
         * Singleton Variables
         * for better sharable state
         */
        var startPolylineFlag = false;
        var polyline = undefined;
        var pols = [];
        var polygon = undefined;
        var helpLine = undefined;
        var helpPolygon = undefined;
        var firstPoint = L.circleMarker();
        // Check whether the drawing state by button is active
        var drawingState = false;

        // NWAGIS
        L.marker(centerView, {
            title: "U.S Forest Department Office"
        }).addTo(mymap);

        function onMapClick(e) {
            if(!drawingState) return;

            if(startPolylineFlag !== true){
                startPolyline(e.latlng);
                pols.push([e.latlng["lat"], e.latlng["lng"]]);
                polyline = L.polyline(pols, {
                    color: '#ee3'
                }).addTo(mymap);
            }
            else {
                pols.push([e.latlng["lat"], e.latlng["lng"]]);
                polyline.addLatLng(e.latlng);
                undoButton.enable();

                if(validateArea()){
                    drawHelpArea();
                    finishButton.enable();
                }
            }
        }

        function onMapMouseMove(e) {
            if(!drawingState || pols.length < 1) return;

            let latlngs = [pols[pols.length - 1], [e.latlng.lat, e.latlng.lng]];

            if(helpLine){
                helpLine.setLatLngs(latlngs);
            }
            else {
                helpLine = L.polyline(latlngs, {
                    color: 'grey',
                    weight: 2,
                    dashArray: '7',
                    className: 'help-layer'
                });
                helpLine.addTo(mymap);
            }
        }

        function onKeyDownEscape(){
            cancelPolyline();
        }

        function onKeyDownEnter(){
            drawArea();
        }

        function centerizeView(){
            let zoomLevel = 17;
            zoomLevel = mymap.getZoom() < zoomLevel ? zoomLevel : mymap.getZoom();

            mymap.setView(
                centerView,
                zoomLevel,
                {
                    animate: true,
                    duration: 1.0
                }
            );
        }

        function getPopupContent(field){
            return `
<div class="ui card">
  <div class="image" id="${field.w_id}">
    <img src="${field.img_url}" alt="Wilderness Image">
  </div>
  <div class="content">
    <span class="header">${field.wildernessName}</span>
    <div class="description">
      Status:&nbsp;${field.boundaryStatus}
    </div>
  </div>
    <div class="extra content">
            <a href="/wilderness/${field.w_id}/edit" class="ui mini primary button">Edit Data</a>
            &nbsp;
            <a href="/geometry/${field.g_id}/edit" class="ui mini positive button">Edit Geom</a>
            &nbsp;
            <a href="/wilderness/${field.w_id}" id="w-${field.w_id}" class="ui mini negative button delete-confirm" onclick="deleteHandler(event, '/wilderness/${field.w_id}')">Remove</a>
    </div>
</div>

            `
        }

        function onEachFeatureCallback(feature, layer){
            if (feature.properties && feature.properties.popupContent) {
                let { wildernessName, boundaryStatus, w_id, g_id, img_url } = feature.properties.popupContent;
                let content = {
                    wildernessName: wildernessName,
                    boundaryStatus: boundaryStatus,
                    w_id : w_id,
                    g_id : g_id,
                    img_url: img_url
                };
                layer.bindPopup(getPopupContent(content));
            }
        }

        // event listeners
        mymap.on('click', onMapClick);
        mymap.addEventListener('mousemove', onMapMouseMove);
        document.onkeydown = (e) => {
            if(!drawingState) return;

            switch(e.keyCode){
                case 13: onKeyDownEnter(); break;
                case 27: onKeyDownEscape(); break;
            }
        };

        let dummyImages = [];
        let idx = 0;

        $.ajax({
            url: "{{ route('dummy-images', ['perPage' => \App\GIS::all()->count()]) }}",
            success: (data) => {dummyImages = JSON.parse(data);}
        });

        loadDataWithPopup({
            url: '/gisdata',
            message: 'Loading GIS data...',
            callback: (data) => {
                let field_response = {type: "FeatureCollection", features: []};

                data.forEach((item, index) => {
                    try {
                        //console.log(item.color);
                        //let geoJSONObj = wkx.Geometry.parse(new buffer.Buffer(item.coordinates, 'hex')).toGeoJSON();

                        //const coba2 = new buffer.Buffer(item.coordinates, 'hex').buffer;
                        //console.log(buf2hex(coba2).toUpperCase()); // = 04080c10

                        //console.log(new buffer.Buffer(item.coordinates, 'hex'));
                        //console.log(JSON.stringify(geoJSONObj));
                        if (item.g_id !== null) {
                            field_response.features.push({
                                type: "Feature",
                                properties: {
                                    color: item.color,
                                    popupContent: {
                                        wildernessName: item["name"],
                                        boundaryStatus: item.boundary_status,
                                        w_id : item.g_id,
                                        g_id: item.g_id,
                                        img_url: dummyImages[idx]
                                    }
                                },
                                geometry: {
                                    type: item.geotype,
                                    coordinates: JSON.parse(item.coordinates)
                                }
                            });
                        }
                        idx++;
                    } catch (e) {
                        console.log(e)
                    }

                });
                L.geoJSON(field_response, {
                    style: function(feature){
                        return {color: feature.properties.color}
                    },
                    onEachFeature: onEachFeatureCallback
                }).addTo(mymap);
            }
        });

    </script>
@endsection
