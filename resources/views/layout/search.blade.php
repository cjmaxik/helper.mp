@if($wot['id'] === 'JUSTMONIKA')
    <div class="col">
        <a href="/">
            <img src="{{ asset('/images/ddlc/okay.jpg') }}" alt="" class="img-fluid">
        </a>
    </div>
@else
    <div class="col" id="form">
        @if (Session::has('bigmp'))
            <div class="card bg-success text-center z-depth-2 mb-1 text-white" role="alert">
                <div class="card-body">
                    <p style="margin-bottom: 0;">
                        TruckersMP Helper is now available at <strong><a class="text-white" style="text-decoration: underline;" href="https://helper.mp">https://helper.mp</a></strong>.<br>
                        Please update your bookmarks!
                    </p>
                </div>
            </div>
            <br>
        @endif

        <div id="app">
            <div class="row text-center">
                <div class="col">
                    <div class="md-form input-group search_form">
                        <div class="input-group-btn d-none d-md-block">
                            <button class="btn btn-success videoOpen" id="videoOpen" data-toggle="tooltip" data-placement="top" title="@lang('HOW SHOULD I USE IT???')">
                                <span class="iconify icon-shadow" data-icon="mdi:help"></span>
                            </button>
                        </div>

                        <input type="text" class="form-control text-center search_needle" placeholder="@lang('Player search') - Steam ID, Steam URL, TruckersMP ID" id="search_needle" required autocomplete="off">

                        <div class="input-group-btn">
                            <button class="btn btn-primary search_button" id="search_button">
                                <span class="iconify icon-shadow" data-icon="mdi:magnify"></span>
                            </button>
                        </div>
                    </div>

                    <div id="error" class="card red lighten-1 text-center z-depth-2 mb-3" style="display: none;">
                        <div class="card-body">
                            <p class="white-text mb-0">@lang('Please provide some information!')</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row text-center">
                <div class="col">
                    <form id="search_mode">
                        <div class="row no-gutters">
                            <div class="col-md-3 col-xs-6">
                                <input class="form-check-input with-gap" type="radio" name="mode" value="auto" id="mode_auto" checked="checked">
                                <label class="form-check-label" for="mode_auto">
                                    <span class="iconify" data-icon="mdi:sitemap"></span> @lang('Auto')
                                </label>
                            </div>

                            <div class="col-md-3 col-xs-6">
                                <input class="form-check-input with-gap" type="radio" name="mode" value="steam_id" id="mode_steam_id">
                                <label class="form-check-label" for="mode_steam_id">
                                    <span class="iconify" data-icon="mdi:steam"></span> Steam ID
                                </label>
                            </div>

                            <div class="col-md-3 col-xs-6">
                                <input class="form-check-input with-gap" type="radio" name="mode" value="steam_url" id="mode_steam_url">
                                <label class="form-check-label" for="mode_steam_url">
                                    <span class="iconify" data-icon="mdi:link-variant"></span> Steam URL
                                </label>
                            </div>

                            <div class="col-md-3 col-xs-6">
                                <input class="form-check-input with-gap" type="radio" name="mode" value="truckersmp_id" id="mode_truckersmp_id">
                                <label class="form-check-label" for="mode_truckersmp_id">
                                    <span class="iconify" data-icon="mdi:truck"></span> TruckersMP ID
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <br>

            <div class="text-center">
                <span id="history">
                    <em>@lang('Search history') - @lang('loading...')</em>
                </span>
            </div>
        </div>
    </div>

    <div class="videoModal modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                @if (App::getLocale() !== 'ru')
                    <h3 class="modal-title">@lang('HOW SHOULD I USE IT???')</h3>
                @endif

                <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="videoModalFrame" class="embed-responsive-item" width="100%" height="100%" src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="player_modal" tabindex="-1">
        {{--Modal content goes here--}}
    </div>

    <div class="modal fade" id="player_search" data-keyboard="false" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content bg-transparent modal-no-shadow">
                <div class="modal-body text-center">
                    <div class="view z-depth-5">
                        <img class="img-fluid" src="{{ $wot['storage'] }}" alt="{{ $wot['nickname'] }}">
                        <div class="mask flex-bottom waves-effects waves-light">
                            <h4 class="card-title text-white text-shadow search__content mb-0">
                                <span class="iconify animated icon-spin" data-icon="mdi:loading"></span> <span class="font-weight-bold"> @lang('Searching...')</span>
                                <span class="d-none d-md-inline">
                                        <br/>@lang('Now you are looking at a screenshot by :nickname', ['nickname' => $wot['nickname']])...
                                    </span>
                                <small id="slowLoading" style="display: none;">
                                    <br>@lang('Please wait more than usual...')
                                </small>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
