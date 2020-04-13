<div class="ui left demo vertical inverted sidebar labeled icon menu visible">
    <div class="item">
        <img src="https://simpleblog.projects.lazydev.me/uploads/profile_user_1.jpg" alt="Profile Picture" class="ui tiny circular image" style="margin-left: 5px;">
        {{--<br><div style="width: 123px;">{{ Auth::user()->name }}</div><br>--}}
        <button class="mini ui button" style="margin-top: 8px" onclick="showModal('pp_change');">
            <i class="camera icon"></i>
            Change
        </button>
        <br>
        <button class="mini ui button" style="margin-top: 8px" onclick="showModal('profile_change');">
            <i class="user icon"></i>
            Change
        </button>
    </div>
    <a href="{{ route('dashboard.home') }}" class="item">
        <i class="home icon"></i>
        Home
    </a>
    <a href="{{ route('dashboard.gisindex') }}" class="item">
        <i class="database icon"></i>
        Gis Index
    </a>
    <a href="{{ route('dashboard.map') }}" class="item">
        <i class="map icon"></i>
        Map
    </a>
    <a href="{{ route('gis.create') }}" class="item">
        <i class="plus icon"></i>
        New GIS Entry
    </a>
    @hasanyrole('sadmin|admin')
    <a href="{{ route('user.index') }}" class="item">
        <i class="user icon"></i>
        Manage Users
    </a>
    @endhasanyrole
</div>
