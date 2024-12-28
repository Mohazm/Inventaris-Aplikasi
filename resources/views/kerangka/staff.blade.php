<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<!-- PWA  -->
<meta name="theme-color" content="#6777ef" />
<link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
<link rel="manifest" href="{{ asset('/manifest.json') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts_staff.style')

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts_staff.sidebar')
            <div class="layout-page">
                @include('layouts_staff.navbar')
                <div class="content-wrapper">
                    @yield('content')
                    @include('layouts_staff.footer')
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @include('layouts_staff.script')

   
</body>

</html>
