<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <noscript>
        <meta http-equiv="refresh" content="0; URL=https://outdatedbrowser.com/{{ app()->getLocale() }}">
    </noscript>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="google-site-verification" content="QCAy9dBY__hyfnVN60P9f_pJR2J1dTpPtSXFhijc4Gw"/>

    <meta name="author" content="CJMAXiK">
    <meta name="description"
          content="MP: @ifSet($mp->version) | L: @ifSet($mp->launcher) | ETS2: @ifSet($mp->ets2) | ATS: @ifSet($mp->ats)">

    <meta property="og:image:width" content="279">
    <meta property="og:image:height" content="279">
    <meta property="og:description" content="MP: @ifSet($mp->version) | L: @ifSet($mp->launcher) | ETS2: @ifSet($mp->ets2) | ATS: @ifSet($mp->ats)">
    <meta property="og:title" content="TruckersMP Helper | @lang('Player search').  @lang('Server status'). ">
    <meta property="og:url" content="{{ config('app.url') }}/">
    <meta property="og:image" content="{{ config('app.url') }}/og-image.jpg">

    <meta name="keywords" content="TruckersMP Helper, @lang('TruckersMP Helper'), truckersmp live map, truckersmp profile link, truckersmp player search, truckersmp status, truckersmp stats, truckersmp update, truckersmp unsupported game version detected, truckersmp version, truckersmp version error, truckersmp wrong version, truckersmp wrong game version">

    {{-- Icons 0_o --}}
    @include('layout.icons')

    <meta http-equiv="cleartype" content="on"/>

    <title>TruckersMP Helper | @lang('Player search'). @lang('Server status')</title>

    @php
        $ui = [
            'information'    => __('Information'),
            'latestFiveBans' => __('Latest 5 bans'),

            'inSteamSince'      => __('In Steam since'),
            'inTruckersmpSince' => __('In TruckersMP since'),

            'vtc' => __('VTC'),

            'rank' => __('Rank'),

            'playerHasNoBans' => __('Player has no bans'),
            'playerHidBans' => __('Player hid ban history'),
            'bans'            => [
                'reason'    => __('Reason'),
                'adminName' => __('Admin Name'),
                'created'   => __('Created'),
                'expires'   => __('Expires'),
                'active'    => __('Active'),

                'never' => __('Never'),

                'banned' => __('Banned'),
                'perm' => __('permanently'),
                'until' => __('until')
            ],

            'cookieContest' => [
                'message' => __('This website uses cookies to ensure you get the best experience on our website'),
                'dismiss' => __('Got it!')
            ],

            'mapOnline' => __('Playing right now'),

            'footer' => [
                'UTC' => __('Datetime in UTC'),
                'updateTime' => __('Information is updated once per 10 minutes'),
            ],

            'errors' => [
                'errorMessage' => __('There is an error. Please reload this page. Thank you for bug hunting') . ' :) wtf@bigmp.ru',
                'userNotFound' => __('User was not found. Sorry for that. You can choose other search mode.'),
                'emptyForm' => __('Please provide some information!'),
                'gotIt' => __('Got it!'),
                'codes' => [
                    148801 => __('Actually, no Steam profile found by this needle'),
                    148802 => __('Actually, no users found by this SteamID'),
                    148810 => __('Actually, no TruckersMP ID found by this needle')
                ]
            ]
        ]
    @endphp

    <script>
      window.csrfToken = @json(csrf_token());
      window.help_src = @json('https://www.youtube-nocookie.com/embed/PeNDyK2NILk?rel=0&controls=0&showinfo=0&cc_load_policy=1&modestbranding=1&hl=en');
      window.lastUpdate = @json(date('d.m.Y H:i:s T', $time['update']));
      window.lastProcessed = @json(date('d.m.Y H:i:s T', $processing_datetime));
      window.no_history_message = '<div class="hist_item disabled">@lang('no items yet') :(</div>'
      window.image = @json($wot['storage_blurred']);
      window.changeOrderURL = @json(route('order.change'));
      window.darkMode = @json($darkMode);

      window.ui = @json($ui);
    </script>

    <script src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
    <script src="{{ mix('js/custom-icons.js') }}"></script>

    {{-- Own stylesheets --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"/>
</head>

<body class="@if($darkMode) dark @endif">
@include('layout.outdated')
@include('layout.navbar')
@include('layout.header')

<div class="container">
    @if (array_key_exists(app()->getLocale(), config('locales.dev')))
        @include('layout.devLang')
    @endif

    <div class="row">
        @include('layout.search')
    </div>
</div>

@if ($traffic)
    <div class="cd_road card-overlay jarallax">
        <img src="{{ ($wot['id'] === 'JUSTMONIKA') ? $wot['storage'] : asset('/images/dd.jpg') }}" alt=""
             class="jarallax-img">

        <div class="container">
            @include('servers.eu2shit')
        </div>
    </div>
@else
    <hr>
@endif

<div class="container">
    <div class="row">
        @include('servers.status', ['servers' => $servers->groupBy('game'), 'time' => $time])
    </div>
</div>

@include('layout.footer')

<!-- ================================================ -->
<script crossorigin="anonymous" src="https://polyfill.io/v3/polyfill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/outdatedbrowser@1.1.5/outdatedbrowser/outdatedbrowser.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jarallax@1.12.0/dist/jarallax.min.js"></script>

<script>
  NProgress.start()
</script>

<script src="https://cdn.jsdelivr.net/npm/bowser@1.9.4/src/bowser.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mdbootstrap@4.8.11/js/mdb.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@0.19.0/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lockr@0.8.5/lockr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/handlebars@4.5.1/dist/handlebars.runtime.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/countup.js@1.9.3/dist/countUp.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/autolinker@3.11.1/dist/Autolinker.min.js"></script>

<script src="{{ mix('js/app.js') }}"></script>

<script type="text/javascript">
  const _userway_config = {
    language: '{{ $lang['iso'] }}',
    account: '<ACCOUNT>'
  }
</script>
<script type="text/javascript" src="https://cdn.userway.org/widget.js"></script>
<!-- ================================================ -->

@if(!app()->isLocal())
    @include('layout.ga')
@endif

{{--Cookie Contest--}}
@include('layout.cookie')

{{--@formatter:off--}}
<script type="application/ld+json">
    {
        "@context":"http://schema.org",
        "@type":"WebSite",
        "name":"TruckersMP Helper | Player search. Server status",
        "description":"MP: @ifSet($mp->version) | L: @ifSet($mp->launcher) | ETS2: @ifSet($mp->ets2) | ATS: @ifSet($mp->ats)",
        "url":"{{ config('app.url') }}",
        "dateModified":"{{ date('Y-m-d\TH:i:s\Z', $time['update']) }}",
        "publisher":"CJMAXiK"
    }

</script>
{{--@formatter:on--}}
</body>
</html>
