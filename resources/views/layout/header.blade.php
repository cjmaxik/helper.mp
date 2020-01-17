@php
    /** @var Collection $servers */use Illuminate\Support\Collection
@endphp

<div class="jumbotron text-center jarallax">
    <img src="{{ $wot['storage_blurred'] }}" alt="" class="jarallax-img">

    <h2 class="d-none d-md-block {{ app()->isLocal() ? 'red-text' : '' }}" style="margin-bottom: 0;">
        @if ($wot['id'] === 'JUSTMONIKA')
            Just Monika
        @else
            TruckersMP Helper
        @endif
    </h2>

    <div class="row">
        @if (!isset($servers->error))
            <div class="col-sm-12 col-md-12 large-12 columns">
                <p class="counts">
                    <span data-toggle="tooltip" data-placement="left" title="@lang('Online players')">
                        <span class="iconify icon-shadow" data-icon="mdi:truck"></span>&nbsp;<strong>{{ number_format($servers_stats['overall']['players_count'], 0) }}</strong>
                    </span>

                    <span class="iconify icon-shadow" data-icon="mdi:unfold-more-vertical"></span>

                    <span data-toggle="tooltip" data-placement="top" title="@lang('Online servers')">
                        <span class="iconify icon-shadow" data-icon="mdi:server-network"></span>&nbsp;<strong>{{ number_format($servers_stats['overall']['online_count']) }}</strong>
                    </span>

                    |

                    <span data-toggle="tooltip" data-placement="right" title="@lang('Users count')">
                        <span class="iconify icon-shadow" data-icon="mdi:account-multiple"></span>&nbsp;<strong>{{ number_format($mp->registered, 0) }}</strong>
                    </span>
                </p>
            </div>
        @endif

        @if(isset($news))
            <div class="col-sm-12 col-md-12 large-12 columns">
                <p class="text-center d-none d-md-block">
                    @lang('TruckersMP News'): <a class="tmp-link" href="{{ $news['link'] }}" target="_blank" rel="noreferrer nofollow noopener">
                        <span class="version_tag">{{ $news['title'] }} <span class="iconify" data-icon="mdi:open-in-new"></span></span>
                    </a>
                </p>
            </div>
        @endif
    </div>

    <div class="container">
        @if (!isset($mp->error) && isset($mp->version))
            <div class="row versions">
                <div class="col-xs-12 col-sm-6 columns">
                    @lang('Multiplayer'): <span class="version_tag">@ifSet($mp->version)</span>
                </div>
                <div class="col-xs-12 col-sm-6 columns">
                    <span class="d-none d-md-block">Euro&nbsp;Truck&nbsp;Simulator&nbsp;2: <span class="version_tag">@ifSet($mp->ets2)</span></span>
                    <span class="d-md-none">@lang('Launcher'): <span class="version_tag">@ifSet($mp->launcher)</span></span>
                </div>

                <div class="col-xs-12 col-sm-6 columns">
                    <span class="d-none d-md-block">@lang('Launcher'): <span class="version_tag">@ifSet($mp->launcher)</span></span>
                    <span class="d-md-none">ETS2: <span class="version_tag">@ifSet($mp->ets2)</span></span>
                </div>
                <div class="col-xs-12 col-sm-6 columns">
                    <span class="d-none d-md-block">American&nbsp;Truck&nbsp;Simulator: <span class="version_tag">@ifSet($mp->ats)</span></span>
                    <span class="d-md-none">ATS: <span class="version_tag">@ifSet($mp->ats)</span></span>
                </div>
            </div>
        @else
            <div class="text-center">
                <span class="version_tag">TruckersMP API isn't available for now... Sorry for that...</span>
            </div>
        @endif
    </div>
</div>
