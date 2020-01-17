@php
    /** @var \Illuminate\Support\Collection $traffic */
@endphp

<div class="col text-center text-white">
    <h3 class="text-center d-none d-sm-block font-weight-bold">
        @lang('Busiest Locations')
    </h3>

    <h4 class="text-center d-sm-none font-weight-bold">
        @lang('Busiest Locations')
    </h4>

    <div class="row justify-content-center pb-3 d-none d-md-flex">
        @foreach($traffic->take(3) as $town)
            <div class="col-xs-12 col-md-4 px-0">
                <span class="badge big-badge cd_count {{ $town['severity'] }} countItUp" data-value="{{ $town['players'] }}">
                    ...
                </span>

                <h3>
                    <span data-toggle="tooltip" title="{{ $town['country'] }}">{{ $town['name'] }} <small><i>({{ $town['server'] }})</i></small></span>
                </h3>
            </div>
        @endforeach
    </div>

    <div class="row justify-content-center d-none d-md-flex">
        @foreach($traffic->slice(3)->take(5) as $town)
            <div class="col px-0">
                <span class="badge small cd_count {{ $town['severity'] }} countItUp" data-value="{{ $town['players'] }}">
                    ...
                </span>
                <div>
                    <span data-toggle="tooltip" title="{{ $town['country'] }}">{{ $town['name'] }} <small><i>({{ $town['server'] }})</i></small></span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row justify-content-center d-md-none">
        <div class="col">
            <ul class="list-group list-group-flush">
                @foreach($traffic->take(8) as $town)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span data-toggle="tooltip" title="{{ $town['country'] }}">{{ $town['name'] }} <small><i>({{ $town['server'] }})</i></small></span>
                        <span class="badge cd_count badge-pill {{ $town['severity'] }}">{{ $town['players'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
