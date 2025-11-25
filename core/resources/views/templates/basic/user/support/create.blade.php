@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card custom--card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-xl-10 col-md-9 col-sm-8 text-sm-start text-center">
                                <h6>@lang('Create new ticket')</h6>
                            </div>
                            <div class="col-xl-2 col-md-3 col-sm-4 mt-sm-0 mt-1 text-sm-end text-center">
                                <a href="{{ route('ticket') }}" class="btn btn-sm btn--base"><i
                                        class="fas fa-ticket-alt"></i> @lang('My Tickets')</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('ticket.store')}}" method="post" enctype="multipart/form-data"
                            onsubmit="return submitUserForm();">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" name="name" class="form--control"
                                        value="{{@$user->firstname . ' '.@$user->lastname}}"
                                        placeholder="@lang('Enter your name')" readonly>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Email address')</label>
                                    <input type="email" name="email" class="form--control" value="{{@$user->email}}"
                                        placeholder="@lang('Enter email address')" readonly>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Subject')</label>
                                    <input type="text" name="subject" class="form--control"
                                        placeholder="@lang('Enter subject')" value="{{old('subject')}}">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Priority')</label>
                                    <select name="priority" class="select">
                                        <option value="3">@lang('High')</option>
                                        <option value="2">@lang('Medium')</option>
                                        <option value="1">@lang('Low')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('Message')</label>
                                <textarea name="message" placeholder="@lang('Your reply...')"
                                    class="form--control">{{old('message')}}</textarea>
                            </div>
                            <div class="form-group">
                                <div class="support-upload-field">
                                    <div class="support-upload-field__left">
                                        <label class="form-label">@lang('Attachments')</label>
                                        <input class="form-control custom--file-upload" type="file"
                                            name="attachments[]">
                                        <div class="form-text text--muted">
                                            @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'),
                                            .@lang('png'),
                                            .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                        </div>
                                    </div>
                                    <div class="support-upload-field__right">
                                        <button type="button" class="btn btn--base addFile"><i
                                                class="las la-plus"></i></button>
                                    </div>
                                </div>
                                <div id="file-upload-list"></div>
                            </div>
                            <div class="form-group text-end">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('script')
<script>
    (function ($) {
            "use strict";
            $('.addFile').on('click',function(){
                $("#file-upload-list").append(`
                <div class="support-upload-field removeField">
                    <div class="support-upload-field__left">
                        <label class="form-label">@lang('Attachments')</label>
                        <input class="form-control custom--file-upload" type="file"
                            name="attachments[]">
                        <div class="form-text text--muted">
                            @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'),
                            .@lang('png'),
                            .@lang('pdf'), .@lang('doc'), .@lang('docx')
                        </div>
                    </div>
                    <div class="support-upload-field__right">
                        <button type="button" class="btn btn--danger remove-btn">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                </div>
                `)
            });
            $(document).on('click','.remove-btn',function(){
                $(this).closest('.removeField').remove();
            });
        })(jQuery);
</script>
@endpush