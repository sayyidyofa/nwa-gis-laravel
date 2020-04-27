@extends('layouts.dashboard')

@section('content')
    <div class="ui container" style="margin-top: 80px;">
        <h3>Edit Geometry Coordinates</h3>
        <form action="{{ route('geometry.update', ['id' => $id]) }}" method="post" class="ui equal width form">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <table class="ui very basic celled table">
                <thead>
                <tr class="center aligned">
                    <th>Axis</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="tbody">

                </tbody>
            </table>
            <a class="ui green button" id="add-row-btn" style="margin-bottom: 20px">Add Row</a>
            <br>
            <button class="ui primary button" type="submit" id="edit-btn">Edit</button>
            <a href="{{ route('dashboard.gisindex') }}" class="ui button">Cancel</a>
        </form>
    </div>
@endsection

@section('plugin_js')
    <script src="{{ asset('js/wkx.min.js') }}"></script>
    <script src="{{ asset('js/buffer.min.js') }}"></script>
@endsection

@section('inline_js')
    <script>
        let Coordinates = JSON.parse('{{ $c }}');// wkx.Geometry.parse(new buffer.Buffer('{{ $c }}', 'hex')).toGeoJSON();
        let geotype = '{{ $t }}';
        let points = geotype === 'MultiPolygon' ? Coordinates[0][0] : Coordinates[0];
        let panjang = points.length;
        let fieldsTemplate = (point_id, coord_x, coord_y) => `
                <tr class="center aligned">
                    <td>
                        <div class="fields">
                            ${fieldTemplate('x', point_id, coord_x)}
                            ${fieldTemplate('y', point_id, coord_y)}
                        </div>
                    </td>
                    <td>
                        <span data-tooltip="Remove Point" onclick="removePoint(${point_id})"><i class="trash link icon"></i></span>
                    </td>
                </tr>
        `;
        let fieldTemplate = (axis, point_id, coord) => { return `
            <div class="inline field">
                <label for="${axis}-${point_id}">${axis}</label>
                <input type="text" id="${axis}-${point_id}" value="${coord}" required>
            </div>
        ` };

        function redrawTable(axisPoints) {
            let tableBody = $('#tbody');
            tableBody.empty();
            for (let i = 1; i <= axisPoints.length; i++) {
                tableBody.append(fieldsTemplate(i, points[i-1][0], points[i-1][1]));
            }
        }

        function hapus (arr, idx){delete arr[idx]; return arr.filter((x)=>{return x!==undefined});}

        function updatePoints() {
            let rows = $('#tbody')[0].rows;
            let temp = [];
            for (let i =0;i<rows.length;i++) {
                let x = rows[i]["cells"][0]["children"][0]["children"][0]["children"][1]["value"];
                let y = rows[i]["cells"][0]["children"][0]["children"][1]["children"][1]["value"];
                temp.push([x, y]);
            }
            points = temp;
        }

        function removePoint(id) {
            updatePoints();
            if (points.length > 3) {
                points = hapus(points, id-1);
                redrawTable(points);
            }
            else {
                $('form')
                    .toast({
                        title: 'No!',
                        message: 'A Geometry needs at least 3 points!',
                        showProgress: 'bottom',
                        classProgress: 'red'
                    })
                ;
            }
        }
        $('#add-row-btn').on('click', () => {
            updatePoints();
            points.push([0, 0]);
            redrawTable(points);
        });
        $('#edit-btn').on('click', (e) => {
            e.preventDefault();
            updatePoints();

            for (let idx = 0;idx<points.length;idx++) {
                points[idx][0] = Number(points[idx][0]);
                points[idx][1] = Number(points[idx][1]);
            }
            Coordinates = geotype === 'MultiPolygon' ? [[points]]: [points] ;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('geometry.update', ['id' => $id]) }}',
                type: 'POST',
                cache: false,
                data: {
                    _method: "PUT",
                    coordinates: JSON.stringify(Coordinates) //buf2hex(wkx.Geometry.parseGeoJSON(Coordinates).toWkb().buffer).toUpperCase()
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    console.log('Error sending data', error);
                },
                success: function(response){
                    console.log(response);
                    Swal.fire('Success', 'Geometry Updated', 'success').then(()=>{location.reload();});
                }
            });
        });
        $((e) => {
            redrawTable(points);
        });
    </script>
@endsection

