<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact_us;
use Illuminate\Http\Request;

class contactUs extends Controller
{
    public function index(){
        $contactUsMessages = Contact_us::orderBy('id', 'desc')->get();
        return view('admins.contact_us.index')->with([
            'contactUsMessages'=> $contactUsMessages,
        ]);
    }

    public function destroy($message_id){
        $message = Contact_us::find($message_id);
        $message->delete();

        return redirect('admins/contact_us')->with('success', trans('admin.success'));
    }
}
