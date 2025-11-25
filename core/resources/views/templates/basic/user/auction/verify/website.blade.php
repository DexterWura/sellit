<div class="verification-section">
    <h5 class="mb-3">@lang('Website Verification')</h5>
    <p class="text-muted mb-4">@lang('Verify ownership of your website using DNS TXT record or file upload method.')</p>
    
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#dns_method">@lang('DNS Method')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#file_method">@lang('File Upload Method')</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- DNS Method -->
        <div id="dns_method" class="tab-pane fade show active">
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
                <h6>@lang('DNS TXT Record Instructions:')</h6>
                <ol>
                    <li>@lang('Log in to your domain registrar or DNS provider')</li>
                    <li>@lang('Navigate to DNS settings for') <strong>{{ $domain->website_url ?? $domain->name }}</strong></li>
                    <li>@lang('Add a new TXT record with the verification code above')</li>
                    <li>@lang('Wait for DNS propagation and click verify')</li>
                </ol>
            </div>

            <form action="{{ route('user.listing.verify.website', $domain->id) }}" method="POST">
                @csrf
                <input type="hidden" name="method" value="dns">
                <div class="form-group">
                    <label>@lang('Verification Code')</label>
                    <input type="text" name="verify_code" class="form-control" value="{{ $domain->verify_code }}" required>
                </div>
                <button type="submit" class="btn btn--primary w-100 mt-3">
                    <i class="fas fa-check"></i> @lang('Verify via DNS')
                </button>
            </form>
        </div>

        <!-- File Upload Method -->
        <div id="file_method" class="tab-pane fade">
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
                <h6>@lang('File Upload Instructions:')</h6>
                <ol>
                    <li>@lang('Create a text file named') <code>{{ $domain->verify_code }}.txt</code></li>
                    <li>@lang('Put only the verification code inside the file (no other text)')</li>
                    <li>@lang('Upload this file to the root directory of your website')</li>
                    <li>@lang('The file should be accessible at:') <code>http://{{ $domain->website_url ?? $domain->name }}/{{ $domain->verify_code }}.txt</code></li>
                    <li>@lang('Click verify once the file is uploaded')</li>
                </ol>
            </div>

            <form action="{{ route('user.listing.verify.website', $domain->id) }}" method="POST">
                @csrf
                <input type="hidden" name="method" value="file">
                <div class="form-group">
                    <label>@lang('Verification Code')</label>
                    <input type="text" name="verify_code" class="form-control" value="{{ $domain->verify_code }}" required>
                </div>
                <button type="submit" class="btn btn--primary w-100 mt-3">
                    <i class="fas fa-check"></i> @lang('Verify via File Upload')
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('@lang("Verification code copied to clipboard!")');
    });
}
</script>

