<?php

namespace App\Http\Controllers;

use App\Exports\MeterReplacementRecordsExport;
use App\Exports\ReleaseMeterRecordsExport;
use App\Exports\SuccessFullRecordsExport;
use App\Exports\SuccessFullRecordsWithImagesExport;
use App\Http\Services\BmrDownloadService;
use App\Http\Services\MeterMainService;
use App\Http\Services\ZoneCodeService;
use App\Http\Services\IndentService;
use App\Models\Admin;
use App\Models\Contractor;
use Illuminate\Support\Facades\File;
use App\Models\Meter_final_reading;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Consumer_detail;
use App\Models\Warehouse_meter;
use App\Models\Inward_released_em_meter;
use App\Models\Outward_released_em_meter;
use Carbon\Carbon;
use App\Models\Zone_code;
use App\Models\Lot_detail;
use App\Models\Contractor_inventory;

use App\Models\Annexure_1;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator;

use App\Models\Meter_main;
use App\Models\Error_record;
use Illuminate\Support\Facades\DB;
use App\Models\Successful_record;
use Maatwebsite\Excel\Facades\Excel;

class ProjectHeadController extends Controller
{
    public function index()
    {
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');

        //$user_with_types =  Admin::where('created_by', $logged_user_id)->get();

        //dd($user_with_types);
//        foreach($user_with_types as $user=>$)

//        $hescom_manager = Admin::where('type', 'hescom_manager')->where('created_by', $logged_user_id)->get();
//        $hescom_manager_count = count($hescom_manager);
//
//        $inventory_manager = Admin::where('type', 'inventory_manager')->where('created_by', $logged_user_id)->get();
//        $inventory_manager_count = count($inventory_manager);
//
//        $qc_manager = Admin::where('type', 'qc_manager')->where('created_by', $logged_user_id)->get();
//        $qc_manager_count = count($qc_manager);
//
//        $contractor_manager = Admin::where('type', 'contractor_manager')->where('created_by', $logged_user_id)->get();
//        $contractor_manager_count = count($contractor_manager);

        // Count of meters project_head wise
        $es_total_meter_count_project_head_wise = 0;
        $es_total_unused_meter_count_project_head_wise = 0;
        $es_total_used_meter_count_project_head_wise = 0;
        $em_total_inward_meter_count_project_head_wise = 0;
        $em_total_outward_meter_count_project_head_wise = 0;
        $total_rejected_meter_count_project_head_wise = 0;

//         $get_all_inventory_managers =  Admin::where('type', 'inventory_manager')->where('created_by', $logged_in_user_id)->get();
//         foreach ($get_all_inventory_managers as $inventory_manager) {
//             $get_all_inventory_executives =  Admin::where('type', 'inventory_executive')->where('created_by', $inventory_manager->id)->get();
//             foreach ($get_all_inventory_executives as $inventory_executive) {
//                 $es_meter_total_warehouse_stock = Warehouse_meter::where('created_by', $inventory_executive->id)->get();
//                 foreach ($es_meter_total_warehouse_stock as $es_meter) {
//                     // counting total meter count
//                     $single_box = $es_meter->meter_serial_no;
//                     if ($single_box !== null && $single_box !== '') {
//                         $break_single_meter = explode(',', $single_box);
//                         foreach ($break_single_meter as $es_meter_individual) {
//                             $es_total_meter_count_project_head_wise++;
//                         }
//                     }
//                     // counting unused meter count
//                     $single_box = $es_meter->unused_meter_serial_no;
//                     if ($single_box !== null && $single_box !== '') {
//                         $break_single_meter = explode(',', $single_box);
//                         foreach ($break_single_meter as $es_meter_individual) {
//                             $es_total_unused_meter_count_project_head_wise++;
//                         }
//                     }
//                     // counting used meter count
//                     //$single_box = $es_meter->used_meter_serial_no;
//                     if ($single_box !== null && $single_box !== '') {
//                         $break_single_meter = explode(',', $single_box);
//                         foreach ($break_single_meter as $es_meter_individual) {
//                             $es_total_used_meter_count_project_head_wise++;
//                         }
//                     }
//                 }
//
//                 $em_total_inward_meter_count_project_head_wise = Inward_released_em_meter::where('created_by', $inventory_executive->id)->get()->count();
//                 //$em_total_inward_meter_count_project_head_wise = count($em_meter_total_inward_stock);
//                 $em_total_outward_meter_count_project_head_wise = Outward_released_em_meter::get()->count();
//                 //$em_total_outward_meter_count_project_head_wise = count($em_total_outward_meter_stock);
//
//                 $field_executives = Admin::where('type', 'field_executive')->where('created_by', $inventory_executive->id)->get();
//                 foreach ($field_executives as $field_executive) {
//                     $total_rejected_meter_count_project_head_wise = DB::table('meter_mains')
//                                                ->where('qc_status', 2)
//                                                ->orWhere('so_status', 2)->orWhere('aee_status', 2)
//                                                ->orWhere('aao_status', 2)->where('created_by', $field_executive->id)
//                                                ->get()->count();
//                     //$total_rejected_meter_count_project_head_wise = count($es_meter_total_rejected);
//                 }
//             }
//         }
//
//        $pending_by_field_executive = DB::table('meter_mains')
//            ->whereNull('meter_mains.serial_no_old')
//            ->orWhereNull('meter_mains.serial_no_new')
//            ->select(DB::raw('count(*) as count_meter_pending_by_field_executive'))
//            ->first();
//         $count_pending_by_field_executive = $pending_by_field_executive->count_meter_pending_by_field_executive;

//        $field_executives = Admin::where('type', 'field_executive')->where('created_by', $inventory_executive->id)->get();
//        foreach ($field_executives as $field_executive) {
//            $es_meter_total_rejected = DB::table('meter_mains')
//                ->where('qc_status', 2)
//                ->orWhere('so_status', 2)->orWhere('aee_status', 2)
//                ->orWhere('aao_status', 2)->where('created_by', $field_executive->id)
//                ->select('id')
//                ->get();
//            $total_rejected_meter_count_project_head_wise = count($es_meter_total_rejected);
//        }
//        $pending_by_field_executive = DB::table('meter_mains')
//            ->whereNull('serial_no_old')
//            ->orWhereNull('serial_no_new')
//            ->select('id')
//            ->get();
//        $count_pending_by_field_executive = count($pending_by_field_executive);

        $package_name = env('PACKAGE_NAME');
        $get_all_division_codes = DB::table('zone_codes')
            ->select('division', 'div_code')
            ->where('package', $package_name)
            ->distinct()
            ->get();
        //dd($get_all_division_codes, env('PACKAGE_NAME'));

        $data = [
            //'hescom_manager_count' => $hescom_manager_count,
            //'inventory_manager_count' => $inventory_manager_count,
            //'qc_manager_count' => $qc_manager_count,
            //'contractor_manager_count' => $contractor_manager_count,
//            'es_total_meter_count_project_head_wise' => $es_total_meter_count_project_head_wise,
//            'es_total_unused_meter_count_project_head_wise' => $es_total_unused_meter_count_project_head_wise,
//            'es_total_used_meter_count_project_head_wise' => $es_total_used_meter_count_project_head_wise,
//            'em_total_inward_meter_count_project_head_wise' => $em_total_inward_meter_count_project_head_wise,
//            'em_total_outward_meter_count_project_head_wise' => $em_total_outward_meter_count_project_head_wise,
//            'total_rejected_meter_count_project_head_wise' => $total_rejected_meter_count_project_head_wise,
//            'count_pending_by_field_executive' => $count_pending_by_field_executive,
            'current_logged_in_user_type' => $logged_in_user_type,
            'divisions' => $get_all_division_codes
        ];

        return view('project_heads.index', compact('data'));
    }

    public function add_inventory_manager()
    {
        return view('project_heads.add_inventory_manager');
    }

    public function consumer_bulk_upload()
    {
        return view('project_heads.consumer_bulk_upload');
    }

    public function add_contractor()
    {
        return view('project_heads.add_contractor');
    }

    public function add_hescom()
    {
        return view('project_heads.add_hescom');
    }

    public function add_qc()
    {
        return view('project_heads.add_qc');
    }

    public function all_contractors()
    {

        // $data = Admin::where('type', 'contractor_manager')->where('created_by', session()->get('rexkod_vishvin_auth_userid'))->get();
        // dd($data);
        return view('project_heads.all_contractors', [
            'show_users' => Admin::where('type', 'contractor_manager')->where('created_by', session()->get('rexkod_vishvin_auth_userid'))->get(),
        ]);
    }

    public function all_hescoms()
    {
        return view('project_heads.all_hescoms', [
            'show_users' => Admin::where('type', 'hescom_manager')->where('created_by', session()->get('rexkod_vishvin_auth_userid'))->get(),
        ]);
    }

    public function all_inventory_managers()
    {
        return view('project_heads.all_inventory_managers', [
            'show_users' => Admin::where('type', 'inventory_manager')->where('created_by', session()->get('rexkod_vishvin_auth_userid'))->get(),
        ]);
    }

    public function all_qcs()
    {
        return view('project_heads.all_qcs', [
            'show_users' => Admin::where('type', 'qc_manager')->where('created_by', session()->get('rexkod_vishvin_auth_userid'))->get(),
        ]);
    }

    public function all_users()
    {
        return view('project_heads.all_users');
    }

    public function login()
    {
        return view('project_heads.login');
    }

    public function authenticate(Request $request)
    {
        // print_r($request->all());

        // dd($request);

        $user = Admin::where('user_name', $request->user_name)->first();
        if ($user) {
            if ($user->type != 'project_head') {
                return back()->withErrors('name', 'name is required!');
                // return back()->with('success', 'Invalid Credentials');
                // return back()->with('error', 'Invalid Credentials');
                die();
            }
        } else {
            return back()->withErrors('success', 'name is required!');

            // return Redirect::back()->withErrors($validator);
            // return back()->with('error', 'Invalid Credentials');
            // return back()->withErrors('name' ,'name is required!');


            die();
        }
        $formFields = $request->validate([
            'user_name' => 'required',
            'password' => 'required',
            // 'type'=>'admin',
        ]);
        // if(auth()->type!="admin"){
        // return back()->with('success', 'Invalid Credentials');
        // }
        if (auth()->attempt($formFields)) {
            auth()->login($user);
            $request->session()->regenerate();


            Session::put('rexkod_project_head', $user);
            Session::put('rexkod_project_head_user_name', $user->name);
            Session::put('rexkod_vishvin_auth_userid', $user->id);
            Session::put('rexkod_project_head_user_type', $user->type);
            Session::put('rexkod_project_head_user_phone', $user->phone);

            $user = admin::where('user_name', '=', $request->user_name)->first();
            return redirect('/project_heads/index')->with('message', 'You are now logged in!');
        }
        return back()->withErrors('name', 'name is required!');

        // return back()->withErrors(['user_name' => 'Invalid Credentials'])->onlyInput('user_name');
        // return back()->with('error', 'Invalid Credentials');
    }

    function create_inventory_manager(Request $req)
    {
        // print_r($req->all());

        $auth = new Admin;


        $result = Admin::where('phone', $req->phone)->first();

        if ($result) {
            session()->put('failed', 'Phone already exists');

            return redirect('/project_heads/add_inventory_manager');
        } else {

            $auth->name = $req->name;

            $auth->phone = $req->phone;

            $auth->project_name = $req->project_name;

            $auth->type = "inventory_manager";
            $auth->password = Hash::make($req->password);

            if (strlen((string)$auth->phone) < 10) {
                session()->put('failed', 'Mobile nummber should be at least 10 digits');
                return redirect()->back();
            }


            $uppercase = preg_match('@[A-Z]@', $req->password);
            $lowercase = preg_match('@[a-z]@', $req->password);
            $number = preg_match('@[0-9]@', $req->password);
            $specialChars = preg_match('@[^\w]@', $req->password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($req->password) < 8) {

                session()->put('failed', 'Password should be atleast 8 characters & must include atleast one upper case letter, one number, and one special character');
                return redirect()->back();
            }

            $auth->created_by = session()->get('rexkod_vishvin_auth_userid');
            $auth->save();
            session()->put('success', 'Inventory Manager added successfully');


            return redirect('/project_heads/all_inventory_managers');
        }
    }

    function create_qc_manager(Request $req)
    {

        $auth = new Admin;


        $result = Admin::where('phone', $req->phone)->first();

        if ($result) {
            session()->put('failed', 'Phone already exists');

            return redirect('/project_heads/add_qc_manager');
        } else {

            $auth->name = $req->name;

            $auth->phone = $req->phone;

            $auth->project_name = $req->project_name;

            $auth->type = "qc_manager";
            $auth->password = Hash::make($req->password);

            if (strlen((string)$auth->phone) < 10) {
                session()->put('failed', 'Mobile nummber should be at least 10 digits');
                return redirect()->back();
            }


            $uppercase = preg_match('@[A-Z]@', $req->password);
            $lowercase = preg_match('@[a-z]@', $req->password);
            $number = preg_match('@[0-9]@', $req->password);
            $specialChars = preg_match('@[^\w]@', $req->password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($req->password) < 8) {

                session()->put('failed', 'Password should be atleast 8 characters & must include atleast one upper case letter, one number, and one special character');
                return redirect()->back();
            }

            $auth->created_by = session()->get('rexkod_vishvin_auth_userid');
            $auth->save();
            session()->put('success', 'QC Manager added successfully');


            return redirect('/project_heads/all_qcs');
        }
    }

    function create_hescom_manager(Request $req)
    {
        $auth = new Admin;


        $result = Admin::where('phone', $req->phone)->first();

        if ($result) {
            session()->put('failed', 'Phone already exists');

            return redirect('/project_heads/add_hescom');
        } else {

            $auth->name = $req->name;

            $auth->phone = $req->phone;

            $auth->project_name = $req->project_name;

            $auth->type = "hescom_manager";
            $auth->password = Hash::make($req->password);

            if (strlen((string)$auth->phone) < 10) {
                session()->put('failed', 'Mobile nummber should be at least 10 digits');
                return redirect()->back();
            }


            $uppercase = preg_match('@[A-Z]@', $req->password);
            $lowercase = preg_match('@[a-z]@', $req->password);
            $number = preg_match('@[0-9]@', $req->password);
            $specialChars = preg_match('@[^\w]@', $req->password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($req->password) < 8) {

                session()->put('failed', 'Password should be atleast 8 characters & must include atleast one upper case letter, one number, and one special character');
                return redirect()->back();
            }

            $auth->created_by = session()->get('rexkod_vishvin_auth_userid');
            $auth->save();
            session()->put('success', 'Hescom Manager added successfully');


            return redirect('/project_heads/all_hescoms');
        }
    }

    function create_contractor_manager(Request $req)
    {
        // print_r($req->all());
        $auth = new Admin;
        $contractor = new Contractor;


        $result = Admin::where('phone', $req->phone)->first();

        if ($result) {
            session()->put('failed', 'Phone already exists');

            return redirect('/project_heads/add_contractor');
        } else {

            $auth->name = $req->name;

            $auth->phone = $req->phone;
            // $auth->user_name = $req->user_name;
            $auth->type = "contractor_manager";
            $auth->password = Hash::make($req->password);


            if (strlen((string)$auth->phone) < 10) {
                session()->put('failed', 'Mobile number should be at least 10 digits');
                return redirect()->back();
            }


            $uppercase = preg_match('@[A-Z]@', $req->password);
            $lowercase = preg_match('@[a-z]@', $req->password);
            $number = preg_match('@[0-9]@', $req->password);
            $specialChars = preg_match('@[^\w]@', $req->password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($req->password) < 8) {

                session()->put('failed', 'Password should be atleast 8 characters & must include atleast one upper case letter, one number, and one special character');
                return redirect()->back();

                //  "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
                //     redirect('pages/add_user');
                // die;
            }

            $auth->created_by = session()->get('rexkod_vishvin_auth_userid');
            $auth->save();
            $auth->refresh();
            $new_contractor_added = Admin::where('phone', $req->phone)->first();
            // return ($new_contractor_added);

            $contractor->auth_id = $new_contractor_added->id;
            $contractor->contractor_name = $req->contractor_name;
            $contractor->firm_name = $req->firm_name;
            $contractor->house_no = $req->house_no;
            $contractor->building_name = $req->building_name;
            $contractor->road_name = $req->road_name;
            $contractor->cross_name = $req->cross_name;
            $contractor->area_name = $req->area_name;
            $contractor->city_name = $req->city_name;
            $contractor->pincode = $req->pincode;
            $contractor->contractor_cell_no = $req->contractor_cell_no;
            $contractor->contractor_email = $req->contractor_email;
            $contractor->pan = $req->pan;
            $contractor->gst = $req->gst;
            $contractor->bank_name = $req->bank_name;
            $contractor->bank_branch = $req->bank_branch;
            $contractor->account_no = $req->account_no;
            $contractor->ifsc_code = $req->ifsc_code;
            $contractor->account_type = $req->account_type;
            $contractor->pan_no = $req->pan_no;
            $contractor->gst_no = $req->gst_no;

            // if (!empty($req->file('pan'))) {
            //     $extension1 = $req->file('pan')->extension();
            //     if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
            //         $filename = Str::random(4) . time() . '.' . $extension1;
            //         $contractor->pan = $req->file('pan')->move(('uploads'), $filename);
            //     }
            // }


            if (!empty($req->file('pan'))) {
                $file = $req->file('pan');
                $mime_type = $file->getClientMimeType();
                $extension = $file->getClientOriginalExtension();
                if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                    $filename = Str::random(4) . time() . '.' . $extension;
                    $contractor->pan = $file->move(('uploads'), $filename);
                } else {
                    session()->put('failed', 'Please add jpeg/png format images');
                    return redirect('/project_heads/add_contractor');
                }
            }
            if (!empty($req->file('gst'))) {
                $file = $req->file('gst');
                $mime_type = $file->getClientMimeType();
                $extension = $file->getClientOriginalExtension();
                if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                    $filename = Str::random(4) . time() . '.' . $extension;
                    $contractor->gst = $file->move(('uploads'), $filename);
                } else {
                    session()->put('failed', 'Please add jpeg/png format images');
                    return redirect('/project_heads/add_contractor');
                }
            }


            $contractor->save();

            session()->put('success', 'Contractor Manager added successfully');

            // $user = Admin::where('user_name', $req->user_email)->first();

            // $req->session()->put('user',$user);

            return redirect('/project_heads/all_contractors');
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/project_heads')->with('message', 'You have been logged out!');
    }

    public function upload_file(Request $req)
    {
        $insert_count = 0;
        $duplicate_count = 0;

        set_time_limit(7200);

        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

        if (!empty($req->file('upload')) && $csvMimes) {


            if (is_uploaded_file($req->file('upload'))) {


                $csvFile = fopen($req->file('upload'), 'r');

                fgetcsv($csvFile);

                while (($line = fgetcsv($csvFile)) !== false) {
                    for ($i = 0; $i < 15; $i++) {
                        if (str_contains($line[$i], 'script')) {
                            session()->flush();
                            return redirect('login');
                        }
                    }
                    //dd($line);

                    $account_id_from_file = $line[2];

                    if (strlen($account_id_from_file) < 7) {
                        $account_id_from_file = "0000" . $account_id_from_file;
                    } elseif (strlen($account_id_from_file) < 8) {
                        $account_id_from_file = "000" . $account_id_from_file;
                    } elseif (strlen($account_id_from_file) < 9) {
                        $account_id_from_file = "00" . $account_id_from_file;
                    } elseif (strlen($account_id_from_file) < 10) {
                        $account_id_from_file = "0" . $account_id_from_file;
                    }

                    $sp_id_from_file = $line[19];

                    if (strlen($sp_id_from_file) < 7) {
                        $sp_id_from_file = "0000" . $sp_id_from_file;
                    } elseif (strlen($sp_id_from_file) < 8) {
                        $sp_id_from_file = "000" . $sp_id_from_file;
                    } elseif (strlen($sp_id_from_file) < 9) {
                        $sp_id_from_file = "00" . $sp_id_from_file;
                    } elseif (strlen($sp_id_from_file) < 10) {
                        $sp_id_from_file = "0" . $sp_id_from_file;
                    }


                    //commentted checking dupilcation account id

                    $existingConsumer = Consumer_detail::where('account_id', $account_id_from_file)->where('rr_no', $line[1])->first();
                    if ($existingConsumer == null) {
                        $consumer = new Consumer_detail();
                        // $consumer->tariff = $line[1];

                        // $consumer->mrcode = $line[2];
                        // $consumer->account_id = $line[3];
                        // // dd($line[3]);
                        // $consumer->rr_no = $line[4];
                        // $consumer->consumer_name = $line[5];
                        // $consumer->consumer_address = $line[6];
                        // // $consumer->read_date = $line[7];
                        // $consumer->meter_type = $line[7];
                        // $consumer->longitude = $line[8];
                        // $consumer->latitude = $line[9];
                        // $consumer->hescom = $line[10];
                        // $consumer->zone = $line[11];
                        // $consumer->circle = $line[12];
                        // $consumer->division = $line[13];
                        // $consumer->sd_pincode = $line[14];
                        // $consumer->so_pincode = $line[15];
                        // $consumer->sub_division = $line[16];
                        // $consumer->section = $line[17];
                        // $consumer->read_date = $line[18];

                        $consumer->rr_no = $line[1];
                        $consumer->account_id = $account_id_from_file;
                        $consumer->consumer_name = $line[3];
                        $consumer->consumer_address = $line[4];
                        $consumer->so_pincode = $line[5];
                        $consumer->sd_pincode = $line[6];
                        $meter_type = null;
                        $phase_name = null;
                        if (trim(Str::lower($line[22])) === 'single phase' || trim(Str::lower($line[22])) === 'singlephase') {
                            $meter_type = '1';
                            $phase_name = 'Single Phase';
                        }
                        if (trim(Str::lower($line[22])) === 'three phase' || trim(Str::lower($line[22])) === 'threephase') {
                            $meter_type = '2';
                            $phase_name = 'Three Phase';
                        }
                        $consumer->meter_type = $meter_type;
                        $consumer->tariff = $line[10];
                        $consumer->mrcode = $line[11];
                        $consumer->division = $line[15];
                        $consumer->section = $line[16];
                        $consumer->sub_division = $line[17];
                        $consumer->sp_id = $sp_id_from_file;

                        $consumer->feeder_name = $line[20];
                        $consumer->feeder_code = $line[21];
                        $consumer->phase_type = $phase_name;
                        // $consumer->read_date = $line[11];

                        $consumer->created_at = now();
                        $consumer->updated_at = now();

                        $consumer->save();
                        $insert_count++;
                    } else {
                        $duplicate_count++;
                        continue;
                    }
                }
                fclose($csvFile);
            }
        }
        //$messages = array('success' => 'Bulk Upload Successful with New Records ->'. $insert_count . ' and Duplicate records ->' . $duplicate_count);
        //dd(session()->get('success'));
        //$data = [
        //    'messages' => $messages
        //];

        session()->put('success_message', 'Bulk Upload Successful with New Records -> ' . $insert_count . ' and Duplicate records -> ' . $duplicate_count . ' ');
        //return redirect('/project_heads/index');

        //dd($data);
        return view('project_heads.consumer_bulk_upload');
    }

    public function upload_file1(Request $req)
    {
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

        if (!empty($req->file('upload')) && $csvMimes) {

            if (is_uploaded_file($req->file('upload'))) {

                $csvFile = fopen($req->file('upload'), 'r');
                fgetcsv($csvFile);

                while (($line = fgetcsv($csvFile)) !== FALSE) {

                    $validator = Validator::make($line, [
                        'account_id' => 'required|unique:consumer_details,account_id',
                    ]);

                    if ($validator->fails()) {
                        // Account ID already exists, skip this row
                        continue;
                    }

                    for ($i = 0; $i < 22; $i++) {
                        if (str_contains($line[$i], 'script')) {
                            session()->flush();
                            return redirect('login');
                        }
                    }

                    // Create new consumer detail record
                    $consumer = new Consumer_detail;
                    $consumer->tariff = $line[1];
                    $consumer->mrcode = $line[2];
                    $consumer->account_id = $line[3];
                    $consumer->rr_no = $line[4];
                    $consumer->consumer_name = $line[5];
                    $consumer->consumer_address = $line[6];
                    $consumer->read_date = $line[7];
                    $consumer->meter_type = $line[8];
                    $consumer->longitude = $line[10];
                    $consumer->latitude = $line[11];
                    $consumer->hescom = $line[12];
                    $consumer->zone = $line[13];
                    $consumer->circle = $line[14];
                    $consumer->division = $line[15];
                    $consumer->sd_pincode = $line[16];
                    $consumer->so_pincode = $line[17];
                    $consumer->save();
                }
                fclose($csvFile);
            }
        }

        session()->put('success', 'Bulk Upload Successfull');
        return redirect('/project_heads/index');
    }

    public function add_bmr()
    {
        return view('project_heads.add_bmr');
    }

    function create_bmr(Request $req)
    {
        // print_r($req->all());

        $auth = new Admin;


        $result = Admin::where('phone', $req->phone)->first();

        if ($result) {
            session()->put('failed', 'Phone already exists');

            return redirect('/project_heads/add_bmr');
        } else {

            $auth->name = $req->name;

            $auth->phone = $req->phone;

            $auth->project_name = $req->project_name;

            $auth->type = "bmr";
            $auth->password = Hash::make($req->password);

            if (strlen((string)$auth->phone) < 10) {
                session()->put('failed', 'Mobile nummber should be at least 10 digits');
                return redirect()->back();
            }


            $uppercase = preg_match('@[A-Z]@', $req->password);
            $lowercase = preg_match('@[a-z]@', $req->password);
            $number = preg_match('@[0-9]@', $req->password);
            $specialChars = preg_match('@[^\w]@', $req->password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($req->password) < 8) {

                session()->put('failed', 'Password should be atleast 8 characters & must include atleast one upper case letter, one number, and one special character');
                return redirect()->back();
            }

            $auth->created_by = session()->get('rexkod_vishvin_auth_userid');
            $auth->save();
            session()->put('success', 'Inventory Manager added successfully');


            return redirect('/project_heads/all_bmr');
        }

    }

    public function all_bmr()
    {

        // $data = Admin::where('type', 'contractor_manager')->where('created_by', session()->get('rexkod_vishvin_auth_userid'))->get();
        // dd($data);
        return view('project_heads.all_bmr', [
            'show_users' => Admin::where('type', 'bmr')->where('created_by', session()->get('rexkod_vishvin_auth_userid'))->get(),
        ]);
    }

    public function reports()
    {
        $total_meter_replaced = DB::table('meter_mains')->select(DB::raw('count(*) as total_meter_replaced_count'))->first();
        //dd($total_meter_replaced);
        $pending_in_qc = DB::table('meter_mains')->where('qc_status', 0)->select(DB::raw('count(*) as pending_in_qc_count'))->first();
        //dd($pending_in_qc);
        $pending_in_ae = DB::table('meter_mains')->where('qc_status', 1)->where('so_status', 0)->select(DB::raw('count(*) as pending_in_ae_count'))->first();
        //dd($pending_in_ae);
        $pending_in_aee = DB::table('meter_mains')->where('qc_status', 1)->where('so_status', 1)->where('aee_status', 0)->select(DB::raw('count(*) as pending_in_aee_count'))->first();
        //dd($pending_in_aee,);
        $pending_in_aao = DB::table('meter_mains')->where('qc_status', 1)->where('so_status', 1)->where('aee_status', 1)->where('aao_status', 0)->select(DB::raw('count(*) as pending_in_aao_count'))->first();
        //dd($pending_in_aao);

        $successful_records = DB::table('successful_records')->select(DB::raw('count(*) as get_total_successful_record_count_bmr_count'))->first();
        //$get_total_successful_record_count_bmr = count($get_total_successful_record_bmr);
        $error_records = DB::table('error_records')->where('updated_by_aao', 0)->select(DB::raw('count(*) as error_records_count'))->first();
        //dd($error_records);
        // pending at bmr download
        $pending_in_bmr = DB::table('meter_mains')->where(function ($query) {
            $query->where('aao_status', 1)
                ->where('download_flag', 0);
        })
            ->orWhere(function ($query) {
                $query->where('error_updated_by_aao', 1);
                // ->where('download_flag',0);
            })
            ->select(DB::raw('count(*) as pending_in_bmr_count'))
            ->first();
        //dd($pending_in_bmr);
        //$pending_in_bmr = count($approved_meters);
        $data = [
            'total_meter_replaced' => $total_meter_replaced->total_meter_replaced_count,
            'pending_in_qc' => $pending_in_qc->pending_in_qc_count,
            'pending_in_ae' => $pending_in_ae->pending_in_ae_count,
            'pending_in_aee' => $pending_in_aee->pending_in_aee_count,
            'pending_in_aao' => $pending_in_aao->pending_in_aao_count,
            'error_records' => $error_records->error_records_count,
            'get_total_successful_record_count_bmr' => $successful_records->get_total_successful_record_count_bmr_count,
            'pending_in_bmr' => $pending_in_bmr->pending_in_bmr_count,
        ];

        return view('project_heads.reports', ['data' => $data]);
    }


    public function report_filter_view()
    {
        $user_id = session('rexkod_vishvin_auth_userid');
        $package_name = env('PACKAGE_NAME');
        $get_all_division_codes = DB::table('zone_codes')
            ->select('division', 'div_code')
            ->where('package', $package_name)
            ->distinct()
            ->get();

        $admin = Admin::where('id', $user_id)->first();
        $zone_code = Zone_code::where('so_code', $admin->so_pincode)->first();

        $data = [
            'divisions' => $get_all_division_codes,
            'zone_code' => $zone_code,
            'user_data' => $admin
        ];

        //dd($data);

        return view('project_heads.report_filter', ['data' => $data]);
    }

    public function get_sd_code($division)
    {
        $sd_pincode = DB::table('zone_codes')
            ->select('sd_code', 'sub_division')
            ->where('div_code', $division)
            ->groupBy('sd_code', 'sub_division')
            ->get();

        return response()->json($sd_pincode);
    }

    public function get_so_code($sub_division)
    {
        # code...
        $so_pincode = DB::table('zone_codes')
            // ->select('sd_code')
            ->where('sd_code', $sub_division)
            ->get();

        return response()->json($so_pincode);
    }

    public function report_filter(Request $request)
    {
        $user_id = session('rexkod_vishvin_auth_userid');
        $admin = Admin::where('id', $user_id)->first();
        $zone_code = Zone_code::where('so_code', $admin->so_pincode)->first();
        $package_name = env('PACKAGE_NAME');
        $get_all_division_codes = DB::table('zone_codes')
            ->select('division', 'div_code')
            ->where('package', $package_name)
            ->distinct()
            ->get();
//        dd($request);
        // dd($request->format);
        $end_date = null;
        $start_date = null;
        $requestData = $request->all();
        //dd($requestData);
        if ($requestData['format'] == 'weekly') {
            $today = Carbon::now();
            $dateSevenDaysAgo = Carbon::now()->subDays(7);
            $start_date = $dateSevenDaysAgo->format('Y-m-d');
            $end_date = $today->format('Y-m-d');
        } else if ($requestData['format'] == 'monthly') {
            $today = Carbon::now();
            $dateMonthsAgo = Carbon::now()->subDays(30);
            $start_date = $dateMonthsAgo->format('Y-m-d');
            $end_date = $today->format('Y-m-d');
        } else if ($requestData['format'] == 'daily') {
            // $today = Carbon::now();
            $start_date = Carbon::now()->format('Y-m-d');
            // $end_date = Carbon::parse($start_date)->addDay()->format('Y-m-d');
            $end_date = $start_date;
        } else if ($requestData['format'] == 'custom') {
            if ($requestData['start_date'] !== NUll && $requestData['end_date'] !== NUll) {
                $start_date = $requestData['start_date'];
                $end_date = $requestData['end_date'];
                // adding one day because its coming till the prevoius day - because the timestamp is differnt in the mysql
//                    $end_date = Carbon::parse($end_date)->addDay()->format('Y-m-d');
                $end_date = Carbon::parse($end_date)->format('Y-m-d');

            }
        }
        if (isset($requestData['section'])) {
            $section = $requestData['section'];
        } else {
            $section = 'null';
        }
        if (isset($requestData['division'])) {
            $division = $requestData['division'];
        } else {
            $division = 'null';
        }
        if (isset($requestData['sub_division'])) {
            $sub_division = $requestData['sub_division'];
        } else {
            $sub_division = 'null';
        }
        if (isset($requestData['feeder_code'])) {
            $feeder_code = $requestData['feeder_code'];
        } else {
            $feeder_code = 'null';
        }
        // reports
        if ($requestData['report_type'] == "1") {
            return redirect('/project_heads/release_meter_report_view/' . $start_date . '/' . $end_date . '/' . $division . '/' . $sub_division . '/' . $section . '/' . $feeder_code);
        } else if ($requestData['report_type'] == "2") {
            // return redirect('/project_heads/meter_replacement_report/'.$req->section . '/'.$start_date . '/'.$end_date);
            return redirect('/project_heads/meter_replacement_report_view/' . $start_date . '/' . $end_date . '/' . $division . '/' . $sub_division . '/' . $section . '/' . $feeder_code);
        } else if ($requestData['report_type'] == "3") {
            // return redirect('/project_heads/anx_1_abstract_report/'.$req->section . '/'.$start_date . '/'.$end_date);
            return redirect('/project_heads/anx_1_abstract_report/' . $start_date . '/' . $end_date . '/' . $division . '/' . $sub_division . '/' . $section);

        } else if ($requestData['report_type'] == "4") {
            // return redirect('/project_heads/anx_1_detailed_report/'.$req->section . '/'.$start_date . '/'.$end_date);
            return redirect('/project_heads/anx_1_detailed_report/' . $start_date . '/' . $end_date . '/' . $division . '/' . $sub_division . '/' . $section);

        } else if ($requestData['report_type'] == "5") {
            // return redirect('/project_heads/anx_3_report/'.$req->section . '/'.$start_date . '/'.$end_date);
            return redirect('/project_heads/anx_3_report/' . $start_date . '/' . $end_date . '/' . $division . '/' . $sub_division . '/' . $section);

        } else if ($requestData['report_type'] == "6") {

            $bmrServiceObj = new BmrDownloadService();

            $success_count = $bmrServiceObj->getSuccessCount($start_date, $end_date, $division, $sub_division, $section, $feeder_code);

            $error_count = $bmrServiceObj->getErrorCount($start_date, $end_date, $division, $sub_division, $section, $feeder_code);

            $results = [
                'success_count' => $success_count,
                'error_count' => $error_count,
                'success_report_url' => env('APP_URL') . '/project_heads/view_bmr_status_success_report/' . $start_date . '/' . $end_date . '/' . $division . '/' . $sub_division . '/' . $section . '/' . $feeder_code,
                'error_report_url' => env('APP_URL') . '/project_heads/view_bmr_status_error_report/' . $start_date . '/' . $end_date . '/' . $division . '/' . $sub_division . '/' . $section . '/' . $feeder_code,
                'from_date' => $start_date,
                'to_date' => $end_date,
                'section' => $section,
                'report_type' => "6",
                'format' => $requestData['format'],
                'divisions' => $get_all_division_codes,
                'zone_code' => $zone_code,
                'user_data' => $admin
            ];

            // return redirect('/project_heads/anx_3_report/'.$req->section . '/'.$start_date . '/'.$end_date);
            return view('/project_heads/report_filter', ['data' => $results]);
            // return redirect('/project_heads/report_filter'.$start_date . '/'.$end_date. '/'.$division. '/'.$sub_division.'/'.$section);

        }

    }

    public function release_meter_report_view($start_date, $end_date = null, $division = null, $subdivision = null, $section = null, $feeder_code = null)
    {
        $admin = Admin::where('id', session('rexkod_vishvin_auth_userid'))->first();
        $contractors = DB::table('admins')->where('type', '=', 'contractor_manager')->select('admins.id as contractor_id', 'admins.name as contractor_name')->get();
        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'division' => $division,
            'subdivision' => $subdivision,
            'section' => $section,
            'feeder_code' => $feeder_code,
            'contractors' => $contractors,
            'users' => $admin,
        ];

        return view('project_heads.release_meter_report', ['data' => $data]);
    }

    public function release_meter_report_data(Request $request, BmrDownloadService $bmrDownloadService)
    {

//            $admin = Admin::where('id', session('rexkod_vishvin_auth_userid'))->first();
//            // Initialize the base query
//            $contractors = DB::table('admins')->where('type', '=', 'contractor_manager')->select('admins.id as contractor_id', 'admins.name as contractor_name')->get();
//            //dd($contractors);
//
//            //$fieldExecutives = DB::table('admins')->where('type', '=','field_executive')->select('admins.id as field_executive_id', 'admins.name as field_executive_name', 'admins.created_by as field_executive_contractor_id')->get();
//            //dd($fieldExecutives);
//
//            $query = DB::table('meter_mains')
//                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
//                ->join('admins', 'meter_mains.created_by', "=", "admins.id")
//                ->whereNotNull('meter_mains.serial_no_new')
//                ->whereNotNull('serial_no_old')
//                ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
//                ->select(\DB::raw('admins.name as field_executive_name,
//        admins.created_by as field_executive_contractor_id,
//        meter_mains.created_by as field_executive_id,
//        meter_mains.created_at,
//        meter_mains.serial_no_old,
//        meter_mains.meter_make_old,
//        meter_mains.mfd_year_old,
//        meter_mains.final_reading,
//        meter_mains.serial_no_new,
//        meter_mains.initial_reading_kvah,
//        consumer_details.meter_type,
//         consumer_details.account_id,
//         consumer_details.rr_no,
//         consumer_details.consumer_name,
//         consumer_details.feeder_name,
//         consumer_details.feeder_code,
//         consumer_details.section,
//         consumer_details.sub_division,
//         consumer_details.tariff,
//         consumer_details.phase_type,
//         consumer_details.sub_division'));
////                ->select(\DB::raw('meter_mains.created_by as field_executive_id, meter_mains.serial_no_old, meter_mains.meter_make_old, meter_mains.mfd_year_old, meter_mains.final_reading, meter_mains.serial_no_new, meter_mains.initial_reading_kvah'))
////                ->select(\DB::raw('consumer_details.account_id, consumer_details.rr_no, consumer_details.consumer_name, consumer_details.feeder_name, consumer_details.feeder_code, consumer_details.section, consumer_details.sub_division, consumer_details.tariff, consumer_details.phase_type, consumer_details.sub_division'));
//
//            // Add division and subdivision conditions if provided
//            if (!empty($division) && $division != "null") {
//                $query->where('consumer_details.division', '=', $division);
//            }
//            if (!empty($subdivision) && $subdivision != "null") {
//                $query->where('consumer_details.sd_pincode', '=', $subdivision);
//            }
//            if (!empty($section) && $section != "null") {
//                $query->where('consumer_details.so_pincode', '=', $section);
//            }
//            if (!empty($feeder_code) && $feeder_code != "null") {
//                $query->where('consumer_details.feeder_code', '=', $feeder_code);
//            }
//
//            // Add end date condition if provided
//            if ($end_date != null) {
//                $query->where('meter_mains.created_at', '<=', $end_date . ' 23:59:59');
//            }
//            // Execute the query
//            try {
//                $meter_main = $query->get();
//                //dd($meter_main);
//            } catch (\Exception $e) {
//                dd($e);
//            }
//
////    $changedMeterMainData = array();
////   foreach($meter_main as $meterMainKey=>$meterMainData){
////       foreach($contractors as $contractorKey => $contractorData){
////           //dd($meterMainData->field_executive_contractor_id, $contractorData->contractor_id);
////           $changedMeterMainData[] = $meterMainData;
////
////           if($meterMainData->field_executive_contractor_id === (string)$contractorData->contractor_id){
////               dd($meterMainData, $contractorData, $changedMeterMainData[$meterMainKey]);
////               ['field_executive_contractor_name'] = (string)$contractorData->contractor_name;
////           }
////           dd($changedMeterMainData);
////       }
////   }
////    dd($changedMeterMainData);
//            $data = [
//                'meter_main' => $meter_main,
//                'division' => $division,
//                'subdivision' => $subdivision,
//                'section' => $section,
//                'contractors' => $contractors,
//                'users' => $admin,
//            ];
//            //dd($data);
//            return view('project_heads.release_meter_report', ['data' => $data]);

        $filter_data = array();
        $request_data = $request->all();
        if (isset($request_data['start_date'])) $filter_data['start_date'] = $request_data['start_date'];
        if (isset($request_data['end_date'])) $filter_data['end_date'] = $request_data['end_date'];
        if (isset($request_data['subdivision'])) $filter_data['subdivision'] = $request_data['subdivision'];
        if (isset($request_data['section'])) $filter_data['section'] = $request_data['section'];
        if (isset($request_data['division'])) $filter_data['division'] = $request_data['division'];
        if (isset($request_data['feeder_code'])) $filter_data['feeder_code'] = $request_data['feeder_code'];
        if (isset($request_data['search']['value'])) $filter_data['search_value'] = $request_data['search']['value'];
        if (isset($request_data['account_id'])) $filter_data['account_id'] = $request_data['account_id'];
        if (isset($request_data['rr_no'])) $filter_data['rr_no'] = $request_data['rr_no'];
        if (isset($request_data['meter_new_serial_no'])) $filter_data['meter_serial_no_new'] = $request_data['meter_new_serial_no'];

        $column_list_data = [
            'meter_mains.created_by as field_executive_id',
            'meter_mains.created_at',
            'meter_mains.serial_no_old',
            'meter_mains.meter_make_old',
            'meter_mains.mfd_year_old',
            'meter_mains.final_reading',
            'meter_mains.serial_no_new',
            'meter_mains.initial_reading_kvah',
            'consumer_details.meter_type',
            'consumer_details.division',
            'consumer_details.sub_division',
            'consumer_details.section',
            'consumer_details.account_id',
            'consumer_details.rr_no',
            'consumer_details.consumer_name',
            'consumer_details.feeder_name',
            'consumer_details.feeder_code',
            'consumer_details.section',
            'consumer_details.sub_division',
            'consumer_details.tariff',
            'consumer_details.phase_type',
            'consumer_details.sub_division',
            'admins.name as field_executive_name',
            'admins.created_by as field_executive_contractor_id',
        ];

        $pagination_data = [
            "limit" => 50,
            "start" => $request_data['start'],
            "length" => $request_data['length'],
        ];

        try {
            $module = 'release_meter';
            //$success_query_results = $success_query->get();
            $aResults = $bmrDownloadService->getSuccessRecordsDataByFilter($module, $filter_data, $column_list_data, $pagination_data);
            //dd($results);
        } catch (\Exception $e) {
            dd($e);
        }

        $aResult = [
            'data' => $aResults['data'],
            'draw' => $request_data['draw'],
            'recordsFiltered' => $aResults['recordsFiltered'],
            'recordsTotal' => $aResults['recordsTotal']
        ];
        return response()->json($aResult);
    }

    public function meter_replacement_report_view($start_date, $end_date = null, $division = null, $subdivision = null, $section = null, $feeder_code = null)
    {

//            $admin = Admin::where('id', session('rexkod_vishvin_auth_userid'))->first();
//            // Initialize the base query
//            $contractors = DB::table('admins')->where('type', '=', 'contractor_manager')->select('admins.id as contractor_id', 'admins.name as contractor_name')->get();
//            //dd($contractors);
//
//            //$fieldExecutives = DB::table('admins')->where('type', '=','field_executive')->select('admins.id as field_executive_id', 'admins.name as field_executive_name', 'admins.created_by as field_executive_contractor_id')->get();
//            //dd($fieldExecutives);
//
//            $query = DB::table('meter_mains')
//                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
//                ->join('admins', 'meter_mains.created_by', "=", "admins.id")
//                ->whereNotNull('meter_mains.serial_no_new')
//                ->whereNotNull('serial_no_old')
//                ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
//                ->select(\DB::raw('admins.name as field_executive_name,
//            admins.created_by as field_executive_contractor_id,
//            meter_mains.created_by as field_executive_id,
//            meter_mains.created_at,
//            meter_mains.serial_no_old,
//            meter_mains.meter_make_old,
//            meter_mains.mfd_year_old,
//            meter_mains.final_reading,
//            meter_mains.serial_no_new,
//            meter_mains.initial_reading_kvah,
//            meter_mains.lat,
//            meter_mains.lon,
//            consumer_details.meter_type,
//             consumer_details.account_id,
//             consumer_details.rr_no,
//             consumer_details.consumer_name,
//             consumer_details.feeder_name,
//             consumer_details.feeder_code,
//             consumer_details.section,
//             consumer_details.sub_division,
//             consumer_details.tariff,
//             consumer_details.phase_type,
//             consumer_details.sub_division'));
////    $query = Meter_main::join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
////                        ->whereNotNull('meter_mains.serial_no_new')
////                        ->whereNotNull('serial_no_old')
////                        ->where('meter_mains.created_at', '>=', $start_date)
////                        ->select('meter_mains.*', 'consumer_details.*');
//
//            // Add division and subdivision conditions if provided
//
//            if (!empty($division) && $division != "null") {
//                $query->where('consumer_details.division', '=', $division);
//            }
//            if (!empty($subdivision) && $subdivision != "null") {
//                $query->where('consumer_details.sd_pincode', '=', $subdivision);
//            }
//            if (!empty($section) && $section != "null") {
//                $query->where('consumer_details.so_pincode', '=', $section);
//            }
//            if (!empty($feeder_code) && $feeder_code != "null") {
//                $query->where('consumer_details.feeder_code', '=', $feeder_code);
//            }
//
//            // Add end date condition if provided
//            if ($end_date != null) {
//                $query->where('meter_mains.created_at', '<=', $end_date . ' 23:59:59');
//            }
//
//            // Execute the query
//            try {
//                $meter_main = $query->get();
//                //dd($meter_main);
//            } catch (\Exception $e) {
//                dd($e);
//            }
//
//
//            $data = [
//                'meter_main' => $meter_main,
//                'division' => $division,
//                'subdivision' => $subdivision,
//                'section' => $section,
//                'contractors' => $contractors,
//                'users' => $admin,
//            ];
//            //dd($data);
//
//            return view('project_heads.meter_replacement_report', ['data' => $data]);

        $admin = Admin::where('id', session('rexkod_vishvin_auth_userid'))->first();
        $contractors = DB::table('admins')->where('type', '=', 'contractor_manager')->select('admins.id as contractor_id', 'admins.name as contractor_name')->get();
        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'division' => $division,
            'subdivision' => $subdivision,
            'section' => $section,
            'feeder_code' => $feeder_code,
            'contractors' => $contractors,
            'users' => $admin,
        ];

        return view('project_heads.meter_replacement_report', ['data' => $data]);
    }

    public function meter_replacement_report_data(Request $request, BmrDownloadService $bmrDownloadService)
    {
        $filter_data = array();
        $column_list_data = array();
        $pagination_data = array();
        $request_data = $request->all();

        if (isset($request_data['start_date'])) $filter_data['start_date'] = $request_data['start_date'];
        if (isset($request_data['end_date'])) $filter_data['end_date'] = $request_data['end_date'];
        if (isset($request_data['subdivision'])) $filter_data['subdivision'] = $request_data['subdivision'];
        if (isset($request_data['section'])) $filter_data['section'] = $request_data['section'];
        if (isset($request_data['division'])) $filter_data['division'] = $request_data['division'];
        if (isset($request_data['feeder_code'])) $filter_data['feeder_code'] = $request_data['feeder_code'];
        if (isset($request_data['search']['value'])) $filter_data['search_value'] = $request_data['search']['value'];
        if (isset($request_data['account_id'])) $filter_data['account_id'] = $request_data['account_id'];
        if (isset($request_data['rr_no'])) $filter_data['rr_no'] = $request_data['rr_no'];
        if (isset($request_data['meter_new_serial_no'])) $filter_data['meter_serial_no_new'] = $request_data['meter_new_serial_no'];

        $column_list_data = [
            'meter_mains.created_by as field_executive_id',
            'meter_mains.created_at',
            'meter_mains.serial_no_old',
            'meter_mains.meter_make_old',
            'meter_mains.mfd_year_old',
            'meter_mains.final_reading',
            'meter_mains.serial_no_new',
            'meter_mains.initial_reading_kvah',
            'meter_mains.lat',
            'meter_mains.lon',
            'consumer_details.meter_type',
            'consumer_details.division',
            'consumer_details.sub_division',
            'consumer_details.section',
            'consumer_details.account_id',
            'consumer_details.rr_no',
            'consumer_details.consumer_name',
            'consumer_details.feeder_name',
            'consumer_details.feeder_code',
            'consumer_details.section',
            'consumer_details.sub_division',
            'consumer_details.tariff',
            'consumer_details.phase_type',
            'consumer_details.sub_division',
            'admins.name as field_executive_name',
            'admins.created_by as field_executive_contractor_id',
        ];


        $pagination_data = [
            "limit" => 50,
            "start" => $request_data['start'],
            "length" => $request_data['length'],
        ];

        try {
            $module = 'meter_replacement';
            //$success_query_results = $success_query->get();
            $aResults = $bmrDownloadService->getSuccessRecordsDataByFilter($module, $filter_data, $column_list_data, $pagination_data);
            //dd($results);
        } catch (\Exception $e) {
            dd($e);
        }

        $aResult = [
            'data' => $aResults['data'],
            'draw' => $request_data['draw'],
            'recordsFiltered' => $aResults['recordsFiltered'],
            'recordsTotal' => $aResults['recordsTotal']
        ];
        return response()->json($aResult);
    }

    public function anx_1_detailed_report1($section, $start_date, $end_date = null)
    {
        if ($end_date == null) {
            $meter_main = Meter_main::join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->join('zone_codes', 'zone_codes.so_code', '=', 'consumer_details.so_pincode')
                ->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('serial_no_old')
                ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
                ->where('consumer_details.so_pincode', '=', $section)
                ->select('zone_codes.*', 'consumer_details.account_id', 'consumer_details.rr_no', 'consumer_details.tariff', 'meter_mains.final_reading', 'meter_mains.initial_reading_kwh', 'meter_mains.created_at', 'meter_mains.so_status', 'meter_mains.aee_status', 'meter_mains.aao_status')
                ->get();
            // dd($meter_main);

            $zone_code = Zone_code::where('so_code', $section)->first();
            $data = [
                'meter_main' => $meter_main,
                'zone_code' => $zone_code,
            ];
        } else {

            $meter_main = Meter_main::join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->join('zone_codes', 'zone_codes.so_code', '=', 'consumer_details.so_pincode')
                ->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('serial_no_old')
                ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
                ->where('meter_mains.created_at', '<=', $end_date . ' 23:59:59')
                ->where('consumer_details.so_pincode', '=', $section)
                ->select('zone_codes.*', 'consumer_details.account_id', 'consumer_details.rr_no', 'consumer_details.tariff', 'meter_mains.final_reading', 'meter_mains.initial_reading_kwh', 'meter_mains.created_at', 'meter_mains.so_status', 'meter_mains.aee_status')
                ->get();
            $zone_code = Zone_code::where('so_code', $section)->first();

            $data = [
                'meter_main' => $meter_main,
                'zone_code' => $zone_code,

            ];
        }


        return view('project_heads.anx_1_detailed_report', ['data' => $data]);
    }

    public function anx_1_detailed_report($start_date, $end_date = null, $division = null, $subdivision = null, $section = null, $feeder_code = null)
    {
        // Initialize the base query
        $query = Meter_main::join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->join('zone_codes', 'zone_codes.so_code', '=', 'consumer_details.so_pincode')
            ->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('serial_no_old')
            ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
            ->select('zone_codes.*', 'consumer_details.account_id', 'consumer_details.rr_no', 'consumer_details.tariff', 'meter_mains.final_reading', 'meter_mains.initial_reading_kwh', 'meter_mains.created_at', 'meter_mains.so_status', 'meter_mains.aee_status');

        // Add division and subdivision conditions if provided

        if (!empty($division) && $division != "null") {
            $query->where('consumer_details.division', '=', $division);
        }
        if (!empty($subdivision) && $subdivision != "null") {
            $query->where('consumer_details.sd_pincode', '=', $subdivision);
        }
        if (!empty($section) && $section != "null") {
            $query->where('consumer_details.so_pincode', '=', $section);
        }
        if (!empty($feeder_code) && $feeder_code != "null") {
            $query->where('consumer_details.feeder_code', '=', $feeder_code);
        }

        // Add end date condition if provided
        if ($end_date != null) {
            $query->where('created_at', '<=', $end_date . ' 23:59:59');
        }

        // Execute the query
        $meter_main = $query->get();


        $data = [
            'meter_main' => $meter_main,
            'division' => $division,
            'subdivision' => $subdivision,
            'section' => $section,

        ];

        return view('project_heads.anx_1_detailed_report', ['data' => $data]);
    }


    public function anx_1_abstract_report($start_date, $end_date = null, $division = null, $subdivision = null, $section = null, $feeder_code = null)
    {
        // Initialize the base query
        $query = DB::table('meter_mains')
            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->join('annexure_1s', 'annexure_1s.so_pincode', '=', 'consumer_details.so_pincode')
            ->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('serial_no_old')
            ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
            ->groupBy('consumer_details.so_pincode', 'annexure_1s.target_to_achieve', 'annexure_1s.sd_pincode', 'annexure_1s.division')
            ->select('consumer_details.so_pincode', DB::raw('COUNT(*) as replaced_count'), 'annexure_1s.target_to_achieve', 'annexure_1s.sd_pincode', 'annexure_1s.division',
                DB::raw('SUM(CASE WHEN meter_mains.aao_status = 1 THEN 1 ELSE 0 END) as bmr_prepared'),// if aao approved and it is in bmr
                DB::raw('SUM(CASE WHEN meter_mains.download_flag != 0 THEN 1 ELSE 0 END) as bmr_updated_count'));//if bmr generated the report

        // Add division and subdivision conditions if provided
        if (!empty($division) && $division != "null") {
            $query->where('consumer_details.division', '=', $division);
        }
        if (!empty($subdivision) && $subdivision != "null") {
            $query->where('consumer_details.sd_pincode', '=', $subdivision);
        }
        if (!empty($section) && $section != "null") {
            $query->where('consumer_details.so_pincode', '=', $section);
        }
        if (!empty($feeder_code) && $feeder_code != "null") {
            $query->where('consumer_details.feeder_code', '=', $feeder_code);
        }

        // Add end date condition if provided
        if ($end_date != null) {
            $query->where('meter_mains.created_at', '<=', $end_date . ' 23:59:59');
        }

        // Execute the query
        $annexure_1 = $query->get();


        $data = [
            'annexure_1' => $annexure_1,
            'division' => $division,
            'subdivision' => $subdivision,
            'section' => $section,

        ];

        return view('project_heads.anx_1_abstract_report', ['data' => $data]);
    }

    public function anx_3_report($start_date, $end_date = null, $division = null, $subdivision = null, $section = null, $feeder_code = null)
    {
        // Initialize the base query
        $query = DB::table('meter_mains')
            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->join('annexure_1s', 'annexure_1s.so_pincode', '=', 'consumer_details.so_pincode')
            ->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('serial_no_old')
            ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
            ->groupBy('consumer_details.so_pincode', 'annexure_1s.target_to_achieve', 'annexure_1s.sd_pincode', 'annexure_1s.division')
            ->select('consumer_details.so_pincode', DB::raw('COUNT(*) as replaced_count'), 'annexure_1s.target_to_achieve', 'annexure_1s.sd_pincode', 'annexure_1s.division',
                DB::raw('SUM(CASE WHEN meter_mains.qc_status = 0 THEN 1 ELSE 0 END) as pending_for_verification'),// pending_for_verification at qc
                DB::raw('SUM(CASE WHEN meter_mains.aee_status = 1 AND meter_mains.aao_status = 0 THEN 1 ELSE 0 END) as pending_for_approval'),// pending_for_verification at aao
                DB::raw('SUM(CASE WHEN meter_mains.aao_status = 1 AND meter_mains.download_flag = 0 THEN 1 ELSE 0 END) as pending_for_bmr_upload'));//if bmr not generated the report
        // Add division and subdivision conditions if provided
        if (!empty($division) && $division != "null") {
            $query->where('consumer_details.division', '=', $division);
        }
        if (!empty($subdivision) && $subdivision != "null") {
            $query->where('consumer_details.sd_pincode', '=', $subdivision);
        }
        if (!empty($section) && $section != "null") {
            $query->where('consumer_details.so_pincode', '=', $section);
        }
        if (!empty($feeder_code) && $feeder_code != "null") {
            $query->where('consumer_details.feeder_code', '=', $feeder_code);
        }

        // Add end date condition if provided
        if ($end_date != null) {
            $query->where('meter_mains.created_at', '<=', $end_date . ' 23:59:59');
        }

        // Execute the query
        $annexure_1 = $query->get();

        $data = [
            'annexure_1' => $annexure_1,
            'division' => $division,
            'subdivision' => $subdivision,
            'section' => $section,
        ];

        return view('project_heads.anx_3_report', ['data' => $data]);
    }

// inventory reports
    public function inventory_report_filter_view()
    {
        $contractor = DB::table('admins')
            ->where('type', 'contractor_manager')
            ->get();
        $package_name = env('PACKAGE_NAME');
        $get_all_division_codes = DB::table('zone_codes')
            ->select('division', 'div_code')
            ->where('package', $package_name)
            ->distinct()
            ->get();

        $data = [
            'contractors' => $contractor,
            'divisions' => $get_all_division_codes
        ];
        //dd($data);

        return view('project_heads.inventory_report_filter', compact('data'));
    }


    public function inventory_report_filter(Request $req)
    {
        // dd($req->format);
        $start_date = 'null';
        $end_date = 'null';
        $contractor_id = 'null';
        $division = 'null';
        if ($req->format == 'weekly') {
            $today = Carbon::now();
            $dateSevenDaysAgo = Carbon::now()->subDays(7);
            $start_date = $dateSevenDaysAgo->format('Y-m-d');
            $end_date = $today->format('Y-m-d');
        } else if ($req->format == 'monthly') {
            $today = Carbon::now();
            $dateMonthsAgo = Carbon::now()->subDays(30);

            $start_date = $dateMonthsAgo->format('Y-m-d');
            $end_date = $today->format('Y-m-d');
        } else if ($req->format == 'daily') {
            // $today = Carbon::now();
            $start_date = Carbon::now();
        } else if ($req->format == 'custom') {
            if ($req->get('start_date') !== NUll && $req->get('end_date') !== NUll) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');
                // adding one day because its coming till the prevoius day - because the timestamp is differnt in the mysql
                $end_date = Carbon::parse($end_date)->format('Y-m-d');
            }
        }
        if (!empty($req->contractor_id)) {
            $contractor_id = $req->contractor_id;
            //dd($contractor_id);
        }
        if (!empty($req->division)) {
            $division = $req->division;
            //dd($contractor_id);
        }

        // reports
        if ($req->report_type == 1) {
            return redirect('/project_heads/inward_meter_report/' . $division . '/' . $start_date . '/' . $end_date);
        } else if ($req->report_type == 2) {
            return redirect('/project_heads/outward_meter_report/' . $division . '/' . $start_date . '/' . $end_date);
        } else if ($req->report_type == 3) {
            return redirect('/project_heads/contractor_wise_stock_report/' . $division . '/' . $start_date . '/' . $end_date . '/' . $contractor_id);
        } else if ($req->report_type == 4) {
            return redirect('/project_heads/contractor_wise_installation_report/' . $division . '/' . $start_date . '/' . $end_date . '/' . $contractor_id);
        } else if ($req->report_type == 5) {
            return redirect('/project_heads/qc_report/' . $division . '/' . $start_date . '/' . $end_date);
        } else if ($req->report_type == 6) {
            return redirect('/project_heads/fe_wise_installation_report/' . $req->division . '/' . $start_date . '/' . $end_date);
        } else if ($req->report_type == 7) {
            return redirect('/project_heads/section-wise-inward-installation-report');
        }

    }

    public function inward_meter_report($division, $start_date, $end_date = null)
    {
        if ($end_date == null) {
            $inward_meter = Warehouse_meter::join('lot_details as lot', 'warehouse_meters.lot_no', '=', 'lot.id')
                ->where('lot.created_at', '>=', $start_date . ' 00:00:00')
                ->where('lot.division', '=', $division)
                ->where('lot.complete_status', 1)
                ->select('lot.created_at', 'lot.id',
                    \DB::raw('count(*) as box_count'),
                    \DB::raw("SUM(LENGTH(warehouse_meters.meter_serial_no) - LENGTH(REPLACE(warehouse_meters.meter_serial_no, ',', '')) + 1) as comma_count"))
                ->groupBy('lot.id', 'lot.created_at') // Include all non-aggregated columns in the GROUP BY clause
                ->get();


            $data = [
                'inward_meter' => $inward_meter,
            ];
        } else {

            $inward_meter = Warehouse_meter::join('lot_details as lot', 'warehouse_meters.lot_no', '=', 'lot.id')
                ->where('lot.created_at', '>=', $start_date . ' 00:00:00')
                ->where('lot.created_at', '<=', $end_date . ' 23:59:59')
                ->where('lot.division', '=', $division)
                ->where('lot.complete_status', 1)
                ->select('lot.created_at', 'lot.id',
                    \DB::raw('count(*) as box_count'),
                    \DB::raw("SUM(LENGTH(warehouse_meters.meter_serial_no) - LENGTH(REPLACE(warehouse_meters.meter_serial_no, ',', '')) + 1) as comma_count"))
                ->groupBy('lot.id', 'lot.created_at') // Include all non-aggregated columns in the GROUP BY clause
                ->get();


            $data = [
                'inward_meter' => $inward_meter,
            ];
        }

        return view('project_heads.inward_meter_report', ['data' => $data]);

    }

    public function outward_meter_report($division, $start_date, $end_date = null)
    {
        if ($end_date == null) {
            // $outward_meter = Contractor_inventory::join('warehouse_meters as ware', 'contractor_inventories.box_id', '=', 'ware.id')
            // ->join('admins', 'contractor_inventories.contractor_id', '=', 'admins.id')
            // ->where('contractor_inventories.created_at', '>=', $start_date)
            // ->where('contractor_inventories.division', '=', $division)
            // ->select('admins.name','contractor_inventories.created_at', 'contractor_inventories.contractor_id','contractor_inventories.division','ware.box_id','ware.lot_no',
            // \DB::raw("LENGTH(serial_no) - LENGTH(REPLACE(serial_no, ',', '')) + 1 as meter_count"))
            // ->get();

            $outward_meter = Contractor_inventory::join('warehouse_meters as ware', 'contractor_inventories.box_id', '=', 'ware.id')
                ->join('admins', 'contractor_inventories.contractor_id', '=', 'admins.id')
                ->where('contractor_inventories.created_at', '>=', $start_date . ' 00:00:00')
                ->where('contractor_inventories.division', '=', $division)
                ->select('admins.name', \DB::raw('DATE(contractor_inventories.created_at) as date'), 'contractor_inventories.contractor_id', 'contractor_inventories.division', 'ware.lot_no',
                    \DB::raw("SUM(
    CASE
        WHEN serial_no LIKE '%,%'
            THEN LENGTH(serial_no) - LENGTH(REPLACE(serial_no, ',', '')) + 1
        WHEN serial_no IS NOT NULL AND serial_no != ''
            THEN 1
        ELSE 0
    END
) as meter_count"),
                    \DB::raw("COUNT(ware.box_id) as box_id_count"))
                ->groupBy('date', 'contractor_id', 'admins.name', 'ware.lot_no', 'contractor_inventories.division')
                ->get();


            $data = [
                'outward_meter' => $outward_meter,
            ];
        } else {
            // $outward_meter = Contractor_inventory::join('warehouse_meters as ware', 'contractor_inventories.box_id', '=', 'ware.id')
            // ->join('admins', 'contractor_inventories.contractor_id', '=', 'admins.id')
            // ->where('contractor_inventories.created_at', '>=', $start_date)
            // ->where('contractor_inventories.created_at', '<=', $end_date)
            // ->where('contractor_inventories.division', '=', $division)
            // ->select('admins.name','contractor_inventories.created_at', 'contractor_inventories.contractor_id','contractor_inventories.division','ware.box_id','ware.lot_no',
            // \DB::raw("LENGTH(serial_no) - LENGTH(REPLACE(serial_no, ',', '')) + 1 as meter_count"))
            // ->get();


            $outward_meter = Contractor_inventory::join('warehouse_meters as ware', 'contractor_inventories.box_id', '=', 'ware.id')
                ->join('admins', 'contractor_inventories.contractor_id', '=', 'admins.id')
                ->where('contractor_inventories.created_at', '>=', $start_date . ' 00:00:00')
                ->where('contractor_inventories.division', '=', $division)
                ->select('admins.name', \DB::raw('DATE(contractor_inventories.created_at) as date'), 'contractor_inventories.contractor_id', 'contractor_inventories.division', 'ware.lot_no',
                    \DB::raw("SUM(
    CASE
        WHEN serial_no LIKE '%,%'
            THEN LENGTH(serial_no) - LENGTH(REPLACE(serial_no, ',', '')) + 1
        WHEN serial_no IS NOT NULL AND serial_no != ''
            THEN 1
        ELSE 0
    END
) as meter_count"),
                    \DB::raw("COUNT(ware.box_id) as box_id_count"))
                ->groupBy('date', 'contractor_id', 'admins.name', 'ware.lot_no', 'contractor_inventories.division')
                ->get();


            $data = [
                'outward_meter' => $outward_meter,
            ];
        }

        return view('project_heads.outward_meter_report', ['data' => $data]);
    }

    public function contractor_wise_stock_report(Request $request)
    {
        $request_data = $request->all();
        //dd($request_data);
        $totalRecords = 0;
        $filterRecords = 0;

        $contractor_inventories_total_count_result = DB::table('warehouse_meters')
            ->join('contractor_inventories', 'warehouse_meters.id', '=', 'contractor_inventories.box_id')
            ->join('admins', 'contractor_inventories.contractor_id', '=', 'admins.id')
            ->select(DB::raw('count(contractor_inventories.box_id) as total_records'))->first();
        $totalRecords = $contractor_inventories_total_count_result->total_records;


        $contractor_inventories_filter_count_query = DB::table('warehouse_meters')
            ->join('contractor_inventories', 'warehouse_meters.id', '=', 'contractor_inventories.box_id')
            ->join('admins', 'contractor_inventories.contractor_id', '=', 'admins.id')
            ->select(DB::raw('count(contractor_inventories.box_id) as filtered_records_count'));

        if ($request_data['contractor_id'] != "undefined" && $request_data['contractor_id'] != "null") $contractor_inventories_filter_count_query->where('contractor_inventories.contractor_id', "=", $request_data['contractor_id']);
        if ($request_data['start_date'] != "undefined" && $request_data['start_date'] != "null") $contractor_inventories_filter_count_query->where('contractor_inventories.created_at', '>=', $request_data['start_date'] . ' 00:00:00');
        if ($request_data['end_date'] != "undefined" && $request_data['end_date'] != "null") $contractor_inventories_filter_count_query->where('contractor_inventories.created_at', '<=', $request_data['end_date'] . ' 23:59:59');

        $contractor_inventories_filter_count_result = $contractor_inventories_filter_count_query->first();

        $filterRecords = $contractor_inventories_filter_count_result->filtered_records_count;


//            dd($request_data['length']);

//            if ($end_date == null) {
//                $outward_meter = Contractor_inventory::join('admins', 'contractor_inventories.contractor_id', '=', 'admins.id')
//                    ->where('contractor_inventories.created_at', '>=', $start_date)
//                    ->where('contractor_inventories.division', '=', $division)
//                    ->select('admins.name', \DB::raw('DATE(contractor_inventories.created_at) as date'), 'contractor_inventories.contractor_id',
//                        \DB::raw("SUM(
//        CASE
//            WHEN serial_no LIKE '%,%'
//                THEN LENGTH(serial_no) - LENGTH(REPLACE(serial_no, ',', '')) + 1
//            WHEN serial_no IS NOT NULL AND serial_no != ''
//                THEN 1
//            ELSE 0
//        END
//    ) as meter_count"),
//                        \DB::raw("SUM(
//        CASE
//            WHEN unused_meter_serial_no LIKE '%,%'
//                THEN LENGTH(unused_meter_serial_no) - LENGTH(REPLACE(unused_meter_serial_no, ',', '')) + 1
//            WHEN unused_meter_serial_no IS NOT NULL AND unused_meter_serial_no != ''
//                THEN 1
//            ELSE 0
//        END
//    ) as unused_meter_count"),
//                        \DB::raw("SUM(
//        CASE
//            WHEN used_meter_serial_no LIKE '%,%'
//                THEN LENGTH(used_meter_serial_no) - LENGTH(REPLACE(used_meter_serial_no, ',', '')) + 1
//            WHEN used_meter_serial_no IS NOT NULL AND used_meter_serial_no != ''
//                THEN 1
//            ELSE 0
//        END
//    ) as used_meter_count"))
//                    //->groupBy('date', 'contractor_id', 'admins.name')
//                    ->get();
//
//
//                $data = [
//                    'outward_meter' => $outward_meter,
//                ];
//            }
//            else {
//                // $outward_meter = Contractor_inventory::join('admins', 'contractor_inventories.contractor_id', '=', 'admins.id')
//                // ->where('contractor_inventories.created_at', '>=', $start_date)
//                // ->where('contractor_inventories.created_at', '<=', $end_date)
//                // ->where('contractor_inventories.division', '=', $division)
//                // ->select('admins.name',\DB::raw('DATE(contractor_inventories.created_at) as date'), 'contractor_inventories.contractor_id',
//                // \DB::raw("COALESCE(LENGTH(serial_no) - LENGTH(REPLACE(serial_no, ',', '')) + 1, 0) as meter_count"),
//                // \DB::raw("COALESCE(LENGTH(unused_meter_serial_no) - LENGTH(REPLACE(unused_meter_serial_no, ',', '')) + 1, 0) as unused_meter_count"),
//                // \DB::raw("COALESCE(LENGTH(used_meter_serial_no) - LENGTH(REPLACE(used_meter_serial_no, ',', '')) + 1, 0) as used_meter_count"))
//                // ->groupBy('date', 'contractor_inventories.contractor_id', 'admins.name', 'meter_count', 'unused_meter_count', 'used_meter_count')
//                // ->get();
//
//                $outward_meter = Contractor_inventory::join('admins', 'contractor_inventories.contractor_id', '=', 'admins.id')
//                    ->where('contractor_inventories.created_at', '>=', $start_date)
//                    ->where('contractor_inventories.created_at', '<=', $end_date)
//                    ->where('contractor_inventories.division', '=', $division)
//                    ->select('admins.name', \DB::raw('DATE(contractor_inventories.created_at) as date'), 'contractor_inventories.contractor_id',
//                        \DB::raw("SUM(
//        CASE
//            WHEN serial_no LIKE '%,%'
//                THEN LENGTH(serial_no) - LENGTH(REPLACE(serial_no, ',', '')) + 1
//            WHEN serial_no IS NOT NULL AND serial_no != ''
//                THEN 1
//            ELSE 0
//        END
//    ) as meter_count"),
//                        \DB::raw("SUM(
//        CASE
//            WHEN unused_meter_serial_no LIKE '%,%'
//                THEN LENGTH(unused_meter_serial_no) - LENGTH(REPLACE(unused_meter_serial_no, ',', '')) + 1
//            WHEN unused_meter_serial_no IS NOT NULL AND unused_meter_serial_no != ''
//                THEN 1
//            ELSE 0
//        END
//    ) as unused_meter_count"),
//                        \DB::raw("SUM(
//        CASE
//            WHEN used_meter_serial_no LIKE '%,%'
//                THEN LENGTH(used_meter_serial_no) - LENGTH(REPLACE(used_meter_serial_no, ',', '')) + 1
//            WHEN used_meter_serial_no IS NOT NULL AND used_meter_serial_no != ''
//                THEN 1
//            ELSE 0
//        END
//    ) as used_meter_count"))
//                    //->groupBy('date', 'contractor_id', 'admins.name')
//                    ->get();
//
//
//                $data = [
//                    'outward_meter' => $outward_meter,
//                ];
//            }
        $contractor_inventories_query = DB::table('warehouse_meters')
            ->join('contractor_inventories', 'warehouse_meters.id', '=', 'contractor_inventories.box_id')
            ->join('admins', 'contractor_inventories.contractor_id', '=', 'admins.id')
            ->select(
                'warehouse_meters.box_id'
                , 'contractor_inventories.meter_type'
                , 'contractor_inventories.division'
                , 'warehouse_meters.lot_no'
                , 'contractor_inventories.serial_no'
                , 'contractor_inventories.unused_meter_serial_no'
                , 'contractor_inventories.used_meter_serial_no'
                , 'contractor_inventories.contractor_id'
                , 'contractor_inventories.created_at'
                , 'admins.name'
            );
//                ->whereRaw('contractor_inventories.box_id IN (SELECT warehouse_meters.id from warehouse_meters)')
//                ->whereNull('contractor_inventories.unused_meter_serial_no')
//                ->whereNotNull('contractor_inventories.used_meter_serial_no');
//                ->where('warehouse_meters.complete_status', "=", 1);


        if ($request_data['contractor_id'] != "undefined" && $request_data['contractor_id'] != "null") $contractor_inventories_query->where('contractor_inventories.contractor_id', "=", $request_data['contractor_id']);
        if ($request_data['start_date'] != "undefined" && $request_data['start_date'] != "null") $contractor_inventories_query->where('contractor_inventories.created_at', '>=', $request_data['start_date'] . ' 00:00:00');
        if ($request_data['end_date'] != "undefined" && $request_data['end_date'] != "null") $contractor_inventories_query->where('contractor_inventories.created_at', '<=', $request_data['end_date'] . ' 23:59:59');
        if (!empty($request_data['search']['value']) && $request_data['search']['value'] != "undefined") $contractor_inventories_query->where('contractor_inventories.serial_no', 'LIKE', "%" . $request_data['search']['value'] . "%");

//            $contractor_inventories_query->select(DB::raw('LIMIT ' . $request_data['start'] . ', ' . $request_data['length']));
        if ($request_data['length'] != "-1") {
            $contractor_inventories_query->limit($request_data['length']);
            $contractor_inventories_query->offset($request_data['start']);
        }

        //dd($contractor_inventories_query);
        $contractor_inventories = $contractor_inventories_query->orderBy('contractor_inventories.created_at', 'desc')
            ->get()->toArray();

        $total_meters = 0;
        $total_unused_meters = 0;
        $total_used_meters = 0;
        $total_balance_meters = 0;
        foreach ($contractor_inventories as $key => $contractor_inventory) {
            $contractor_inventory->index = $key + 1;

            if (!empty($contractor_inventory->serial_no)) {
                $contractor_inventory->serial_no = str_replace(',', '| ', $contractor_inventory->serial_no);
                $contractor_inventory->total_meters = count(explode('|', $contractor_inventory->serial_no));
            } else {
                $contractor_inventory->total_meters = 0;
            }

            if (!empty($contractor_inventory->unused_meter_serial_no)) {
                $contractor_inventory->unused_meter_serial_no = str_replace(',', '| ', $contractor_inventory->unused_meter_serial_no);
                $contractor_inventory->total_unused_meters = count(explode('|', $contractor_inventory->unused_meter_serial_no));
            } else {
                $contractor_inventory->total_unused_meters = 0;
            }

            if (!empty($contractor_inventory->used_meter_serial_no)) {
                $contractor_inventory->used_meter_serial_no = str_replace(',', '| ', $contractor_inventory->used_meter_serial_no);
                $contractor_inventory->total_used_meters = count(explode('|', $contractor_inventory->used_meter_serial_no));
            } else {
                $contractor_inventory->total_used_meters = 0;
            }

            $contractor_inventory->balance_meters = $contractor_inventory->total_meters - $contractor_inventory->total_used_meters;

            $total_meters = $total_meters + $contractor_inventory->total_meters;
            $total_unused_meters = $total_unused_meters + $contractor_inventory->total_unused_meters;
            $total_used_meters = $total_used_meters + $contractor_inventory->total_used_meters;
            $total_balance_meters = $total_balance_meters + $contractor_inventory->balance_meters;
            //$filterRecords++;
            //$totalRecords++;
        }
        //dd($contractor_inventories, $total_meters, $total_unused_meters, $total_used_meters, $total_balance_meters);
        $data = [
            //'contractors' => $contractor,
            'contractor_inventories' => $contractor_inventories,

        ];

        $aResult = [
            'data' => $contractor_inventories,
            'total_meters' => $total_meters,
            'total_unused_meters' => $total_unused_meters,
            'total_used_meters' => $total_used_meters,
            'total_balance_meters' => $total_balance_meters,
            'recordsFiltered' => $filterRecords,
            'recordsTotal' => $totalRecords,
            'draw' => $request_data['draw'],
        ];

        return response()->json($aResult);
        //dd($data);
        //return view('project_heads.contractor_wise_stock_report', ['data' => $data]);

    }

    public function contractor_wise_installation_report($division, $start_date, $end_date = null)
    {
        if ($end_date == null) {
            $meter_main = Meter_main::join('admins', 'admins.id', '=', 'meter_mains.created_by')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->where('consumer_details.division', '=', $division)
                ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
                ->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('meter_mains.serial_no_old')
                ->select(\DB::raw('DATE(meter_mains.created_at) as date'), 'admins.created_by as contractor_id', \DB::raw('(SELECT name FROM admins WHERE id = contractor_id) as contractor_name'),
                    'consumer_details.division as division', 'consumer_details.sd_pincode as sd_pincode', 'consumer_details.so_pincode as so_pincode',
                    \DB::raw('count(*) as installed_count'),
                )
                ->groupBy('date', 'contractor_id', 'division', 'sd_pincode', 'so_pincode')
                ->get();

            $data = [
                'meter_main' => $meter_main,
            ];
        } else {

            $meter_main = Meter_main::join('admins', 'admins.id', '=', 'meter_mains.created_by')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->where('consumer_details.division', '=', $division)
                ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
                ->where('meter_mains.created_at', '<=', $end_date . ' 23:59:59')
                ->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('meter_mains.serial_no_old')
                ->select(\DB::raw('DATE(meter_mains.created_at) as date'), 'admins.created_by as contractor_id', \DB::raw('(SELECT name FROM admins WHERE id = contractor_id) as contractor_name'),
                    'consumer_details.division as division', 'consumer_details.sd_pincode as sd_pincode', 'consumer_details.so_pincode as so_pincode',
                    \DB::raw('count(*) as installed_count'),
                )
                ->groupBy('date', 'contractor_id', 'division', 'sd_pincode', 'so_pincode')
                ->get();


            $data = [
                'meter_main' => $meter_main,
            ];
        }

        return view('project_heads.contractor_wise_installation_report', ['data' => $data]);

    }

    public function qc_report($division, $start_date, $end_date = null)
    {
        if ($end_date == null) {
            $meter_main = Meter_main::join('admins', 'admins.id', '=', 'meter_mains.qc_updated_by')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->where('consumer_details.division', '=', $division)
                ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
                ->where('meter_mains.qc_status', '=', 1)
                ->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('meter_mains.serial_no_old')
                ->select(\DB::raw('DATE(meter_mains.qc_updated_at) as date'), 'meter_mains.qc_updated_by as qc_id', 'admins.name as qc_name',
                    'consumer_details.division as division', 'consumer_details.sd_pincode as sd_pincode', 'consumer_details.so_pincode as so_pincode',
                    \DB::raw('count(*) as installed_count'),
                )
                ->groupBy('date', 'qc_id', 'qc_name', 'division', 'sd_pincode', 'so_pincode')
                ->get();

            $data = [
                'meter_main' => $meter_main,
            ];
        } else {

            $meter_main = Meter_main::join('admins', 'admins.id', '=', 'meter_mains.qc_updated_by')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->where('consumer_details.division', '=', $division)
                ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
                ->where('meter_mains.created_at', '<=', $end_date . ' 23:59:59')
                ->where('meter_mains.qc_status', '=', 1)
                ->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('meter_mains.serial_no_old')
                ->select(\DB::raw('DATE(meter_mains.qc_updated_at) as date'), 'meter_mains.qc_updated_by as qc_id', 'admins.name as qc_name',
                    'consumer_details.division as division', 'consumer_details.sd_pincode as sd_pincode', 'consumer_details.so_pincode as so_pincode',
                    \DB::raw('count(*) as installed_count'),
                )
                ->groupBy('date', 'qc_id', 'qc_name', 'division', 'sd_pincode', 'so_pincode')
                ->get();


            $data = [
                'meter_main' => $meter_main,
            ];
        }

        return view('project_heads.qc_report', ['data' => $data]);

    }


    public function fe_wise_installation_report($division, $start_date, $end_date = null)
    {
        if ($end_date == null) {
            $meter_main = Meter_main::join('admins', 'admins.id', '=', 'meter_mains.created_by')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->where('consumer_details.division', '=', $division)
                ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
                ->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('meter_mains.serial_no_old')
                ->select(\DB::raw('DATE(meter_mains.created_at) as date'), 'admins.created_by as contractor_id', \DB::raw('(SELECT name FROM admins WHERE id = contractor_id) as contractor_name'),
                    'consumer_details.division as division', 'consumer_details.sd_pincode as sd_pincode', 'consumer_details.so_pincode as so_pincode',
                    \DB::raw('count(*) as installed_count'),
                )
                ->groupBy('date', 'contractor_id', 'division', 'sd_pincode', 'so_pincode')
                ->get();

            $data = [
                'meter_main' => $meter_main,
            ];
        } else {

            $meter_main = Meter_main::join('admins', 'admins.id', '=', 'meter_mains.created_by')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->where('consumer_details.division', '=', $division)
                ->where('meter_mains.created_at', '>=', $start_date . ' 00:00:00')
                ->where('meter_mains.created_at', '<=', $end_date . ' 23:59:59')
                ->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('meter_mains.serial_no_old')
                ->select(\DB::raw('DATE(meter_mains.created_at) as date'), 'meter_mains.created_by as fe_id', 'admins.name as fe_name',
                    'consumer_details.division as division', 'consumer_details.sd_pincode as sd_pincode', 'consumer_details.so_pincode as so_pincode',
                    \DB::raw('count(*) as installed_count'),
                )
                ->groupBy('date', 'fe_id', 'fe_name', 'division', 'sd_pincode', 'so_pincode')
                ->get();


            $data = [
                'meter_main' => $meter_main,
            ];
        }

        return view('project_heads.fe_wise_installation_report', ['data' => $data]);

    }


    public function bmr_status_view()
    {
        return view('project_heads.view_bmr_status');
    }

    public function bmr_status_view_ae(Request $req)
    {
        $section_code = $req->section;
        $start_date = $req->fliterFrom;
        $end_date = $req->fliterTo;
        if (!empty($start_date) && !empty($end_date)) {
            return redirect('/project_heads/view_bmr_status_filter_view/' . $start_date . '/' . $end_date);
        }
        return view('project_heads.view_bmr_status_filter');
    }

    public function bmr_status_view_filter(Request $request)
    {
        $start_date = $request->fliterFrom;
        $end_date = $request->fliterTo;
        if (!empty($start_date) && !empty($end_date)) {
            return redirect('/project_heads/view_bmr_status_filter_view/' . $start_date . '/' . $end_date);
        }
        if (isset($request->fliterFrom)) dd($request);
        return view('project_heads.view_bmr_status');
    }

    public function bmr_status_view_filter_view($start_date, $end_date = null)
    {
        $success_count = Successful_record::where('created_at', '>=', $start_date . ' 00:00:00')->where('created_at', '<=', $end_date . ' 23:59:59')->get()->count();
        $error_count = Error_record::where('created_at', '>=', $start_date . ' 00:00:00')->where('created_at', '<=', $end_date . ' 23:59:59')->get()->count();
        $results = [
            'success_count' => $success_count,
            'error_count' => $error_count,
            'success_report_url' => env('APP_URL') . '/project_heads/view_bmr_status_success_report/' . $start_date . '/' . $end_date,
            'error_report_url' => env('APP_URL') . '/project_heads/view_bmr_status_error_report/' . $start_date . '/' . $end_date,
            'from_date' => $start_date,
            'to_date' => $end_date
        ];
        //dd($results);
        return view('project_heads.view_bmr_status', ['data' => $results]);
    }


    public function view_bmr_status_success_report($start_date, $end_date = null)
    {
        $success_query = DB::table('successful_records')
            ->join('meter_mains', 'successful_records.account_id', '=', 'meter_mains.account_id')
            ->join('consumer_details', 'successful_records.account_id', '=', 'consumer_details.account_id')
            ->where('successful_records.created_at', '>=', $start_date . ' 00:00:00')
            ->where('successful_records.created_at', '<=', $end_date . ' 23:59:59')
            ->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('meter_mains.serial_no_old')
            ->select(\DB::raw('successful_records.created_at as successful_records_report_created_at, successful_records.updated_at as successful_records_report_updated_at'), 'meter_mains.*', 'consumer_details.*');
        try {
            $results = $success_query->get();
            //dd($results);
        } catch (\Exception $e) {
            dd($e);
        }

        $data = [
            'results' => $results,
            'dateFrom' => $start_date,
            'dateTo' => $end_date,
        ];

        return view('project_heads.view_bmr_status_success_report', ['data' => $data]);
    }

    public function view_bmr_status_error_report($start_date, $end_date = null)
    {
        $error_query = DB::table('error_records')
            ->join('meter_mains', 'error_records.account_id', '=', 'meter_mains.account_id')
            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->where('error_records.created_at', '>=', $start_date . ' 00:00:00')
            ->where('error_records.created_at', '<=', $end_date . ' 23:59:59')
            ->where('error_records.updated_by_aao', '=', 0)
            ->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('meter_mains.serial_no_old')
            ->select(\DB::raw('error_records.error_reason, error_records.updated_by_aao as error_updated_by_aao, error_records.created_at as error_report_created_at, error_records.updated_at as error_report_updated_at'), 'meter_mains.*', 'consumer_details.*');
        try {
            $results = $error_query->get();
            //dd($results);
        } catch (\Exception $e) {
            dd($e);
        }

        $data = [
            'results' => $results,
            'dateFrom' => $start_date,
            'dateTo' => $end_date,
        ];

        return view('project_heads.view_bmr_status_error_report', ['data' => $data]);
    }

    public function bmr_status_success_report_data(Request $request, BmrDownloadService $bmrDownloadService)
    {
//            $success_query = DB::table('successful_records')
//                ->join('meter_mains', 'successful_records.account_id', '=', 'meter_mains.account_id')
//                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
//                ->where('successful_records.created_at', '>=', $start_date . ' 00:00:00')
//                ->where('successful_records.created_at', '<=', $end_date . ' 23:59:59')
//                ->whereNotNull('meter_mains.serial_no_new')
//                ->whereNotNull('meter_mains.serial_no_old')
//                ->select(\DB::raw('successful_records.created_at as successful_records_report_created_at,
//    successful_records.updated_at as successful_records_report_updated_at,
//    meter_mains.created_by as field_executive_id,
//    meter_mains.created_at,
//    meter_mains.serial_no_old,
//    meter_mains.meter_make_old,
//    meter_mains.mfd_year_old,
//    meter_mains.final_reading,
//    meter_mains.serial_no_new,
//    meter_mains.initial_reading_kvah,
//    consumer_details.meter_type,
//    consumer_details.division,
//    consumer_details.sub_division,
//    consumer_details.section,
//    consumer_details.account_id,
//    consumer_details.rr_no,
//    consumer_details.consumer_name,
//    consumer_details.feeder_name,
//    consumer_details.feeder_code,
//    consumer_details.section,
//    consumer_details.sub_division,
//    consumer_details.tariff,
//    consumer_details.phase_type,
//    consumer_details.sub_division'));
//            if (!empty($division) && $division != "null") {
//                $success_query->where('consumer_details.division', '=', $division);
//            }
//            if (!empty($subdivision) && $subdivision != "null") {
//                $success_query->where('consumer_details.sd_pincode', '=', $subdivision);
//            }
//            if (!empty($section) && $section != "null") {
//                $success_query->where('consumer_details.so_pincode', '=', $section);
//            }
//            if (!empty($feeder_code) && $feeder_code != "null") {
//                $success_query->where('consumer_details.feeder_code', '=', $feeder_code);
//            }
        $request_data = $request->all();
        if (isset($request_data['start_date'])) $filter_data['start_date'] = $request_data['start_date'];
        if (isset($request_data['end_date'])) $filter_data['end_date'] = $request_data['end_date'];
        if (isset($request_data['subdivision'])) $filter_data['subdivision'] = $request_data['subdivision'];
        if (isset($request_data['section'])) $filter_data['section'] = $request_data['section'];
        if (isset($request_data['division'])) $filter_data['division'] = $request_data['division'];
        if (isset($request_data['feeder_code'])) $filter_data['feeder_code'] = $request_data['feeder_code'];
        if (isset($request_data['search']['value'])) $filter_data['search_value'] = $request_data['search']['value'];
        if (isset($request_data['account_id'])) $filter_data['account_id'] = $request_data['account_id'];
        if (isset($request_data['rr_no'])) $filter_data['rr_no'] = $request_data['rr_no'];
        if (isset($request_data['meter_new_serial_no'])) $filter_data['meter_serial_no_new'] = $request_data['meter_new_serial_no'];

        $column_list_data = [
            'successful_records.created_at as successful_records_created_at',
            'successful_records.updated_at as successful_records_updated_at',
            'meter_mains.created_by as field_executive_id',
            'meter_mains.created_at',
            'meter_mains.serial_no_old',
            'meter_mains.meter_make_old',
            'meter_mains.mfd_year_old',
            'meter_mains.final_reading',
            'meter_mains.serial_no_new',
            'meter_mains.initial_reading_kvah',
            'consumer_details.meter_type',
            'consumer_details.division',
            'consumer_details.sub_division',
            'consumer_details.section',
            'consumer_details.account_id',
            'consumer_details.rr_no',
            'consumer_details.consumer_name',
            'consumer_details.feeder_name',
            'consumer_details.feeder_code',
            'consumer_details.section',
            'consumer_details.sub_division',
            'consumer_details.tariff',
            'consumer_details.phase_type',
            'consumer_details.sub_division'
        ];

        $pagination_data = [
            "limit" => 50,
            "start" => $request_data['start'],
            "length" => $request_data['length'],
        ];

        try {
            $module = 'success_records';
            //$success_query_results = $success_query->get();
            $successful_account_results = $bmrDownloadService->getSuccessRecordsDataByFilter($module, $filter_data, $column_list_data, $pagination_data);
            //dd($results);
        } catch (\Exception $e) {
            dd($e);
        }

        $aResult = [
            'data' => $successful_account_results['data'],
            'draw' => $request_data['draw'],
            'recordsFiltered' => $successful_account_results['recordsFiltered'],
            'recordsTotal' => $successful_account_results['recordsTotal']
        ];
        return response()->json($aResult);
    }

    public function view_bmr_status_success_report_view($start_date, $end_date = null, $division = null, $subdivision = null, $section = null, $feeder_code = null)
    {

        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'division' => $division,
            'subdivision' => $subdivision,
            'section' => $section,
            'feeder_code' => $feeder_code,
        ];

        return view('project_heads.view_bmr_status_success_report', ['data' => $data]);
    }

    public function view_bmr_status_error_report_ae($start_date, $end_date = null, $division = null, $subdivision = null, $section = null, $feeder_code = null)
    {
        $error_query = DB::table('error_records')
            ->join('meter_mains', 'error_records.account_id', '=', 'meter_mains.account_id')
            ->join('consumer_details', 'error_records.account_id', '=', 'consumer_details.account_id')
            ->where('error_records.created_at', '>=', $start_date . ' 00:00:00')
            ->where('error_records.created_at', '<=', $end_date . ' 23:59:59')
            ->where('error_records.updated_by_aao', '=', 0)
            ->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('meter_mains.serial_no_old')
            ->select(\DB::raw('error_records.error_reason,
    error_records.updated_by_aao as error_updated_by_aao,
    error_records.created_at as error_report_created_at,
    error_records.updated_at as error_report_updated_at,
    meter_mains.created_by as field_executive_id,
    meter_mains.created_at,
    meter_mains.serial_no_old,
    meter_mains.meter_make_old,
    meter_mains.mfd_year_old,
    meter_mains.final_reading,
    meter_mains.serial_no_new,
    meter_mains.initial_reading_kvah,
    consumer_details.meter_type,
    consumer_details.division,
    consumer_details.sub_division,
    consumer_details.section,
    consumer_details.account_id,
    consumer_details.rr_no,
    consumer_details.consumer_name,
    consumer_details.feeder_name,
    consumer_details.feeder_code,
    consumer_details.section,
    consumer_details.sub_division,
    consumer_details.tariff,
    consumer_details.phase_type,
    consumer_details.sub_division'));
        if (!empty($division) && $division != "null") {
            $error_query->where('consumer_details.division', '=', $division);
        }
        if (!empty($subdivision) && $subdivision != "null") {
            $error_query->where('consumer_details.sd_pincode', '=', $subdivision);
        }
        if (!empty($section) && $section != "null") {
            $error_query->where('consumer_details.so_pincode', '=', $section);
        }
        if (!empty($feeder_code) && $feeder_code != "null") {
            $error_query->where('consumer_details.feeder_code', '=', $feeder_code);
        }

        try {
            $error_query_results = $error_query->get();
            //dd($error_query_results);
        } catch (\Exception $e) {
            dd($e);
        }

        $data = [
            'results' => $error_query_results,
            'dateFrom' => $start_date,
            'dateTo' => $end_date,
        ];

        return view('project_heads.view_bmr_status_error_report', ['data' => $data]);
    }

    public function get_feeder_code($division_code = null, $sub_division_code = null, $section_code = null)
    {

        //dd($division_code, $sub_division_code, $section_code);
        $feeder_codes_query = DB::table('consumer_details')
            ->distinct('feeder_code')
//            ->select('feeder_code', 'feeder_name', 'section');
            ->select(\DB::raw('consumer_details.division as division, consumer_details.sub_division as sub_division, consumer_details.section as section, consumer_details.feeder_name as feeder_name, consumer_details.feeder_code as feeder_code'));
        if (!empty($section_code) && $section_code != "null") {
            $feeder_codes_query->where('section', $section_code);
        }
        if (!empty($division_code) && $division_code != "null") {
            $feeder_codes_query->where('division', $division_code);
        }
        if (!empty($sub_division_code) && $sub_division_code != "null") {
            $feeder_codes_query->where('sub_division', $sub_division_code);
        }
        $feeder_codes_result = $feeder_codes_query->orderBy('feeder_name')->get();

        //\DB::raw('consumer_details.division, consumer_details.sub_division, consumer_details.section, consumer_details.feeder_code, consumer_details.feeder_name')

        return response()->json($feeder_codes_result);
    }

    public function update_account_id(Request $request)
    {
        $get_all_consumer_details_sp_ids = DB::table('consumer_details')->whereRaw("LENGTH(sp_id) <= 9")->select('id', 'account_id', 'sp_id')->get();
        //dd($get_all_consumer_details);
        $nine_digits = 0;
        $eight_digits = 0;
        $seven_digits = 0;
        $six_digits = 0;
        foreach ($get_all_consumer_details_sp_ids as $meter) {
            $update_meter_id = Consumer_detail::find($meter->id);
            if (strlen($meter->sp_id) < 7) {
                $six_digits++;
                $update_meter_id->sp_id = "0000" . $meter->sp_id;
            } elseif (strlen($meter->sp_id) < 8) {
                $seven_digits++;
                //var_dump($meter->sp_id);
                $update_meter_id->sp_id = "000" . $meter->sp_id;
            } elseif (strlen($meter->sp_id) < 9) {
                $eight_digits++;
                $update_meter_id->sp_id = "00" . $meter->sp_id;
            } elseif (strlen($meter->sp_id) < 10) {
                $nine_digits++;
                $update_meter_id->sp_id = "0" . $meter->sp_id;
            }
            $update_meter_id->save();
        }
        $results['consumer_details_sp_id'] = ['nine_digits' => $nine_digits, 'eight_digits' => $eight_digits, 'seven_digits' => $seven_digits, 'six_digits' => $six_digits];

        $get_all_consumer_details_account_ids = DB::table('consumer_details')->whereRaw("LENGTH(account_id) <= 9")->select('id', 'account_id')->get();
        //dd($get_all_consumer_details);
        $nine_digits = 0;
        $eight_digits = 0;
        $seven_digits = 0;
        $six_digits = 0;
        foreach ($get_all_consumer_details_account_ids as $meter) {
            $update_meter_id = Consumer_detail::find($meter->id);
            if (strlen($meter->account_id) < 7) {
                $six_digits++;
                $update_meter_id->account_id = "0000" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 8) {
                $seven_digits++;
                //var_dump($meter->account_id);
                $update_meter_id->account_id = "000" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 9) {
                $eight_digits++;
                $update_meter_id->account_id = "00" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 10) {
                $nine_digits++;
                $update_meter_id->account_id = "0" . $meter->account_id;
            }
            $update_meter_id->save();
        }
        $results['consumer_details'] = ['nine_digits' => $nine_digits, 'eight_digits' => $eight_digits, 'seven_digits' => $seven_digits, 'six_digits' => $six_digits];

        $get_all_meter_mains = DB::table('meter_mains')->whereRaw("LENGTH(account_id) <= 9")->select('id', 'account_id')->get();
        $nine_digits = 0;
        $eight_digits = 0;
        $seven_digits = 0;
        $six_digits = 0;
        foreach ($get_all_meter_mains as $meter) {
            $update_meter_id = Meter_main::find($meter->id);
            if (strlen($meter->account_id) < 7) {
                $six_digits++;
                $update_meter_id->account_id = "0000" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 8) {
                $seven_digits++;
                //var_dump($meter->account_id);
                $update_meter_id->account_id = "000" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 9) {
                $eight_digits++;
                $update_meter_id->account_id = "00" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 10) {
                $nine_digits++;
                $update_meter_id->account_id = "0" . $meter->account_id;
            }
            $update_meter_id->save();
        }
        $results['meter_mains'] = ['nine_digits' => $nine_digits, 'eight_digits' => $eight_digits, 'seven_digits' => $seven_digits, 'six_digits' => $six_digits];

        $get_all_success_records = DB::table('successful_records')->whereRaw("LENGTH(account_id) <= 9")->select('id', 'account_id')->get();
        $nine_digits = 0;
        $eight_digits = 0;
        $seven_digits = 0;
        $six_digits = 0;
        foreach ($get_all_success_records as $meter) {
            $update_meter_id = Successful_record::find($meter->id);
            if (strlen($meter->account_id) < 7) {
                $six_digits++;
                $update_meter_id->account_id = "0000" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 8) {
                $seven_digits++;
                //var_dump($meter->account_id);
                $update_meter_id->account_id = "000" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 9) {
                $eight_digits++;
                $update_meter_id->account_id = "00" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 10) {
                $nine_digits++;
                $update_meter_id->account_id = "0" . $meter->account_id;
            }
            $update_meter_id->save();
        }
        $results['success_records'] = ['nine_digits' => $nine_digits, 'eight_digits' => $eight_digits, 'seven_digits' => $seven_digits, 'six_digits' => $six_digits];

        $get_all_error_records = DB::table('error_records')->whereRaw("LENGTH(account_id) <= 9")->select('id', 'account_id')->get();
        $nine_digits = 0;
        $eight_digits = 0;
        $seven_digits = 0;
        $six_digits = 0;
        foreach ($get_all_error_records as $meter) {
            $update_meter_id = Error_record::find($meter->id);
            if (strlen($meter->account_id) < 7) {
                $six_digits++;
                $update_meter_id->account_id = "0000" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 8) {
                $seven_digits++;
                //var_dump($meter->account_id);
                $update_meter_id->account_id = "000" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 9) {
                $eight_digits++;
                $update_meter_id->account_id = "00" . $meter->account_id;
            } elseif (strlen($meter->account_id) < 10) {
                $nine_digits++;
                $update_meter_id->account_id = "0" . $meter->account_id;
            }
            $update_meter_id->save();
        }
        $results['error_records'] = ['nine_digits' => $nine_digits, 'eight_digits' => $eight_digits, 'seven_digits' => $seven_digits, 'six_digits' => $six_digits];

        return $results;
    }

    public function meter_replacement_statistics_view(Request $req, MeterMainService $meterMainService)
    {
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');
        if (!$logged_in_user_id) {
            session()->put('failed', 'Session Timeout');
            return redirect('/');
        }

        $data = array();
        $requestType = $req->radioStatistics;

        if ($requestType == "daily") {
            $data = $meterMainService->meter_replacement_statistics_daily();
        } else if ($requestType == "weekly") {
            $data = $meterMainService->meter_replacement_statistics_weekly();
        } else if ($requestType == "monthly") {
            $data = $meterMainService->meter_replacement_statistics_monthly();
        }

//            dd(['data' => $data, 'radioStatistics' => $requestType]);

        return view('/project_heads/meter_replacement_statistics', ['data' => $data, 'radioStatistics' => $requestType]);
    }

    public function section_wise_inward_installation_report(Request $req, MeterMainService $meterMainService, IndentService $indentService, ZoneCodeService $zoneCodeService)
    {
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');
        if (!$logged_in_user_id) {
            session()->put('failed', 'Session Timeout');
            return redirect('/');
        }

        $data = array();
        $total_inward_meters = 0;
        $total_meter_installed = 0;
        $requestType = $req->radioStatistics;

        $section_codes = $zoneCodeService->getSectionCodes();

        foreach ($section_codes as $key => $section_code) {
            $array_section_code = (array)$section_code;

            $filter_array = array(
                'division_code' => $array_section_code['div_code'],
                'sub_division_code' => $array_section_code['sd_code'],
                'section_code' => $array_section_code['so_code']
            );

            $data[$key] = $array_section_code;

            $data[$key]['total_meter_installed'] = $meterMainService->getMeterMainsInstallationCountByFilter($filter_array);
            $total_meter_installed = $total_meter_installed + $data[$key]['total_meter_installed'];

            $data[$key]['total_inward_meter'] = $indentService->getTotalQuantitiesSectionWise($filter_array);
            $total_inward_meters = $total_inward_meters + $data[$key]['total_inward_meter'];

            $data[$key]['balance_meters'] = $data[$key]['total_inward_meter'] - $data[$key]['total_meter_installed'];
        }

        $aResult = [
            'data' => $data,
            'total_inward_meters' => $total_inward_meters,
            'total_meter_installed' => $total_meter_installed,
        ];

        return response()->json($aResult);
    }

    public function section_wise_inward_installation_report_view(Request $req)
    {
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');
        if (!$logged_in_user_id) {
            session()->put('failed', 'Session Timeout');
            return redirect('/');
        }

        return view('project_heads.section_wise_inward_and_installation_report');
    }

    public function contractor_wise_stock_report_view(Request $req, $division, $start_date = null, $end_date = null, $contractor_id = null)
    {
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');
        if (!$logged_in_user_id) {
            session()->put('failed', 'Session Timeout');
            return redirect('/');
        }

        $data = [
            'division' => $division,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'contractor_id' => $contractor_id,
        ];

        return view('project_heads.contractor_wise_stock_report', ['filter' => json_encode($data)]);
    }

    public function check_unused_meters_in_meter_mains()
    {
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');
        if (!$logged_in_user_id) {
            session()->put('failed', 'Session Timeout');
            return redirect('/');
        }

        $contractor_unused_meters = DB::table('contractor_inventories')->whereNotNull("unused_meter_serial_no")->select('unused_meter_serial_no')->get();

        $unused = array();
        foreach ($contractor_unused_meters as $key => $value) {
            //dd(explode(",", $value->unused_meter_serial_no));
            $unused = array_merge($unused, explode(",", $value->unused_meter_serial_no));
            //if(sizeof($unused) > 60 )dd($unused);
        }
        $meter_mains_used = array();
        $meter_mains_un_used = array();
        foreach ($unused as $key => $value) {
            $meter_mains_check = (array)DB::table('meter_mains')->where("serial_no_new", "=", $value)->select('account_id')->get()->toArray();
            if (is_array($meter_mains_used) && sizeof($meter_mains_check) > 0) {
                $bmr_successful_records = (array)DB::table('successful_records')->where("account_id", "=", $meter_mains_check[0]->account_id)->select('account_id')->get()->toArray();
                $meter_mains_used[$value]['account_id'] = $meter_mains_check[0]->account_id;
                if (is_array($bmr_successful_records) && sizeof($bmr_successful_records) > 0) {
                    $meter_mains_used[$value]['bmr_success'] = 'yes';
                } else {
                    $meter_mains_used[$value]['bmr_success'] = 'not found';
                }
            } else {
                $meter_mains_un_used[$value] = $meter_mains_check;
            }
        }


//            foreach($meter_mains_used as $meter_serial_no=>$account_id){
//
//                $existingInventory = Contractor_inventory::where('serial_no', 'LIKE', "%" . $meter_serial_no . "%")->first();
//
//                $unused_meter_serial_no = explode(',', $existingInventory->unused_meter_serial_no);
//
//                $used_meter_serial_no = explode(',', $existingInventory->used_meter_serial_no);
//
//                //var_dump($unused_meter_serial_no, $used_meter_serial_no, 'before matches');
//
//                $key = array_search($meter_serial_no, $unused_meter_serial_no);
//                if ($key !== false) {
//                    unset($unused_meter_serial_no[$key]);
//                    $used_meter_serial_no[] = (string)$meter_serial_no;
//                }
//
//                $existingInventory->unused_meter_serial_no = implode(',', $unused_meter_serial_no);
//                if (empty($existingInventory->unused_meter_serial_no)) {
//                    $existingInventory->unused_meter_serial_no = null;
//                }
//                $existingInventory->used_meter_serial_no = implode(',', $used_meter_serial_no);
//
//                $existingInventory->used_meter_serial_no = ltrim($existingInventory->used_meter_serial_no, ',');
//
//                $existingInventory->save();
//            }
//
//            foreach($unused as $key=>$value){
//                //dd($value);
//                $meter_mains_check = (array) DB::table('meter_mains')->where("serial_no_new", "=", $value)->select('account_id')->get()->toArray();
//                if(is_array($meter_mains_used) && sizeof($meter_mains_check) > 0) $meter_mains_used[$value] = $meter_mains_check[0]->account_id;
//                else $meter_mains_un_used[$value] = $meter_mains_check;
//            }

        $data = [
            //'meters' => $contractor_unused_meters,
            //'unused' => $unused,
            'meter_mains_used' => $meter_mains_used,
            'meter_mains_un_used' => $meter_mains_un_used,
        ];

        return response()->json($data);
    }

//        public function download_bmr_successful_records($start_date = null, $end_date = null, $division = null, $subdivision = null, $section = null, $feeder_code = null)
    public function download_bmr_successful_records(Request $request)
    {
        $request_data = $request->all();
        //dd($request_data['start_date']);

        $success_query = DB::table('successful_records')
            ->join('meter_mains', 'successful_records.account_id', '=', 'meter_mains.account_id')
            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->where('successful_records.created_at', '>=', $request_data['start_date'] . ' 00:00:00')
            ->where('successful_records.created_at', '<=', $request_data['end_date'] . ' 23:59:59')
            ->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('meter_mains.serial_no_old')
            ->select(\DB::raw('
                                    consumer_details.account_id,
                                    consumer_details.rr_no,
                                    consumer_details.consumer_name,
                                    consumer_details.feeder_code,
                                    consumer_details.feeder_name,
                                    consumer_details.division,
                                    consumer_details.sub_division,
                                    consumer_details.section,
                                    consumer_details.tariff,
                                    consumer_details.phase_type,
                                    meter_mains.serial_no_old,
                                    meter_mains.meter_make_old,
                                    meter_mains.mfd_year_old,
                                    meter_mains.final_reading,
                                    meter_mains.serial_no_new,
                                    meter_mains.meter_make_new,
                                    meter_mains.created_at,
                                    successful_records.created_at as success_reported_at')
            );

        if (!empty($request_data['division']) && $request_data['division'] != "null") {
            $success_query->where('consumer_details.division', '=', $request_data['division']);
        }
        if (!empty($request_data['subdivision']) && $request_data['subdivision'] != "null") {
            $success_query->where('consumer_details.sd_pincode', '=', $request_data['subdivision']);
        }
        if (!empty($request_data['section']) && $request_data['section'] != "null") {
            $success_query->where('consumer_details.so_pincode', '=', $request_data['section']);
            $get_section_office = Zone_code::where('so_code', '=', $request_data['section'])->select('section_office', 'sub_division')->first();
            $section_office_name = $get_section_office->section_office;
            $sub_division_name = $get_section_office->sub_division;
        }
        if (!empty($request_data['feeder_code']) && $request_data['feeder_code'] != "null") {
            $success_query->where('consumer_details.feeder_code', '=', $request_data['feeder_code']);
        }

        $success_query->orderBy('meter_mains.created_at', 'ASC');

        try {
            $from_text = "(From:" . date('d-m-Y', strtotime($request_data['start_date']));
            $to_text = "To:" . date('d-m-Y', strtotime($request_data['end_date'])) . ")";
            $second_row_text = "BMR Success Report " . $from_text . ' - ' . $to_text;
            $file_name = $second_row_text . '.xlsx';
            $excel_first_data_array = array(
                ['Project Name : Replacement work of Electromechanical meters by Electrostatic meters in Hubli - Dharwad and Gadag Towns of HESCOM'],
                ['DWA No: HESCOM/SEE(IT&MT)/EEIT1/AEEIT1/2022-23/CYS-7824 Dated 30.01.2023'],
                ['Annexure - II Electromechanical to Electrostatic Meters Replacement Form'],
                [$second_row_text],
                [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
                ['Sl No.', 'Account Id', 'RR No.', 'Consumer Name', 'Feeder Code', 'Feeder Name', 'Division', 'Sub division', 'section', 'Tariff', 'Installation Type', 'EM Meter Sl. No.', 'EM Make', 'EM MFY', 'EM Meter FR', 'ES Meter Sl. No.', 'ES Make', 'Date of Replacement', 'Success Reported on'],
            );

            $success_query_results = $success_query->get()->all();
            //dd($success_query_results);
            $formatted_data = array();
            foreach ($success_query_results as $key => $value) {
                $formatted_data[$key] = array_merge(['slno' => ++$key], (array)$value);
                if (empty($formatted_data[$key]['meter_make_new'])) {
                    $formatted_data[$key]['meter_make_new'] = 'GENUS POWER INFRASTRUCTURE LTD';
                }
                if (!empty($formatted_data[$key]['created_at'])) {
                    $formatted_data[$key]['created_at'] = date('d-m-Y', strtotime($formatted_data[$key]['created_at']));
                }
                if (!empty($formatted_data[$key]['success_reported_at'])) {
                    $formatted_data[$key]['success_reported_at'] = date('d-m-Y', strtotime($formatted_data[$key]['success_reported_at']));
                }
//                    dd($key, $value, $formatted_data);
            }
//                $success_query_results = $success_query->get()->map(function($data, $key) {
//                    $data->slno = ++$key;
//                    if ( ! $data->meter_make_new) {
//                        $data->meter_make_new = 'GENUS POWER INFRASTRUCTURE LTD';
//                    }
//                    return $data;
//                })->all();

            //dd($success_query_results);

            $excel_data_array = array_merge($excel_first_data_array, $formatted_data);

            if (!empty($request_data['section']) && $request_data['section'] != "null") {
                $excel_last_data_array = array(
                    [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
                    [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
                    [' ', ' ', ' ', ' Section Officer ', ' ', ' ', ' ', ' ', ' Asst. Account Officer ', ' ', ' ', ' ', ' ', ' Asst. Executive Engineer (Ele) ', ' ', ' ', ' '],
                    [' ', ' ', ' ', $section_office_name, ' ', ' ', ' ', ' ', $sub_division_name, ' ', ' ', ' ', ' ', $sub_division_name, ' ', ' ', ' '],
                    [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
                    [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
                    [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '],
                );

                $excel_data_array = array_merge($excel_data_array, $excel_last_data_array);

                $file_name = $section_office_name . '_' . $second_row_text . '.xlsx';
            }

            //$array = json_decode(json_encode($success_query_results), true);
            //$data = $query->fetchAll(PDO::FETCH_ASSOC);;
            //dd($success_query_results);
            //dd($excel_data_array);
        } catch (\Exception $e) {
            dd($e);
        }

        return Excel::download(new SuccessFullRecordsExport($excel_data_array), $file_name,);
    }

    public function download_meter_replacement_records(Request $request, BmrDownloadService $bmrDownloadService)
    {
        $filter_data = array();
        $column_list_data = array();
        $pagination_data = array();
        $request_data = $request->all();
        if (isset($request_data['start_date'])) $filter_data['start_date'] = $request_data['start_date'];
        if (isset($request_data['end_date'])) $filter_data['end_date'] = $request_data['end_date'];
        if (isset($request_data['subdivision'])) $filter_data['subdivision'] = $request_data['subdivision'];
        if (isset($request_data['section'])) {
            $filter_data['section'] = $request_data['section'];
            if (!empty($request_data['section'] != "null")) {
                $get_section_office = Zone_code::where('so_code', '=', $request_data['section'])->select('section_office', 'sub_division')->first();
                $section_office_name = $get_section_office->section_office;
                $sub_division_name = $get_section_office->sub_division;
            }
        }
        if (isset($request_data['division'])) $filter_data['division'] = $request_data['division'];
        if (isset($request_data['feeder_code'])) $filter_data['feeder_code'] = $request_data['feeder_code'];
        if (isset($request_data['search']['value'])) $filter_data['search_value'] = $request_data['search']['value'];
        if (isset($request_data['account_id'])) $filter_data['account_id'] = $request_data['account_id'];
        if (isset($request_data['rr_no'])) $filter_data['rr_no'] = $request_data['rr_no'];
        if (isset($request_data['meter_new_serial_no'])) $filter_data['meter_serial_no_new'] = $request_data['meter_new_serial_no'];

        $column_list_data = [
            'meter_mains.created_by as field_executive_id',
            'meter_mains.created_at',
            'meter_mains.serial_no_old',
            'meter_mains.meter_make_old',
            'meter_mains.mfd_year_old',
            'meter_mains.final_reading',
            'meter_mains.serial_no_new',
            'meter_mains.initial_reading_kvah',
            'meter_mains.lat',
            'meter_mains.lon',
            'consumer_details.meter_type',
            'consumer_details.division',
            'consumer_details.sub_division',
            'consumer_details.section',
            'consumer_details.account_id',
            'consumer_details.rr_no',
            'consumer_details.consumer_name',
            'consumer_details.feeder_name',
            'consumer_details.feeder_code',
            'consumer_details.section',
            'consumer_details.sub_division',
            'consumer_details.tariff',
            'consumer_details.phase_type',
            'consumer_details.sub_division',
            'admins.name as field_executive_name',
            'admins.created_by as field_executive_contractor_id',
        ];

        try {
            $module = 'meter_replacement';
            $aResults = $bmrDownloadService->getSuccessRecordsDataByFilter($module, $filter_data, $column_list_data, $pagination_data);
        } catch (\Exception $e) {
            dd($e);
        }

        try {
            $from_text = "(From:" . date('d-m-Y', strtotime($request_data['start_date']));
            $to_text = "To:" . date('d-m-Y', strtotime($request_data['end_date'])) . ")";
            $second_row_text = $module . " " . $from_text . ' - ' . $to_text;
            $file_name = $second_row_text . '.xlsx';
            $extra_data['first_line'] = $second_row_text;
            if (!empty($request_data['section']) && $request_data['section'] != "null") {
                $file_name = $section_office_name . '_' . $second_row_text . '.xlsx';
                $extra_data['first_line'] = $section_office_name . ' ' . $second_row_text;
            }

        } catch (\Exception $e) {
            dd($e);
        }

        return Excel::download(new MeterReplacementRecordsExport($aResults['data'], $extra_data, $request_data['project_head']), $file_name);
    }

    public function download_release_meter_records(Request $request, BmrDownloadService $bmrDownloadService)
    {
        $filter_data = array();
        $column_list_data = array();
        $pagination_data = array();
        $request_data = $request->all();
        if (isset($request_data['start_date'])) $filter_data['start_date'] = $request_data['start_date'];
        if (isset($request_data['end_date'])) $filter_data['end_date'] = $request_data['end_date'];
        if (isset($request_data['subdivision'])) $filter_data['subdivision'] = $request_data['subdivision'];
        if (isset($request_data['section'])) {
            $filter_data['section'] = $request_data['section'];
            if (!empty($request_data['section'] != "null")) {
                $get_section_office = Zone_code::where('so_code', '=', $request_data['section'])->select('section_office', 'sub_division')->first();
                $section_office_name = $get_section_office->section_office;
                $sub_division_name = $get_section_office->sub_division;
            }
        }
        if (isset($request_data['division'])) $filter_data['division'] = $request_data['division'];
        if (isset($request_data['feeder_code'])) $filter_data['feeder_code'] = $request_data['feeder_code'];
        if (isset($request_data['search']['value'])) $filter_data['search_value'] = $request_data['search']['value'];
        if (isset($request_data['account_id'])) $filter_data['account_id'] = $request_data['account_id'];
        if (isset($request_data['rr_no'])) $filter_data['rr_no'] = $request_data['rr_no'];
        if (isset($request_data['meter_new_serial_no'])) $filter_data['meter_serial_no_new'] = $request_data['meter_new_serial_no'];

        $column_list_data = [
            'meter_mains.created_by as field_executive_id',
            'meter_mains.created_at',
            'meter_mains.serial_no_old',
            'meter_mains.meter_make_old',
            'meter_mains.mfd_year_old',
            'meter_mains.final_reading',
            'meter_mains.serial_no_new',
            'meter_mains.initial_reading_kvah',
            'meter_mains.lat',
            'meter_mains.lon',
            'consumer_details.meter_type',
            'consumer_details.division',
            'consumer_details.sub_division',
            'consumer_details.section',
            'consumer_details.account_id',
            'consumer_details.rr_no',
            'consumer_details.consumer_name',
            'consumer_details.feeder_name',
            'consumer_details.feeder_code',
            'consumer_details.section',
            'consumer_details.sub_division',
            'consumer_details.tariff',
            'consumer_details.phase_type',
            'consumer_details.sub_division',
            'admins.name as field_executive_name',
            'admins.created_by as field_executive_contractor_id',
        ];

        try {
            $module = 'release_meter';
            $aResults = $bmrDownloadService->getSuccessRecordsDataByFilter($module, $filter_data, $column_list_data, $pagination_data);
        } catch (\Exception $e) {
            dd($e);
        }

        try {
            $from_text = "(From:" . date('d-m-Y', strtotime($request_data['start_date']));
            $to_text = "To:" . date('d-m-Y', strtotime($request_data['end_date'])) . ")";
            $second_row_text = $module . " " . $from_text . ' - ' . $to_text;
            $file_name = $second_row_text . '.xlsx';
            $extra_data['first_line'] = $second_row_text;
            if (!empty($request_data['section']) && $request_data['section'] != "null") {
                $file_name = $section_office_name . '_' . $second_row_text . '.xlsx';
                $extra_data['first_line'] = $section_office_name . ' ' . $second_row_text;
            }
        } catch (\Exception $e) {
            dd($e);
        }

        return Excel::download(new ReleaseMeterRecordsExport($aResults['data'], $extra_data, $request_data['project_head']), $file_name);
    }

    public function download_meter_mains_successful_records(Request $request, BmrDownloadService $bmrDownloadService)
    {
        $filter_data = array();
        $column_list_data = array();
        $pagination_data = array();
        $request_data = $request->all();
        if (isset($request_data['start_date'])) $filter_data['start_date'] = $request_data['start_date'];
        if (isset($request_data['end_date'])) $filter_data['end_date'] = $request_data['end_date'];
        if (isset($request_data['subdivision'])) $filter_data['subdivision'] = $request_data['subdivision'];
        if (isset($request_data['section'])) {
            $filter_data['section'] = $request_data['section'];
            if (!empty($request_data['section'] != "null")) {
                $get_section_office = Zone_code::where('so_code', '=', $request_data['section'])->select('section_office', 'sub_division')->first();
                $section_office_name = $get_section_office->section_office;
                $sub_division_name = $get_section_office->sub_division;
            }
        }
        if (isset($request_data['division'])) $filter_data['division'] = $request_data['division'];
        if (isset($request_data['feeder_code'])) $filter_data['feeder_code'] = $request_data['feeder_code'];
        if (isset($request_data['search']['value'])) $filter_data['search_value'] = $request_data['search']['value'];
        if (isset($request_data['account_id'])) $filter_data['account_id'] = $request_data['account_id'];
        if (isset($request_data['rr_no'])) $filter_data['rr_no'] = $request_data['rr_no'];
        if (isset($request_data['meter_new_serial_no'])) $filter_data['meter_serial_no_new'] = $request_data['meter_new_serial_no'];
        $request_data['project_head'] = "yes";

        $column_list_data = [
            'meter_mains.created_by as field_executive_id',
            'meter_mains.created_at',
            'meter_mains.serial_no_old',
            'meter_mains.meter_make_old',
            'meter_mains.mfd_year_old',
            'meter_mains.final_reading',
            'meter_mains.serial_no_new',
            'meter_mains.initial_reading_kvah',
            'meter_mains.lat',
            'meter_mains.lon',
            'meter_mains.image_1_old',
            'meter_mains.image_2_old',
            'meter_mains.image_3_old',
            'meter_mains.image_1_new',
            'meter_mains.image_2_new',
            'consumer_details.meter_type',
            'consumer_details.division',
            'consumer_details.sub_division',
            'consumer_details.section',
            'consumer_details.account_id',
            'consumer_details.rr_no',
            'consumer_details.consumer_name',
            'consumer_details.feeder_name',
            'consumer_details.feeder_code',
            'consumer_details.section',
            'consumer_details.sub_division',
            'consumer_details.tariff',
            'consumer_details.phase_type',
            'admins.name as field_executive_name',
            'admins.created_by as field_executive_contractor_id',
            'successful_records.created_at as success_reported_at'
        ];

        try {
            $module = 'meter_mains_success_records_with_image';
            $aResults = $bmrDownloadService->getSuccessRecordsDataByFilter($module, $filter_data, $column_list_data, $pagination_data);
        } catch (\Exception $e) {
            dd($e);
        }

        try {
            $from_text = "(From:" . date('d-m-Y', strtotime($request_data['start_date']));
            $to_text = "To:" . date('d-m-Y', strtotime($request_data['end_date'])) . ")";
            $second_row_text = $module . " " . $from_text . ' - ' . $to_text;
            $file_name = $second_row_text . '.xlsx';
            $extra_data['first_line'] = $second_row_text;
            if (!empty($request_data['section']) && $request_data['section'] != "null") {
                $file_name = $section_office_name . '_' . $second_row_text . '.xlsx';
                $extra_data['first_line'] = $section_office_name . ' ' . $second_row_text;
            }
        } catch (\Exception $e) {
            dd($e);
        }

        //return response()->json($aResults);
        $success_obj = new SuccessFullRecordsWithImagesExport($aResults['data'], $extra_data, $request_data['project_head']);
        $success_obj->custom();
        return Excel::download($success_obj, $file_name);
        //return Excel::download(new SuccessFullRecordsWithImagesExport($aResults['data'], $extra_data, $request_data['project_head']), $file_name);

    }

    public function get_account_id_details_view(Request $request)
    {
        $contractor_manager = Admin::where('type', 'contractor_manager')->get();
        $aaos = Admin::where('type', 'aao')->get();
        $aees = Admin::where('type', 'aee')->get();
        $aes = Admin::where('type', 'ae')->get();
        $qcs = Admin::where('type', 'qc_executive')->get();

        $request_data = $request->all();
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');
        $select_query_results = array();
        if (!$logged_in_user_id) {
            session()->put('failed', 'Session Timeout');
            return redirect('/');
        }
        $select_query = DB::table('meter_mains')
            ->leftJoin('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->leftJoin('successful_records', 'meter_mains.account_id', '=', 'successful_records.account_id')
            ->leftJoin('error_records', 'meter_mains.account_id', '=', 'error_records.account_id')
            ->leftJoin('admins', 'meter_mains.created_by', '=', 'admins.id')
            ->select('meter_mains.created_by as field_executive_id',
                'meter_mains.created_at as meter_installed_at',
                'meter_mains.serial_no_old',
                'meter_mains.meter_make_old',
                'meter_mains.mfd_year_old',
                'meter_mains.final_reading',
                'meter_mains.serial_no_new',
                'meter_mains.qc_status',
                'meter_mains.qc_updated_by',
                'meter_mains.qc_updated_at',
                'meter_mains.so_status',
                'meter_mains.so_updated_by',
                'meter_mains.so_updated_at',
                'meter_mains.aee_status',
                'meter_mains.aee_updated_by',
                'meter_mains.aee_updated_at',
                'meter_mains.aao_status',
                'meter_mains.aao_updated_by',
                'meter_mains.aao_updated_at',
                'meter_mains.initial_reading_kvah',
                'meter_mains.account_id as meter_mains_account_id',
                'consumer_details.meter_type',
                'consumer_details.division',
                'consumer_details.sub_division',
                'consumer_details.section',
                'consumer_details.account_id as consumer_details_account_id',
                'consumer_details.rr_no',
                'consumer_details.consumer_name',
                'consumer_details.feeder_name',
                'consumer_details.feeder_code',
                'consumer_details.section',
                'consumer_details.sub_division',
                'consumer_details.tariff',
                'consumer_details.phase_type',
                'consumer_details.sub_division',
                'admins.name as field_executive_name',
                'admins.created_by as field_executive_contractor_id',
                'successful_records.account_id as successful_records_account_id',
                'successful_records.created_at as successful_reported_at',
                'error_records.account_id as error_records_account_id',
                'error_records.created_at as error_records_reported_at');

        if (!empty($request_data['account_id']) && strlen($request_data['account_id']) == 10) {
            $select_query->where('meter_mains.account_id', '=', $request_data['account_id']);
            $select_query_results = $select_query->first();
        } else if (!empty($request_data['new_meter_serial_no'])) {
            $select_query->where('meter_mains.serial_no_new', '=', $request_data['new_meter_serial_no']);
            $select_query_results = $select_query->first();
        } else if (!empty($request_data['account_id']) || !empty($request_data['new_meter_serial_no'])) {
            $select_query_results = 'not found';
        }

        $data = [
            'account_id' => $request_data['account_id'] ?? NULL,
            'new_meter_serial_no' => $request_data['new_meter_serial_no'] ?? NULL,
            'account_data' => $select_query_results,
            'contractors' => $contractor_manager,
            'qcs' => $qcs,
            'aes' => $aes,
            'aees' => $aees,
            'aaos' => $aaos,
        ];

        //dd($data);

        return view('project_heads.view_account_id_status', ['data' => $data]);
    }

    public function upload_previous_final_reading_view()
    {
        return view('project_heads.upload_previous_final_reading');
    }

    public function upload_previous_final_reading(Request $req)
    {
        $read_count = 0;
        $insert_count = 0;
        $duplicate_count = 0;

        $file = $req->file('upload');

        set_time_limit(7200);

        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

        if (!empty($file) && $csvMimes) {
            if (is_uploaded_file($file)) {
                //$file = $file->store('csv', ['disk' => 'public']);
                $fileStream = fopen($file, 'r');
                //$csvFile = fopen($req->file('upload'), 'r');
                //fgetcsv($csvFile);

                while (($line = fgetcsv($fileStream)) !== false) {
                    for ($i = 0; $i < 3; $i++) {
                        //var_dump($i);
                        //var_dump($line[$i]);
                        if (str_contains($line[$i], 'script')) {
                            session()->flush();
                            return redirect('login');
                        }
                    }
                    if ($line[1] != "Billing Day") {
                        //dd($line);

                        $account_id_from_file = $line[0];

                        if (strlen($account_id_from_file) < 7) {
                            $account_id_from_file = "0000" . $account_id_from_file;
                        } elseif (strlen($account_id_from_file) < 8) {
                            $account_id_from_file = "000" . $account_id_from_file;
                        } elseif (strlen($account_id_from_file) < 9) {
                            $account_id_from_file = "00" . $account_id_from_file;
                        } elseif (strlen($account_id_from_file) < 10) {
                            $account_id_from_file = "0" . $account_id_from_file;
                        }

                        //dd($line);
                        $billed_date_from_file = Carbon::parse($line[1])->timezone('asia/kolkata')->format('Y-m-d');
                        //dd($billed_date_from_file);

                        //commented checking duplication account id

                        //$existingConsumer = Consumer_detail::where('account_id', $account_id_from_file)->first();
                        //if (!empty($existingConsumer)) {
                        //$existing_meter_reading = Meter_final_reading::where('account_id', $account_id_from_file)->where('billed_date', $billed_date_from_file)->first();
                        //dd($existing_meter_reading['id']??NULL);
                        //if(empty($existing_meter_reading)){


                        try {
                            Meter_final_reading::updateOrCreate(
                                ['account_id' => $account_id_from_file],
                                [
                                    'account_id' => $account_id_from_file,
                                    'billed_date' => $billed_date_from_file,
                                    'reading' => $line[2],
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]
                            );
                            $read_count++;
                        } catch (\Exception $e) {
                            dd($e);
                        }
                    }
//                    if(empty($existing_meter_reading)){
//                        $meter_reading =  new Meter_final_reading();
//                        $meter_reading->created_at = date('Y-m-d H:i:s');
//                    }
//                    else{
//                        $meter_reading =  Meter_final_reading::findOrFail($existing_meter_reading['id']);
//                    }
//
//                    $meter_reading->account_id = $account_id_from_file;
//                    $meter_reading->billed_date = $billed_date_from_file;
//                    $meter_reading->reading = $line[2];
//                    $meter_reading->updated_at = date('Y-m-d H:i:s');
//                    $meter_reading->active = 'Y';
//                    $meter_reading->save();
//                    //dd('saved');
//                    $insert_count++;
                    //dd($consumer);
                    //}
                    //else{
                    //    $duplicate_count++;
                    //}
                    //} else {
                    //    $duplicate_count++;
                    //    continue;
                    //}
                    //dd($line);
                }
                fclose($fileStream);
            }
        }
        //$messages = array('success' => 'Bulk Upload Successful with New Records ->'. $insert_count . ' and Duplicate records ->' . $duplicate_count);
        //dd(session()->get('success'));
        //$data = [
        //    'messages' => $messages
        //];

        session()->put('success_message', 'Bulk Upload Successful with Records -> ' . $read_count .' ');
        //return redirect('/project_heads/index');

        //dd($data);
        return view('project_heads.upload_previous_final_reading');
    }

    public function show_users(Request $request)
    {
        $request_data = $request->all();
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');
        //dd($logged_in_user_type);
        if (!$logged_in_user_id && $logged_in_user_type != "project_head") {
            session()->put('failed', 'Session Timeout');
            return redirect('/');
        }
        if(!empty($request_data) && $request_data['filter_mobile']){
            //dd($request->filter_mobile);
            $getAdmins = DB::table('admins')->where('phone', "=", $request_data['filter_mobile'])->get();
        }
        else{
            $getAdmins = DB::table('admins')->get();
        }

        return view('admins.show_users', [
            'show_users' => $getAdmins,
        ]);
    }

    public function update_user_data(Request $request)
    {
        $request_data = $request->all();
        //dd($request_data);
        if(isset($request_data['user_id'])){
            $user = Admin::where('id', '=', $request_data['user_id'])->first();
            if($request_data['userActiveStatus'] == "inactive") {
                $user->password = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
            }
            else if (!empty($request_data['password'])){
                $user->password = hash::make($request_data['password']);
            }
            $user->save();
        }
        return redirect('project-heads/show-users');
    }
	
	
    public function half_work(Request $request)
    {
        // Retrieve user session data
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');
        
        // Validate session and user type
        if (!$logged_in_user_id || $logged_in_user_type != "project_head") {
            session()->put('failed', 'Session Timeout');
            return redirect('/');
        }
    
        // Query to fetch required data from meter_mains, checking for NULL conditions
        $getmeter_mains_data = DB::table('meter_mains')
            // Add the necessary whereNull conditions for meter_mains
            ->whereNull('meter_mains.serial_no_new')
            ->whereNull('meter_mains.image_1_old')
            ->whereNull('meter_mains.image_2_old')
            ->whereNull('meter_mains.image_3_old')
            ->whereNull('meter_mains.serial_no_old')
            ->whereNull('meter_mains.final_reading')
            ->select('meter_mains.account_id', 'meter_mains.created_by') // Select account_id and created_by
            ->get();
        
        // Extract account_ids from the result and convert to an array
        $account_ids = $getmeter_mains_data->pluck('account_id')->toArray(); // Convert the collection to an array
    
        // Fetch consumer details and field executive using the account_id with a single query
        $detailed_data = DB::table('meter_mains')
            ->leftJoin('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id') // Join with consumer_details on account_id
            ->leftJoin('successful_records', 'meter_mains.account_id', '=', 'successful_records.account_id') // Join with successful_records
            ->leftJoin('error_records', 'meter_mains.account_id', '=', 'error_records.account_id') // Join with error_records
            ->leftJoin('admins', 'meter_mains.created_by', '=', 'admins.id') // Join with admins to get field executive's name
            ->whereIn('meter_mains.account_id', $account_ids) // Filter based on account_ids
            ->select(
                'meter_mains.id', // Add this line to select the id
                'meter_mains.image_3_old',
                'meter_mains.image_2_old',
                'admins.name',
                'meter_mains.serial_no_old',
                'meter_mains.serial_no_new',
                'meter_mains.final_reading',
                'meter_mains.created_by',
                'meter_mains.created_by as field_executive_id',
                'meter_mains.created_at as meter_installed_at',
                'meter_mains.serial_no_old',
                'meter_mains.meter_make_old',
                'meter_mains.mfd_year_old',
                'meter_mains.final_reading',
                'meter_mains.serial_no_new',
                'meter_mains.qc_status',
                'meter_mains.qc_updated_by',
                'meter_mains.qc_updated_at',
                'meter_mains.so_status',
                'meter_mains.so_updated_by',
                'meter_mains.so_updated_at',
                'meter_mains.aee_status',
                'meter_mains.aee_updated_by',
                'meter_mains.aee_updated_at',
                'meter_mains.aao_status',
                'meter_mains.aao_updated_by',
                'meter_mains.aao_updated_at',
                'meter_mains.initial_reading_kvah',
                'meter_mains.account_id as meter_mains_account_id',
                'consumer_details.meter_type',
                'consumer_details.division',
                'consumer_details.sub_division',
                'consumer_details.section',
                'consumer_details.account_id as consumer_details_account_id',
                'consumer_details.rr_no',
                'consumer_details.consumer_name',
                'consumer_details.feeder_name',
                'consumer_details.feeder_code',
                'consumer_details.section',
                'consumer_details.sub_division',
                'consumer_details.tariff',
                'consumer_details.phase_type',
                'consumer_details.sub_division',
                'admins.name as field_executive_name',
                'admins.created_by as field_executive_contractor_id',
                'successful_records.account_id as successful_records_account_id',
                'successful_records.created_at as successful_reported_at',
                'error_records.account_id as error_records_account_id',
                'error_records.created_at as error_records_reported_at'
            )
            ->get(); // Get all the data for the specified account_ids
    
        // Debugging: Output the data to check the result
        //dd($detailed_data);
    
        // Pass the data to the view
        return view('admins.half_work', [
            'show_users' => $detailed_data,
        ]);
    }
    
    
    
    

    public function half_install_delete($id)
{
    $user = Meter_main::find($id);
    if ($user)
     {
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Half installed deleted successfully.']);
    } else {
        return response()->json(['success' => false, 'message' => 'Half installed data not found']);
    }
}
	
	    public function viewHalfInstall($id)
            {
                try {
                    // Fetch the details for the given ID
                    $record = DB::table('meter_mains')
                        ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                        ->select(
                            'meter_mains.account_id',
                            'consumer_details.rr_no',
                            'consumer_details.consumer_name',
                            'consumer_details.consumer_address',
                            'meter_mains.serial_no_old',
                            'meter_mains.serial_no_new',
                            'meter_mains.final_reading',
                            'meter_mains.created_at'
                        )
                        ->where('meter_mains.id', $id)
                        ->first();

                    // Check if the record exists
                    if (!$record) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Record not found.'
                        ], 404);
                    }

                    // Return success response with data
                    return response()->json([
                        'success' => true,
                        'data' => $record
                    ]);

                } catch (\Exception $e) {
                    // Handle errors and return a failure response
                    return response()->json([
                        'success' => false,
                        'message' => 'An error occurred while fetching the record: ' . $e->getMessage()
                    ], 500);
                }
            }




    public function showForm()
    {
        return view('project_heads.fetch_image'); // Render form for user input
    }

    public function showImage(Request $request)
    {
        // Validate that the filename was provided
        $request->validate([
            'filename' => 'required|string',
        ]);

        // Get the filename from user input
        $filename = $request->input('filename');

        // Construct the full file path
        $filePath = public_path('uploads/' . $filename);

        // Check if the file exists
        if (File::exists($filePath)) {
            // Return the 'show_image' view with the file
            return view('show_image', ['file' => 'uploads/' . $filename]);
        } else {
            // If the file doesn't exist, return an error message
            return back()->with('error', 'File not found.');
        }
    }

}
