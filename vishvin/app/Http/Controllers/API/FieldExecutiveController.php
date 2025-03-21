<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Consumer_detail;

class FieldExecutiveController extends BaseController
{
    public function upadteConsumer(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        if($user){
            $consumer = Consumer_detail::where("account_id", $request->account_id)->first();
            $consumer->consumer_name= $request->name;
            $consumer->save();
            return $this->sendResponse($consumer, 'User login successfully.');

        }else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised', 'request'=> $request->all()]);
        }
    }
}