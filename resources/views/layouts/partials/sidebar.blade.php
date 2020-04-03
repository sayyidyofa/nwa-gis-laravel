<div class="ui left demo vertical inverted sidebar labeled icon menu visible">
    <div class="item">
        <img src="https://simpleblog.projects.lazydev.me/uploads/profile_user_1.jpg" alt="Profile Picture" class="ui tiny circular image" style="margin-left: 5px;">
        <br> hello <br>
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
    <!--<a class="item" style="
    padding-top: 36px;">
    </a>-->
    {{--<a href="{{ route('home') }}" class="item">
        <i class="home icon"></i>
        Home
    </a>--}}
    <a href="{{ route('admin.home') }}" class="item">
        <i class="database icon"></i>
        GIS Index
    </a>
    <a href="{{ route('admin.map') }}" class="item">
        <i class="map icon"></i>
        Map
    </a>
    <a href="{{ route('gis.create') }}" class="item">
        <i class="plus icon"></i>
        New GIS Entry
    </a>
</div>
