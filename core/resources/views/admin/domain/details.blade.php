@extends('admin.layouts.app')

@section('panel')
<div class="row mb-none-30">
    <div class="col-lg-4 col-md-4 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Auction Created By') {{__(@$domain->user->fullname)}}</h5>

                <div class="p-3 bg--white">
                    <div class="">
                        <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. @$domain->user->image) }}"
                            alt="@lang('Image')" class="b-radius--10 withdraw-detailImage">
                    </div>
                </div>

                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Date')
                        <span class="font-weight-bold">{{ showDateTime($domain->created_at) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Username')
                        <span class="font-weight-bold">
                            <a href="{{ route('admin.users.detail', $domain->user_id) }}">{{
                                @$domain->user->username }}</a>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Email')
                        <span class="font-weight-bold">{{@$domain->user->email }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('User Balance')
                        <span class="font-weight-bold">{{ showAmount(@$domain->user->balance)
                            }}&nbsp;{{__($general->cur_text)}}</span>
                    </li>


                </ul>
            </div>
        </div>

        @if($domain->status == 0)

        <div class="card b-radius--10 overflow-hidden box--shadow1 mt-3">
            <div class="card-body">

                <ul class="list-group">

                    <li class="list-group-item d-flex justify-content-center align-items-center">
                        <span class="font-weight-bold">
                            @lang('For domain verification send file and details to user')
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Send File')
                        <span class="font-weight-bold">
                            <button class="btn btn--primary ml-1 sendBtn" data-toggle="tooltip" data-original-title="@lang('Send File')" data-id="{{ $domain->id }}">@lang('Click Here')
                            </button>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        @endif

    </div>
    <div class="col-lg-8 col-md-8 mb-30">

        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2">@lang('Domain Information')</h5>

                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Domain Name')
                        <span class="font-weight-bold">{{ __($domain->name) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Domain Category')
                        <span class="font-weight-bold">
                            {{ __(@$domain->category->name??'Other') }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Domain Price')
                        <span class="font-weight-bold">
                            {{ showAmount($domain->price) }}&nbsp;{{ $general->cur_text }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Domain Register Date')
                        <span class="font-weight-bold">
                            {{ showDateTime($domain->register_date) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Auction End Date')
                        <span class="font-weight-bold">
                            {{ showDateTime($domain->end_time) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Traffic')
                        <span class="font-weight-bold">
                            {{ $domain->traffic }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex  justify-content-between align-items-center">
                        @lang('Domain Verification Code')
                        <span class="font-weight-bold">
                            {{ __($domain->verify_code) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang(' Description')
                        <span class="font-weight-bold">
                            @php echo $domain->description; @endphp
                        </span>

                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Status')
                        @php
                            echo $domain->statusText
                        @endphp
                    </li>
                </ul>
                @if($domain->status == 0)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <button class="btn btn--success ml-1 approveBtn" data-toggle="tooltip"
                            data-original-title="@lang('Approve')" data-id="{{ $domain->id }}">
                            <i class="fas la-check"></i> @lang('Approve')
                        </button>

                        <button class="btn btn--danger ml-1 rejectBtn" data-toggle="tooltip"
                            data-original-title="@lang('Reject')" data-id="{{ $domain->id }}">
                            <i class="fas fa-ban"></i> @lang('Reject')
                        </button>
                        <button class="btn btn--info ml-1 viewFile" data-toggle="tooltip"
                            data-original-title="@lang('View Uploaded File')" data-id="{{ $domain->id }}">
                            <i class="fas fa-eye"></i> @lang('View Uploaded File')
                        </button>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<div id="approveModal" class="modal fade" id="modal-8" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.domain.approve') }}" method="POST">
            <div class="modal-body">
                <h6 class="mb-15">@lang('Are you sure to approve this domain?')</h6>
                    @csrf
                    <input type="hidden" name="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal"> @lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Domain Reject Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.domain.reject')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <label class="form-control-label font-weight-bold">@lang('Reason of Rejection')</label>
                    <textarea name="admin_feedback" class="form-control pt-3" rows="3"
                        placeholder="@lang('Provide the Details')" required=""></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="sendModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Send Verfication File')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.domain.send')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label font-weight-bold">@lang('Verification Details')</label>
                        <textarea name="verify_detail" class="form-control pt-3" rows="3" required=""></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="viewFileModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Domain Verification')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-md-6 domainCode"></div>
                    <div class="col-md-6 fileCode"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function ($) {
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.rejectBtn').on('click', function() {
                var modal = $('#rejectModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.sendBtn').on('click', function() {
                var modal = $('#sendModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.viewFile').on('click', function() {
                var modal = $('#viewFileModal');
                var verifyCode = "{{ $domain->verify_code }}";
                var fileCode = "{{ $fileVal }}";
                modal.find('.domainCode').html(`<p>@lang('Verify Code')</p><strong>${verifyCode}</strong>`);
                modal.find('.fileCode').html(`<p>@lang('File Code')</p><strong>${fileCode}</strong>`);
                modal.modal('show');
            });

        })(jQuery);

</script>
@endpush
