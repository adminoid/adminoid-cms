<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="icon" href="/favicon.ico">

    @section('css')
        <link rel="stylesheet" href="{{ mix('/css/admin.css') }}">
    @show
</head>

<body>

<!-- Begin page content -->
<main role="main" class="container" id="app">
    <div class="row">
        <nav id="main-menu" class="navbar fixed-top navbar-expand-lg navbar-light col-sm-12">
            <div class="container">
                <button class="navbar-toggler" type="button" @click="toggleSidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <transition-group name="list-complete" tag="div" id="alerts-wrapper">
                <alert-item v-for="(alert, index) in alerts" :data="alert" :key="alert.id" :data-index="index" @close="removeAlert" class="list-complete-item"></alert-item>
            </transition-group>
            <div id="modal-place">
                <div id="modals"></div>
            </div>
            @yield('content')
        </div>
    </div>
</main>

@section('js')
    <script src="{{ mix('/js/admin.js') }}"></script>
@show


</body></html>