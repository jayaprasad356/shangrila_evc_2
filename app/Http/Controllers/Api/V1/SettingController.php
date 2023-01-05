<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Setting;

class SettingController extends Controller
{
    public function get_settings()
    {
            $settings = Setting::get();
    
            return response()->json([
                    'success' =>true,
                    'message' => 'Settings Details Listed successfully',
                    'data' => $settings,
            ],200);
        }
    
}
