@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-50 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 pe-xl-4">
                <div class="action-sidebar">
                    <button type="button" class="action-sidebar-close"><i class="las la-times"></i></button>
                    <div class="action-widget pt-0 pb-2">
                        <div class="action-widget__body">
                            <h4>@lang('Filter')</h4>
                        </div>
                    </div>
                    <div class="action-widget">
                        <h4 class="action-widget__title">@lang('Keyword')</h4>
                        <div class="action-widget__body">
                            <div class="search-form-inline">
                                <input type="text" name="search" value="{{ request()->search }}" class="form--control form-control-sm mySearch" placeholder="@lang('Search here')">
                                <button class="search-form-inline__btn searchBtn">
                                    <i class="las la-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="action-widget">
                        <h4 class="action-widget__title">@lang('Domain Extensions')</h4>
                        <div class="action-widget__body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-check custom--checkbox">
                                        <input class="form-check-input myExtention" type="checkbox" name="extension" id="all-checkbox" value="" checked>
                                        <label class="form-check-label" for="all-checkbox">
                                            @lang('All')
                                        </label>
                                    </div>
                                </div>
                                @foreach ($extensions as $item)
                                <div class="col-lg-4">
                                    <div class="form-check custom--checkbox">
                                        <input class="form-check-input myExtention" type="checkbox" name="extension"
                                            id="chekbox-{{ $item->id }}" value="{{ __($item->name) }}">
                                        <label class="form-check-label" for="chekbox-{{ $item->id }}">
                                            {{ __($item->name) }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="action-widget">
                        <h4 class="action-widget__title">@lang('Listing Sort')</h4>
                        <div class="action-widget__body">

                            <div class="form-check custom--radio mb-2">
                                <input class="list_short form-check-input" type="radio" name="list_sort" id="all"
                                    value="" checked>
                                <label class="form-check-label" for="all">@lang('All')</label>
                            </div>

                            <div class="form-check custom--radio mb-2">
                                <input class="list_short form-check-input" type="radio" name="list_sort" id="sort2" value="id_desc">
                                <label class="form-check-label" for="sort2">@lang('Newly Listed')</label>
                            </div>

                            <div class="form-check custom--radio mb-2">
                                <input class="list_short form-check-input" type="radio" name="list_sort" id="sort3" value="id_asc">
                                <label class="form-check-label" for="sort3">@lang('Ending Soon')</label>
                            </div>
                            <div class="form-check custom--radio mb-2">
                                <input class="list_short form-check-input" type="radio" name="list_sort" id="sort4"
                                    value="price_asc">
                                <label class="form-check-label" for="sort4">@lang('Low to High')</label>
                            </div>
                            <div class="form-check custom--radio mb-2">
                                <input class="list_short form-check-input" type="radio" name="list_sort" id="sort5"
                                    value="price_desc">
                                <label class="form-check-label" for="sort5">@lang('High to Low')</label>
                            </div>
                        </div>
                    </div>
                    <div class="action-widget">
                        <div class="price-slider">
                            <div class="slider-range" data-max="{{ getAmount($maxPrice) }}"></div>
                            <div class="amount__field">
                                <label>@lang('Price')</label>
                                <input type="text" class="range-amount" readonly>
                                <input type="hidden" name="min_price" value="{{ getAmount($minPrice) }}">
                                <input type="hidden" name="max_price" value="{{ getAmount($maxPrice) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-9">
                <div class="row gy-2 align-items-center mb-3">
                    <div class="col-sm-3 d-lg-none">
                        <button type="button" class="action-sidebar-open"><i
                                class="las la-sliders-h"></i>@lang('Filter')</button>
                    </div>
                    <div class="col-lg-9 col-sm-6 col-7 text-lg-start text-sm-center">
                    </div>
                    <div class="col-lg-3 col-sm-3 col-5">
                        <div class="d-flex align-items-center">
                            <span class="mx-1">
                                @lang('Showing')
                            </span>
                            <select class="select select-sm DomainPaginate">
                                <option value="" selected disabled>@lang('Select One')</option>
                                <option value="5">@lang('5') @lang('items per page')</option>
                                <option value="10">@lang('10') @lang('items per page')</option>
                                <option value="20">@lang('20') @lang('items per page')</option>
                                <option value="40">@lang('40') @lang('items per page')</option>
                                <option value="60">@lang('60') @lang('items per page')</option>
                                <option value="80">@lang('80') @lang('items per page')</option>
                                <option value="100">@lang('100') @lang('items per page')</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="domain-list-table-wrapper">
                    <h6 class="search-value-show mb-2">
                        @if(request()->search)
                            @lang('Search Result for : ') {{ request()->search }}
                        @endif
                    </h6>
                    <div class="domain-list-table-header">
                        <div class="left">
                            <div class="domain">@lang('Domain')</div>
                            <div class="traffic">@lang('Traffic')</div>
                            <div class="bid">@lang('Bid')</div>
                        </div>
                        <div class="right">
                            <div class="price">@lang('Price')</div>
                            <div class="action">@lang('Action')</div>
                        </div>
                    </div>
                    <div class="domain-list-table-body" id="showDomain">
                        @include($activeTemplate.'domain.partials.domain_list')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>

    (function($){
        'use strict';

        let page = null;

        $('.DomainPaginate').val({{ getPaginate() }});

        $('.action-sidebar-open').on('click', function(){
          $('.action-sidebar').addClass('active');
        });

        $('.action-sidebar-close').on('click', function(){
          $('.action-sidebar').removeClass('active');
        });

        $('.action-widget__title').each(function(){
          let ele = $(this).siblings('.action-widget__body');
          $(this).on('click', function(){
            ele.slideToggle();
          });
        });

        $('.slider-range').each(function(){
          var rangeSlider = $(this);
          var maxValue = rangeSlider.attr('data-max');

          var slideAmount = $(this).siblings('.amount__field').children('.range-amount');

          $(rangeSlider).slider({
            range: true,
            min: {{ $minPrice }},
            max: {{ $maxPrice }},
            values: [{{ $minPrice }}, {{ $maxPrice }}],
            slide: function( event, ui ) {
              $(slideAmount).val( "{{$general->cur_sym}}" + ui.values[ 0 ] + " to {{$general->cur_sym}}" + ui.values[ 1 ] );
              $('input[name=min_price]').val(ui.values[ 0 ]);
              $('input[name=max_price]').val(ui.values[ 1 ]);
            },
            change: function(){
                fetchDomain();
            }
          });

          $(slideAmount).val( "{{$general->cur_sym}}" + $(rangeSlider).slider( "values", 0 ) +
            " to {{$general->cur_sym}}" + $(rangeSlider).slider( "values", 1 ) );
        });

        $('.myExtention, .list_short, .searchBtn').on('click', function () {
            if($('#all-checkbox').is(':checked')){
                $("input[type='checkbox'][name='extension']").not(this).prop('checked', false);
            }
            fetchDomain();
        });

        $('.DomainPaginate').on('change',function(e){
            fetchDomain();
        });

        function fetchDomain(){
            let data = {};
            data.sort = $("input[type='radio'][name='list_sort']:checked").val();
            data.min = $('input[name="min_price"]').val();
            data.max = $('input[name="max_price"]').val();
            data.search = $('.mySearch').val();
            data.paginateValue = $('.DomainPaginate').find(":selected").val();
            data.extensions = [];

            $.each($("input[name=extension]:checked"), function() {
                if($(this).val()){
                    data.extensions.push($(this).val());
                }
            });
            let url =  `{{ route('domain.filter') }}`;

            if(page){
                url = `{{ route('domain.filter') }}?page=${page}`;
            }
            $.ajax({
                method: "get",
                url:url,
                data: data,
                success: function(response){
                    $('#showDomain').html(response);
                }
            });
        }

        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            page = $(this).attr('href').split('page=')[1];
            fetchDomain();
        });

    })(jQuery);

</script>
@endpush
