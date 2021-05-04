<!doctype html>
<html class="no-js" lang="en">

@include('backend.layouts.partials.head')

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    {{-- <div id="preloader">
        <div class="loader"></div>
    </div> --}}
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">
        @include('backend.layouts.partials.sidebar')
        <!-- main content area start -->
        <div class="main-content">
            @include('backend.layouts.partials.header')

            @yield('admin-content')
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        @include('backend.layouts.partials.footer')
        <!-- footer area end-->
    </div>
    <!-- page container area end -->
    @include('backend.layouts.partials.offsets')

    <!-- jquery latest version -->
        @include('backend.layouts.partials.scripts')

</body>

</html>
