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
    <h1>Create new Geometry</h1>
    <h3>With following Wilderness Data:</h3>
    <p id="w_name" data-value="{{ $w->name }}">Name: {{ $w->name }}</p>
    <p id="w_boundary_status" data-value="{{ $w->boundary_status }}">Boundary Status: {{ $w->boundary_status }}</p>
    <p id="w_color" data-value="{{ $w->color }}">Color: {{ $w->color }}</p>
    <div id="map" style="width: 1760px; height: 672px;">

    </div>
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
        "use strict"

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

        var startDrawingButton = L.easyButton({
            id: 'start-drawing-button',
            states: [{
                icon: 'fa fa-pen',
                title: 'Start drawing',
                stateName: 'start-polyline',
                onClick: (btn, map) => {
                    btn.button.style.backgroundColor = "#f00";
                    btn.button.style.color = "#fff";
                    document.getElementById("map").style.cursor = "crosshair";

                    btn.state('cancel-polyline');
                    drawingState = true;
                }
            }, {
                icon: 'fa fa-times',
                title: 'Cancel drawing',
                stateName: 'cancel-polyline',
                onClick: (btn, map) => {
                    btn.button.style.backgroundColor = "#fff";
                    btn.button.style.color = "#000";
                    document.getElementById("map").style.cursor = "grab";

                    btn.state('start-polyline');
                    cancelPolyline();
                    drawingState = false;
                }
            }]
        });
        startDrawingButton.addTo(mymap);

        var undoButton = L.easyButton({
            id: 'undo-polyline',
            states: [{
                icon: 'fa fa-undo',
                ttle: 'Cancel last point',
                stateName: 'undo-polyline',
                onClick: (btn, map) => {
                    undoPoint();
                }
            }]
        });
        undoButton.addTo(mymap);
        undoButton.disable();

        var finishButton = L.easyButton({
            id: 'finish-polyline',
            states: [{
                icon: 'fa fa-check',
                title: 'Finish drawing',
                stateName: 'finish-polyline',
                onClick: (btn, map) => {
                    drawArea();
                }
            }]
        });
        finishButton.addTo(mymap);
        finishButton.disable();

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

        function startPolyline(latlng){
            placeFirstPoint(latlng);
            startPolylineFlag = true;
        }

        function finishPolyline(){
            removeMapLayers();

            startPolylineFlag = false;
            pols = [];
            polygon = undefined;
            polyline = undefined;
            helpLine = undefined;
            helpPolygon = undefined;

            finishButton.disable();
            undoButton.disable();
        }

        function cancelPolyline(){
            if(polyline === undefined) return;

            removeMapLayers();
            finishPolyline();
        }

        function undoPoint(){
            if(!drawingState) return;
            if(pols.length === 0) return;

            pols.pop();

            polyline.setLatLngs(pols);
            helpPolygon.setLatLngs(pols);

            if(!validateArea()){
                finishButton.disable();
            }

            if(pols.length === 0){
                finishPolyline();
                undoButton.disable();
            }
        }

        function validateArea(){
            return pols.length > 2;

        }

        function drawArea(){
            if(polyline === undefined) return;
            if(!validateArea()) return;

            drawingState = false;

            polygon = L.polygon([pols], {
                color: '{{ $w->color }}',
                fillOpacity: 0.4
            }).addTo(mymap);
            let popup = L.popup({
                closeButton: false,
                autoClose: false,
                closeOnEscapeKey: false,
                closeOnClick: false,
            })
                .setContent(
                    `
<button onclick="cancelArea()">
    <i class="times circle icon"></i>
</button> | <button onclick="confirmArea('{{ $w->color }}')">
<i class="check circle icon"></i></button>`);

            polygon.bindPopup(popup).openPopup();
        }

        function drawHelpArea(){
            if(polyline === undefined) return;
            if(!validateArea()) return;

            if(helpPolygon){
                helpPolygon.setLatLngs(pols)
            }
            else {
                helpPolygon = L.polygon([pols], {
                    color: '#ee0',
                    stroke: false,
                    className: 'help-layer'
                });
                helpPolygon.addTo(mymap);
            }
        }

        function cancelArea(){
            drawingState = true;
            mymap.removeLayer(polygon);
        }

        function confirmArea(color){
            closeFullscreen();
            popupForm(color);
        }

        function removeMapLayers(){
            mymap.removeLayer(polyline);
            mymap.removeLayer(helpLine);
            mymap.removeLayer(helpPolygon);
            mymap.removeLayer(firstPoint);
        }

        function placeFirstPoint(latlng){
            let icon = L.divIcon({
                className: 'first-point',
                iconSize: [10, 10],
                iconAnchor: [5, 5]
            });

            firstPoint = L.marker(latlng, {icon: icon});
            firstPoint.addTo(mymap);
            firstPoint.on('click', function(){
                if(validateArea()){
                    drawArea();
                }
            });
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
</div>
  `
        }

        async function popupForm(color){
            const { value: formValues, dismiss } = await Swal.fire({
                title: 'Confirm',
                html: `
      <div id="field-form">
        <table>
          <tr>
            <th>W. Name</th>
            <td><input type="text" id="area_name" class="swal2-input" value="{{ $w->name }}" disabled></td>
          </tr>
          <tr>
            <th>W. Status</th>
            <td><input type="text" id="desc" class="swal2-input" value="{{ $w->boundary_status }}" disabled></td>
          </tr>
        </table>
      </div>
      `,
                focusConfirm: false,
                confirmButtonText: 'Save',
                confirmButtonColor: '#0c0',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    let v = {
                        areaName: document.getElementById('area_name').value,
                        desc: document.getElementById('desc').value,
                    };

                    // check empty value
                    for (let [, val] of Object.entries(v)) {
                        if(val === ''){
                            Swal.showValidationMessage(`Harap isi semua input yang ada`);
                        }
                    }
                    return v;
                }
            });

            polygon.closePopup();
            polygon.unbindPopup();

            if(dismiss === Swal.DismissReason.cancel){
                cancelArea();
                return;
            }

            polygon.bindPopup(getPopupContent(formValues)).openPopup();

            let sendData = {
                color: color,
                areaName: formValues.areaName,
                desc: formValues.desc,
            };
            sendPolygonJSON(sendData);

            drawingState = true;
            finishPolyline();
        }

        function sendPolygonJSON(data){
            let polygonGeoJSON = polygon.toGeoJSON(15);
            polygonGeoJSON.properties = {
                color: data.color,
                popupContent: {
                    areaName: data.areaName,
                    desc: data.desc
                }
            };

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: `${url}/wilderness/{{$w->id}}/addgeometry`,
                type: 'POST',
                cache: false,
                data: {
                    wilderness_id: '{{$w->id}}',
                    geotype: 'Polygon',
                    // https://stackoverflow.com/questions/40031688/javascript-arraybuffer-to-hex
                    coordinates: JSON.stringify(polygonGeoJSON.geometry.coordinates) //buf2hex(wkx.Geometry.parseGeoJSON({type:'Polygon', coordinates: polygonGeoJSON.geometry.coordinates}).toWkb().buffer).toUpperCase()
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    console.log('Error sending data', error);
                    console.log(data.color,data.areaName,data.desc,JSON.stringify(polygonGeoJSON.geometry.coordinates))
                },
                success: function(response){
                    console.log(response);
                    Swal.fire('Success', 'GIS Entry added', 'success').then(()=>{location.href="{{route('dashboard.gisindex')}}";});
                }
            });
        }

        function onEachFeatureCallback(feature, layer){
            if (feature.properties && feature.properties.popupContent) {
                let { wildernessName, boundaryStatus } = feature.properties.popupContent;
                let content = {
                    wildernessName: wildernessName,
                    boundaryStatus: boundaryStatus
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
                        //console.log(JSON.stringify(wkx.Geometry.parse(new buffer.Buffer(item.coordinates, 'hex')).toGeoJSON()));
                        //let geoJSONObj = /*wkx.Geometry.parse(item.coordinates).toGeoJSON(); */wkx.Geometry.parse(new buffer.Buffer(item.coordinates, 'hex')).toGeoJSON();
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
