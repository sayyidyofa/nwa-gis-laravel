<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ (!empty($gtitle) ? $gtitle : 'NWA-GIS').(!empty($second_title) ? ' - '.$second_title : '') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.4/dist/semantic.min.css">
    @yield('plugin_css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('inline_css')

</head>
<body>
@include('layouts.partials.navbar')
@include('layouts.partials.sidebar')
<div style="margin-left: 140px; margin-top: 60px">
    @yield('content')
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.4/dist/semantic.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.10.10/dist/sweetalert2.all.min.js" integrity="sha256-kkBIa2jsEbSAOcxhNNuIquIK4IENf+VLUhxnd+TmJk8=" crossorigin="anonymous"></script>
<script src="{{ asset('js/ajax-progress.min.js') }}"></script>
<script src="{{ asset('js/wkx.min.js') }}"></script>
@yield('plugin_js')
<script src="{{ asset('js/main.js') }}"></script>
<script>
    $(".logout-confirm").on('click', function() {
        Swal.fire({
            title: "Logout",
            text: "Are you sure want to logout?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#dd3333",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.value) {
                window.location.href = "{{ route('logout') }}";
            }
        });
    });

    function deleteHandler(event, url) {
        event.preventDefault();
        //const url = $(this).attr('href');
        //console.log(url);
        //let id = $(this).data("id");
        let token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dd3333",
            cancelButtonColor: "#3085d6",
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {_method:"DELETE", _token:token},
                    success: function(result) {console.log(result)},
                    error: (jqXHR) => {console.log(jqXHR.responseText)}
                });
                Swal.fire(
                    "Deleted!",
                    "Data has been deleted.",
                    "success"
                ).then(() => {
                    location.reload();
                });
            }
            else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    "Cancelled",
                    "Your data is safe :)",
                    "error"
                )
            }
        })
    }

    $('.delete-confirm').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        //console.log(url);
        //let id = $(this).data("id");
        let token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dd3333",
            cancelButtonColor: "#3085d6",
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {_method:"DELETE", _token:token},
                    success: function(result) {console.log(result)},
                    error: (jqXHR) => {console.log(jqXHR.responseText)}
                });
                Swal.fire(
                    "Deleted!",
                    "Data has been deleted.",
                    "success"
                ).then(() => {
                    location.reload();
                });
            }
            else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    "Cancelled",
                    "Your data is safe :)",
                    "error"
                )
            }
        })
    });

    $('#download-gis').on('click', (e) => {
        e.preventDefault();
        const url = '{{ route('gis.export') }}';
        Swal.fire({
            title: "Warning",
            text: "The dataset contain rows that are bigger than Ms.Excel character limit (32767). It is save to be downloaded but please make sure to check upon corrupted coordinates before importing",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dd3333",
            cancelButtonColor: "#3085d6",
            confirmButtonText: 'I know what I\'m doing',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.value) {
                window.location.href = url;
            }
            else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    "Cancelled",
                    "The dataset will not be downloaded",
                    "error"
                )
            }
        })
    });
    $(()=>{
        try {
            let flash = JSON.parse('{!! Session::get('flash') !!}');

            let errorProp = {
                title: 'Error!',
                class: 'error',
                message: flash.message
            };
            let successProp = {
                title: 'Success',
                class: 'success',
                message: flash.message
            };

            if (Object.keys(flash).length > 0 && flash.constructor === Object) {
                $('body').toast(flash.status === 'success' ? successProp : errorProp);
            }
        } catch (e) {
            console.log(e)
        }
    });
</script>

@yield('inline_js')
</body>
</html>
