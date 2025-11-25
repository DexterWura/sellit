<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Domain;
use App\Models\GeneralSetting;
use App\Enums\ListingType;
use App\Services\VerificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuctionController extends Controller {

    public function __construct() {
        $this->activeTemplate = activeTemplate();
    }

    public function list(Request $request) {
        $pageTitle    = 'My Auctions';
        $emptyMessage = 'No auction created yet';
        $user         = auth()->user();

        $domains = Domain::where('user_id', $user->id)->withCount([
            'contactMessages' => function ($query) {
                $query->where('seen_status', 0);
            },
        ])
        ->withCount(['bids' => function ($q) {
            $q->where('seen_status', 0);
        }])
        ->latest()
        ->paginate(getPaginate());

        return view($this->activeTemplate . 'user.auction.list', compact('pageTitle', 'emptyMessage', 'domains'));
    }

    public function create() {
        $pageTitle  = 'Create Auction';
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $categories = Category::where('status', 1)->get();
        $listingTypes = ListingType::all();
        return view($this->activeTemplate . 'user.auction.create', compact('pageTitle', 'countries', 'categories', 'listingTypes'));
    }

    public function edit($id) {
        $pageTitle  = 'Edit Domain';
        $domain     = Domain::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $categories = Category::where('status', 1)->get();
        return view($this->activeTemplate . 'user.auction.edit', compact('pageTitle', 'domain', 'categories', 'countries'));
    }

    public function store(Request $request) {

        $request->validate([
            'listing_type'  => 'required|in:domain,website,social_media',
            'category_id'   => 'required|integer',
            'price'         => 'required|numeric|gt:0',
            'register_date' => 'required|date_format:Y-m-d|before:tomorrow',
            'end_time'      => 'required|date_format:Y-m-d h:i a|after:now',
            'location'      => 'required',
            'traffic'       => 'required|integer|gt:0',
            'description'   => 'required',
            'thumbnail'     => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'images.*'      => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $general  = GeneralSetting::first();
        $user     = auth()->user();

        // Validate based on listing type
        if ($request->listing_type === ListingType::DOMAIN || $request->listing_type === ListingType::WEBSITE) {
            $request->validate([
                'name' => 'required|string',
            ]);
        } elseif ($request->listing_type === ListingType::SOCIAL_MEDIA) {
            $request->validate([
                'social_username' => 'required|string',
                'social_platform' => 'required|string|in:instagram,facebook,twitter,youtube,tiktok,linkedin',
            ]);
        }

        $domainName = $request->name ?? null;
        
        if ($domainName) {
            $findElement = substr($domainName, 0, 4);
            if ($findElement == 'www.') {
                $domainName = str_replace($findElement, '', $domainName);
            }
        }

        // Check for duplicates
        if ($domainName) {
            $domainStatus = Domain::active()
                ->where('user_id', $user->id)
                ->where('name', $domainName)
                ->where('listing_type', $request->listing_type)
                ->where('status', 1)
                ->exists();

            if ($domainStatus) {
                $notify[] = ['error', ucfirst($request->listing_type) . ' already exists'];
                return back()->withNotify($notify)->withInput();
            }
        }

        $endDate = Carbon::parse($request->end_time);
        $verificationService = new VerificationService();

        $domain                = new Domain();
        $domain->listing_type  = $request->listing_type;
        $domain->name          = $domainName;
        $domain->user_id       = $user->id;
        $domain->category_id   = $request->category_id;
        $domain->price         = $request->price;
        $domain->register_date = $request->register_date;
        $domain->end_time      = $endDate;
        $domain->location      = $request->location;
        $domain->traffic       = $request->traffic;
        $domain->description   = $request->description;
        $domain->note          = $request->note;
        
        // Set listing-specific fields
        if ($request->listing_type === ListingType::WEBSITE) {
            $domain->website_url = $request->website_url ?? $domainName;
        } elseif ($request->listing_type === ListingType::SOCIAL_MEDIA) {
            $domain->social_username = $request->social_username;
            $domain->social_platform = $request->social_platform;
            $domain->name = $request->social_username; // Use username as name for display
        }

        // Store analytics and links if provided
        if ($request->has('analytics_data')) {
            $domain->analytics_data = json_decode($request->analytics_data, true);
        }
        if ($request->has('additional_links')) {
            $domain->additional_links = json_decode($request->additional_links, true);
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            try {
                $path = 'assets/images/listings/thumbnails';
                $size = '400x300';
                $domain->thumbnail = uploadImage($request->thumbnail, $path, $size);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload thumbnail image.'];
                return back()->withNotify($notify)->withInput();
            }
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $images = [];
            $path = 'assets/images/listings/images';
            $size = '800x600';
            
            foreach ($request->file('images') as $image) {
                try {
                    $images[] = uploadImage($image, $path, $size);
                } catch (\Exception $exp) {
                    // Continue with other images if one fails
                }
            }
            
            if (count($images) > 0) {
                $domain->images = $images;
            }
        }

        // Generate verification code
        $domain->verify_code = $verificationService->generateVerificationCode();
        
        // Set status - require verification before approval
        $domain->status = 0; // Always start as pending until verified
        $domain->is_verified = false;
        $domain->save();

        $listingTypeName = ListingType::all()[$request->listing_type] ?? 'listing';
        $displayName = $domain->display_name;

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = $user->username . ' posted a ' . strtolower($listingTypeName) . ' for sale';
        $adminNotification->click_url = urlPath('admin.domain.all');
        $adminNotification->save();

        notify($user, 'AUCTION_CREATE', [
            'user_name'   => $user->username,
            'domain_name' => $displayName,
            'price'       => $request->price,
            'currency'    => $general->cur_text,
            'end_time'    => showDateTime($endDate),
            'created_at'  => $domain->created_at,
        ]);

        $notify[] = ['success', ucfirst($listingTypeName) . ' auction created successfully. Please verify ownership to proceed.'];
        return redirect()->route('user.listing.verify', $domain->id)->withNotify($notify);
    }

    public function update(Request $request, $id) {
        $domain = Domain::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'name'          => 'required|unique:domains,name,' . $domain->id,
            'category_id'   => 'required|integer',
            'price'         => 'required|numeric|gt:0',
            'register_date' => 'required|date_format:Y-m-d|before:tomorrow',
            'end_time'      => 'required|after:now',
            'location'      => 'required',
            'traffic'       => 'required|integer|gt:0',
            'description'   => 'required',
        ]);

        $endDate = Carbon::parse($request->end_time);

        $domainName  = $request->name;
        $findElement = substr($domainName, 0, 4);

        if ($findElement == 'www.') {
            $domainName = str_replace($findElement, '', $request->name);
        }

        $general = GeneralSetting::first();

        $domain->name          = $domainName;
        $domain->category_id   = $request->category_id;
        $domain->price         = $request->price;
        $domain->register_date = $request->register_date;
        $domain->end_time      = $endDate;
        $domain->location      = $request->location;
        $domain->traffic       = $request->traffic;
        $domain->description   = $request->description;
        $domain->note          = $request->note;
        $domain->status        = $general->auto_approve ?? 0;
        $domain->save();

        $notify[] = ['success', 'Auction updated successfully'];
        return back()->withNotify($notify);
    }


    public function download($id) {
        $domain    = Domain::where('id', $id)->where('user_id', auth()->id())->where('status', 0)->firstOrFail();
        $file      = $domain->verify_file;
        $path      = imagePath()['domain']['verify']['path'];
        $full_path = $path . '/' . $file;
        return response()->download($full_path);

    }

}
