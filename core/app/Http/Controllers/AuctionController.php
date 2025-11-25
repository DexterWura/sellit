<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Domain;
use App\Models\GeneralSetting;
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
        return view($this->activeTemplate . 'user.auction.create', compact('pageTitle', 'countries', 'categories'));
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
            'name'          => 'required',
            'category_id'   => 'required|integer',
            'price'         => 'required|numeric|gt:0',
            'register_date' => 'required|date_format:Y-m-d|before:tomorrow',
            'end_time'      => 'required|date_format:Y-m-d h:i a|after:now',
            'location'      => 'required',
            'traffic'       => 'required|integer|gt:0',
            'description'   => 'required',
        ]);

        $general  = GeneralSetting::first();
        $user     = auth()->user();

        $domainStatus = Domain::active()->where('user_id', $user->id)->where('name', $request->name)->where('status', 1)->exists();

        if ($domainStatus == 1) {
            $notify[] = ['error', 'Domain already exists'];
            return back()->withNotify($notify)->withInput();
        }

        $domainName  = $request->name;
        $findElement = substr($domainName, 0, 4);

        if ($findElement == 'www.') {
            $domainName = str_replace($findElement, '', $request->name);
        }

        $endDate = Carbon::parse($request->end_time);

        $domain                = new Domain();
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
        $domain->status        = $general->auto_approve ?? 0;
        $domain->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = $user->username . ' ' . 'posted a domain for sale';
        $adminNotification->click_url = urlPath('admin.domain.all');
        $adminNotification->save();

        notify($user, 'AUCTION_CREATE', [
            'user_name'   => $user->username,
            'domain_name' => $request->name,
            'price'       => $request->price,
            'currency'    => $general->cur_text,
            'end_time'    => showDateTime($endDate),
            'created_at'  => $domain->created_at,
        ]);

        $notify[] = ['success', 'Auction created successfully'];
        return redirect()->route('user.auction.list')->withNotify($notify);
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
