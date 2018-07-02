<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    {{--@if(app()->environment() == 'production')--}}
    {{--<!-- Global site tag (gtag.js) - Google Analytics -->--}}
    {{--<!-- Yandex.Metrika counter -->--}}
    {{--@endif--}}

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <link rel="icon" href="/favicon.ico">

    @section('css')
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    @show

    @section('head-script')
        @auth
            <script>User = 'Logged'</script>
        @endauth

        @guest
            <script>User = 'Guest'</script>
        @endguest
    @show
</head>

<body>

<!-- Begin page content -->
<main role="main" class="container">
    <div class="row">

        <nav id="main-menu" class="navbar fixed-top navbar-expand-lg navbar-light col-sm-12">
            <div class="container">

                <a class="navbar-brand only-mobile" href="#">Menu</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav mr-auto">
                        @foreach($roots as $root)
                            @if($root->hide_submenu || $root->sortChildren()->get()->count() < 1)
                                <li class="nav-item @if($root->id == $page->id)active @endif "><a class="nav-link" href="{{ $root->absUri() }}">{{ $root->name }}</a></li>
                            @else
                                <li class="nav-item dropdown @if(!$page->isRoot() && $root->sortChildren()->get()->first()->parent->id == $page->parent->id)active @endif ">
                                    <a class="nav-link dropdown-toggle" href="{{ $root->absUri() }}" id="navbarDropdown-{{ $root->id }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ $root->name }}
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown-{{ $root->id }}">
                                        <a class="dropdown-item" href="{{ $root->absUri() }}">{{ $root->name }}</a>
                                        <div class="dropdown-divider"></div>
                                        @foreach($root->sortChildren()->get() as $children)
                                            <a class="dropdown-item @if($children->id == $page->id)active @endif " href="{{ $children->absUri() }}">{{ $children->name }}</a>
                                        @endforeach
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="row" id="header-pane">
        <div class="col-sm-3">

        </div>
        <div class="col-sm-9">

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h1>@yield('title')</h1>
            @yield('content')
        </div>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="d-none d-sm-block col-sm-4 text-left"></div>
            <div class="col-sm-4 text-center">
            </div>
            <div class="d-none d-sm-block col-sm-4 text-right"></div>
        </div>
    </div>
</footer>

@section('js')
    <script src="{{ mix('/js/app.js') }}"></script>
@show


</body></html>