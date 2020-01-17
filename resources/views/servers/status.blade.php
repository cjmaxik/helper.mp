@php
    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    /** @var \Illuminate\Support\Collection $servers */
    /** @var \Illuminate\Support\Collection $servers_stats */
@endphp

@if (count($servers))
    <div class="col text-center">
        <h3 class="text-center d-none d-sm-block font-weight-bold">
            @lang('Server status')
        </h3>

        <h4 class="text-center d-sm-none font-weight-bold">
            @lang('Server status')
        </h4>

        @if ($time)
            <p data-toggle="tooltip" data-placement="top" title="@lang('In-game time')">
                <span class="iconify" data-icon="mdi:clock"></span> <span class="gameTime">{{ $time['game'] }}</span>
            </p>
        @endif

        <div class="switch">
            <label>
                @lang('By popularity')<input type="checkbox" id="orderChange" {{ session('orderBy') ? 'checked' : '' }}/><span class="lever"></span>@lang('In default order')
            </label>
        </div>

        @if (time() - $time['update'] >= 120)
            <blockquote class="blockquote bq-danger z-depth-2 mb-4 text-center">
                <p class="bq-title">@lang('WARNING!') @lang('This information is not up-to-date.')</p>
                <p>@lang('If you saw this message several times today, please give us a feedback'): <a href="https://forum.truckersmp.com/index.php?/topic/36486-truckersmp-helper/#comment-350007">@lang('Forum topic')</a> <span class="iconify" data-icon="mdi:open-in-new"></span></p>
            </blockquote>
        @endif

        <br>

        @foreach ($servers as $game => $all)
            @php
                $count_empty = $servers_stats[$game]['empty'];
                $count_offline = $servers_stats[$game]['offline'];
                $count = $servers_stats[$game]['count'];

                /** @noinspection PhpFullyQualifiedNameUsageInspection */
                /** @var \Illuminate\Support\Collection $servers_in */
                $servers_in = $all->where('online', true)->where('players', '>', 0);

                if (session('orderBy')) {
                   $servers_in = $servers_in->sortBy('id');
                } else {
                    $servers_in = $servers_in->sortByDesc(static function ($server) {
                        if ($server) {
                            return $server->percents;
                        }

                        return $server->id;
                    });
                }
            @endphp

            <h4 class="text-center">
                {{ ($game === 'ETS2') ? 'Euro Truck Simulator 2' : 'American Truck Simulator' }}
            </h4>

            <div class="row align-items-center align-self-center justify-content-center">
                @foreach ($servers_in as $server)
                    @include('servers.card', ['server' => $server])
                @endforeach
            </div>

            @if ($count_empty || $count_offline)
                <div class="row text-center">
                    <div class="col">
                        <div class="btn-group" role="group">
                            @each('servers.empty', $all, 'server')
                        </div>
                    </div>
                </div>
            @endif

            @if (!$loop->last)
                <hr>
            @endif
        @endforeach
    </div>
@endif
