<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Code;
use App\Model\Bill;
use App\Model\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class ApiController extends Controller
{
        public function Signup(Request $request) {
     
                $password = $request->input('password');
                $email = $request->input('email');
                $property_type = $request->input('property_type');
                $bedrooms_count = $request->input('bedrooms_count');
                $evc_code = $request->input('evc_code');
        
                if(empty($email)){
                        return response()->json([
                            'success'=>false,
                            'message' => 'Email Id is Empty',
                        ], 200);
                }
                if(empty($password)){
                    return response()->json([
                        'success'=>false,
                        'message' => 'Password is Empty',
                    ], 200);
                }
                if(empty($property_type)){
                    return response()->json([
                        'success'=>false,
                        'message' => 'Type Of Property is Empty',
                    ], 200);
                }
                if(empty($bedrooms_count)){
                  return response()->json([
                      'success'=>false,
                      'message' => 'Bedrooms Count is Empty',
                  ], 200);
                }
                if(empty($evc_code)){
                    return response()->json([
                        'success'=>false,
                        'message' => 'EVC Code is Empty',
                    ], 200);
                }
        
                // Check if a user with the given email address exists in the database
                    $userExists = User::where('email', $request->input('email'))->exists();
                    if ($userExists) {
                        return response()->json([
                           "success" => false ,
                            'message' => 'Email Id is Already exists'
                        ], 400);
                    }
                    $evc_codes=Code::where('evc_code',$evc_code)->get();
                    if(count($evc_codes)==0){
                        return response()->json([
                                "success" => false ,
                                 'message' => 'EVC Code is Invalid'
                             ], 400);
                    }
                    $wallet=$evc_codes[0]['amount'];
                    $user = new User;
                    $user->email = $request->email;
                    $user->password = $request->password;
                    $user->property_type = $request->property_type;
                    $user->bedrooms_count = $request->bedrooms_count;
                    $user->wallet = $wallet;
                    $user->save();
                
                    return response()->json([
                      "success" => true ,
                      'message'=> "Registered Successfully",
                      'data' =>$user,
                    ], 201);
        }
       
        public function Recharge(Request $request) {
     
                $user_id = $request->input('user_id');
                $evc_code = $request->input('evc_code');
        
                if(empty($user_id)){
                        return response()->json([
                            'success'=>false,
                            'message' => 'User Id is Empty',
                        ], 200);
                }
                if(empty($evc_code)){
                    return response()->json([
                        'success'=>false,
                        'message' => 'EVC Code is Empty',
                    ], 200);
                }
        
                    $codes = Code::where('evc_code', $request->input('evc_code'))->get();
                    if (count($codes)==1) {
                        $amount=$codes[0]['amount'];
                        User::where('id', $user_id)
                        ->update(['wallet' => DB::raw('wallet + '.$amount)]);
                        $user=User::where('id',$user_id)->get();
                        return response()->json([
                           "success" => true ,
                            'message' => 'Wallet Recharged Successfully',
                            'data' =>$user,
                        ], 201);
                    }
                    else{
                        return response()->json([
                                "success" => false ,
                                'message'=> "Code Not Found",
                              ], 400);
                    }
        }


        public function Calculatebill(Request $request) {
     
                $user_id = $request->input('user_id');
                $emr_day = $request->input('emr_day',0);
                $emr_night = $request->input('emr_night',0);
                $gmr = $request->input('gmr',0);

                if(empty($user_id)){
                        return response()->json([
                            'success'=>false,
                            'message' => 'User Id is Empty',
                        ], 200);
                }
        
                $settings = Setting::where('id',1)->get();
                if (count($settings)==1) {
                        $day_emr_price = $settings[0]['day_electricity_meter_reading'];
                        $night_emr_price = $settings[0]['night_electricity_meter_reading'];
                        $day_gmr_price = $settings[0]['day_gas_meter_reading'];
                    
                    $bills=Bill::where('user_id',$user_id)->orderBy('id','desc')->take(1)->get();
                    if(count($bills)>=1){
                        $emr_day_bill = $bills[0]['emr_day'];
                        $emr_night_bill = $bills[0]['emr_night'];
                        $gmr_bill = $bills[0]['gmr'];
                        if($emr_day !=0 && $emr_day <= $emr_day_bill){
                                return response()->json([
                                        'success'=>false,
                                        'message'=> 'Day Electricity Meter Reading Low',
                                    ], 401);
                                return false;
                        }
                        if($emr_night !=0 && $emr_night <= $emr_night_bill){
                                return response()->json([
                                        'success'=>false,
                                        'message'=> 'Night Electricity Meter Reading Low',
                                    ], 401);
                                return false;
                        }
                        if($day_gmr_price !=0 && $day_gmr_price <= $gmr_bill){
                                return response()->json([
                                        'success'=>false,
                                        'message'=> 'Gas Meter Reading Low',
                                    ], 401);
                                return false;
                        }
                        $emr_day = $emr_day - $emr_day_bill;
                        $emr_night = $emr_night - $emr_night_bill;
                        $gmr = $gmr - $gmr_bill;
                    }
                    $day_emr=$day_emr_price*$emr_day;
                    $night_emr=$night_emr_price*$emr_night;
                    $day_gmr=$day_gmr_price*$gmr;
                    $total=$day_emr+ $night_emr+ $day_gmr;

                    return response()->json([
                        'success'=>true,
                        'message'=> 'Bill Calculated Successfully',
                        'total_amount'=> $total,

                    ], 200);
            }
            else{
                    return response()->json([
                            "success" => false ,
                            'message'=> "Unit Rate Not Found",
                            ], 400);
            }
        }


        public function Paybill(Request $request) {
     
            $user_id = $request->input('user_id');
            $date = $request->input('date');
            $emr_day = $request->input('emr_day',0);
            $emr_night = $request->input('emr_night',0);
            $gmr = $request->input('gmr',0);
            $total = $request->input('total');
    
            if(empty($user_id)){
                    return response()->json([
                        'success'=>false,
                        'message' => 'User Id is Empty',
                    ], 200);
            }
            if(empty($date)){
                return response()->json([
                    'success'=>false,
                    'message' => 'Date is Empty',
                ], 200);
            }
            if(empty($total)){
                return response()->json([
                    'success'=>false,
                    'message' => 'Total is Empty',
                ], 200);
            }
    
            $user = User::where('id', $user_id)->get();
            if ($total>= $user[0]['wallet']) {
                return response()->json([
                    "success" => false ,
                    'message' => 'Your Wallet Balance is Low',
                ], 401);
            }
            else{
                $bill = new Bill;
                $bill->user_id = $request->user_id;
                $bill->date = $request->date;
                $bill->emr_day = $request->emr_day;
                $bill->emr_night = $request->emr_night;
                $bill->gmr =$request->gmr;
                $bill->total = $request->total;
                $bill->save();
                User::where('id', $user_id)->update(['wallet' => DB::raw('wallet - '.$total)]);
                $user=User::where('id',$user_id)->get();
                return response()->json([
                        "success" => true ,
                        'message'=> "Bill Paid Successfully",
                        'data'=> $user,
                        ], 400);
            }
        }


        
}
