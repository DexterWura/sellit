<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Services\VerificationService;
use App\Enums\ListingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingVerificationController extends Controller
{
    protected $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
        $this->activeTemplate = activeTemplate();
    }

    /**
     * Show verification page based on listing type
     */
    public function show($id)
    {
        $domain = Domain::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pageTitle = 'Verify ' . $domain->listing_type_name;

        // Generate verification code if not exists
        if (!$domain->verify_code) {
            $domain->verify_code = $this->verificationService->generateVerificationCode();
            $domain->save();
        }

        return view($this->activeTemplate . 'user.auction.verify', compact('domain', 'pageTitle'));
    }

    /**
     * Verify domain via DNS
     */
    public function verifyDomain(Request $request, $id)
    {
        $request->validate([
            'verify_code' => 'required|string',
        ]);

        $domain = Domain::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($domain->listing_type !== ListingType::DOMAIN) {
            $notify[] = ['error', 'Invalid listing type'];
            return back()->withNotify($notify);
        }

        $isVerified = $this->verificationService->verifyDomain($domain, $request->verify_code);

        if ($isVerified) {
            $domain->is_verified = true;
            $domain->verification_method = 'dns';
            $domain->save();

            $notify[] = ['success', 'Domain verified successfully!'];
            return redirect()->route('user.auction.list')->withNotify($notify);
        }

        $notify[] = ['error', 'Verification failed. Please check your DNS TXT record.'];
        return back()->withNotify($notify);
    }

    /**
     * Verify website via DNS or file upload
     */
    public function verifyWebsite(Request $request, $id)
    {
        $request->validate([
            'verify_code' => 'required|string',
            'method' => 'required|in:dns,file',
        ]);

        $domain = Domain::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($domain->listing_type !== ListingType::WEBSITE) {
            $notify[] = ['error', 'Invalid listing type'];
            return back()->withNotify($notify);
        }

        $isVerified = $this->verificationService->verifyWebsite(
            $domain,
            $request->verify_code,
            $request->method
        );

        if ($isVerified) {
            $domain->is_verified = true;
            $domain->verification_method = $request->method === 'dns' ? 'dns' : 'file_upload';
            $domain->save();

            $notify[] = ['success', 'Website verified successfully!'];
            return redirect()->route('user.auction.list')->withNotify($notify);
        }

        $notify[] = ['error', 'Verification failed. Please check your verification method.'];
        return back()->withNotify($notify);
    }

    /**
     * Verify social media account
     */
    public function verifySocialMedia(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string',
            'platform' => 'required|string|in:instagram,facebook,twitter,youtube,tiktok,linkedin',
        ]);

        $domain = Domain::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($domain->listing_type !== ListingType::SOCIAL_MEDIA) {
            $notify[] = ['error', 'Invalid listing type'];
            return back()->withNotify($notify);
        }

        // Update domain with social media info
        $domain->social_username = $request->username;
        $domain->social_platform = $request->platform;

        // Verify (placeholder - implement actual verification)
        $isVerified = $this->verificationService->verifySocialMedia(
            $domain,
            $request->username,
            $request->password ?? null,
            $request->platform
        );

        if ($isVerified) {
            $domain->is_verified = true;
            $domain->verification_method = 'credentials';
            $domain->save();

            $notify[] = ['success', 'Social media account verified successfully!'];
            return redirect()->route('user.auction.list')->withNotify($notify);
        }

        $notify[] = ['error', 'Verification failed. Please check your credentials.'];
        return back()->withNotify($notify);
    }

    /**
     * Download verification file
     */
    public function downloadFile($id)
    {
        $domain = Domain::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 0)
            ->firstOrFail();

        if (!$domain->verify_file) {
            $notify[] = ['error', 'Verification file not found'];
            return back()->withNotify($notify);
        }

        $path = imagePath()['domain']['verify']['path'];
        $full_path = $path . '/' . $domain->verify_file;
        
        return response()->download($full_path);
    }
}

