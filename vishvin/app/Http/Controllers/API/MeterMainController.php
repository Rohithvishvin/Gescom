<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Meter_main;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;


class MeterMainController extends BaseController
{
    //old meter upload controller
    public function upload_old_meter(Request $request)
    {

        $base_folder_name = "../uploads";
        $base_file_path = "uploads/";

        // Get authenticated user
        $user = auth()->user();
    
        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Validate the incoming request to ensure all required fields are present
        $validator = Validator::make($request->all(), [
            'account_id'       => 'required',
            'serial_no_old'    => 'required|string|unique:meter_mains,serial_no_old',
            'mfd_year_old'     => 'required|integer|digits:4',
            'final_reading'    => 'required|numeric',
           // 'image_1_old'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
           // 'image_2_old'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // If validation fails, return error response with validation errors
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }
    
        // Check if the meter record exists for the given account_id
        $meter_mains = Meter_main::where("account_id", $request->account_id)->first();
    
        // If the meter record exists, update the fields
        if ($meter_mains) {
            // Handle image_1_old upload
            if ($request->hasFile('image_1_old') && $request->file('image_1_old')->isValid()) {
                $image_1_extension = $request->file('image_1_old')->getClientOriginalExtension();
                $image_1_name = 'image1-' . time() . '.' . $image_1_extension;

                $image_large = Image::read($request->image_1_old);

                $image_large->save(public_path($image_1_name));

                $saved_image_uri = public_path($image_1_name);

                //Storage::putFileAs('uploads/', new File($saved_image_uri), $image_1_name);

                //Storage::disk('vishin_uploads')->put($image_1_name, new File($saved_image_uri));
                $request->image_1_old->storeAs($base_file_path, $image_1_name);
    
                // Update the meter record with the image path
                $meter_mains->image_1_old = 'uploads/' . $image_1_name;
            }
    
            // Handle image_2_old upload
            if ($request->hasFile('image_2_old') && $request->file('image_2_old')->isValid()) {
                $image_2_extension = $request->file('image_2_old')->getClientOriginalExtension();
                $image_2_name = 'image2-' . time() . '.' . $image_2_extension;

                $image_large = Image::read($request->image_2_old);

                $image_large->save(public_path($image_2_name));

                $saved_image_uri = public_path($image_2_name);

                //Storage::putFileAs($base_folder_name.$base_file_path, new File($saved_image_uri), $image_2_name);

                Storage::disk('vishin_uploads')->put($image_2_name, new File($saved_image_uri));
    
                // Update the meter record with the image path
                $meter_mains->image_2_old = 'uploads/' . $image_2_name;
            }
    
            // Update other fields
            $meter_mains->serial_no_old = $request->serial_no_old;
            $meter_mains->mfd_year_old = $request->mfd_year_old;
            $meter_mains->final_reading = $request->final_reading;
    
            // Save the updated meter record
            $meter_mains->save();
    
            // Return success response
            return $this->sendResponse($meter_mains, 'Old meter data uploaded successfully');
        } else {
            // If no record is found for the provided account_id, return error
            return $this->sendError('Meter record not found.', [
                'error' => 'No record found for the given account ID',
                'request' => $request->all(),
            ]);
        }
    }
    


    public function upload_new_meter(Request $request)
{
    // Get authenticated user
    $user = auth()->user();

    // Check if the user is authenticated
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Validate the incoming request to ensure all required fields are present
    $validator = Validator::make($request->all(), [
       'account_id'       => 'required|integer', // Make sure the 'account_id' is included in the request and is an integer
        //'image_1_new'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
       // 'image_2_new'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'meter_make_new'   => 'required|integer',
        'serial_no_new'    => 'required|string|unique:meter_mains,serial_no_new',
        'mfd_year_new'     => 'required|integer|digits:4',
        'lat'              => 'required|numeric',
        'lon'              => 'required|numeric',
    ]);

    // If validation fails, return error response with validation errors
    if ($validator->fails()) {
        
        return $this->sendError('Validation error', $validator->errors());
    }

    // Check if the meter record exists for the given account_id
    $meter_mains = Meter_main::where("account_id", $request->account_id)->first();

    // If the meter record exists, update the fields
    if ($meter_mains) {
        // Handle image_1_new upload
        if ($request->hasFile('image_1_new') && $request->file('image_1_new')->isValid()) {
            $image_1_extension = $request->file('image_1_new')->getClientOriginalExtension();
            $image_1_name = 'image_1_' . time() . '.' . $image_1_extension;

            Storage::disk('uploads')->put($image_1_name, file_get_contents($request->file('image_1_new')));

            // Update the meter record with the image path
            $meter_mains->image_1_new = 'uploads/' . $image_1_name;
        }

        // Handle image_2_new upload
        if ($request->hasFile('image_2_new') && $request->file('image_2_new')->isValid()) {
            $image_2_extension = $request->file('image_2_new')->getClientOriginalExtension();
            $image_2_name = 'image_2_' . time() . '.' . $image_2_extension;

            Storage::disk('uploads')->put($image_2_name, file_get_contents($request->file('image_2_new')));

            // Update the meter record with the image path
            $meter_mains->image_2_new = 'uploads/' . $image_2_name;
        }

        // Update other fields
        $meter_mains->meter_make_new = $request->meter_make_new;
        $meter_mains->mfd_year_new = $request->mfd_year_new;
        $meter_mains->serial_no_new = $request->serial_no_new;
        $meter_mains->lat = $request->lat;
        $meter_mains->lon = $request->lon;

        $meter_mains->save();

        // Return success response
        return $this->sendResponse($meter_mains, 'New meter data uploaded successfully');
    } else {
        // If no record is found for the provided account_id, return error
        return $this->sendError('Meter record not found.', [
            'error' => 'No record found for the given account ID',
            'request' => $request->all(),
        ]);
    }
}

    
}