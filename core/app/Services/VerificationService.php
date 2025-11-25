<?php

namespace App\Services;

use App\Models\Domain;
use App\Enums\ListingType;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VerificationService
{
    /**
     * Verify domain ownership via DNS TXT record
     */
    public function verifyDomain(Domain $domain, $verifyCode)
    {
        try {
            $domainName = $domain->name;
            $txtRecords = dns_get_record($domainName, DNS_TXT);
            
            if ($txtRecords === false) {
                return false;
            }

            foreach ($txtRecords as $record) {
                if (isset($record['txt']) && $record['txt'] === $verifyCode) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Domain verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify website ownership via DNS TXT record or file upload
     */
    public function verifyWebsite(Domain $domain, $verifyCode, $method = 'dns')
    {
        if ($method === 'dns') {
            return $this->verifyDomain($domain, $verifyCode);
        }

        // File upload verification
        if ($method === 'file') {
            $websiteUrl = $domain->website_url ?? $domain->name;
            if (!$websiteUrl) {
                return false;
            }

            // Ensure URL has protocol
            if (!preg_match('/^https?:\/\//', $websiteUrl)) {
                $websiteUrl = 'http://' . $websiteUrl;
            }

            try {
                // Try with .txt extension first
                $verifyUrl = rtrim($websiteUrl, '/') . '/' . $verifyCode . '.txt';
                $response = Http::timeout(10)->get($verifyUrl);
                
                if ($response->successful()) {
                    $content = trim($response->body());
                    if ($content === $verifyCode) {
                        return true;
                    }
                }
                
                // Try without extension
                $verifyUrl = rtrim($websiteUrl, '/') . '/' . $verifyCode;
                $response = Http::timeout(10)->get($verifyUrl);
                
                if ($response->successful()) {
                    $content = trim($response->body());
                    return $content === $verifyCode;
                }
            } catch (\Exception $e) {
                Log::error('Website verification error: ' . $e->getMessage());
            }

            return false;
        }

        return false;
    }

    /**
     * Verify social media account ownership
     */
    public function verifySocialMedia(Domain $domain, $username, $password, $platform)
    {
        if (empty($username) || empty($platform)) {
            return false;
        }

        try {
            $socialMediaService = new \App\Services\SocialMediaVerificationService();
            return $socialMediaService->verify($platform, $username, ['password' => $password]);
        } catch (\Exception $e) {
            Log::error('Social media verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate verification code for a domain/website
     */
    public function generateVerificationCode()
    {
        return getTrx(20);
    }

    /**
     * Verify based on listing type
     */
    public function verify(Domain $domain, $data = [])
    {
        switch ($domain->listing_type) {
            case ListingType::DOMAIN:
                return $this->verifyDomain($domain, $data['verify_code'] ?? $domain->verify_code);
            
            case ListingType::WEBSITE:
                $method = $data['method'] ?? 'dns';
                return $this->verifyWebsite($domain, $data['verify_code'] ?? $domain->verify_code, $method);
            
            case ListingType::SOCIAL_MEDIA:
                return $this->verifySocialMedia(
                    $domain,
                    $data['username'] ?? $domain->social_username,
                    $data['password'] ?? null,
                    $data['platform'] ?? $domain->social_platform
                );
            
            default:
                return false;
        }
    }
}



