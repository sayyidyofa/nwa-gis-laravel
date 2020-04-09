@extends('layouts.dashboard')

@section('content')
    <p>There exists {{ $users->count() }} users @role('sadmin') and admins @endrole in this system</p>

    <p>User list: </p>
    <table class="ui selectable sortable celled table">
        <tr class="center aligned">
            <th class="sorted ascending">ID</th>
            <th>Name</th>
            <th>Email</th>
            @role('sadmin')
            <th>Role</th>
            @endrole
            <th>Created At</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
        @foreach($users as $user)
            <tr class="center aligned">
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                @role('sadmin')
                <td>{{ $user->getRoleNames()[0] }}</td>
                @endrole
                <td>{{ $user->created_at }}</td>
                <td>{{ $user->updated_at }}</td>
                <td><a href="{{ route('user.edit', ['id' => $user->id]) }}" data-tooltip="Edit User Data"><i class="pencil icon"></i></a> |
                    <a href="{{ route('user.destroy', ['id' => $user->id]) }}" data-tooltip="Remove User" {{--id="{{$user->id}}"--}} class="delete-confirm"><i class="x icon"></i></a></td>
            </tr>
        @endforeach
    </table>
    {{ $users->links() }}
    <a href="{{ route('user.create') }}" class="ui primary button">Create New User</a>
@endsection()

