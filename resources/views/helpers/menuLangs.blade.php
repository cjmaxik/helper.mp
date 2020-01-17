@if($key !== app()->getLocale())
    <div class="p-2">
        <a href="{{ $key === app()->getLocale() ? '#' : route('locale.set', $key) }}" class="dropdown-item {{ $key === app()->getLocale() ? 'active' : '' }} waves-effect">
            <span class="flag-icon flag-icon-big {{ $key }}" data-toggle="tooltip" data-placement="bottom" data-title="{{ $lang['name'] }}"></span>
{{--            @if(isset($lang['new']))--}}
{{--                <span class="badge flag-icon-badge danger-color">NEW!</span>--}}
{{--            @endif--}}
{{--            @if(isset($lang['updated']))--}}
{{--                <span class="badge flag-icon-badge warning-color-dark">UPD!</span>--}}
{{--            @endif--}}
        </a>
    </div>
@endif
