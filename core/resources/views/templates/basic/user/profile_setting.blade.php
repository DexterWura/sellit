@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="card custom--card section--bg">
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-center gy-4">
                        <div class="col-xl-4 col-lg-5">
                            <div class="user-profile">
                                <div class="profile-thumb-wrapper text-center">
                                    <div class="profile-thumb">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview"
                                                style="background-image: url('{{ getImage(imagePath()['profile']['user']['path'].'/'. $user->image,null,true) }}')">
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type='file' class="profilePicUpload" name="image"
                                                id="profilePicUpload1" accept=".jpg, .jpeg, .png" />
                                            <label for="profilePicUpload1">@lang('Browse')</label>
                                        </div>
                                    </div>
                                </div>
                                <ul class="profile-info-list">
                                    <li>
                                        <span class="caption"><i class="lar la-user"></i> @lang('Username')</span>
                                        <span class="details">{{ __($user->username) }}</span>
                                    </li>
                                    <li>
                                        <span class="caption"><i class="las la-envelope"></i> @lang('E-mail')</span>
                                        <span class="details">{{ __($user->email) }}</span>
                                    </li>
                                    <li>
                                        <span class="caption"><i class="las la-phone"></i> @lang('Phone')</span>
                                        <span class="details">@lang('+'){{ __($user->mobile) }}</span>
                                    </li>
                                    <li>
                                        <span class="caption"><i class="las la-flag"></i> @lang('Country')</span>
                                        <span class="details">{{ __(@$user->address->country) }}</span>
                                    </li>
                                </ul>
                            </div><!-- user-profile end -->
                        </div>
                        <div class="col-xl-8 col-lg-7 ps-xl-4">
                            <h5 class="mb-3">@lang('Profile Settings')</h5>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>@lang('First Name')</label>
                                    <input type="text" name="firstname" autocomplete="off" class="form--control"
                                        placeholder="@lang('Enter firstname')" value="{{ __($user->firstname) }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>@lang('Last Name')</label>
                                    <input type="text" name="lastname" autocomplete="off" class="form--control"
                                        placeholder="@lang('Enter lastname')" value="{{ __($user->lastname) }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>@lang('Address')</label>
                                    <input type="text" name="address" autocomplete="off" class="form--control"
                                        placeholder="@lang('Enter address')" value="{{ __(@$user->address->address) }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>@lang('State')</label>
                                    <input type="text" name="state" autocomplete="off" class="form--control"
                                        placeholder="@lang('Enter state')" value="{{ __(@$user->address->state) }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>@lang('City')</label>
                                    <input type="text" name="city" autocomplete="off" class="form--control"
                                        placeholder="@lang('Enter city')" value="{{ __(@$user->address->city) }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>@lang('Zip Code')</label>
                                    <input type="text" name="zip" autocomplete="off" class="form--control"
                                        placeholder="@lang('Enter zip code')" value="{{ __(@$user->address->zip) }}">
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn--base w-100">@lang('Update Now')</button>
                                </div>
                            </div><!-- row end -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .intl-tel-input {
        position: relative;
        display: inline-block;
        width: 100%;
         !important;
    }
</style>
@endpush
@push('script')
<script>
    "use strict";
    function proPicURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = $(input).parents('.profile-thumb').find('.profilePicPreview');
                $(preview).css('background-image', 'url(' + e.target.result + ')');
                $(preview).addClass('has-image');
                $(preview).hide();
                $(preview).fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
        }
        $(".profilePicUpload").on('change', function() {
            proPicURL(this);
        });

        $(".remove-image").on('click', function(){
            $(".profilePicPreview").css('background-image', 'none');
            $(".profilePicPreview").removeClass('has-image');
        });

</script>
@endpush
