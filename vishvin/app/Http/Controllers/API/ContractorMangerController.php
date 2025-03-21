<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Contractor_inventory;

class ContractorMangerController extends BaseController
{
    public function fetchnewserial(Request $request)
    {
        // Validate incoming payload to check for "serial_no_new"
        $validator = Validator::make($request->all(), [
            'serial_no_new' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

       
        $user = auth()->user();

   
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $contactor_inventory = Contractor_inventory::where('unused_meter_serial_no', 'LIKE', "%{$request->serial_no_new}%")
            ->first();

   
        if ($contactor_inventory) {
        
            return $this->sendResponse($contactor_inventory->toArray(), 'Contractor inventory found.');
        } else {
            // If no matching contractor inventory found, return an error
            return $this->sendError('Serial number not found.', ['error' => 'Serial number mismatch or not found', 'request' => $request->all()]);
        }
    }
}

