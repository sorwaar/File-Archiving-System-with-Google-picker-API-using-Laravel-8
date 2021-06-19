 <!-- sidebar menu area start -->
 <div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}">
                <h2 class="text-white">FAS</h2>
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}">My FAS</a></li>

                    <li class="{{ Route::is('category.index') ? 'active' : '' }}"><a href="{{ route('category.index') }}">Categories</a></li>
                    <li class="{{ Route::is('admin.media') ? 'active' : '' }}"><a href="{{route('admin.media')}}">Upload Files</a></li>
                    <br>
                    <li ><a href="{{route('admin.logout')}}">Log Out</a></li>

                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->
