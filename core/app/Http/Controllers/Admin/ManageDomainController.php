<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidConversation;
use App\Models\Domain;
use App\Models\DomainBid;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Enums\ListingType;
use Illuminate\Http\Request;

class ManageDomainController extends Controller {

    public function allDomain(Request $request) {
        $pageTitle    = 'All Listings';
        $emptyMessage = 'No listing found';
        $domains      = Domain::query();

        if ($request->search) {
            $domains->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->search%")
                  ->orWhere('website_url', 'LIKE', "%$request->search%")
                  ->orWhere('social_username', 'LIKE', "%$request->search%");
            });
        }

        if ($request->listing_type && ListingType::isValid($request->listing_type)) {
            $domains->where('listing_type', $request->listing_type);
        }

        $domains = $domains->with('user')->withCount('bids')->latest()->paginate(getPaginate());
        $listingTypes = ListingType::all();
        return view('admin.domain.index', compact('pageTitle', 'emptyMessage', 'domains', 'listingTypes'));
    }

    public function pendingDomain(Request $request) {
        $pageTitle     = 'All Pending Listings';
        $emptyMessage = 'No listing found';
        $domains      = Domain::query();

        if ($request->search) {
            $domains->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->search%")
                  ->orWhere('website_url', 'LIKE', "%$request->search%")
                  ->orWhere('social_username', 'LIKE', "%$request->search%");
            });
        }

        if ($request->listing_type && ListingType::isValid($request->listing_type)) {
            $domains->where('listing_type', $request->listing_type);
        }

        $domains = $domains->where('status', 0)->with('user')->withCount('bids')->latest()->paginate(getPaginate());
        $listingTypes = ListingType::all();
        return view('admin.domain.index', compact('pageTitle', 'emptyMessage', 'domains', 'listingTypes'));
    }

    public function approvedDomain(Request $request) {
        $pageTitle    = 'All Approved Listings';
        $emptyMessage = 'No listing found';
        $domains      = Domain::query();

        if ($request->search) {
            $domains->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->search%")
                  ->orWhere('website_url', 'LIKE', "%$request->search%")
                  ->orWhere('social_username', 'LIKE', "%$request->search%");
            });
        }

        if ($request->listing_type && ListingType::isValid($request->listing_type)) {
            $domains->where('listing_type', $request->listing_type);
        }

        $domains = $domains->active()->with('user')->withCount('bids')->latest()->paginate(getPaginate());
        $listingTypes = ListingType::all();
        return view('admin.domain.index', compact('pageTitle', 'emptyMessage', 'domains', 'listingTypes'));
    }

    public function finishedDomain(Request $request) {
        $pageTitle     = 'All Finished Listings';
        $emptyMessage = 'No listing found';
        $domains      = Domain::query();

        if ($request->search) {
            $domains->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->search%")
                  ->orWhere('website_url', 'LIKE', "%$request->search%")
                  ->orWhere('social_username', 'LIKE', "%$request->search%");
            });
        }

        if ($request->listing_type && ListingType::isValid($request->listing_type)) {
            $domains->where('listing_type', $request->listing_type);
        }

        $domains = $domains->finished()->with('user')->withCount('bids')->latest()->paginate(getPaginate());
        $listingTypes = ListingType::all();
        return view('admin.domain.index', compact('pageTitle', 'emptyMessage', 'domains', 'listingTypes'));
    }

    public function soldDomain(Request $request) {
        $pageTitle    = 'All Sold Listings';
        $emptyMessage = 'No listing found';
        $domains      = Domain::query();

        if ($request->search) {
            $domains->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->search%")
                  ->orWhere('website_url', 'LIKE', "%$request->search%")
                  ->orWhere('social_username', 'LIKE', "%$request->search%");
            });
        }

        if ($request->listing_type && ListingType::isValid($request->listing_type)) {
            $domains->where('listing_type', $request->listing_type);
        }

        $domains = $domains->sold()->with('user')->withCount('bids')->latest()->paginate(getPaginate());
        $listingTypes = ListingType::all();
        return view('admin.domain.index', compact('pageTitle', 'emptyMessage', 'domains', 'listingTypes'));
    }

    public function rejectedDomain(Request $request) {
        $pageTitle    = 'All Rejected Listings';
        $emptyMessage = 'No listing found';
        $domains      = Domain::query();

        if ($request->search) {
            $domains->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->search%")
                  ->orWhere('website_url', 'LIKE', "%$request->search%")
                  ->orWhere('social_username', 'LIKE', "%$request->search%");
            });
        }

        if ($request->listing_type && ListingType::isValid($request->listing_type)) {
            $domains->where('listing_type', $request->listing_type);
        }

        $domains = $domains->where('status', 9)->with('user')->withCount('bids')->latest()->paginate(getPaginate());
        $listingTypes = ListingType::all();
        return view('admin.domain.index', compact('pageTitle', 'emptyMessage', 'domains', 'listingTypes'));
    }

    public function viewDomain($id) {

        $domain       = Domain::with('user', 'bids.user', 'category')->findOrFail($id);
        $emptyMessage = 'No bids Found';
        $pageTitle    = 'Details for' . ' ' . $domain->name;
        $code         = getTrx();
        $fileVal      = null;

        if ($domain->status==0 && $domain->verify_file != null) {
            $file    = 'http://' . $domain->name . '/' . $domain->verify_file;
            $fileVal = (@fopen($file, "r")) ? file_get_contents($file) : 'No Code Found';
        }

        return view('admin.domain.details', compact('pageTitle', 'domain', 'code', 'fileVal', 'emptyMessage'));
    }

    public function domainApprove(Request $request) {
        $request->validate([
            'id' => 'required|integer|gt:0',
        ]);

        $domain         = Domain::with('user')->findOrFail($request->id);
        $domain->status = 1;
        $domain->save();

        $user    = $domain->user;
        $general = GeneralSetting::first();
        notify($user, 'DOMAIN_APPROVE', [
            'user_name'   => $user->username,
            'domain_name' => $domain->name,
            'price'       => showAmount($domain->price),
            'currency'    => $general->cur_text,
            'end_time'    => $domain->end_time,
            'created_at'  => $domain->created_at,
        ]);
        $notify[] = ['success', 'Domain approved successfully'];
        return back()->withNotify($notify);
    }

    public function domainReject(Request $request) {
        $request->validate([
            'id'             => 'required|integer|gt:0',
            'admin_feedback' => 'required',
        ]);

        $domain                 = Domain::with('user')->findOrFail($request->id);
        $domain->status         = 9;
        $domain->admin_feedback = $request->admin_feedback;
        $domain->save();

        $user    = $domain->user;
        $general = GeneralSetting::first();
        notify($user, 'DOMAIN_REJECT', [
            'reject_issue' => $request->admin_feedback,
            'user_name'    => $user->username,
            'domain_name'  => $domain->name,
            'price'        => showAmount($domain->price),
            'currency'     => $general->cur_text,
            'end_time'     => $domain->end_time,
            'created_at'   => $domain->created_at,
        ]);
        $notify[] = ['success', 'Domain rejected successfully'];
        return back()->withNotify($notify);
    }

    public function fileSend(Request $request) {

        $request->validate([
            'verify_detail' => 'required',
        ]);

        $verifyCode = getTrx();

        $domain = Domain::with('user')->findOrFail($request->id);
        $user   = $domain->user;

        $domain->verify_code   = $verifyCode;
        $domain->verify_detail = $request->verify_detail;

        $path = imagePath()['domain']['verify']['path'];
        try {
            $attachment = sendFile($path, null, null, $verifyCode);
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Could not upload your file'];
            return back()->withNotify($notify);
        }

        $domain->verify_file = $attachment;
        $domain->save();

        $general = GeneralSetting::first();
        notify($user, 'FILE_SEND', [
            'domain_name' => $domain->name,
            'user_name'   => $user->username,
            'price'       => showAmount($domain->price),
            'currency'    => $general->cur_text,
            'end_time'    => $domain->end_time,
        ]);
        $notify[] = ['success', 'Verification file send successfully'];
        return back()->withNotify($notify);

    }

    public function allBids($id) {
        $domain = Domain::findOrFail($id);
        $pageTitle    = 'All Bids for '.$domain->name;
        $emptyMessage = 'No bid found for this domain';
        $bids         = DomainBid::where('domain_id', $domain->id)->with('user', 'domain')->latest()->paginate(getPaginate());
        return view('admin.domain.bids', compact('pageTitle', 'bids', 'emptyMessage'));
    }

    public function reportedBids() {
        $pageTitle    = 'Reported Bids';
        $emptyMessage = 'No reported bid found';
        $bids         = DomainBid::reported()->with('user', 'domain')->latest()->paginate(getPaginate());
        return view('admin.domain.reported_bids', compact('pageTitle', 'bids', 'emptyMessage'));
    }

    public function viewConversation($bid_id) {
        $pageTitle    = 'View Conversation';
        $emptyMessage = 'No conversation found';
        $bid          = DomainBid::where('id', $bid_id)->with('user', 'domain.user')->firstOrFail();
        $conversation = BidConversation::where('bid_id', $bid_id)->with('sender')->latest()->get();
        return view('admin.domain.chat', compact('pageTitle', 'emptyMessage', 'conversation', 'bid'));
    }

    public function sendMessage(Request $request) {

        $request->validate([
            'message' => 'string|required'
        ]);

        $conversation           = new BidConversation();
        $conversation->bid_id   = $request->bid_id;
        $conversation->admin_id = auth()->guard('admin')->id();
        $conversation->message  = $request->message;
        $conversation->save();

        $notify[] = ['success', 'Message Send Successfully'];
        return back()->withNotify($notify);
    }

    public function reportAction(Request $request, $id) {

        $request->validate([
            'status' => 'required|in:1, 9',
        ]);

        $bid = DomainBid::with('user', 'domain')->findOrFail($id);

        $seller = $bid->domain->user;
        $buyer  = $bid->user;

        $domain = $bid->domain;

        $general = GeneralSetting::first();

        if ($request->status == 1) {

            $this->credentialGiven($bid, $domain, $seller,$general);
            $notification = 'Domain sold successfully';

        } else {
            $this->cancelBid($bid, $domain, $buyer,$general);
            $notification = 'Auction cancelled successfully';
        }

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);

    }

    protected function credentialGiven($bid, $domain, $seller, $general) {
        $bid->status = 1;
        $bid->save();

        $domain->status = 2;
        $domain->save();

        $seller->balance += $bid->price;
        $seller->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $seller->id;
        $transaction->amount       = $bid->price;
        $transaction->charge       = 0;
        $transaction->post_balance = $seller->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Domain sold';
        $transaction->trx          = getTrx();
        $transaction->save();

        notify($seller, 'DOMAIN_SOLD', [
            'payment_added' => 'Bid price added in your account',
            'domain_name'   => $domain->name,
            'user_name'     => $seller->username,
            'price'         => showAmount($domain->price),
            'currency'      => $general->cur_text,
            'post_balance'  => $seller->balance,
        ]);

    }

    protected function cancelBid($bid, $domain, $buyer,$general) {
        $bid->status = 9;
        $bid->save();

        $domain->status = 9;
        $domain->save();

        $buyer->balance += $bid->price;
        $buyer->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $buyer->id;
        $transaction->amount       = $bid->price;
        $transaction->charge       = 0;
        $transaction->post_balance = $buyer->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Bid amount refunded';
        $transaction->trx          = getTrx();
        $transaction->save();

        notify($buyer, 'PAYMENT_REFUND', [
            'payment_added' => 'Bid price added in your account',
            'domain_name'   => $domain->name,
            'user_name'     => $buyer->username,
            'price'         => showAmount($domain->price),
            'currency'      => $general->cur_text,
            'post_balance'  => $buyer->balance,
        ]);
    }


    public function downloadAttachment ($value, $id) {
        $file      = $value;
        $path      = imagePath()['seller']['message']['path'];
        $full_path = $path . '/' . $file;
        return response()->download($full_path);

    }
}
