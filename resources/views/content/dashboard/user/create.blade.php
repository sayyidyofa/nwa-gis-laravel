@extends('layouts.dashboard')

@section('content')
    <h3>Buat Jadwal Baru</h3>
    <form action="{{ route('user.store') }}" method="post" class="ui form" id="create_jadwal">
        {{ csrf_field() }}
        <div class="field">
            <label for="name">Name</label>
            <input type="text" name="name" required id="name"><br>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" name="email" required id="email"><br>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" name="password" required id="password"><br>
        </div>
        @role('sadmin')
        <div class="field">
            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        @endrole
        <button class="ui primary button" type="submit">Create User</button>
        <a href="{{ route('user.index') }}" class="ui button">Cancel</a>
    </form>
@endsection
