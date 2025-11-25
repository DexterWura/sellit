<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\BidConversation;
use App\Models\DomainBid;
use App\Models\Comment;
use App\Models\Domain;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BidController extends Controller {
    public function __construct() {
        $this->activeTemplate = activeTemplate();
    }

    public function makeBid($id) {
        $pageTitle = 'Bid Now';
        $domain    = Domain::active()->findOrFail($id);
        return view($this->activeTemplate . 'bid.bid_now', compact('pageTitle', 'domain'));
    }

    public function bidStore(Request $request, $id) {

        $request->validate([
            'offer_price' => 'required|numeric|gt:0',
        ]);

        $user = auth()->user();

        $domain = Domain::active()->with('user')->find($id);

        if ($domain->user_id == $user->id) {
            $notify[] = ['error', 'You Can\'t bid on your own domain'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->offer_price > $user->balance) {
            $notify[] = ['error', 'Insufficient balance'];
            return back()->withNotify($notify)->withInput();
        }

        if ($domain->price > $request->offer_price) {
            $notify[] = ['error', 'Please follow the limit'];
            return back()->withNotify($notify)->withInput();
        }

        $preOffer = DomainBid::where('user_id', $user->id)->where('domain_id', $domain->id)->exists();

        if ($preOffer) {
            $notify[] = ['error', 'You have already bidden for this domain'];
            return back()->withNotify($notify)->withInput();
        }

        $offer            = new DomainBid();
        $offer->domain_id = $id;
        $offer->user_id   = $user->id;
        $offer->price     = $request->offer_price;
        $offer->save();

        $user->balance -= $request->offer_price;
        $user->save();

        $general = GeneralSetting::first();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $request->offer_price;
        $transaction->charge       = 0;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type     = '-';
        $transaction->details      = $general->cur_sym . $request->offer_price . ' ' . 'has been deducted from your account for your bid offer';
        $transaction->trx          = getTrx();
        $transaction->save();

        $domainOwner = $domain->user;

        notify($domainOwner, 'BID_DOMAIN', [
            'domain_name' => $domain->name,
            'bid_by'      => $user->username,
            'price'       => $request->offer_price,
            'currency'    => $general->cur_text,
            'created_at'  => $offer->created_at,
            'end_time'    => $domain->end_time,
        ]);

        notify($user, 'MAKE_BID', [
            'method_detail' => $transaction->details,
            'domain_name'   => $domain->name,
            'bid_by'        => $user->username,
            'price'         => $request->offer_price,
            'currency'      => $general->cur_text,
            'created_at'    => $offer->created_at,
            'end_time'      => $domain->end_time,
            'post_balance'  => $user->balance,
        ]);

        $notify[] = ['success', 'Bidden successfully'];
        return back()->withNotify($notify);
    }

    public function myBids() {
        $pageTitle    = 'My Bids';
        $emptyMessage = 'No domain found';
        $DomainBid    = DomainBid::where('user_id', auth()->id())->with('domain')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'bid.list', compact('pageTitle', 'emptyMessage', 'DomainBid'));
    }

    public function bidWinner() {
        $pageTitle    = 'Bid Winners';
        $emptyMessage = 'No bid found';
        $domain_id    = Domain::where('user_id', auth()->id())->select('id')->get();
        $DomainBid    = DomainBid::whereIn('domain_id', $domain_id)->where('win_status', 1)->with('domain')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'bid.list', compact('pageTitle', 'emptyMessage', 'DomainBid'));
    }

    public function commentPost(Request $request) {
        $validator = Validator::make($request->all(), [
            'comment_detail' => 'required',
            'domain_id'      => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user = auth()->user();

        $comment                 = new Comment();
        $comment->user_id        = $user->id;
        $comment->domain_id      = $request->domain_id;
        $comment->comment_detail = $request->comment_detail;
        $comment->save();
        return response()->json(['success' => 'Comment added successfully']);
    }

    public function commentReply(Request $request, $id) {
        $request->validate([
            'reply_comment' => 'required',
            'comment_id'    => 'required|integer',
        ]);
        $user   = auth()->user();
        $domain = Domain::active()->findOrFail($id);

        $comment                 = new Comment();
        $comment->user_id        = $user->id;
        $comment->domain_id      = $domain->id;
        $comment->parent_id      = $request->comment_id;
        $comment->comment_detail = $request->reply_comment;
        $comment->save();

        $notify[] = ['success', 'Comment replied successfully'];
        return back()->withNotify($notify);
    }


    public function bidDetail($id) {
        $domain   = Domain::where('id', $id)->where('user_id', auth()->id())->with('user', 'bids')->firstOrFail();
        DomainBid::where('domain_id', $domain->id)->update(['seen_status' => 1]);
        $userBids = DomainBid::where('domain_id', $domain->id)->with('user')->orderBy('price', 'desc')->paginate(getPaginate());
        $pageTitle    = 'Bids for This Domain';
        $emptyMessage = 'No data found';
        return view($this->activeTemplate . 'user.auction.bids', compact('pageTitle', 'emptyMessage', 'userBids', 'domain'));
    }

    public function bidApprove(Request $request, $id) {

        $bid    = DomainBid::with('user', 'domain')->where('id', $id)->firstOrFail();
        $buyer  = auth()->user();
        $domain = Domain::where('id', $bid->domain_id)->where('user_id', $buyer->id)->with('user')->firstOrFail();
        $isSold = DomainBid::where('domain_id', $domain->id)->where('status', 1)->exists();

        if ($isSold) {
            $notify[] = ['error', 'This domain is already sold'];
            return back()->withNotify($notify);
        }

        $bid->win_status = 1;
        $bid->save();

        $otherBids = DomainBid::where('id', '!=', $id)->where('domain_id', $domain->id)->get();

        $general = GeneralSetting::first();

        foreach ($otherBids as $otherBid) {
            $otherBid->win_status = 2;
            $otherBid->save();

            $user = User::where('id', $otherBid->user_id)->first();
            $user->balance += $otherBid->price;
            $user->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $otherBid->price;
            $transaction->charge       = 0;
            $transaction->post_balance = $user->balance;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Bid amount returned';
            $transaction->trx          = getTrx();
            $transaction->save();

        }

        $bidUser = $bid->user;

        $bidMessage              = new BidConversation();
        $bidMessage->bid_id      = $bid->id;
        $bidMessage->sender_id   = $buyer->id;
        $bidMessage->receiver_id = $bidUser->id;
        $bidMessage->message    = 'Congratulations! You win bid';
        $bidMessage->save();

        notify($bidUser, 'BID_APPROVE', [
            'domain_name'  => $domain->name,
            'user_name'    => $bidUser->username,
            'price'        => showAmount($bid->price),
            'currency'     => $general->cur_text,
            'post_balance' => showAmount($bidUser->balance),
            'updated_at'   => $bid->updated_at,
        ]);

        $notify[] = ['success', 'Bid win successfully'];
        return back()->withNotify($notify);

    }

    public function conversation($id, $domain_id) {

        $pageTitle    = 'Bid Conversations';
        $emptyMessage = 'No message to display yet';
        $bid          = DomainBid::where('id', $id)->where('domain_id', $domain_id)->where('win_status', 1)->with('domain.user', 'user', 'bidConversation')->first();
        return view($this->activeTemplate . 'bid.conversation', compact('pageTitle', 'emptyMessage', 'bid'));

    }

    public function conversationShow($id) {
        $emptyMessage = 'No message to display yet';
        $bid          = DomainBid::findOrFail($id);
        $messages     = [];

        if ($bid) {
            $messages = BidConversation::where('bid_id', $id)->with('sender')->latest()->get();
        }

        return view($this->activeTemplate . 'bid.show_message', compact('emptyMessage', 'messages'));
    }

    public function conversationStore(Request $request, $bid_id, $domain_id) {
        $validator = Validator::make($request->all(), [
            'message'   => 'required',
            'attachments' => 'nullable|array',
            'attachments.*' => ['required', new FileTypeValidate(['jpg','jpeg','png','pdf','doc','docx','txt'])]
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user = auth()->user();
        $bid  = DomainBid::where('id', $bid_id)->where('domain_id', $domain_id)->where('win_status', 1)->whereIn('status', [0,2,8])->with('domain.user')->firstOrFail();

        $msgAttachment = '';

        if ($request->hasFile('attachments')) {

            $path = imagePath()['seller']['message']['path'];

            foreach ($request->file('attachments') as $file) {
                try {
                    $arrFile[] = uploadFile($file, $path);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload your file'];
                    return back()->withNotify($notify)->withInput();
                }

            }

            $msgAttachment = $arrFile;
        }

        $message              = new BidConversation();
        $message->bid_id      = $bid_id;
        $message->sender_id   = $user->id;
        $message->receiver_id = ($user->id == $bid->user_id) ? $bid->domain->user_id : $user->id;
        $message->message     = $request->message;
        $message->attachment  = json_encode($msgAttachment);
        $message->save();

        return response()->json(['success' => 'Message send successfully']);
    }

    public function giveCredential(Request $request, $id) {

        $bid         = DomainBid::where('id', $id)->where('domain_id', $request->domain_id)->where('win_status', 1)->firstOrFail();
        $bid->status = 2;
        $bid->save();

        $notify[] = ['success', 'Bid status change successfully'];
        return back()->withNotify($notify);
    }

    public function reportOnBid(Request $request, $id) {

        $bid = DomainBid::where('id', $id)->where('user_id', auth()->id())->where('win_status', 1)->with('domain.user')->firstOrFail();
        $bid->status = 8;
        $bid->reported_at = now();
        $bid->save();

        $seller = $bid->domain->user;

        $general = GeneralSetting::first();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $bid->user_id;
        $adminNotification->title     = 'Reported for the ' . $bid->domain->name;
        $adminNotification->click_url = urlPath('admin.domain.bid.conversation.view',$bid->id);
        $adminNotification->save();

        notify($seller, 'DOMAIN_REPORT', [
            'report'      => $request->report,
            'user_name'   => $bid->user->username,
            'domain_name' => $bid->domain->name,
            'bid_price'   => showAmount($bid->price),
            'currency'    => $general->cur_text,
            'end_time'    => $bid->domain->end_time,
        ]);

        $notify[] = ['success', 'Bid status change successfully'];
        return back()->withNotify($notify);
    }

    public function completeBid(Request $request, $id) {

        $bid = DomainBid::where('id', $id)->where('domain_id', $request->domain_id)->where('win_status', 1)->with('domain.user')->firstOrFail();

        $bid->status = 1;
        $bid->save();

        $domain         = $bid->domain;
        $domain->status = 2;
        $domain->save();

        $buyer = $bid->domain->user;
        $buyer->balance += $bid->price;
        $buyer->save();

        $general = GeneralSetting::first();

        $transaction               = new Transaction();
        $transaction->user_id      = $buyer->id;
        $transaction->amount       = $bid->price;
        $transaction->charge       = 0;
        $transaction->post_balance = $buyer->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Bid price ' . $general->cur_sym . showAmount($bid->price) . ' added on account';
        $transaction->trx          = getTrx();
        $transaction->save();

        notify($domain->user, 'DOMAIN_GOT', [
            'method_detail' => 'Buyer got the domain',
            'domain_name'   => $domain->name,
            'detail'        => $transaction->details,
        ]);

        $notify[] = ['success', 'Domain Sold successfully'];
        return back()->withNotify($notify);
    }


    public function attachmentDownload($value, $id) {
        $file      = $value;
        $message   = BidConversation::findOrFail($id);
        if($message->sender_id == auth()->id() || $message->receiver_id == auth()->id()){
            $path      = imagePath()['seller']['message']['path'];
            $full_path = $path . '/' . $file;
            return response()->download($full_path);
        }

        abort(403, 'Unauthorized Action');
    }

}
