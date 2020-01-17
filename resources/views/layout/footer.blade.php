<footer class="page-footer text-center jarallax pt-4 mt-4">
    <img src="{{ $wot['storage_blurred'] }}" alt="" class="jarallax-img">

    <div class="container">
        <div class="row">
            <div class="col-sm" style="margin: auto;">
                WoTr Image Courtesy of <a href="{{ $wot['page'] }}" target="_blank" rel="noreferrer nofollow noopener" data-toggle="tooltip" title="worldoftrucks.com"><u>{{ $wot['nickname'] }}</u></a>&nbsp;<span class="iconify icon-shadow" data-toggle="modal" data-target="#wot_check" data-icon="mdi:eye"></span>
            </div>

            <div class="col-sm" style="margin: auto;">
                @if (__('translator_nicknames') !== 'translator_nicknames')
                    Translation: <strong>@lang('translator_nicknames')</strong><br>
                @endif

                <div class="buttons">
                    <a href="https://cjm.page.link/kN5G" target="_blank" rel="noreferrer nofollow noopener" class="btn btn-danger left-gradient" data-toggle="tooltip" title="forum.truckersmp.com">
                        @lang('Forum topic')
                    </a>
                </div>
            </div>

            <div class="col-sm" style="margin: auto;">
                Map-related Things Courtesy of <a href="https://krashnz.com" target="_blank" rel="noreferrer nofollow noopener" data-toggle="tooltip" title="krashnz.com"><u>Krashnz</u></a>
            </div>
        </div>
    </div>

    <div class="footer-copyright py-3 text-center">
        <div class="container">
            &copy;&nbsp;2014 – 2019 <a href="https://cjmaxik.ru" target="_blank" rel="noreferrer nofollow noopener" class="font-weight-bold">
                <img src="{{ asset('images/cjmaxik.png') }}" class="d-inline-block align-text-top" alt="CJMAXiK Logo"> CJMAXiK
            </a>

            <a href="https://cjm.page.link/Q4s9" target="_blank" rel="noreferrer nofollow noopener" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<strong>TruckersMP Helper</strong> - {{ __('2nd Best Unofficial Tool of TruckersMP Awards 2016') }}">
                <span class="iconify icon-shadow" data-icon="mdi:trophy-variant"></span>
            </a>

            <br>

            <i>TruckersMP Helper is not in any shape or form affiliated with TruckersMP Team and/or SCS Software</i>
        </div>
    </div>
</footer>

<div class="modal fade" id="wot_check" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bg-transparent modal-no-shadow">
            <div class="modal-body text-center">
                <div class="view z-depth-5">
                    <img class="img-fluid" src="{{ $wot['storage'] }}" alt="{{ $wot['nickname'] }} Picture from World of Trucks">
                    <div class="mask flex-bottom waves-effects waves-light">
                        <h4 class="card-title text-white font-weight-bold text-shadow search__content mb-0">
                            <a href="{{ $wot['page'] }}" target="_blank" rel="noreferrer nofollow noopener" data-toggle="tooltip" title="worldoftrucks.com" class="text-white"><u>{{ $wot['nickname'] }}</u></a> on World of Trucks
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
