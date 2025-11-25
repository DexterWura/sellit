<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\DomainExtension;
use App\Enums\ListingType;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function __construct() {
        $this->activeTemplate = activeTemplate();
    }

    public function domains() {
        $pageTitle    = 'All Listings';
        $extensions   = DomainExtension::where('status', 1)->orderBy('id', 'asc')->get();
        $domains      = Domain::active();

        $cloneDomains = clone $domains;
        $minPrice     = $cloneDomains->min('price') ?? 0;
        $maxPrice     = $cloneDomains->max('price') ?? 0;

        if(request()->search){
            $pageTitle    = 'Search Results';
            $domains = $domains->where(function($q) {
                $search = request()->search;
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('website_url', 'like', '%' . $search . '%')
                  ->orWhere('social_username', 'like', '%' . $search . '%');
            });
        }

        $domains = $domains->with('bids')->latest()->paginate(getPaginate());
        $listingTypes = ListingType::all();
        $emptyMessage = 'No listing found';

        return view($this->activeTemplate . 'domain.domains', compact('pageTitle', 'emptyMessage', 'extensions', 'domains', 'minPrice', 'maxPrice', 'listingTypes'));
    }

    public function domainDetail($id, $name) {
        $pageTitle = 'Domain Detail';
        $domain    = Domain::active()->with('category', 'user', 'bids')->findOrFail($id);
        return view($this->activeTemplate . 'domain.detail', compact('pageTitle', 'domain'));
    }

    public function domainFilter() {
        $request    = request();

        if ($request->paginateValue != null) {
            $paginateCount = $request->paginateValue;
        } else {
            $paginateCount = getPaginate();
        }

        $sortDomain   = $this->filterDomain();
        $domains      = $sortDomain->paginate($paginateCount);
        $emptyMessage = 'No domain found';
        return view($this->activeTemplate . 'domain.partials.domain_list', compact('domains', 'emptyMessage'));

    }

    protected function filterDomain() {
        $request    = request();
        $domains    = Domain::active()->with('bids');
        $min        = $request->min;
        $max        = $request->max;
        $search     = $request->search;

        if ($search) {
            $domains = $domains->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('website_url', 'like', '%' . $search . '%')
                  ->orWhere('social_username', 'like', '%' . $search . '%');
            });
        }

        if ($request->listing_type && ListingType::isValid($request->listing_type)) {
            $domains = $domains->where('listing_type', $request->listing_type);
        }

        if ($request->extensions) {
            $extensions = $request->extensions;
            $domains = $domains->Where(function ($query) use ($extensions) {
                for ($i = 0; $i < count($extensions); $i++) {
                    $query->orWhere('name', 'like', '%' . $extensions[$i]);
                }
            });
        }

        if ($min && $max) {
            $domains = $domains->where('price', '>=', $min)->where('price', '<=', $max);
        }

        if($request->sort){
            $sort = explode('_', $request->sort);
            $domains = $domains->orderBy(@$sort[0], @$sort[1]);
        }

        return $domains;
    }



}
