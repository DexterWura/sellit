<div class="verification-section">
    <h5 class="mb-3">@lang('Social Media Account Verification')</h5>
    <p class="text-muted mb-4">@lang('Verify ownership of your social media account by providing your account credentials.')</p>
    
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>@lang('Note:')</strong> @lang('Your credentials will be securely verified. We recommend using a strong password and enabling two-factor authentication on your account.')
    </div>

    <form action="{{ route('user.listing.verify.social', $domain->id) }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label>@lang('Platform')<span class="text--danger">*</span></label>
            <select class="form-control" name="platform" required>
                <option value="" selected disabled>@lang('Select Platform')</option>
                <option value="instagram" @if($domain->social_platform == 'instagram') selected @endif>Instagram</option>
                <option value="facebook" @if($domain->social_platform == 'facebook') selected @endif>Facebook</option>
                <option value="twitter" @if($domain->social_platform == 'twitter') selected @endif>Twitter</option>
                <option value="youtube" @if($domain->social_platform == 'youtube') selected @endif>YouTube</option>
                <option value="tiktok" @if($domain->social_platform == 'tiktok') selected @endif>TikTok</option>
                <option value="linkedin" @if($domain->social_platform == 'linkedin') selected @endif>LinkedIn</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label>@lang('Username/Handle')<span class="text--danger">*</span></label>
            <input type="text" name="username" class="form-control" 
                placeholder="@lang('e.g. @username or username')" 
                value="{{ $domain->social_username ?? old('username') }}" required>
            <small class="text-muted">@lang('Enter your account username or handle (without @ symbol)')</small>
        </div>

        <div class="form-group mb-3">
            <label>@lang('Account Verification Method')</label>
            <div class="alert alert-info">
                <p class="mb-2"><strong>@lang('How verification works:')</strong></p>
                <ul class="mb-0">
                    <li>@lang('We will verify that you have access to this account')</li>
                    <li>@lang('You may be asked to complete additional verification steps')</li>
                    <li>@lang('Your account must be active and publicly accessible')</li>
                </ul>
            </div>
        </div>

        <button type="submit" class="btn btn--primary w-100 mt-3">
            <i class="fas fa-check"></i> @lang('Verify Social Media Account')
        </button>
    </form>

    <div class="mt-4">
        <h6>@lang('Verification Requirements:')</h6>
        <ul>
            <li>@lang('Account must be active and publicly accessible')</li>
            <li>@lang('Account must have a minimum number of followers/engagement (platform dependent)')</li>
            <li>@lang('Account must not be restricted or banned')</li>
            <li>@lang('You must be able to prove ownership through platform-specific methods')</li>
        </ul>
    </div>
</div>

