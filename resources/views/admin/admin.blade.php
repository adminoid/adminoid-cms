<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    @if(app()->environment() == 'production')
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-121815197-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-121815197-1');
        </script>
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript" >
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter49460335 = new Ya.Metrika2({
                            id:49460335,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true,
                            webvisor:true
                        });
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/tag.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks2");
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/49460335" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
    @endif

    <link rel="icon" href="/favicon.ico">

    @section('css')
        <link rel="stylesheet" href="{{ mix('/css/admin.css') }}">
    @show
</head>

<body>

<!-- Begin page content -->
<main role="main" class="container container-fluid" id="app">
    <div class="row">
        <nav id="main-menu" class="navbar fixed-top navbar-expand-lg navbar-light col-sm-12">
            <div class="container">
                <a class="navbar-brand order-md-last" href="#/">Adminoid CMS</a>
                <button class="navbar-toggler" type="button" @click="toggleSidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
    </div>

    <div class="row" id="content-wrapper">
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