@extends($activeTemplate.'layouts.frontend')
@push('style-lib')
<link rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
@endpush
@section('content')
<div class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-10">
                <form class="create-list-form" action="{{ route('user.auction.store') }}" method="POST" id="auctionForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>@lang('What are you selling?')<span class="text--danger">*</span></label>
                        <select class="select form--control" name="listing_type" id="listing_type" required>
                            <option value="" selected disabled>@lang('Select Listing Type')</option>
                            @foreach($listingTypes as $key => $value)
                                <option value="{{ $key }}" @if(old('listing_type') == $key) selected @endif>{{ __($value) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Domain Fields -->
                    <div class="form-group" id="domain_fields" style="display: none;">
                        <label>@lang('Domain name')<span class="text--danger">*</span></label>
                        <input type="text" name="name" autocomplete="off" class="form--control"
                            placeholder="@lang('e.g. dummydomain.com')" value="{{ old('name') }}">
                    </div>

                    <!-- Website Fields -->
                    <div class="form-group" id="website_fields" style="display: none;">
                        <label>@lang('Website URL')<span class="text--danger">*</span></label>
                        <input type="text" name="name" autocomplete="off" class="form--control"
                            placeholder="@lang('e.g. example.com')" value="{{ old('name') }}">
                        <input type="hidden" name="website_url" id="website_url">
                    </div>

                    <!-- Social Media Fields -->
                    <div id="social_media_fields" style="display: none;">
                        <div class="form-group">
                            <label>@lang('Platform')<span class="text--danger">*</span></label>
                            <select class="select form--control" name="social_platform" id="social_platform">
                                <option value="" selected disabled>@lang('Select Platform')</option>
                                <option value="instagram">Instagram</option>
                                <option value="facebook">Facebook</option>
                                <option value="twitter">Twitter</option>
                                <option value="youtube">YouTube</option>
                                <option value="tiktok">TikTok</option>
                                <option value="linkedin">LinkedIn</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Username/Handle')<span class="text--danger">*</span></label>
                            <input type="text" name="social_username" autocomplete="off" class="form--control"
                                placeholder="@lang('e.g. @username')" value="{{ old('social_username') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row gy-3">
                            <div class="col-sm-6">
                                <label>@lang('Domain Category')<span class="text--danger">*</span></label>
                                <select class="select" name="category_id">
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    @foreach ($categories as $item)
                                    <option value="{{ __($item->id) }}" @if (old('category_id')==$item->id)
                                        selected="selected" @endif>{{ __($item->name) }}</option>
                                    @endforeach
                                    <option value="0">@lang('Other')</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>@lang('Domain Price')<span class="text--danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="any" name="price" autocomplete="off"
                                        class="form--control" value="{{ old('price') }}" required>
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
                                    placeholder="@lang('yyyy-mm-dd')">
                            </div>
                            <div class="col-sm-6">
                                <label>@lang('Auction End Time')<span class="text--danger">*</span></label>
                                <input type="text" name="end_time" id='dateAndTimePicker'
                                    class="form-control form--control" data-language='en' data-date-format="yyyy-mm-dd"
                                    data-position='top left' placeholder="@lang('yyyy-mm-dd h:i:s a')">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row gy-3">
                            <div class="col-sm-6">
                                <label>@lang('Your Country')<span class="text--danger">*</span></label>
                                <select class="select" name="location">
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    @foreach ($countries as $item)
                                    <option value="{{ __($item->country) }}">{{ __($item->country) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>@lang('Domain Traffic')<span class="text--danger">*</span></label>
                                <input type="number" name="traffic" autocomplete="off" class="form--control"
                                    value="{{ old('traffic') }}" required>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label id="description_label">@lang('Description')<span class="text--danger">*</span></label>
                        <textarea rows="5" class="form-control nicEdit" name="description" required autocomplete="off">
                            {{ old('description') }}
                        </textarea>
                        <small class="d-inline-block mt-1"><i class="fas fa-info-circle"></i>
                            <span id="description_hint">@lang('Add a summary to briefly introduce your listing.')</span>
                        </small>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group">
                        <label>@lang('Thumbnail Image') <small>@lang('(Recommended: 400x300px)')</small></label>
                        <div class="image-upload-wrapper">
                            <div class="image-preview mb-2" id="thumbnailPreview" style="display: none;">
                                <img id="thumbnailPreviewImg" src="" alt="Thumbnail Preview" style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                            </div>
                            <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*" onchange="previewThumbnail(this)">
                            <small class="text-muted">@lang('This image will be displayed on listing cards')</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>@lang('Additional Images') <small>@lang('(Optional - Multiple images allowed)')</small></label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">@lang('Upload multiple images to showcase your listing')</small>
                    </div>

                    <!-- Additional Fields for Website/Social Media -->
                    <div class="form-group" id="analytics_fields" style="display: none;">
                        <label>@lang('Analytics Data') <small>@lang('(Optional - JSON format)')</small></label>
                        <textarea rows="3" class="form-control" name="analytics_data" placeholder='{"monthly_visitors": 10000, "revenue": 5000}'></textarea>
                    </div>

                    <div class="form-group" id="links_fields" style="display: none;">
                        <label>@lang('Additional Links') <small>@lang('(Optional - JSON format)')</small></label>
                        <textarea rows="3" class="form-control" name="additional_links" placeholder='["https://example.com", "https://another-link.com"]'></textarea>
                    </div>
                    <div class="form-group">
                        <label>@lang('Seller Note')&nbsp;<span>@lang('[optional]')</span></label>
                        <textarea name="note" class="form-control" placeholder="@lang('Write your note....')">{{ old('note') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
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
        $('.datepicker-here').datepicker();

         // Create start date
   var start = new Date(),
        prevDay,
        startHours = 1;

    // 09:00 AM
    start.setHours(1);
    start.setMinutes(0);

    // If today is Saturday or Sunday set 10:00 AM
    if ([6, 0].indexOf(start.getDay()) != -1) {
        start.setHours(1);
        startHours = 1
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
                minHours: 1,
                maxHours: 24
            })
        } else {
            picker.update({
                minHours: 1,
                maxHours: 24
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

            // Handle listing type change
            $('#listing_type').on('change', function() {
                var listingType = $(this).val();
                
                // Hide all fields
                $('#domain_fields, #website_fields, #social_media_fields, #analytics_fields, #links_fields').hide();
                $('#domain_fields input, #website_fields input, #social_media_fields input').removeAttr('required');
                
                if (listingType === 'domain') {
                    $('#domain_fields').show();
                    $('#domain_fields input[name="name"]').attr('required', 'required');
                    $('#description_label').text('@lang("About the domain") *');
                    $('#description_hint').text('@lang("Add a summary to briefly introduce your domain detail.")');
                } else if (listingType === 'website') {
                    $('#website_fields').show();
                    $('#website_fields input[name="name"]').attr('required', 'required');
                    $('#analytics_fields, #links_fields').show();
                    $('#description_label').text('@lang("About the website") *');
                    $('#description_hint').text('@lang("Add a summary to briefly introduce your website.")');
                } else if (listingType === 'social_media') {
                    $('#social_media_fields').show();
                    $('#social_media_fields select[name="social_platform"], #social_media_fields input[name="social_username"]').attr('required', 'required');
                    $('#analytics_fields, #links_fields').show();
                    $('#description_label').text('@lang("About the account") *');
                    $('#description_hint').text('@lang("Add a summary to briefly introduce your social media account.")');
                }
            });

            // Set website_url when website name changes
            $('#website_fields input[name="name"]').on('blur', function() {
                $('#website_url').val($(this).val());
            });

            // Trigger change on page load if old value exists
            @if(old('listing_type'))
                $('#listing_type').trigger('change');
            @endif

            // Thumbnail preview function
            window.previewThumbnail = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#thumbnailPreviewImg').attr('src', e.target.result);
                        $('#thumbnailPreview').show();
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            };
        })(jQuery);
</script>
@endpush
