@extends('layouts.dashboard')

@section('content')
    <h3>Edit User</h3>
    <form action="{{ route('user.update', ['id' => $user->id]) }}" method="post" class="ui form" id="create_jadwal">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}
        <div class="field">
            <label for="name">Name</label>
            <input type="text" name="name" required id="name" value="{{ $user->name }}"><br>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" name="email" required id="email" value="{{ $user->email }}"><br>
        </div>
        <div class="field">
            <label for="password">Password (leave blank if not editing)</label>
            <input type="password" name="password" id="password"><br>
        </div>
        @role('sadmin')
        <div class="field">
            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="admin" {{ $user->getRoleNames()[0] === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $user->getRoleNames()[0] === 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>
        @endrole
        <button class="ui primary button" type="submit">Update User</button>
        <a href="{{ route('user.index') }}" class="ui button">Cancel</a>
    </form>
@endsection

