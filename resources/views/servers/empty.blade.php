@php
    /** @var App\TruckersMP\Types\Server $server */

    $buttonText = null;
    $buttonStyle = null;
    $buttonIcon = null;

    if (!$server->online) {
        $buttonText = 'Server is offline';
        $buttonStyle = 'btn-danger';
        $buttonIcon = 'server-network-off';
    } else if ($server->players === 0) {
        $titleText = 'Server is empty';
        $buttonStyle = 'btn-warning';
        $buttonIcon = 'account-remove';
    }
@endphp

@if($buttonText && $buttonStyle && $buttonIcon)
    <button class="btn {{ $buttonStyle }} btn-disabled" type="button" data-toggle="tooltip" data-placement="top" title="@lang($buttonText)">
        <span class="iconify" data-icon="mdi:{{ $buttonIcon }}"></span>
        <small>{{ $server->game }}</small> {{ $server->name }}
    </button>
@endif
