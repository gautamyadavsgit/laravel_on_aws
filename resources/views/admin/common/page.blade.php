@include('admin.common.header')

@include('admin.common.sidebar')

@hasSection('content')
    @yield('content')
@endif

@include('admin.common.footer')
