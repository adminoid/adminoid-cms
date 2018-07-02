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
<main role="main" class="container">
    <div class="row">

        <nav id="main-menu" class="navbar fixed-top navbar-expand-lg navbar-light col-sm-12">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="/admin-panel">Admin panel<span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="row">
        <div class="col-sm-12" id="app">

            <transition-group name="list-complete" tag="div" id="alerts-wrapper">
                <alert-item v-for="(alert, index) in alerts" :data="alert" :key="alert.id" :data-index="index" @close="removeAlert" class="list-complete-item"></alert-item>
            </transition-group>

            @yield('content')
        </div>
    </div>
</main>

@section('js')
    <script src="{{ mix('/js/admin.js') }}"></script>
@show


</body></html>