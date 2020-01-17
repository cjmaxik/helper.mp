<nav class="navbar navbar-dark navbar-expand-md fixed-top scrolling-navbar" id="coolNavbar">
    <div class="container">
        <a class="navbar-brand" href="#" id="navbar-brand">
            <img alt="Brand" width="32" height="32" class="d-inline-block align-top" src="{{ asset('/favicon-32x32.png') }}?v={{ app()->version() }}">
            <strong class="d-md-none text-white">TruckersMP Helper</strong>
        </a>

        <button class="navbar-toggler navbar-toggler-right"
                type="button" data-toggle="collapse"
                data-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link game-link" href="https://www.instant-gaming.com/igr/cjmaxik/" target="_blank" rel="noreferrer nofollow noopener">
                        <span class="iconify icon-shadow" data-icon="mdi:gamepad-variant"></span> @lang('Buy games/DLCs')
                    </a>
                </li>

                {{-- green-text blue-text yellow-text red-text --}}
                <li class="nav-item">
                    <a href="https://truckersmpstatus.com/" class="nav-link" target="_blank" rel="noreferrer nofollow noopener" data-toggle="tooltip" data-placement="bottom"
                       data-html="true" title="@lang('MP Status')@if(!$text_status)<br><strong>{{ $text_status }}</strong>@endif">
                        <span class="iconify icon-shadow {{ $status }}-text" data-icon="mdi:circle"></span>
                    </a>
                </li>
            </ul>

            <div class="navbar-nav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item d-none d-md-block">
                        <a href="#" class="nav-link darkModeToggle" data-toggle="tooltip" data-placement="bottom" title="{{ __('On/Off Dark mode') }}">
                            <span id="darkMode_dark" class="iconify icon-shadow" data-icon="mdi:sunglasses" @if (!$darkMode) style="display: none;" @endif></span>
                            <span id="darkMode_white" class="iconify icon-shadow" data-icon="mdi:white-balance-sunny" @if ($darkMode) style="display: none;" @endif></span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link lang-name" data-toggle="modal" data-target="#langModal">
                            <span class="flag-icon {{ app()->getLocale() }} flag-icon-fw dropdown"></span><strong>{{ $lang['name'] }}</strong>
                        </a>
                    </li>

                    <li class="navbar-brand version d-none d-md-block">
                        <span class="z-depth-1 badge version-badge">
                            v{{ config('app.version') }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="modal fade right" id="langModal" tabindex="-1">
    <div class="modal-dialog modal-full-height modal-right modal-notify modal-info modal-lang">
        <div class="modal-content text-center">
            <div class="modal-header d-flex justify-content-center">
                <p class="heading">
                    <span class="iconify white-text" data-icon="mdi:earth"></span> Choose your language
                </p>
            </div>

            <div class="modal-body">
                <div class="container">
                    <div class="d-flex flex-wrap justify-content-center">
                        @each ('helpers.menuLangs', config('locales.prod'), 'lang')
                    </div>
                    <hr>

                    <h6>Not completed</h6>
                    <div class="d-flex flex-wrap justify-content-center">
                        @each ('helpers.menuLangs', config('locales.dev'), 'lang')
                    </div>

                    {{--                    <hr>--}}
                    {{--                    Issues? Errors? Your language is missing?<br/>You can <a href="https://translate.helper.mp" target="_blank" rel="noreferrer nofollow noopener"><i class="mdi mdi-earth"></i>help us now</a>!--}}
                </div>
            </div>
        </div>
    </div>
</div>
