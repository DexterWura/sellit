<?php

namespace App\Http\Controllers;
use App\Models\AdminNotification;
use App\Models\Comment;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller {

    public function __construct() {
        $this->activeTemplate = activeTemplate();
    }

    public function index() {
        try {
            $reference = @$_GET['reference'];

            if ($reference) {
                session()->put('reference', $reference);
            }

            $pageTitle = 'Home';
            $sections  = Page::where('tempname', $this->activeTemplate)->where('slug', 'home')->first();
            
            \Illuminate\Support\Facades\Log::info('Home page accessed', [
                'template' => $this->activeTemplate,
                'sections' => $sections ? 'found' : 'not found'
            ]);
            
            return view($this->activeTemplate . 'home', compact('pageTitle', 'sections'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Home page error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function pages($slug) {
        $page      = Page::where('tempname', $this->activeTemplate)->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections  = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle', 'sections'));
    }

    public function contact() {
        $pageTitle = "Contact Us";
        $sections  = Page::where('tempname', $this->activeTemplate)->where('slug', 'contact')->first();
        return view($this->activeTemplate . 'contact', compact('pageTitle', 'sections'));
    }

    public function contactSubmit(Request $request) {

        $this->validate($request, [
            'name'    => 'required|max:191',
            'email'   => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);

        $random = getNumber();

        $ticket           = new SupportTicket();
        $ticket->user_id  = auth()->id() ?? 0;
        $ticket->name     = $request->name;
        $ticket->email    = $request->email;
        $ticket->priority = 2;

        $ticket->ticket     = $random;
        $ticket->subject    = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status     = 0;
        $ticket->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title     = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message                   = new SupportMessage();
        $message->supportticket_id = $ticket->id;
        $message->message          = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return redirect()->route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function changeLanguage($lang = null) {
        $language = Language::where('code', $lang)->first();

        if (!$language) {
            $lang = 'en';
        }

        session()->put('lang', $lang);
        return redirect()->back();
    }

    public function blog() {
        $blogs     = Frontend::where('data_keys', 'blog.element')->paginate(getPaginate());
        $pageTitle = 'Blog';
        $sections  = Page::where('tempname', $this->activeTemplate)->where('slug', 'blog')->first();
        return view($this->activeTemplate . 'blog', compact('blogs', 'pageTitle', 'sections'));
    }

    public function blogDetails($id, $slug) {
        $blog        = Frontend::where('id', $id)->where('data_keys', 'blog.element')->firstOrFail();
        $pageTitle   = 'Blog Detail';
        $latestBlogs = Frontend::where('id', '!=', $id)->where('data_keys', 'blog.element')->orderBy('id', 'desc')->limit(10)->get();
        return view($this->activeTemplate . 'blog_details', compact('blog', 'pageTitle', 'latestBlogs'));
    }

    public function cookieAccept() {
        session()->put('cookie_accepted', true);
        return response('Cookie accepted successfully');
    }

    public function placeholderImage($size = null) {
        $imgWidth  = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize  = round(($imgWidth - 50) / 8);

        if ($fontSize <= 9) {
            $fontSize = 9;
        }

        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function pageDetails($id, $slug) {
        $page      = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $page->data_values->title;
        return view($this->activeTemplate . 'policy_pages', compact('page', 'pageTitle'));
    }

    public function subscribe(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()]);
        }

        $emailExist = Subscriber::where('email', $request->email)->first();

        if (!$emailExist) {
            $subscribe        = new Subscriber();
            $subscribe->email = $request->email;
            $subscribe->save();
            return response()->json(['success' => 'Subscribed Successfully']);
        } else {
            return response()->json(['error' => 'Already Subscribed']);
        }

    }

    public function commentShow($id) {
        $allComment   = Comment::where('domain_id', $id)->where('parent_id',0)->with(['user', 'replies.user'])->orderBy('id', 'desc')->get();
        $emptyMessage = 'No comments to display yet';
        return view($this->activeTemplate . 'show_comment', compact('emptyMessage', 'allComment'));
    }

    public function sellerProfile($id, $name) {
        $pageTitle    = 'Seller Profile';
        $user         = User::with('domains', 'transactions')->findOrFail($id);
        $emptyMessage = 'No Domain found';
        return view($this->activeTemplate . 'seller.profile', compact('pageTitle', 'emptyMessage', 'user'));
    }
}
