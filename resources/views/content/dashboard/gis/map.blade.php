@extends('layouts.dashboard')

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

        #map {
            width: 1900px;
            height: 900px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
          integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
          crossorigin=""/>
@endsection

@section('content')
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

        function getPopupContent(field) {
            return `
    <table>
      <tr>
        <th>Name</th>
        <td>${field.wildernessName}</td>
      </tr>
      <tr>
        <th>Boundary Status</th>
        <td>${field.boundaryStatus}</td>
      </tr>
    </table>
  `
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

        loadDataWithPopup({
            url: '/gisdata',
            message: 'Loading GIS data...',
            callback: (data) => {
                let field_response = {type: "FeatureCollection", features: []};

                data.forEach((item, index) => {
                    try {
                        console.log(item.color);
                        let geoJSONObj = wkx.Geometry.parse(new buffer.Buffer(item.coordinates, 'hex')).toGeoJSON();
                        field_response.features.push({
                            type: "Feature",
                            properties: {
                                color: item.color,
                                popupContent: {
                                    wildernessName: item["name"],
                                    boundaryStatus: item.boundary_status
                                }
                            },
                            geometry: {
                                type: item.geotype,
                                coordinates: geoJSONObj.coordinates
                            }
                        });
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

@section('plugin_js')
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
        crossorigin="">
    </script>
@endsection
