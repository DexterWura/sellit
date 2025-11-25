@extends($activeTemplate.'layouts.frontend')

@section('content')

<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card custom--card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-10 d-flex flex-wrap align-items-center">
                                @if($my_ticket->status == 0)
                                <span class="badge badge--success">@lang('Open')</span>
                                @elseif($my_ticket->status == 1)
                                <span class="badge badge--primary">@lang('Answered')</span>
                                @elseif($my_ticket->status == 2)
                                <span class="badge badge--warning">@lang('Replied')</span>
                                @elseif($my_ticket->status == 3)
                                <span class="badge badge--dark">@lang('Closed')</span>
                                @endif
                                <h6 class="ms-2">[@lang('Ticket')#{{ __($my_ticket->ticket) }}] {{
                                    __($my_ticket->subject)
                                    }}
                                </h6>
                            </div>
                            <div class="col-sm-2 text-end">
                                <button class="btn btn--danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#DelModal"><i class="las la-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($my_ticket->status != 4)
                        <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}"
                            enctype="multipart/form-data" class="mb-5">
                            @csrf
                            <input type="hidden" name="replayTicket" value="1">
                            <div class="form-group">
                                <textarea name="message" placeholder="@lang('Your reply...')"
                                    class="form--control"></textarea>
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
                                <button type="submit" class="btn btn--base w-100">@lang('Reply')</button>
                            </div>
                        </form>
                        @endif
                        @foreach($messages as $message)
                        @if($message->admin_id == 0)
                        <div class="single-reply">
                            <div class="left">
                                <h6>{{ $message->ticket->name }}</h6>
                            </div>
                            <div class="right">
                                <span class="fst-italic fs--14px text--base mb-2">@lang('Posted on') {{
                                    $message->created_at->format('l, dS F Y @ H:i') }}
                                </span>
                                <p>{{$message->message}}</p>
                                @if($message->attachments()->count() > 0)
                                <div class="mt-2">
                                    @foreach($message->attachments as $k=> $image)
                                    <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i
                                            class="fa fa-file"></i> @lang('Attachment') {{++$k}}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="single-reply">
                            <div class="left">
                                <h6>{{ $message->admin->name }}</h6>
                            </div>
                            <div class="right">
                                <span class="fst-italic fs--14px text--base mb-2"> @lang('Posted on') {{
                                    $message->created_at->format('l, dS F Y @ H:i') }}
                                </span>
                                <p>{{$message->message}}</p>
                                @if($message->attachments()->count() > 0)
                                <div class="mt-2">
                                    @foreach($message->attachments as $k=> $image)
                                    <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i
                                            class="fa fa-file"></i> @lang('Attachment') {{++$k}}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}">
                @csrf
                <input type="hidden" name="replayTicket" value="2">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Confirmation Alert')</h5>
                    <button type="button" class="close btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <strong class="text-dark">@lang('Are you sure to close this ticket?')</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn-sm" data-bs-dismiss="modal">
                        @lang('No')
                    </button>
                    <button type="submit" class="btn btn--base btn-sm">@lang("Yes")
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function ($) {
            "use strict";
            $('.delete-message').on('click', function (e) {
                $('.message_id').val($(this).data('id'));
            });
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