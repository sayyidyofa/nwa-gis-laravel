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

        #map {
            width: 1127px;
            height: 90vh;
        }
    </style>
@endsection

@section('content')
    <div class="ui grid">
        <div class="row">
            <div class="column padding-reset">
                <div class="jumbotron ui huge message page">
                    <h1 class="ui centered huge header title1">NATIONAL WILDERNESS AREAS OF USA</h1>
                    <h1 class="ui centered header title2">Geographic Information System</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="ui hidden divider"></div>
    <div class="ui grid">
        <div class="row">
            <div class="ui container">
                <h1 class="ui centered header title3">Map</h1>
                <div class="ui card">
                    <div id="map">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui hidden divider"></div>
    <div class="ui inverted vertical footer segment">
        <div class="ui center aligned container">
            <div class="ui horizontal inverted small divided copyright">
                2020 © <strong>Computer Engineering</strong>. All Rights Reserved
            </div>
        </div>
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
