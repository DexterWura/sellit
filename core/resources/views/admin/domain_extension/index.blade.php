@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($extensions as $item)
                            <tr>
                                <td data-label="@lang('S.N.')">{{ $loop->index + $extensions->firstItem() }}</td>
                                <td data-label="@lang('Name')">
                                    <div class="user">
                                        <span class="name">{{ __($item->name) }}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Status')">
                                    @if ($item->status == 1)
                                    <span class="text--small badge font-weight-normal badge--success">
                                        @lang('Enable')
                                    </span>
                                    @else
                                    <span class="text--small badge font-weight-normal badge--danger">
                                        @lang('Disabled')
                                    </span>
                                    @endif

                                </td>
                                <td data-label="@lang('Action')">
                                    <button data-toggle="modal" data-target="#editExtension"
                                        class="icon-btn bg--primary ml-1 editButton" data-id="{{ $item->id }}"
                                        data-name="{{ __($item->name) }}" data-status="{{ __($item->status) }}"
                                        data-original-title="@lang('Edit')">
                                        <i class="las la-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
            @if($extensions->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($extensions) }}
            </div>
            @endif
        </div>
    </div>
</div>
<div id="createExtension" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @lang('Add New Extension')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.extension.domain.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label font-weight-bold">
                            @lang('Name') <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                placeholder="@lang('e.g .com')" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editExtension" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @lang('Update Extension')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-label font-weight-bold">
                            @lang('Name') <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="name" class="form-control border-radius-5" value="" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label font-weight-bold">
                            @lang('Status')</label>
                        <input type="checkbox" id="status" data-width="100%" data-onstyle="-success"
                            data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')"
                            data-off="@lang('Disabled')" name="status">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
<a data-toggle="modal" href="#createExtension" class="btn btn-sm btn--primary mr-2"><i class="las la-plus"></i>
    @lang('Add new')
</a>

<form method="GET" class="form-inline float-sm-right bg-white search-form w-sm-auto w-unset">
    <div class="input-group has_append">
        <input type="text" name="search" id="mySearch" class="form-control" placeholder="@lang('Extention name')"
            value="{{ request()->search }}">
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>

@endpush
@push('script')
<script>
    (function($) {
            "use strict"
            $('.editButton').on('click', function() {
                var modal = $('#editExtension');
                var status = $(this).data('status');
                modal.find('form').attr('action', `{{ route('admin.extension.domain.store','') }}/${$(this).data('id')}`);
                modal.find('input[name=name]').val($(this).data('name'));

                if ($(this).data('status') == 1) {
                    modal.find('input[name=status]').bootstrapToggle('on');
                } else {
                    modal.find('input[name=status]').bootstrapToggle('off');
                }
            });
        })(jQuery);
</script>
@endpush
