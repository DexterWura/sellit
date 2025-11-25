<div class="verification-section">
    <h5 class="mb-3">@lang('Domain Verification via DNS TXT Record')</h5>
    <p class="text-muted mb-4">@lang('To verify ownership of your domain, add a TXT record to your domain\'s DNS settings.')</p>
    
    <div class="alert alert-info">
        <strong>@lang('Your Verification Code:')</strong>
        <div class="mt-2">
            <code class="fs-4">{{ $domain->verify_code }}</code>
            <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyToClipboard('{{ $domain->verify_code }}')">
                <i class="fas fa-copy"></i> @lang('Copy')
            </button>
        </div>
    </div>

    <div class="instructions mb-4">
        <h6>@lang('Instructions:')</h6>
        <ol>
            <li>@lang('Log in to your domain registrar or DNS provider')</li>
            <li>@lang('Navigate to DNS settings for') <strong>{{ $domain->name }}</strong></li>
            <li>@lang('Add a new TXT record with the following:')</li>
            <ul>
                <li><strong>@lang('Type:')</strong> TXT</li>
                <li><strong>@lang('Name/Host:')</strong> @ (or leave blank for root domain)</li>
                <li><strong>@lang('Value:')</strong> {{ $domain->verify_code }}</li>
                <li><strong>@lang('TTL:')</strong> 3600 (or default)</li>
            </ul>
            <li>@lang('Wait a few minutes for DNS propagation (can take up to 24 hours)')</li>
            <li>@lang('Click the verify button below once you\'ve added the record')</li>
        </ol>
    </div>

    <form action="{{ route('user.listing.verify.domain', $domain->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label>@lang('Verification Code')</label>
            <input type="text" name="verify_code" class="form-control" value="{{ $domain->verify_code }}" required>
            <small class="text-muted">@lang('Enter the verification code you added to your DNS TXT record')</small>
        </div>
        <button type="submit" class="btn btn--primary w-100 mt-3">
            <i class="fas fa-check"></i> @lang('Verify Domain')
        </button>
    </form>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('@lang("Verification code copied to clipboard!")');
    });
}
</script>

