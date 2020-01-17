@if (isset($link['divider']))
    <div class="dropdown-divider"></div>
@elseif (isset($link['header']))
    <h6 class="dropdown-header">{{ $link['header'] }}</h6>
@else
    <a class="dropdown-item waves-effect" href="{{ $link['href'] }}"
       {{ (isset($link['no_blank']) ? '' : 'target=_blank')  }} rel="noreferrer nofollow noopener">
        @lang($link['name'])@if (isset($link['lang']))<span class="label label-primary">{{ $link['lang'] }}</span>@endif
    </a>
@endif
