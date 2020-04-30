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
        var mymap = L.map('map').setView(centerView, 5);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 20,
            id: 'mapbox/satellite-v9',
            accessToken: 'pk.eyJ1Ijoic2F5eWlkeW9mYSIsImEiOiJjazdvaHVyanUwNmF3M2dxbnMzaHJqd2hmIn0.eurgCqMjF3XR7m0oZ1Ludw'
        }).addTo(mymap);

        function getPopupContent(field) {
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
            /*return `
    <table>
      <tr>
        <th>Name</th>
        <td>${field.wildernessName}</td>
      </tr>
      <tr>
        <th>Boundary Status</th>
        <td>${field.boundaryStatus}</td>
      </tr>
      <tr>
        <th></th>
        <td><img src="${field.img_url}" alt="Image" class="gis-image"></td>
      </tr>
    </table>
  `*/
        }
        function onEachFeatureCallback(feature, layer){
            if (feature.properties && feature.properties.popupContent) {
                let { wildernessName, boundaryStatus, img_url, w_id } = feature.properties.popupContent;
                let content = {
                    wildernessName: wildernessName,
                    boundaryStatus: boundaryStatus,
                    w_id: w_id,
                    img_url: img_url
                };

                layer.bindPopup(getPopupContent(content));
            }
        }

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
                                        w_id: item.w_id,
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

        // Add 5 random markers
        let bruh = [];
        $.ajax({
            url: "{{ route('dummy-images', ['perPage' => \App\GIS::all()->count()]) }}",
            success: (data) => {
                bruh.push(JSON.parse(data));
                console.log(bruh);
                let marker = L.marker(centerView, {title: 'Dummy Marker 0'}).addTo(mymap);

                let marker1 = L.marker([39.8948932, -76.0365529], {title: 'Marker 01'}).addTo(mymap);

                let marker2 = L.marker([40.8948932, -75.0365529], {title: 'Marker 02'}).addTo(mymap);

                let marker3 = L.marker([41.8948932, -74.0365529], {title: 'Marker 03'}).addTo(mymap);

                let marker4 = L.marker([42.8948932, -73.0365529], {title: 'Marker 04'}).addTo(mymap);
                marker.bindPopup(`
<div class="ui card">
  <div class="image">
    <img src="${bruh[0][0]}" alt="Wilderness Image">
  </div>
  <div class="content">
    <span class="header">Dummy Marker Name 0</span>
    <div class="description">
      Dummy Marker Description
    </div>
  </div>
</div>
            `);
                marker1.bindPopup(`
<div class="ui card">
  <div class="image">
    <img src="${bruh[0][1]}" alt="Wilderness Image">
  </div>
  <div class="content">
    <span class="header">Dummy Marker Name 1</span>
    <div class="description">
      Dummy Marker Description
    </div>
  </div>
</div>
            `);
                marker2.bindPopup(`
<div class="ui card">
  <div class="image">
    <img src="${bruh[0][2]}" alt="Wilderness Image">
  </div>
  <div class="content">
    <span class="header">Dummy Marker Name 2</span>
    <div class="description">
      Dummy Marker Description
    </div>
  </div>
</div>
            `);
                marker3.bindPopup(`
<div class="ui card">
  <div class="image">
    <img src="${bruh[0][3]}" alt="Wilderness Image">
  </div>
  <div class="content">
    <span class="header">Dummy Marker Name 3</span>
    <div class="description">
      Dummy Marker Description
    </div>
  </div>
</div>
            `);
                marker4.bindPopup(`
<div class="ui card">
  <div class="image">
    <img src="${bruh[0][4]}" alt="Wilderness Image">
  </div>
  <div class="content">
    <span class="header">Dummy Marker Name 4</span>
    <div class="description">
      Dummy Marker Description
    </div>
  </div>
</div>
            `);
            }
        });
        //console.log(bruh);


    </script>
@endsection
