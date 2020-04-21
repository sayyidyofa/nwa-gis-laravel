@extends('layouts.dashboard')

@section('content')
    <h1>Welcome back, {{Auth::user()->name}}</h1>
    @role('sadmin')
        <p>test admin</p>
    @endrole

@endsection

@section('inline_js')
    <script>
        $(() => {
            let status = '';
            status = status.concat('{{ Session::get('notif') ?? null }}');
            if (status.length > 0) {
                $('body').toast({
                    class: 'info',
                    message: 'Login Berhasil!'
                });
            }
        });
    </script>
@endsection
