<?php

namespace App\Http\Controllers;
// Import the Image class at the top of your file
use App\Http\Requests\UpdateOldMeterDetailRequest;
use App\Http\Services\ConsumerDetailService;
use App\Http\Services\MeterMainService;
use App\Models\Admin;
use App\Models\Meter_final_reading;
use App\Models\Meter_main;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Consumer_detail;
use App\Models\Contractor_inventory;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{
    // known functionality codes
    public function index()
    {
        return view('pages.index');
    }

    public function login()
    {
        if (session()->get('rexkod_pages_id')) {
            return redirect('pages/home');
        } else {
            return view('pages.login');
        }
    }

    public function authenticate(Request $req)
    {
        // return($req->all());
        $user = Admin::where('phone', $req->phone)->first();
        // return($req->all());
        if ($user && Hash::check($req->password, $user->password) && $user->type == "field_executive") {
            Session::put('rexkod_pages_name', $user->name);
            Session::put('rexkod_pages_id', $user->id);
            Session::put('rexkod_pages_user_phone', $user->phone);
            Session::put('rexkod_pages_uesr_type', $user->type);
            return redirect('pages/home');
        } else {
            session()->put('failed', 'Invalid credentials');
            return redirect('/pages');
        }
    }

    public function home(Request $request, MeterMainService $meter_main_service)
    {
        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/pages')->with('message', 'You have been logged out!');
        }

        $fieldExecutiveId = session()->get('rexkod_pages_id');

        $meter_main = $meter_main_service->getMeterMainsCreatedByFieldExecutiveId($fieldExecutiveId);

        $lat = session()->get('user_lat');
        $lon = session()->get('user_lon');
        $city = session()->get('user_city');

        $data = [
            'meter_main' => $meter_main,
            'lat' => $lat,
            'lon' => $lon,
            'city' => $city,
        ];

       // dd($data);

        return view('pages.home', compact('data'));
    }

    public function check_rr_number(Request $req, ConsumerDetailService $consumer_detail_service, MeterMainService $meter_main_service)
    {
        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/pages')->with('message', 'You have been logged out!');
        }
        else{
            // dd($req->account_id);
            // delete_flag
            // the pages has been provided with edit page too, in future they can edit the meter reading page also.
            // first case: its present in consumer_detail

            // consumer detail can be search by either accoount_id or rr_number -- added by ashutosh
            if ($req->account_id) {
                $check_consumer_detail_filter_data = array(
                    "account_id" => $req->account_id,
                );
                $check_consumer_detail_column_list_data = array("account_id");
                $check_consumer_detail = $consumer_detail_service->getSingleConsumerDetailsByFilter($check_consumer_detail_filter_data, $check_consumer_detail_column_list_data);
                if (empty($check_consumer_detail)) {
                    session()->put('failed', 'Account ID Doesnt Exist in Consumer Detail');
                    return redirect('/pages/add_meter_first_step');
                }

                $consumer_detail_filter_data = array(
                    "account_id" => $req->account_id,
                    "so_pincode" => $req->section_code
                );
                $consumer_detail_column_list_data = array("account_id");
                $consumer_detail = $consumer_detail_service->getSingleConsumerDetailsByFilter($consumer_detail_filter_data, $consumer_detail_column_list_data);
                //dd($consumer_detail);

                if ($consumer_detail) {
                    $meter_main_filter_data = array(
                        "account_id" => $consumer_detail->account_id,
                    );
                    $meter_main = $meter_main_service->getSingleMeterMainsByFilter($meter_main_filter_data);
                } else {
                    $meter_main = null;
                }
            } else if ($req->rr_number) {
                $check_consumer_detail_filter_data = array(
                    "rr_no" => $req->rr_number,
                );
                $check_consumer_detail_column_list_data = array("account_id");
                $check_consumer_detail = $consumer_detail_service->getSingleConsumerDetailsByFilter($check_consumer_detail_filter_data, $check_consumer_detail_column_list_data);
                if (empty($check_consumer_detail)) {
                    session()->put('failed', 'RR Number Doesnt Exist in Consumer Detail');
                    return redirect('/pages/add_meter_first_step');
                }

                $consumer_detail_filter_data = array(
                    "rr_no" => $req->rr_number,
                    "so_pincode" => $req->section_code
                );
                $consumer_detail_column_list_data = array("account_id");
                $consumer_detail = $consumer_detail_service->getSingleConsumerDetailsByFilter($consumer_detail_filter_data, $consumer_detail_column_list_data);
                //dd($consumer_detail);

                if ($consumer_detail) {
                    $meter_main_filter_data = array(
                        "account_id" => $consumer_detail->account_id,
                    );
                    $meter_main = $meter_main_service->getSingleMeterMainsByFilter($meter_main_filter_data);
                } else {
                    $meter_main = null;
                }
            }


            // $consumer_detail = Consumer_detail::where('account_id', $req->account_id)->first();
            // $meter_main = Meter_main::where('account_id', $req->account_id)->first();
            // $count = 0;
            // foreach ($meter_main as $individual_meter_main) {
            //     $count++;
            // }


            if (!($consumer_detail)) {
                if ($req->rr_number) session()->put('failed', 'RR number and Section Code Doesnt Match');
                if ($req->account_id) session()->put('failed', 'Account ID and Section Code Doesnt Match');
                return redirect('/pages/add_meter_first_step');
            }
            if ($meter_main) {
                $meter_main_id = $meter_main->id;

                // there is one scenario
                // consider this has just relocated, so it reach to qc_vishvin already
                // so he can again approve
                // give allocate_flag or something to control that scenario
                if ((($meter_main->qc_status == 0) && ($meter_main->so_status == 0) && ($meter_main->aee_status == 0) && ($meter_main->aao_status == 0) && ($meter_main->allocation_flag == 1)) || ($meter_main->serial_no_new == Null)) {
                    $save_column_list_data = array(
                        "allocation_flag" => 0,
                        "created_by" => session()->get('rexkod_pages_id'),
                        "create_at" => date('Y-m-d H:m:s')
                    );
                    //dd($meter_main->id);
                    $meter_main = $meter_main_service->updateMeterMainData($meter_main->id, $save_column_list_data);
//                $meter_main-> = 0;
//                $meter_main->save();

                    return redirect('/pages/add_old_meter_detail/' . $meter_main->id);
                } elseif ((($meter_main->qc_status == 1) && ($meter_main->so_status == 1) && ($meter_main->aee_status == 1) && ($meter_main->aao_status == 1))) {
                    session()->put('success', 'Meter already approved');
                    return redirect('/pages/add_meter_first_step');
                } else {
                    session()->put('failed', 'Meter status under progress');
                    return redirect('/pages/add_meter_first_step');
                }
            } else {

                $save_column_list_data = array(
                    "account_id" => $consumer_detail->account_id,
                    "created_by" => session()->get('rexkod_pages_id')
                );
                $meter_main = $meter_main_service->saveMeterMainData($save_column_list_data);
//            $Meter_main = new Meter_main();
//            // $Meter_main->account_id = $req->account_id;
//            $Meter_main->account_id = $consumer_detail->account_id;
//
//            $Meter_main->created_by = session()->get('rexkod_pages_id');
//
//            $Meter_main->save();
//            $Meter_main->id;

                // session()->put('success', 'Please fill the old Electromechanical meter details');
                // $req->session()->flash('alert-success', 'Group created successfully');

                return redirect('/pages/add_old_meter_detail/' . $meter_main->id);

            }
            // if ($count < 2) {
            //     if (($meter_main->delete_flag == 1) && $consumer_detail) {
            //         $meter_main_id = $meter_main->id;
            //         return redirect('/pages/add_old_meter_detail/' . $meter_main_id);
            //     } elseif (($meter_main->delete_flag == 0) && $consumer_detail) {
            //         session()->put('failed', 'Either Account Number Invalid or already in process');
            //         return redirect('/pages/add_meter_first_step');
            //     } elseif ($consumer_detail) {
            //         $Meter_main = new Meter_main();
            //         $Meter_main->account_id = $req->account_id;
            //         $Meter_main->created_by = session()->get('rexkod_pages_id');

            //         $Meter_main->save();
            //         $Meter_main->id;

            //         session()->put('success', 'Please fill the old Electromechanical meter details');
            //         return redirect('/pages/add_old_meter_detail/' . $Meter_main->id);
            //     } else {
            //         session()->put('failed', 'Either Account Number Invalid or already in process');
            //         return redirect('/pages/add_meter_first_step');
            //     }
            // } else {
            //     $check_active_meter_reading = 0;
            //     foreach ($meter_main as $individual_meter_main) {
            //         if ($individual_meter_main->delete_flag == 0) {
            //             $check_active_meter_reading = 1;
            //         }
            //     }
            //     if ($check_active_meter_reading == 0) {
            //         $Meter_main = new Meter_main();
            //         $Meter_main->account_id = $req->account_id;
            //         $Meter_main->created_by = session()->get('rexkod_pages_id');

            //         $Meter_main->save();
            //         $Meter_main->id;

            //         session()->put('failed', 'Please fill the old Electromechanical meter details');
            //         return redirect('/pages/add_old_meter_detail/' . $Meter_main->id);
            //     } else {
            //         session()->put('failed', 'Either Account Number Invalid or already in process');
            //         return redirect('/pages/add_meter_first_step');
            //     }
            // }
        }
    }

    public function add_meter_first_step()
    {
        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/pages')->with('message', 'You have been logged out!');
        }
        $consumer_detail_service = new ConsumerDetailService();
        $so_pin_codes = $consumer_detail_service->getDistinctSoPincode();
//        $so_pin_codes = DB::table('zone_codes')->orderBy('so_code')->where('package', '=', 'BVU')->get();
//        $so_pin_codes = DB::table('consumer_details')->distinct('so_pincode')->select('so_pincode as so_code')->orderBy('so_pincode')->get();
        return view('pages.add_meter_first_step', ['so_pincodes' => $so_pin_codes]);
    }

    public function add_old_meter_detail($id, MeterMainService $meter_main_service, ConsumerDetailService $consumer_detail_service)
    {

        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/pages')->with('message', 'You have been logged out!');
        }

        $meter_main = $meter_main_service->getMeterMainsById($id);

        $get_consumer_detail = $consumer_detail_service->getConsumerDetailByAccountId($meter_main->account_id);

        $meter_previous_final_reading = Meter_final_reading::where('account_id', $meter_main->account_id)->first();

        $data = [
            'meter_main' => $meter_main,
            'get_consumer_detail' => $get_consumer_detail,
            'meter_previous_final_reading' => $meter_previous_final_reading,
            'id' => $id,
        ];
        //dd($data);

        return view('pages.add_old_meter_detail', ['data' => $data]);
    }

    public function update_old_meter_detail($id, Request $request, MeterMainService $meter_main_service): RedirectResponse
    {
        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/pages')->with('message', 'You have been logged out!');
        }
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            "meter_make_old" => 'required',
            "serial_no_old" => 'required|numeric',
            "mfd_year_old" => 'required|numeric',
            "final_reading" => 'required|numeric',
            "image_1_old" => 'required', // File::types(['png', 'jpeg', 'jpg']
            "image_2_old" => 'required', // 'jpg'
        ]);

        if ($validator->fails()) {
//            dd($validator);
            $errors = $validator->errors();
            //dd($errors);
//            $error_messages = array();
//            foreach($errors->messages as $messageKey=>$messageValue){
//                dd($messageValue);
//                Session::put(['errors', [$messageKey => $messageValue]]);
//            }
            Session::put('input data validation check failed', $errors);
            return redirect()->back()//->with(['errors' => $errors]);
            ->withErrors($validator)
                ->withInput();
            //->with('data', $errors);
        }
        //
        //$data = $request->validated();
        $data = $validator->validated();

        //$errors = $data->errors();
        //dd($data, $errors);

        // first case: its present in consumer_detail
        // dd($id);
        $save_column_list_data = array(
            "meter_make_old" => $data['meter_make_old'],
            "serial_no_old" => $data['serial_no_old'],
            "mfd_year_old" => $data['mfd_year_old'],
            "final_reading" => $data['final_reading'],
        );
        //dd($meter_main->id);

        $meter_main = $meter_main_service->getMeterMainsById($id);

        //$meter_main->meter_make_old = $req->meter_make_old;
        //$meter_main->serial_no_old = $req->serial_no_old;
        //$meter_main->mfd_year_old = $req->mfd_year_old;
        //$meter_main->final_reading = $req->final_reading;
        // if ($req->hasFile('image_1_old')) {
        //     $image = $req->file('image_1_old');
        //     $name = time().'.'.$image->getClientOriginalExtension();
        //     $destinationPath = public_path('/uploads');
        //    $meter_main->image_1_old= $image->move($destinationPath, $name);

        // }


        if (!empty($data['image_1_old']) && empty($meter_main->image_1_old)) {
            $file = $data['image_1_old'];
            // $mime_type = $file->getClientMimeType();
            $extension = $file->getClientOriginalExtension();
            // if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
            if (($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                // $filename = Str::random(4) . time() . '.' . $extension;

                // giving the image name as account id
                $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;

                //$meter_main->image_1_old = $file->move(('uploads'), $filename);
                $save_column_list_data['image_1_old'] = $file->move(('uploads'), $filename);
                //dd($meter_main);
                //ImageCompressionController::compress_image($meter_main->image_1_old);
            } else {
                session()->put('failed', 'Only JPEG and PNG images are allowed.');
                return redirect('/pages/add_old_meter_detail/' . $id);
            }
        }


        if (!empty($data['image_2_old']) && empty($meter_main->image_2_old)) {
            $file = $data['image_2_old'];
            // $mime_type = $file->getClientMimeType();
            $extension = $file->getClientOriginalExtension();
            // if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
            if (($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                // $filename = Str::random(4) . time() . '.' . $extension;

                // giving the image name as account id
                $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;
                //$meter_main->image_2_old= $file->move(('uploads'), $filename);
                $save_column_list_data['image_2_old'] = $file->move(('uploads'), $filename);
                //ImageCompressionController::compress_image($meter_main->image_2_old);
            } else {
                session()->put('failed', 'Only JPEG and PNG images are allowed.');
                return redirect('/pages/add_old_meter_detail/' . $id);
            }
        }


        if (!empty($data['image_3_old'])) {
            $file = $data['image_3_old'];
            // $mime_type = $file->getClientMimeType();
            $extension = $file->getClientOriginalExtension();
            // if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
            if (($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                // $filename = Str::random(4) . time() . '.' . $extension;

                // giving the image name as account id
                $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;
                //$meter_main->image_3_old= $file->move(('uploads'), $filename);
                $save_column_list_data['image_3_old'] = $file->move(('uploads'), $filename);
                //ImageCompressionController::compress_image($meter_main->image_3_old);
            } else {
                session()->put('failed', 'Only JPEG and PNG images are allowed.');
                return redirect('/pages/add_old_meter_detail/' . $id);
            }
        }


        $meter_main = $meter_main_service->updateMeterMainData($id, $save_column_list_data);
        //$meter_main->save();
        Session::put('meter_main_id', $id);

        return redirect('/pages/load_current_location');
    }

    public function load_current_location()
    {
        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/pages')->with('message', 'You have been logged out!');
        }
        return view('pages.load_current_location');
    }

    public function current_location_fetch($lat, $lon)
    {
        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/pages')->with('message', 'You have been logged out!');
        }
        if ($lat !== null && $lon !== null) {
            Session::put('rexkod_pages_lat', $lat);
            Session::put('rexkod_pages_lon', $lon);

            return redirect('/pages/add_new_meter_detail/' . session()->get('meter_main_id'));
        } else {
            session()->put('failed', 'Please turn on your mobile location and try again !!');
            return redirect('/pages/home');
        }
    }

    public function add_new_meter_detail($id, MeterMainService $meter_main_service, ConsumerDetailService $consumer_detail_service)
    {
        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/pages')->with('message', 'You have been logged out!');
        }
        $meter_main = $meter_main_service->getMeterMainsById($id);

        $get_consumer_detail = $consumer_detail_service->getConsumerDetailByAccountId($meter_main->account_id);

        $data = [
            'meter_main' => $meter_main,
            'get_consumer_detail' => $get_consumer_detail,
            'id' => $id,
        ];

        return view('pages.add_new_meter_detail', ['data' => $data]);
    }

    public function update_new_meter_detail($id, Request $request, MeterMainService $meter_main_service)
    {
        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/pages')->with('message', 'You have been logged out!');
        }
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            "image_1_new" => 'required', // File::types(['png', 'jpeg', 'jpg']
            "image_2_new" => 'required', // 'jpg'
            "serial_no_new" => 'required|numeric',
            "mfd_year_new" => 'required',
            "initial_reading_kwh" => 'required',
            "initial_reading_kvah" => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            //dd($errors);
            Session::put('input data validation check failed', $errors);
            return redirect()->back()//->with(['errors' => $errors]);
            ->withErrors($validator)
                ->withInput();
        }
        //
        //$data = $request->validated();
        $data = $validator->validated();

        $save_column_list_data = array(
            "serial_no_new" => $data['serial_no_new'],
            "mfd_year_new" => $data['mfd_year_new'],
            "initial_reading_kwh" => $data['initial_reading_kwh'],
            "initial_reading_kvah" => $data['initial_reading_kvah'],
            "lat" => session()->get('rexkod_pages_lat'),
            "lon" => session()->get('rexkod_pages_lon'),
            "created_at" => now()
        );

        //$errors = $data->errors();
        //dd($data, $errors);
        if (session()->get('rexkod_pages_id') != Null) {
            // first case: its present in consumer_detail
            // dd($id);
            $meter_serial_no = $request->serial_no_new;

            $get_field_executive_contractor = Admin::where('id', session('rexkod_pages_id'))->first();
            //   print_r($get_field_executive_contractor);
            $contractor_inventories = Contractor_inventory::where('contractor_id', $get_field_executive_contractor->created_by)->get();


            foreach ($contractor_inventories as $contractor_inventory) {
                $individual_inventory = $contractor_inventory->unused_meter_serial_no;
                // dd($individual_inventory);
                $individual_serial_nos = explode(",", $individual_inventory);
                foreach ($individual_serial_nos as $individual_serial_no) {
                    // dd($individual_serial_no);
                    if ($individual_serial_no == $meter_serial_no) {
                        $current_inventory_id = $contractor_inventory->id;
                    }
                }
            }
            // dd($current_inventory_id);
            if (isset($current_inventory_id)) {
                // ****************
                $existingInventory = Contractor_inventory::where('id', $current_inventory_id)->first();


                $unused_meter_serial_no = explode(',', $existingInventory->unused_meter_serial_no);
                $used_meter_serial_no = explode(',', $existingInventory->used_meter_serial_no);

                $input_values = $meter_serial_no; // assume the checkbox values are submitted as an array
                // dd($input_values);
                // Remove the input values from unused data and add them to used data
                if (!$input_values) {
                    session()->put('failed', 'Enter a assigned meter serial no.');
                    //return redirect('/pages/add_new_meter_det');
                    return redirect('/pages/add_new_meter_detail/' . $id);
                }

                $key = array_search($input_values, $unused_meter_serial_no);
                if ($key !== false) {
                    unset($unused_meter_serial_no[$key]);
                    $used_meter_serial_no[] = $input_values;
                }


                $existingInventory->unused_meter_serial_no = implode(',', $unused_meter_serial_no);
                if (empty($existingInventory->unused_meter_serial_no)) {
                    $existingInventory->unused_meter_serial_no = null;
                }
                $existingInventory->used_meter_serial_no = implode(',', $used_meter_serial_no);
                $existingInventory->used_meter_serial_no = ltrim($existingInventory->used_meter_serial_no, ',');
                $existingInventory->save();


                $Inventory = new Inventory();


                $Inventory->serial_no = $meter_serial_no;
                $Inventory->created_by = session()->get('rexkod_pages_id');

                $Inventory->save();
            }

            $meter_main = $meter_main_service->getMeterMainsById($id);
//            $meter_main = Meter_main::find($id);
//            $meter_main = new meter_main;
//            $meter_main->meter_make_new = $req->meter_make_new;
//            $meter_main->serial_no_new = $data['serial_no_new'];
//            $meter_main->mfd_year_new = $req->mfd_year_new;
//            $meter_main->initial_reading_kwh = $req->initial_reading_kwh;
//            $meter_main->initial_reading_kvah = $req->initial_reading_kvah;


            if (!empty($request->file('image_1_new')) && empty($meter_main->image_1_new)) {
                //$file = $req->file('image_1_new');
                $file = $data['image_1_new'];
                $mime_type = $file->getClientMimeType();
                $extension = $file->getClientOriginalExtension();
                //if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                if (($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                    // $filename = Str::random(4) . time() . '.' . $extension;

                    // giving the image name as account id
                    $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;

                    //$meter_main->image_1_new = $file->move(('uploads'), $filename);
                    $save_column_list_data['image_1_new'] = $file->move(('uploads'), $filename);
                    //ImageCompressionController::compress_image($meter_main->image_1_new);
                } else {
                    session()->put('failed', 'Only JPEG and PNG images are allowed.');
                    return redirect('/pages/add_new_meter_detail/' . $id);
                }
            }

            if (!empty($request->file('image_2_new')) && empty($meter_main->image_2_new)) {
                //$file = $req->file('image_2_new');
                $file = $data['image_2_new'];
                $mime_type = $file->getClientMimeType();
                $extension = $file->getClientOriginalExtension();
                //if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                if (($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                    // $filename = Str::random(4) . time() . '.' . $extension;

                    // giving the image name as account id
                    $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;
                    //$meter_main->image_2_new = $file->move(('uploads'), $filename);
                    $save_column_list_data['image_2_new'] = $file->move(('uploads'), $filename);
                    //ImageCompressionController::compress_image($meter_main->image_2_new);
                } else {
                    session()->put('failed', 'Only JPEG and PNG images are allowed.');
                    return redirect('/pages/add_new_meter_detail/' . $id);
                }
            }
            //$meter_main->created_at = now();
            //$meter_main->lat = session()->get('rexkod_pages_lat');
            //$meter_main->lon = session()->get('rexkod_pages_lon');
            //$meter_main->save();

            $save_column_list_data['created_by'] = session()->get('rexkod_pages_id');
            $save_column_list_data['create_at'] = date('Y-m-d H:i:s');

            $meter_main = $meter_main_service->updateMeterMainData($id, $save_column_list_data);


            // session()->put('success', 'Congrats! The new meter and old meter has been stored successfully.');

            // $user = Admin::where('user_name', $req->user_email)->first();
            // $req->session()->put('user',$user);

            return redirect('/pages/home');

            // second case: this is present in meter_mains

            return ($req);
        } else {
            return redirect('/pages')->with('message', 'Session Time Out!');
        }
    }

    public function account()
    {
        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/pages')->with('message', 'You have been logged out!');
        }
        return view('pages.account');
    }

    public function logout(Request $request)
    {
        Session::forget('rexkod_pages_id');
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::flush();

        return redirect('/pages')->with('message', 'You have been logged out!');
    }

    // unknown codes
    public function location_fetch($lat, $lon)
    {
        Session::put('rexkod_pages_lat', $lat);
        Session::put('rexkod_pages_lon', $lon);

        return redirect('pages/index');
        // return view('pages.location_fetch');
    }

    public function load_location()
    {
        return view('pages.load_location');
    }

    public function add2()
    {
        return view('pages.add2');
    }

    public function records()
    {
        // $meter_main = Meter_main::where('delete_flag', 1)->get();
        // return view('pages.records', ['meter_main' => $meter_main]);
        return view('pages.records');
    }

    public function records2()
    {
        return view('pages.records2');
    }

    public function location()
    {
        return view('pages.location');
    }

    public function login2()
    {
        return view('pages.login2');
    }

    // public function storeUserLocation(Request $request)
    // {

    //     $request->session()->put('user_lat', $request->lat);
    //     $request->session()->put('user_lon', $request->lon);
    //     $request->session()->put('user_city', $request->city);

    // return response()->json(['success' => true]);
    // }
    public function storeUserLocation(Request $request)
    {
        $lat = $request->lat;
        $lon = $request->lon;
        $city = $request->city;

        session(['user_lat' => $lat]);
        session(['user_lon' => $lon]);
        session(['user_city' => $city]);

        return response()->json(['success' => true]);
    }

    // =================api methods==================
    public function authenticate_api(Request $req)
    {
        // return($req->all());
        $user = Admin::where('phone', $req->phone)->first();
        // return($req->all());
        if ($user && Hash::check($req->password, $user->password) && $user->type == "field_executive") {

            Session::put('rexkod_pages_name', $user->name);
            Session::put('rexkod_pages_id', $user->id);
            Session::put('rexkod_pages_user_phone', $user->phone);
            Session::put('rexkod_pages_uesr_type', $user->type);
            $data = [
                'name' => session('rexkod_pages_name'),
                'id' => session('rexkod_pages_id'),
                'phone' => session('rexkod_pages_user_phone'),
                'type' => session('rexkod_pages_uesr_type')
            ];
            return response()->json(['status' => true,
                'msg' => '',
                'user_info' => $data]);
        } else {
            // session()->put('failed', 'Invalid credentials');
            return response()->json(['status' => false,
                'msg' => 'Invalid credentials',
                'user_info' => '']);

        }
    }

    public function add_meter_first_step_api()
    {
        return view('pages.add_meter_first_step');
    }

    public function check_rr_number_api(Request $req)
    {

        // if (session()->get('rexkod_pages_id') != Null) {
        // dd($req->account_id);
        // delete_flag
        // the pages has been provided with edit page too, in future they can edit the meter reading page also.
        // first case: its present in consumer_detail

        // consumer detail can be search by either accoount_id or rr_number -- added by ashutosh
        if ($req->account_id) {
            $consumer_detail = Consumer_detail::where('account_id', $req->account_id)->first();
            $meter_main = Meter_main::where('account_id', $req->account_id)->first();
        } else if ($req->rr_number) {
            $consumer_detail = Consumer_detail::where('rr_no', $req->rr_number)->first();
            if ($consumer_detail) {
                $meter_main = Meter_main::where('account_id', $consumer_detail->account_id)->first();
            } else {
                $meter_main = null;
            }
            // dd($consumer_detail->account_id);
        }


        if (!($consumer_detail)) {
            // session()->put('failed', 'Account number Invalid');
            // return redirect('/pages/add_meter_first_step');
            return response()->json(['status' => false,
                'msg' => 'Account number Invalid',
                'id' => '',
                'account_id' => '']);
        }
        if ($meter_main) {
            $meter_main_id = $meter_main->id;

            // there is one scenario
            // consider this has just relocated, so it reach to qc_vishvin already
            // so he can again approve
            // give allocate_flag or something to control that scenario
            if ((($meter_main->qc_status == 0) && ($meter_main->so_status == 0) && ($meter_main->aee_status == 0) && ($meter_main->aao_status == 0) && ($meter_main->allocation_flag == 1)) || ($meter_main->serial_no_new == Null)) {
                $meter_main->allocation_flag = 0;
                $meter_main->save();

                // return redirect('/pages/add_old_meter_detail/' . $meter_main_id);
                return response()->json(['status' => true,
                    'msg' => '',
                    'id' => $meter_main->id,
                    'account_id' => $meter_main->account_id]);

            } elseif ((($meter_main->qc_status == 1) && ($meter_main->so_status == 1) && ($meter_main->aee_status == 1) && ($meter_main->aao_status == 1))) {
                // session()->put('success', 'Meter already approved');
                // return redirect('/pages/add_meter_first_step');
                return response()->json(['status' => false,
                    'msg' => 'Meter already approved',
                    'id' => '',
                    'account_id' => '']);
            } else {
                // session()->put('failed', 'Meter status under progress');
                // return redirect('/pages/add_meter_first_step');
                return response()->json(['status' => false,
                    'msg' => 'Meter status under progress',
                    'id' => '',
                    'account_id' => '']);
            }
        } else {
            $Meter_main = new Meter_main();
            // $Meter_main->account_id = $req->account_id;
            $Meter_main->account_id = $consumer_detail->account_id;

            $Meter_main->created_by = session()->get('rexkod_pages_id');

            $Meter_main->save();
            $Meter_main->id;

            // session()->put('success', 'Please fill the old Electromechanical meter details');
            // return redirect('/pages/add_old_meter_detail/' . $Meter_main->id);
            return response()->json(['status' => true,
                'msg' => 'Please fill the old Electromechanical meter details',
                'id' => $Meter_main->id,
                'account_id' => $Meter_main->account_id]);
        }

        // }else{
        //     return redirect('/pages')->with('message', 'Session Time Out!');
        // }
    }

    public function add_old_meter_detail_api($id)
    {
        // dd($id);

        $meter_main = Meter_main::where('id', $id)->first();
        // dd($meter_main);
        // dd($meter_main->account_id);
        $get_consumer_detail = Consumer_detail::where('account_id', $meter_main->account_id)->first();

        $data = [
            'meter_main' => $meter_main,
            'get_consumer_detail' => $get_consumer_detail,
            'id' => $id,
        ];
        // return view('pages.add_old_meter_detail', ['data' => $data]);
        return response()->json(['status' => true,
            'msg' => '',
            'meter_main' => $meter_main,
            'get_consumer_detail' => $get_consumer_detail,
            'id' => $id]);
    }

    public function update_old_meter_detail_api(Request $req, $id)
    {
        // first case: its present in consumer_detail
        //dd($req);
        $meter_main = Meter_main::find($id);
        $meter_main->meter_make_old = $req->meter_make_old;
        $meter_main->serial_no_old = $req->serial_no_old;
        $meter_main->mfd_year_old = $req->mfd_year_old;
        $meter_main->final_reading = $req->final_reading;
        // if ($req->hasFile('image_1_old')) {
        //     $image = $req->file('image_1_old');
        //     $name = time().'.'.$image->getClientOriginalExtension();
        //     $destinationPath = public_path('/uploads');
        //    $meter_main->image_1_old= $image->move($destinationPath, $name);

        // }


        if (!empty($req->file('image_1_old'))) {
            $file = $req->file('image_1_old');
            $mime_type = $file->getClientMimeType();
            $extension = $file->getClientOriginalExtension();
            if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                // $filename = Str::random(4) . time() . '.' . $extension;

                // giving the image name as account id
                $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;

                $meter_main->image_1_old = $file->move(('uploads'), $filename);
                //ImageCompressionController::compress_image($meter_main->image_1_old);
            } else {
                session()->put('failed', 'Only JPEG and PNG images are allowed.');
                return redirect('/pages/add_old_meter_detail/' . $id);
            }
        }
        if (!empty($req->file('image_2_old'))) {
            $file = $req->file('image_2_old');
            $mime_type = $file->getClientMimeType();
            $extension = $file->getClientOriginalExtension();
            if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                // $filename = Str::random(4) . time() . '.' . $extension;

                // giving the image name as account id
                $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;
                $meter_main->image_2_old = $file->move(('uploads'), $filename);
                //ImageCompressionController::compress_image($meter_main->image_2_old);
            } else {
                session()->put('failed', 'Only JPEG and PNG images are allowed.');
                return redirect('/pages/add_old_meter_detail/' . $id);
            }
        }
        if (!empty($req->file('image_3_old'))) {
            $file = $req->file('image_3_old');
            $mime_type = $file->getClientMimeType();
            $extension = $file->getClientOriginalExtension();
            if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                // $filename = Str::random(4) . time() . '.' . $extension;

                // giving the image name as account id
                $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;
                $meter_main->image_3_old = $file->move(('uploads'), $filename);
                //ImageCompressionController::compress_image($meter_main->image_3_old);
                // $meter_main->image_3_old= "image 3";
            } else {
                // session()->put('failed', 'Only JPEG and PNG images are allowed.');
                // return redirect('/pages/add_old_meter_detail/' . $id);
                $meter_main->image_3_old = $mime_type;

            }
        } else {
            $meter_main->image_3_old = "image 3 didn't read file";

        }


        $meter_main->save();


        session()->put('success', 'Please fill the new Electrostatic meter details');

        // return redirect('/pages/add_new_meter_detail/' . $id);
        return response()->json(['status' => true,
            'msg' => '',
            'meter_make_old' => $meter_main->meter_make_old,
            'serial_no_old' => $meter_main->serial_no_old,
            'mfd_year_old' => $meter_main->mfd_year_old,
            'final_reading' => $meter_main->final_reading,
            'image_1_old' => $meter_main->image_1_old,
            'image_2_old' => $meter_main->image_2_old,
            'image_3_old' => $meter_main->image_3_old,]);
        // 'image_2_old' => "no image"]);

    }

    public function add_new_meter_detail_api($id)
    {
        $meter_main = Meter_main::where('id', $id)->first();
        // dd($meter_main->account_id);
        $get_consumer_detail = Consumer_detail::where('account_id', $meter_main->account_id)->first();

        $data = [
            'meter_main' => $meter_main,

            'get_consumer_detail' => $get_consumer_detail,
            'id' => $id,
        ];
        // return view('pages.add_new_meter_detail', ['data' => $data]);
        return response()->json(['meter_main' => $meter_main,
            'get_consumer_detail' => $get_consumer_detail,
            'id' => $id]);
    }

    public function update_new_meter_detail_api(Request $req, $id)
    {
        if (session()->get('rexkod_pages_id') != Null) {
            // first case: its present in consumer_detail
            // dd($id);
            $meter_serial_no = $req->serial_no_new;

            $get_field_executive_contractor = Admin::where('id', session('rexkod_pages_id'))->first();
            //   print_r($get_field_executive_contractor);
            $contractor_inventories = Contractor_inventory::where('contractor_id', $get_field_executive_contractor->created_by)->get();


            foreach ($contractor_inventories as $contractor_inventory) {
                $individual_inventory = $contractor_inventory->unused_meter_serial_no;
                // dd($individual_inventory);
                $individual_serial_nos = explode(",", $individual_inventory);
                foreach ($individual_serial_nos as $individual_serial_no) {
                    // dd($individual_serial_no);
                    if ($individual_serial_no == $meter_serial_no) {
                        $current_inventory_id = $contractor_inventory->id;
                    }
                }
            }

            // dd($current_inventory_id);
            if (isset($current_inventory_id)) {
                // ****************
                $existingInventory = Contractor_inventory::where('id', $current_inventory_id)->first();


                $unused_meter_serial_no = explode(',', $existingInventory->unused_meter_serial_no);
                $used_meter_serial_no = explode(',', $existingInventory->used_meter_serial_no);

                $input_values = $meter_serial_no; // assume the checkbox values are submitted as an array
                // dd($input_values);
                // Remove the input values from unused data and add them to used data
                if (!$input_values) {
                    session()->put('failed', 'Please select any meter serial no');
                    return redirect('/inventory_executives/add_outward_installation');
                }

                $key = array_search($input_values, $unused_meter_serial_no);
                if ($key !== false) {
                    unset($unused_meter_serial_no[$key]);
                    $used_meter_serial_no[] = $input_values;
                }


                $existingInventory->unused_meter_serial_no = implode(',', $unused_meter_serial_no);
                if (empty($existingInventory->unused_meter_serial_no)) {
                    $existingInventory->unused_meter_serial_no = null;
                }
                $existingInventory->used_meter_serial_no = implode(',', $used_meter_serial_no);
                $existingInventory->used_meter_serial_no = ltrim($existingInventory->used_meter_serial_no, ',');
                $existingInventory->save();


                $Inventory = new Inventory();


                $Inventory->serial_no = $meter_serial_no;
                $Inventory->created_by = session()->get('rexkod_pages_id');

                $Inventory->save();
            }
            $meter_main = Meter_main::find($id);
            // $meter_main = new meter_main;
            // $meter_main->meter_make_new = $req->meter_make_new;
            $meter_main->serial_no_new = $req->serial_no_new;


            $meter_main->mfd_year_new = $req->mfd_year_new;
            $meter_main->initial_reading_kwh = $req->initial_reading_kwh;
            $meter_main->initial_reading_kvah = $req->initial_reading_kvah;


            if (!empty($req->file('image_1_new'))) {
                $file = $req->file('image_1_new');
                $mime_type = $file->getClientMimeType();
                $extension = $file->getClientOriginalExtension();
                if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                    // $filename = Str::random(4) . time() . '.' . $extension;

                    // giving the image name as account id
                    $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;

                    $meter_main->image_1_new = $file->move(('uploads'), $filename);
                    //ImageCompressionController::compress_image($meter_main->image_1_new);
                } else {
                    session()->put('failed', 'Only JPEG and PNG images are allowed.');
                    return redirect('/pages/add_new_meter_detail/' . $id);
                }
            }

            if (!empty($req->file('image_2_new'))) {
                $file = $req->file('image_2_new');
                $mime_type = $file->getClientMimeType();
                $extension = $file->getClientOriginalExtension();
                if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                    // $filename = Str::random(4) . time() . '.' . $extension;

                    // giving the image name as account id
                    $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;
                    $meter_main->image_2_new = $file->move(('uploads'), $filename);
                    //ImageCompressionController::compress_image($meter_main->image_2_new);
                } else {
                    session()->put('failed', 'Only JPEG and PNG images are allowed.');
                    return redirect('/pages/add_new_meter_detail/' . $id);
                }
            }


            $meter_main->lat = session()->get('rexkod_pages_lat');
            $meter_main->lon = session()->get('rexkod_pages_lon');
            $meter_main->save();


            session()->put('success', 'Congrats! The new meter and old meter has been stored successfully.');

            // $user = Admin::where('user_name', $req->user_email)->first();

            // $req->session()->put('user',$user);

            // return redirect('/pages/home');
            return response()->json(['status' => true,
                'msg' => '',
                'serial_no_new' => $meter_main->serial_no_new,
                'initial_reading_kwh' => $meter_main->initial_reading_kwh,
                'initial_reading_kvah' => $meter_main->initial_reading_kvah,
                'image_1_new' => $meter_main->image_1_new,
                'image_2_new' => $meter_main->image_2_new,
            ]);

            // second case: this is present in meter_mains

            return ($req);
        } else {
            return redirect('/pages')->with('message', 'Session Time Out!');
        }
    }

    public function home_api(Request $request)
    {
        $meter_main = Meter_main::where('created_by', Session('rexkod_pages_id'))->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->get();
        $lat = session('user_lat');
        $lon = session('user_lon');
        $city = session('user_city');
        $data = [
            'meter_main' => $meter_main,
            'lat' => $lat,
            'lon' => $lon,
            'city' => $city,


        ];
        // return view('pages.home', compact('data'));
        return response()->json(['status' => true,
            'msg' => '',
            'meter_main' => $meter_main,
            'lat' => $lat,
            'lon' => $lon,
            'city' => $city,
        ]);
    }

    public function logout_api(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // return redirect('/pages')->with('message', 'You have been logged out!');
        return response()->json(['status' => true,
            'msg' => 'logged out',
        ]);
    }

    public function location_api()
    {
        return view('pages.location');
    }

    public function check_images_availability_api()
    {
        $get_all_meter_mains = DB::table('meter_mains')
            //->join('successful_records', 'successful_records.account_id', '!=', 'meter_mains.created_by')
            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->join('admins', 'admins.id', '=', 'meter_mains.created_by')
            ->whereNotNull('meter_mains.image_1_old')
            ->whereNotNull('meter_mains.image_2_old')
            ->whereNotNull('meter_mains.image_1_new')
            ->whereNotNull('meter_mains.image_2_new')
            ->whereNotNull('meter_mains.serial_no_new')
            ->select('meter_mains.account_id',
                'consumer_details.rr_no',
                'consumer_details.consumer_name',
                'consumer_details.consumer_address',
                'consumer_details.so_pincode',
                'consumer_details.sd_pincode',
                'admins.name as created_by',
                'meter_mains.image_1_old',
                'meter_mains.image_2_old',
                'meter_mains.image_1_new',
                'meter_mains.image_2_new',
                'meter_mains.qc_status',
                'meter_mains.so_status',
                'meter_mains.aee_status',
                'meter_mains.aao_status',
                'meter_mains.image_2_new')
            ->orderBy('meter_mains.created_at')
            ->get()
            ->toArray();
//dd($get_all_meter_mains);
        $validity_images = array();
        foreach ($get_all_meter_mains as $key => $meter_data) {
            //$validity_images[$meter_data->account_id]['image_1_old_exist'] = File::exists($meter_data->image_1_old);
            //dd($meter_data->account_id);
//            $validity_images[$meter_data->account_id] = $meter_data;
//            //dd($validity_images);
////            array_merge($validity_images[$meter_data->account_id], ['image_1_old_exist' => File::exists($meter_data->image_1_old)]);
//            $validity_images[$meter_data->account_id]->image_1_old_exist = File::exists($meter_data->image_1_old) ? 'Found' : 'Not Found';
//            $validity_images[$meter_data->account_id]->image_2_old_exist = File::exists($meter_data->image_2_old) ? 'Found' : 'Not Found';
//            $validity_images[$meter_data->account_id]->image_1_new_exist = File::exists($meter_data->image_1_new) ? 'Found' : 'Not Found';
//            $validity_images[$meter_data->account_id]->image_2_new_exist = File::exists($meter_data->image_2_new) ? 'Found' : 'Not Found';


            $validity_images[$key] = $meter_data;
            //dd($validity_images);
//            array_merge($validity_images[$meter_data->account_id], ['image_1_old_exist' => File::exists($meter_data->image_1_old)]);
            $validity_images[$key]->image_1_old_exist = File::exists($meter_data->image_1_old) ? 'Yes' : 'No';
            $validity_images[$key]->image_2_old_exist = File::exists($meter_data->image_2_old) ? 'Yes' : 'No';
            $validity_images[$key]->image_1_new_exist = File::exists($meter_data->image_1_new) ? 'Yes' : 'No';
            $validity_images[$key]->image_2_new_exist = File::exists($meter_data->image_2_new) ? 'Yes' : 'No';
            if ($meter_data->aao_status == '1') {
                $last_worker = 'aao';
            } else if ($meter_data->aee_status == '1') {
                $last_worker = 'aee';
            } else if ($meter_data->so_status == '1') {
                $last_worker = 'ae';
            } else if ($meter_data->qc_status == '1') {
                $last_worker = 'qc';
            }
            $validity_images[$key]->last_worker = $last_worker;
            if ($validity_images[$key]->image_1_old_exist == 'Yes'
                && $validity_images[$key]->image_2_old_exist == 'Yes'
                && $validity_images[$key]->image_1_new_exist == 'Yes'
                && $validity_images[$key]->image_2_new_exist == 'Yes') unset($validity_images[$key]);
            //dd($validity_images);
        }

//        dd($get_all_meter_mains);
        //return response()->json(['data' =>$get_all_meter_mains, 'data_count' => count($get_all_meter_mains)]);
        return view('pages.image_validity_report', ['image_validity_data' => $validity_images, 'image_validity_data_count' => count($validity_images)]);
    }
	
	
	
	
	
	//api controllers
	    public function getSectionCodes()
    {
        // Fetch the distinct section codes and related columns without using 'package' variable
        $get_section_codes = DB::table('zone_codes')
            ->select('so_code', 'section_office', 'sd_code', 'sub_division', 'div_code', 'division')
            ->groupBy('so_code', 'section_office', 'sd_code', 'sub_division', 'div_code', 'division')  // Group by all selected columns
            ->get();
    
        // Return the data as JSON response
        return response()->json([
            'status' => 'success',
            'data' => $get_section_codes
        ]);
    }
	
	    public function old_meter_get(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'section' => 'required|string',
                'account_id' => 'required|string',
            ]);
    
            // Extract validated inputs
            $section = $validatedData['section'];
            $accountId = $validatedData['account_id'];
    
            // Check if the account_id already exists in the meter_mains table
            $existingMeter = DB::table('meter_mains')
                ->where('account_id', $accountId)
                ->exists();
    
            if ($existingMeter) {
                return response()->json([
                    'message' => 'Meter already installed for this account.',
                ], 400); // 400 Bad Request
            }
    
            // Fetch data from consumer_details table
            $consumerDetails = DB::table('consumer_details')
                ->where('section', $section)
                ->where('account_id', $accountId)
                ->get();
    
            // Return the result as JSON
            if ($consumerDetails->isEmpty()) {
                return response()->json([
                    'message' => 'No records found.',
                    'data' => [],
                ], 404);
            }
    
            return response()->json([
                'message' => 'Data retrieved successfully.',
                'data' => $consumerDetails,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
	
	
	
     public function Fe_installed_records(Request $request, $fieldExecutiveId)
    {
        $data = []; // Initialize an empty array to hold the data
        $todayDate = now()->toDateString(); // Get today's date in 'YYYY-MM-DD' format
    
        // Fetch all meter_main data for the given field executive
        $meterMains = DB::table('meter_mains')
            ->where('created_by', $fieldExecutiveId)
            ->whereNotNull('serial_no_new')
            ->whereNotNull('serial_no_old')
            ->get(); // Fetch all matching records
    
        if ($meterMains->isNotEmpty()) {
            foreach ($meterMains as $meterMain) {
                $meterMainData = [
                    'serial_no_new' => $meterMain->serial_no_new,
                    'account_id' => $meterMain->account_id,
                    'serial_no_old' => $meterMain->serial_no_old,
                    'lat' => $meterMain->lat,
                    'lon' => $meterMain->lon,
                    'created_at' => $meterMain->created_at, // Use the actual created_at value
                ];
    
                // Fetch consumer details for the current meter_main
                $consumerDetails = DB::table('consumer_details')
                    ->where('account_id', $meterMain->account_id)
                    ->first();
    
                $consumerData = $consumerDetails
                    ? [
                        'account_id' => $consumerDetails->account_id,
                        'rr_no' => $consumerDetails->rr_no,
                        'consumer_name' => $consumerDetails->consumer_name,
                        'consumer_address' => $consumerDetails->consumer_address,
                    ]
                    : null;
    
                // Fetch admin details for the field executive
                $admin = DB::table('admins')
                    ->where('id', $fieldExecutiveId)
                    ->first();
    
                $adminData = $admin
                    ? [
                        'id' => $admin->id,
                        'name' => $admin->name,
                        'type' => $admin->type,
                    ]
                    : null;
    
                // Append the data for this meter_main record to the array
                $data[] = [
                    'meter_main' => $meterMainData,
                    'consumer_details' => $consumerData,
                    'admins' => $adminData,
                ];
            }
        }
    
        // Explicitly set numeric keys in the response
        $responseData = array_values($data); // Ensure keys are sequential numbers (0, 1, 2, ...)
    
        // Return response with the collected data
        return response()->json([
            'message' => 'Data retrieved successfully.',
            'data' => $responseData,
        ], 200);
    }

	
	
    public function account_rr_no_search(Request $req)
{
    // Validate the input to ensure either account_id or rr_number is provided
    $validatedData = $req->validate([
        'account_id' => 'nullable|string',
        'rr_number' => 'nullable|string',
    ]);

    // Fetch consumer_detail based on account_id or rr_number
    if ($req->filled('account_id')) {
        $consumer_detail = Consumer_detail::where('account_id', $req->account_id)->first();
        $meter_main = Meter_main::where('account_id', $req->account_id)->first();
    } elseif ($req->filled('rr_number')) {
        $consumer_detail = Consumer_detail::where('rr_no', $req->rr_number)->first();
        $meter_main = $consumer_detail
            ? Meter_main::where('account_id', $consumer_detail->account_id)->first()
            : null;
    } else {
        return response()->json([
            'status' => false,
            'msg' => 'Either account_id or rr_number must be provided.',
            'id' => '',
            'account_id' => ''
        ]);
    }

    // Handle case where consumer_detail is not found
    if (!$consumer_detail) {
        return response()->json([
            'status' => false,
            'msg' => 'Account number Invalid',
            'id' => '',
            'account_id' => ''
        ]);
    }

    // Handle meter_main logic
    if ($meter_main) {
        $meter_main_id = $meter_main->id;

        if ((($meter_main->qc_status == 0 && $meter_main->so_status == 0 && $meter_main->aee_status == 0 && $meter_main->aao_status == 0 && $meter_main->allocation_flag == 1) ||
            is_null($meter_main->serial_no_new))) {
            $meter_main->allocation_flag = 0;
            $meter_main->save();

            return response()->json([
                'status' => true,
                'msg' => '',
                'id' => $meter_main->id,
                'account_id' => $meter_main->account_id,
                'rr_no' => $consumer_detail->rr_no,
                "consumer_name" => $consumer_detail->consumer_name,
                "consumer_address" => $consumer_detail->consumer_address,
                "division" => $consumer_detail->division,
                "section" => $consumer_detail->section,
                "sub_division" => $consumer_detail->sub_division,
                "phase_type" => $consumer_detail->phase_type

            ]);
        } elseif ($meter_main->qc_status == 1 && $meter_main->so_status == 1 && $meter_main->aee_status == 1 && $meter_main->aao_status == 1) {
            return response()->json([
                'status' => false,
                'msg' => 'Meter already approved',
                'id' => '',
                'account_id' => ''
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'Meter status under progress',
                'id' => '',
                'account_id' => ''
            ]);
        }
    } else {
        // Create a new Meter_main entry
        $new_meter_main = new Meter_main();
        $new_meter_main->account_id = $consumer_detail->account_id;
        $new_meter_main->created_by = session()->get('rexkod_pages_id');
        $new_meter_main->save();

        return response()->json([
            'status' => true,
            'msg' => 'Please fill the old Electromechanical meter details',
            'id' => $new_meter_main->id,
            'account_id' => $new_meter_main->account_id,
            'rr_no' => $consumer_detail->rr_no,
                "consumer_name" => $consumer_detail->consumer_name,
                "consumer_address" => $consumer_detail->consumer_address,
                "division" => $consumer_detail->division,
                "section" => $consumer_detail->section,
                "sub_division" => $consumer_detail->sub_division,
                "phase_type" => $consumer_detail->phase_type
               
        ]);
    }
}
	
public function accounts_details_so_details(Request $req)
{
    // Validate the request to ensure so_pincode is provided
    $validatedData = $req->validate([
        'so_pincode' => 'required|string',
    ]);

    // Fetch consumer account_ids directly, excluding those present in meter_mains
    $consumer_details = Consumer_detail::where('so_pincode', $req->so_pincode)
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('meter_mains')
                ->whereColumn('meter_mains.account_id', 'consumer_details.account_id');
        })
        ->get();

    // Format the response data
    $response_data = $consumer_details->map(function ($consumer) {
        return [
            'id'=> $consumer->id,
            'account_id' => $consumer->account_id,
            'rr_no' => $consumer->rr_no,
            "consumer_name" => $consumer->consumer_name ?? 'N/A',
            "consumer_address" => $consumer->consumer_address ?? 'N/A',
            "division" => $consumer->division,
            "section" => $consumer->section,
            "sub_division" => $consumer->sub_division,
            "phase_type" => $consumer->phase_type
        ];
    });

    return response()->json([
        'status' => true,
        'data' => $response_data
    ]);
}

    

}
