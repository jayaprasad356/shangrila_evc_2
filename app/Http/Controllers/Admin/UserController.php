<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Rap2hpoutre\FastExcel\FastExcel;

class UserController extends Controller
{
    public function index()
    {
        return view('admin-views.user.list');
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $user = User::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('property_type', 'like', "%{$value}%")
                        ->orWhere('bedrooms_count', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $user = new User();
        }

        $users = $user->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.user.list', compact('users', 'search'));
    }

    public function search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $user = User::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('email', 'like', "%{$value}%")
                    ->orWhere('property_type', 'like', "%{$value}%")
                    ->orWhere('wallet', 'like', "%{$value}%");
            }
        })->get();
        return response()->json([
            'view' => view('admin-views.user.partials._table', compact('users'))->render()
        ]);
    }


    // public function preview($id)
    // {
    //     $client = Client::where(['id' => $id])->first();
    //     return view('admin-views.client.view', compact('client'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|unique:clients',
    //         'password' => 'required',
    //         'property_type' => 'required',


    //     ], [
    //         'property_type.required' => translate('Property Type is required!'),
    //         'email.required' => translate('Email Id is required!'),
    //         'password.required' => translate('Password is required!'),

    //     ]);

    //     $id_img_names = [];

    //     $user = new User();
    //     $user->email = $request->email;
    //     $client->place = $request->place;
    //     $client->email = $request->email;
    //     $client->mobile = $request->mobile;
    //     $client->save();

    //     Toastr::success(translate('Client added successfully!'));
    //     return redirect('admin/client/list');
    // }

    // public function edit($id)
    // {
    //     $client = Client::find($id);
    //     return view('admin-views.client.edit', compact('client'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
    //     ], [
    //         'name.required' => translate('First name is required!'),
    //         'mobile.required' => translate('Mobile Number is required!'),
    //         'email.required' => translate('Email Id is required!')
    //     ]);

    //     $client = Client::find($id);

    //     if ($client['email'] != $client['email']) {
    //         $request->validate([
    //             'email' => 'required|unique:clients',
    //         ]);
    //     }

    //     if ($client['mobile'] != $client['mobile']) {
    //         $request->validate([
    //             'mobile' => 'required|unique:clients',
    //         ]);
    //     }

    //     $client->name = $request->name;
    //     $client->email = $request->email;
    //     $client->mobile = $request->mobile;
    //     $client->place = $request->place;
    //     $client->save();

    //     Toastr::success(translate('Client updated successfully!'));
    //     return redirect('admin/client/list');
    // }

    // public function delete(Request $request)
    // {
    //     $client = Client::find($request->id);
    //     $client->delete();
    //     Toastr::success(translate('Client removed Successfully!'));
    //     return back();
    // }
}
