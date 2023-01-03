<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Code;
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

class CodeController extends Controller
{
    public function index()
    {
        return view('admin-views.codes.index');
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $code = Code::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('amount', 'like', "%{$value}%")
                        ->orWhere('evc_code', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $code = new Code();
        }

        $evc_codes = $code->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.codes.list', compact('evc_codes', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required',


        ], [
            'amount.required' => translate('Amount is required!'),

        ]);

        $id_img_names = [];

        $code = new Code();
        $code->amount = $request->amount;
        $number = mt_rand(10000000, 99999999);
        $code->evc_code =sprintf("%08d", $number);
        $code->save();

        Toastr::success(translate('EVC Code Generated successfully!'));
        return redirect('admin/codes/list');
    }

    public function delete(Request $request)
    {
        $evc_codes = Code::find($request->id);
        $evc_codes->delete();
        Toastr::success(translate('Evc Code removed Successfully!'));
        return back();
    }
}
