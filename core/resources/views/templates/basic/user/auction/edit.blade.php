@extends($activeTemplate.'layouts.frontend')
@push('style-lib')
<link rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
@endpush
@section('content')
<div class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-10">
                <form class="create-list-form" action="{{ route('user.auction.update',$domain->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>@lang('Domain name')<span class="text--danger">*</span></label>
                        <input type="text" name="name" autocomplete="off" class="form--control"
                            placeholder="@lang('e.g. dummydomain.com')" value="{{ $domain->name }}">
                    </div>
                    <div class="form-group">
                        <div class="row gy-3">
                            <div class="col-sm-6">
                                <label>@lang('Domain Category')<span class="text--danger">*</span></label>
                                <select class="select" name="category_id">
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    @foreach ($categories as $item)
                                    <option value="{{ __($item->id) }}" {{ $domain->category_id == $item->id ?
                                        'selected':'' }}>{{ __($item->name) }}</option>
                                    @endforeach
                                    <option value="0" {{ $domain->category_id == 0 ? 'selected':'' }}>@lang('Other')
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>@lang('Domain Price')<span class="text--danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="any" name="price" autocomplete="off"
                                        class="form--control" value="{{ $domain->price }}">
                                    <span class="input-group-text text-white border-0">{{__($general->cur_text)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row gy-3">
                            <div class="col-sm-6">
                                <label>@lang('When did you register the domain?')<span
                                        class="text--danger">*</span></label>
                                <input type="text" name="register_date"
                                    class="datepicker-here form-control form--control" data-language='en'
                                    data-date-format="yyyy-mm-dd" data-position='top left'
                                    placeholder="@lang('Select date')" value="{{ $domain->register_date }}">
                            </div>
                            <div class="col-sm-6">
                                <label>@lang('Auction End Time')<span class="text--danger">*</span></label>
                                <input type="text" name="end_time" id='dateAndTimePicker'
                                    class="form-control form--control" data-language='en' data-date-format="yyyy-mm-dd"
                                    data-position='top left' placeholder="@lang('Select date')"
                                    value="{{ $domain->end_time }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row gy-3">
                            <div class="col-sm-6">
                                <label>@lang('Your location')<span class="text--danger">*</span></label>
                                <select class="select" name="location">
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    @foreach ($countries as $item)
                                    <option value="{{ __($item->country) }}" {{ $item->country == $domain->location ?
                                        'selected':'' }}>{{ __($item->country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>@lang('Domain Traffic')<span class="text--danger">*</span></label>
                                <input type="number" name="traffic" autocomplete="off" class="form--control"
                                    value="{{ $domain->traffic }}">
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label>@lang('About the domain')<span class="text--danger">*</span></label>
                        <textarea rows="5" class="form-control nicEdit"
                            name="description">{{ $domain->description }}</textarea>
                        <small class="d-inline-block mt-1"><i class="fas fa-info-circle"></i>
                            @lang('Add a summary to briefly introduce your business. Be impactful. Use keywords that
                            help a buyer’s search.')
                        </small>
                    </div>
                    <div class="form-group">
                        <label>@lang('Seller Note')&nbsp;<span>@lang('[optional]')</span></label>
                        <textarea name="note" class="form-control"
                            placeholder="@lang('write your note....')">{{ $domain->note }}</textarea>
                    </div>
                    <button class="btn btn--base w-100">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-lib')
<script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
<script src="{{ asset('assets/admin/js/nicEdit.js') }}"></script>
@endpush
@push('script')
<script>
    "use strict";
     // Create start date
   var start = new Date(),
        prevDay,
        startHours = 9;

    // 09:00 AM
    start.setHours(9);
    start.setMinutes(0);

    // If today is Saturday or Sunday set 10:00 AM
    if ([6, 0].indexOf(start.getDay()) != -1) {
        start.setHours(10);
        startHours = 10
    }
  // date and time picker
  $('#dateAndTimePicker').datepicker({
    timepicker: true,
    language: 'en',
    dateFormat: 'dd/mm/yyyy',
    startDate: start,
    minHours: startHours,
    maxHours: 18,
    onSelect: function (fd, d, picker) {
        // Do nothing if selection was cleared
        if (!d) return;

        var day = d.getDay();

        // Trigger only if date is changed
        if (prevDay != undefined && prevDay == day) return;
        prevDay = day;

        // If chosen day is Saturday or Sunday when set
        // hour value for weekends, else restore defaults
        if (day == 6 || day == 0) {
            picker.update({
                minHours: 10,
                maxHours: 16
            })
        } else {
            picker.update({
                minHours: 9,
                maxHours: 18
            })
        }
    }
})
        bkLib.onDomLoaded(function() {
            $( ".nicEdit" ).each(function( index ) {
                $(this).attr("id","nicEditor"+index);
                new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
            });
        });
        (function($){
            $( document ).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain',function(){
                $('.nicEdit-main').focus();
            });
        })(jQuery);
</script>
@endpush
