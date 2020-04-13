<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.gif') }}">

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
<script src="{{ asset('js/wkx.min.js') }}"></script>
@yield('plugin_js')
<script src="{{ asset('js/main.js') }}"></script>
<script>
    //$(document).ready(function(){
        $(".logout-confirm").click(function(){
            Swal.fire({
                title: "Logout",
                text: "Are you sure want to logout?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#dd3333",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            }).then((result) => {
                if (result.value) {
                    //form.submit();
                    window.location.href = "{{ route('logout') }}";
                }
            });
        });
    //});
</script>
<script>
    $('.delete-confirm').on('click', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        //console.log(url);
        let id = $(this).data("id");
        let token = $("meta[name='csrf-token']").attr("content");
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {"id":id, "_method":"DELETE", "_token":token},
                    success: function(result) {console.log(result)},
                    error: (jqXHR) => {console.log(jqXHR.responseText)}
                });
                swalWithBootstrapButtons.fire(
                    'Deleted!',
                    'Data has been deleted.',
                    'success'
                ).then(()=>{location.reload();});
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your data is safe :)',
                    'error'
                )
            }
        })
    });
</script>

@yield('inline_js')
</body>
</html>
