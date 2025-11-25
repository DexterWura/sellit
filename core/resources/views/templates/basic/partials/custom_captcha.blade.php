@php
$captcha = loadCustomCaptcha();
@endphp
@if($captcha)
<div class="form-group">
    @php echo $captcha @endphp
</div>
<div class="form-group">
    <label>@lang('Captcha Code') <sup class="text--danger">*</sup></label>
    <div class="custom-icon-field">
        <input type="text" name="captcha" placeholder="@lang('Enter Code')" autocomplete="off" class="form--control">
        <i class="las la-fingerprint"></i>
    </div>
</div>
@endif
