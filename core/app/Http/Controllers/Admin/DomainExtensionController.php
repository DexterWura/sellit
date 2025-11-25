<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainExtension;
use Illuminate\Http\Request;

class DomainExtensionController extends Controller {

    public function index(Request $request) {

        $pageTitle    = 'All Domain Extensions';
        $emptyMessage = 'No extensions found';
        $extensions   = DomainExtension::query();

        if ($request->search) {
            $extensions->where('name', 'LIKE', "%$request->search%");
        }

        $extensions = $extensions->latest()->paginate(getPaginate());
        return view('admin.domain_extension.index', compact('pageTitle', 'emptyMessage', 'extensions'));
    }

    public function store(Request $request, $id = 0) {
        $request->validate([
            'name' => 'required|unique:domain_extensions,name,' . $id,
        ]);

        if ($id) {
            $extension         = DomainExtension::findOrFail($request->id);
            $extension->status = $request->status ? 1 : 0;
            $notification      = 'Extension updated successfully.';
        } else {
            $extension    = new DomainExtension();
            $notification = 'Extension added successfully.';
        }

        $extension->name = $request->name;
        $extension->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

}
