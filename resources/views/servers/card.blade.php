@php
    /** @var App\TruckersMP\Types\Server $server */

    $percents = $server->percents;

    if ($percents >= 20 && $percents < 50) {
        $rush_hour = 'success-color';
    } elseif ($percents >= 50 && $percents < 95) {
        $rush_hour = 'warning-color';
    } elseif ($percents >= 95) {
        $rush_hour = 'danger-color';
    } else {
        $rush_hour = 'primary-color';
    }

    $col_lg = 6;

    if ($loop->count > 2) {
        $col_lg = 4;
    }
@endphp

<div class="col-sm-6 col-lg-{{ $col_lg }} col-xs-12 servers">
    @if ($server->event)
        <span class="badge badge-danger event-badge">Event</span>
    @endif

    <div class="card hoverable {{ $server->queue ? 'has-queue' : '' }}">
        <div class="card-header {{ $rush_hour ?? 'primary-color' }} text-white">
            <small>{{ $server->game }}</small>
            <strong>{{ $server->name }}</strong>

            @if ($server->promods)
                <span data-toggle="tooltip" data-placement="top" title="@lang('ProMods Support')">
                    <span class="iconify server-icon" data-icon="helper:promods"></span>
                </span>
            @endif
        </div>

        <div class="card-body">
            <h3>
                {{--@formatter:off--}}
                <span>{{ trim(number_format($server->players)) }}</span><small class="grey-text">/{{ $server->maxPlayers }}</small>
                @if ($server->queue)
                    <span class="server-queue" data-toggle="tooltip" data-placement="top" title="@lang('Queue')">
                        <span class="iconify" data-icon="mdi:timer-sand"></span><span id="server_{{ $server->id }}_queue">{{ $server->queue }}</span>
                    </span>
                @endif
                {{--@formatter:on--}}
            </h3>
        </div>

        <div class="progress md-progress">
            <div class="progress-bar {{ $rush_hour }} progress-bar-striped progress-bar-animated"
                 style="width: {{ ($percents >= 98) ? 100 : $percents }}%;"
                 aria-valuenow="{{ ($percents >= 98) ? 100 : $percents }}"
                 aria-valuemin="0"
                 aria-valuemax="100">
                @if ($percents > 10)
                    {{ round($percents) }}%
                @endif
            </div>
        </div>

        {{-- success-color-text, warning-color-text, danger-color-text, primary-color-text --}}
        <div class="card-footer">
            <span class="icon-stack" data-toggle="tooltip" data-placement="top" title="@lang('Freeroam mode')">
                <span class="iconify icon-rotate:90deg server-icon {{ !$server->collisions ? $rush_hour.'-text' : 'grey-text' }}" data-icon="mdi:car-multiple"></span>
            </span>

            <span class="icon-stack" data-toggle="tooltip" data-placement="top" title="@lang('Trucks only')">
                <span class="iconify server-icon {{ !$server->carsForPlayers ? $rush_hour.'-text' : 'grey-text' }}" data-icon="mdi:truck"></span>
            </span>

            <span class="icon-stack" data-toggle="tooltip" data-placement="top" title="@lang('Police cars for players')">
                <span class="iconify server-icon {{ $server->policeCarsForPlayers ? $rush_hour.'-text' : 'grey-text' }}" data-icon="mdi:car-sports"></span>
            </span>

            <span class="icon-stack" data-toggle="tooltip" data-placement="top" title="@lang('AFK Auto-kick')">
                <span class="iconify server-icon {{ $server->afkEnabled ? $rush_hour.'-text' : 'grey-text' }}" data-icon="mdi:sleep"></span>
            </span>

            <span class="icon-stack" data-toggle="tooltip" data-placement="top" title="@lang('Speedlimiter')">
                <span class="iconify server-icon {{ $server->speedLimiter ? $rush_hour.'-text' : 'grey-text' }}" data-icon="mdi:speedometer"></span>
            </span>
        </div>
    </div>
</div>
