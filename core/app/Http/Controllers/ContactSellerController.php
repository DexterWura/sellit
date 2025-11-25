<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Domain;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\Validator;

class ContactSellerController extends Controller
{

    public function __construct() {
        $this->activeTemplate = activeTemplate();
    }

    public function contactSeller($id) {
        $pageTitle = 'Contact Seller';
        $domain    = Domain::active()->with('category', 'user')->findOrFail($id);
        $messages     = ContactMessage::where('domain_id', $id)->where('user_id', auth()->id())->with('sender')->latest()->get();
        return view($this->activeTemplate . 'user.contact_seller.contact_form', compact('pageTitle', 'domain', 'messages'));
    }

    public function messageToSeller(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'message'       => 'required',
            'attachments'   => 'nullable|array|max:10',
            'attachments.*' => ['required', new FileTypeValidate(['jpg','jpeg','png','pdf','doc','docx','txt'])]
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user   = auth()->user();
        $domain = Domain::active()->findOrFail($id);
        $attachments = null;

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

            $attachments = $arrFile;
        }

        $message                  = new ContactMessage();
        $message->domain_id       = $domain->id;
        $message->user_id         = $user->id;
        $message->sender_id       = $user->id;
        $message->seller_id       = $domain->user_id;
        $message->message         = $request->message;
        $message->attachment      = json_encode($attachments);
        $message->save();
        return response()->json(['success' => true]);
    }

    public function allMessages($id) {
        $messages     = ContactMessage::where('domain_id', $id)->where('user_id', auth()->id())->with('sender')->latest()->get();
        return view($this->activeTemplate . 'user.contact_seller.messages', compact('messages'));
    }

    public function showAllMessage($id) {
        $pageTitle = 'Show All Messages';

        $domain    = Domain::where('user_id', auth()->id())->where(function ($query) {
            $query->where('status', 1)->orWhere('status', 2);
        })->where('id', $id)->firstOrFail();

        $conversations    = ContactMessage::where('seller_id', auth()->id())->where('domain_id', $id)
        ->groupBy('user_id')
        ->latest()
        ->paginate(getPaginate());

        $emptyMessage     = 'No conversation yet to display';
        return view($this->activeTemplate . 'user.contact_seller.conversations', compact('pageTitle', 'domain', 'emptyMessage', 'conversations'));
    }

    // Seller's Methods

    public function conversationView(Request $request, $id) {
        $pageTitle      = 'Conversation';
        $emptyMessage   = 'No message yet to display';
        $unseenMessages = ContactMessage::where('domain_id', $id)->where('seen_status', 0)->update(['seen_status'=>1]);

        $messages       = ContactMessage::where('domain_id', $id)
        ->with(['domain'=>function($domain){
            $domain->select(['id', 'name', 'price', 'end_time']);
        }, 'user'=>function($user){
            $user->select(['id', 'username', 'email', 'mobile']);
        }])
        ->latest()
        ->get();

        if(!$messages->count()){
            $notify[]=['error','No conversation yet'];
            return back()->withNotify($notify);
        }

        $conversation = $messages[0];

        return view($this->activeTemplate . 'user.contact_seller.seller_contact_form', compact('pageTitle', 'emptyMessage', 'messages', 'conversation'));
    }


    public function messageReply(Request $request, $domainId, $userId) {

        $validator = Validator::make($request->all(), [
            'message'       => 'required',
            'attachments' => 'nullable|array|max:10',
            'attachments.*' => ['required', new FileTypeValidate(['jpg','jpeg','png','pdf','doc','docx','txt'])]
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user        = auth()->user();

        $domain = Domain::where(function ($query) {
            $query->where('status', 1)->orWhere('status', 2);
        })->find($domainId);

        if(!$domain){
            return response()->json(['error' => 'Domain not found']);
        }

        $attachments = null;

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
            $attachments = $arrFile;
        }

        $message                  = new ContactMessage();
        $message->domain_id       = $domain->id;
        $message->seller_id       = $user->id;
        $message->sender_id       = $user->id;
        $message->user_id         = $userId;
        $message->message         = $request->message;
        $message->attachment      = json_encode($attachments);
        $message->save();

        return response()->json(['success' => 'Message send successfully']);
    }

    public function showMessages($domainId, $userId) {
        $user         = auth()->user();
        $messages = ContactMessage::where('domain_id', $domainId)->where('user_id', $userId)->with('user')->latest()->get();

        return view($this->activeTemplate . 'user.contact_seller.messages', compact('messages'));
    }


    public function attachmentDownload($value, $id) {
        $file      = $value;
        $message   = ContactMessage::findOrFail($id);
        if($message->user_id == auth()->id() || $message->seller_id == auth()->id()){
            $path      = imagePath()['seller']['message']['path'];
            $full_path = $path . '/' . $file;
            return response()->download($full_path);
        }

        abort(403, 'Unauthorized Action');
    }

}
