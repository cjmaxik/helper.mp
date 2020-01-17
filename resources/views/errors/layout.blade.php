<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>TruckersMP Helper</title>

    {{-- Icons 0_o --}}
    @include('layout.icons')

    <link href="https://fonts.googleapis.com/css?family=Google+Sans" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            background-color: {{ $background_color ?? '#CC0000' }};
            margin: 0;
            padding: 0;
            width: 100%;
            color: {{ $text_color ?? '#ff4444' }};
            display: table;
            font-weight: 100;
            font-family: 'Google Sans', sans-serif;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
            width: 95%;
        }

        @media (min-width: 769px) {
            .content {
                width: 60%;
            }
        }

        .title {
            font-size: 5em;
        }

        .lead {
            font-style: italic;
            color: {{ $text_color ?? '#ff4444' }};
            font-size: 2em;
        }

        .lead > a {
            color: {{ $text_color ?? '#ff4444' }};
        }
    </style>
</head>

<body>
<div class="container">
    <div class="content">
        <div class="title">
            {!! $back_message !!}<br>
        </div>

        <div class="lead">
            @if(!empty(Sentry::getLastEventID()))
                <p>
                    <small>Error ID: {{ Sentry::getLastEventID() }}</small>
                </p>
            @endif
            @if (!isset($no_link))
                <a href="{{ url('/') }}">
                    <small>Return to the home page</small>
                </a>
            @endif
        </div>
    </div>
</div>

@if(app()->bound('sentry') && !empty(Sentry::getLastEventID()))
    <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

    <script>
      Raven.showReportDialog({
        eventId: '{{ Sentry::getLastEventID() }}',
        dsn: '{{ config('sentry.dsn') }}'
      })
    </script>
@endif

@if (!app()->isLocal())
    @include('layout.ga')
@endif
</body>
</html>
