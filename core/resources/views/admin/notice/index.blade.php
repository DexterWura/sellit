@extends('admin.layouts.app')
@section('panel')
@php
$notice = $general->notice_for_buyer;
@endphp

<div class="row mb-none-30">
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.notice.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label  font-weight-bold">@lang('Heading')</label>
                                <input type="text" class="form-control" placeholder="@lang('Heading')" name="notice[heading]"
                                    value="{{ @$notice->heading }}" required />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Notice')</label>
                                <textarea name="notice[notice]" class="form-control" required>{{ @$notice->notice }}
                                </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
