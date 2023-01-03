<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Setting;
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

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin-views.settings.edit');
    }


    public function edit()
    {
        $setting = Setting::find(1);
        return view('admin-views.settings.edit', compact('setting'));
    }

    public function update(Request $request){
        $request->validate([
            'day_electricity_meter_reading' => 'required',
            'night_electricity_meter_reading' => 'required',
            'day_gas_meter_reading' => 'required',
        ], [
            'day_electricity_meter_reading.required' => translate('Day Electricity Meter Reading is required!'),
            'night_electricity_meter_reading.required' => translate('Night Electricity Meter Reading is required!'),
            'day_gas_meter_reading.required' => translate('Gas Meter Reading is required!')
        ]);

        $settings = Setting::find(1);

        $settings->day_electricity_meter_reading = $request->day_electricity_meter_reading;
        $settings->night_electricity_meter_reading = $request->night_electricity_meter_reading;
        $settings->day_gas_meter_reading = $request->day_gas_meter_reading;
        $settings->save();

        Toastr::success(translate('Settings updated successfully!'));
        return redirect('admin/settings/edit');
    }
}
