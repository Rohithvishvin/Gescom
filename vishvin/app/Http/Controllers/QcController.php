<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Contractor;
use App\Models\Meter_final_reading;
use App\Models\Meter_main;
// use Session;
use App\Models\Contractor_inventory;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Consumer_detail;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class QcController extends Controller
{

    public function add_qc_executive()
    {
        return view('qcs.add_qc_executive');
    }
    public function login()
    {
        return view('qcs.login');
    }
    public function qc_view(Request $req)
    {
        $admin = Admin::where('id',session('rexkod_vishvin_auth_userid'))->first();
        // Initialize the base query
        $contractors = DB::table('admins')->where('type', '=','contractor_manager')->select('admins.id as contractor_id', 'admins.name as contractor_name')->get();
        //dd($contractors);
        //dd($req->all());
//        $so_pin_codes = DB::table('zone_codes')->orderBy('so_code')->where('package', '=', env('PACKAGE_NAME'))->get();
        $so_pin_codes = DB::table('consumer_details')->distinct('so_pincode')->select('so_pincode as so_code')->orderBy('so_pincode')->get();
        //dd($so_pin_codes);
        $query = DB::table('meter_mains')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->join('admins', 'meter_mains.created_by', "=", "admins.id")
                ->select('meter_mains.id',
                    'meter_mains.account_id',
                    'meter_mains.created_at',
                    'consumer_details.section','consumer_details.sub_division',
                    'admins.name as field_executive_name',
                    'admins.created_by as field_executive_contractor_id',
                    'meter_mains.created_by as field_executive_id')
                ->where('qc_status', '=', '0')
                ->whereNotNull('meter_mains.serial_no_new')
                ->orderBy('id');

        if(!empty($req->so_code)){
            $filter_section_code = $req->so_code;
            Session::put('vishvin_qc_executive_filters', ['section_code' => $filter_section_code]);
            //dd( session('vishvin_qc_executive_filters'));
            $query->where('consumer_details.section', '=', $filter_section_code);
        }

        $meter_mains = $query->get();

        //dd($meter_mains);
        #$meter_mains = Meter_main::where('qc_status', 0)->whereNotNull('serial_no_new')->orderBy('id')->get();
        return view('qcs.qc_view', ['meter_mains' => $meter_mains, 'so_pincodes' => $so_pin_codes, 'contractors' => $contractors, 'filter_requests' =>session('vishvin_qc_executive_filters')]);
    }

    public function qc_view_detail($id)
    {
        $meter_main = Meter_main::where('id', $id)->first();

        $consumer_detail = Consumer_detail::where('account_id', $meter_main->account_id)->first();

        $meter_previous_final_reading = Meter_final_reading::where('account_id', $meter_main->account_id)->first();
        $data = [
            'meter_main' => $meter_main,
            'consumer_detail' => $consumer_detail,
            'meter_previous_final_reading' => $meter_previous_final_reading,
            'id' => $id,
        ];
        return view('qcs.qc_view_detail', ['data' => $data, 'filter_requests' =>session('vishvin_qc_executive_filters')]);
    }
    public function edit_qc_report($id)
    {
        $meter_main = Meter_main::where('id', $id)->first();
        $consumer_detail = Consumer_detail::where('account_id', $meter_main->account_id)->first();
        $data = [
            'meter_main' => $meter_main,
            'consumer_detail' => $consumer_detail,
            'id' => $id,
        ];
        return view('qcs.edit_qc_report', ['data' => $data, 'filter_requests' =>session('vishvin_qc_executive_filters')]);
    }
    public function edit_qc_detail($id)
    {
        $meter_main = Meter_main::where('id', $id)->first();

        $consumer_detail = Consumer_detail::where('account_id', $meter_main->account_id)->first();

        $data = [
            'meter_main' => $meter_main,
            'consumer_detail' => $consumer_detail,
            'id' => $id,
        ];
        return view('qcs.edit_qc_detail', ['data' => $data, 'filter_requests' =>session('vishvin_qc_executive_filters')]);
    }
    public function index()
    {
        // qc manager
        $qc_executive = Admin::where('type', 'qc_executive')->where('created_by', session('rexkod_vishvin_auth_userid'))->get();
        $qc_executive_count = count($qc_executive);
        $get_qc_executives = Admin::where('type', 'qc_executive')->where('created_by',session('rexkod_vishvin_auth_userid'))->get();
        $data = [
            'qc_executive_count' => $qc_executive_count,
            'get_qc_executives' => $get_qc_executives,
        ];
        return view('qcs.index', ['data' => $data, 'filter_requests' =>session('vishvin_qc_executive_filters')]);
    }
    public function all_qc_executives()
    {
        // ->where('type', 'inventory_reporter')
        return view('qcs.all_qc_executives', [
            'show_users' => Admin::where('type', 'qc_executive')->where('created_by', session()->get('rexkod_vishvin_auth_userid'))->get(),
            'filter_requests' =>session('vishvin_qc_executive_filters')
        ]);
    }
    function authenticate(Request $req)
    {
        // return ($req);
        $user = Admin::where('user_name', $req->user_name)->first();
        // return($req->all());
        if ($user && Hash::check($req->password, $user->password) && $user->type == "qc_manager") {
            Session::put('rexkod_vishvin_auth_name', $user->name);
            Session::put('rexkod_vishvin_auth_userid', $user->id);
            Session::put('rexkod_vishvin_auth_phone', $user->phone);
            Session::put('rexkod_vishvin_auth_user_type', $user->type);
            return redirect('qcs/index');
        } else {
            session()->put('failed', 'Invalid Credentials');
            return redirect('/qcs');
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/qcs')->with('message', 'You have been logged out!');
    }
    function create_qc_executive(Request $req)
    {

        $auth = new Admin;


        $result = Admin::where('phone', $req->phone)->first();

        if ($result) {
            session()->put('failed', 'Phone already exists');

            return redirect('/qcs/add_qc_executive');
        } else {

            $auth->name = $req->name;

            $auth->phone = $req->phone;


            $auth->type = "qc_executive";
            $auth->password = Hash::make($req->password);

            if (strlen((string)$auth->phone) < 10) {
                session()->put('failed', 'Mobile nummber should be at least 10 digits');
                return redirect()->back();
            }


            $uppercase = preg_match('@[A-Z]@', $req->password);
            $lowercase = preg_match('@[a-z]@', $req->password);
            $number    = preg_match('@[0-9]@', $req->password);
            $specialChars = preg_match('@[^\w]@', $req->password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($req->password) < 8) {

                session()->put('failed', 'Password should be atleast 8 characters & must include atleast one upper case letter, one number, and one special character');
                return redirect()->back();
            }
            $auth->created_by = session()->get('rexkod_vishvin_auth_userid');
            $auth->save();
            session()->put('success', 'Executive added successfully');

            // $user = Admin::where('user_name', $req->user_email)->first();

            // $req->session()->put('user',$user);

            return redirect('/qcs/all_qc_executives');
        }
    }
    function bulk_approve_qcs_report(Request $req)
    {

        if ($req->input('meter_main_id') != null) {
            $meter_main_arr = implode(',', $req->input('meter_main_id'));
            $meter_main_id = explode(',', $meter_main_arr);

            for ($i = 0; $i < count($meter_main_id); $i++) {
                $meter_main = Meter_main::find($meter_main_id[$i]);
                // $meter_main->qc_remark = $req->qc_remark;
                $meter_main->qc_status = 1;
                $meter_main->qc_updated_by = session()->get('rexkod_vishvin_auth_userid');
                $meter_main->qc_updated_at = now();
                $meter_main->save();
            }
            session()->put('success', 'Congrats! The meter status has been approved for: ' . $meter_main_arr);
            // return redirect('/qcs/qc_view_detail/' . $id);
            return redirect('/qcs/qc_view');
        } else {
            session()->put('failed', 'Please check any QC report to approve.');
            return redirect()->back();
        }
    }
    public function approve_qc_reports_status(Request $req, $id)
    {
        // first case: its present in consumer_detail

        $session_so_code = '';
        if(!empty(session()->get('vishvin_qc_executive_filters')['section_code'])) $session_so_code = session()->get('vishvin_qc_executive_filters')['section_code'];

        $meter_main = Meter_main::find($id);
        // $meter_main->qc_remark = $req->qc_remark;
        $meter_main->qc_status = 1;
        $meter_main->qc_updated_by = session()->get('rexkod_vishvin_auth_userid');
        $meter_main->qc_updated_at = now();
        $meter_main->save();

        session()->put('success', 'Congrats! The meter status has been approved');
        // return redirect('/qcs/qc_view_detail/' . $id);
        //dd( session('vishvin_qc_executive_filters')['section_code']);
        return redirect('/qcs/qc_view?so_code=' . $session_so_code);
    }
    public function reject_qc_reports_status(Request $req, $id)
    {
        // first case: its present in consumer_detail

        $meter_main = Meter_main::find($id);
        $meter_main->qc_remark = $req->qc_remark;
        $meter_main->qc_status = 2;
        $meter_main->qc_updated_by = session()->get('rexkod_vishvin_auth_userid');
        $meter_main->qc_updated_at = now();
        $meter_main->save();

        session()->put('success', 'The meter status has been rejected!');
        // return redirect('/qcs/qc_view_detail/' . $id);
        return redirect('/qcs/qc_view');
    }

    public function preview_qc_reports_status(Request $req, $id)
    {
        // first case: its present in consumer_detail

        $meter_main = Meter_main::find($id);
        $meter_main->qc_remark = $req->qc_remark;
        $meter_main->qc_status = 3;
        $meter_main->qc_updated_by = session()->get('rexkod_vishvin_auth_userid');
        $meter_main->qc_updated_at = now();
        $meter_main->save();

        session()->put('success', 'The meter status has been Previwed!');
        // return redirect('/qcs/qc_view_detail/' . $id);
        return redirect('/qcs/qc_view');
    }
	
	

  /*  public function preview_meter_reports(Request $req)
    {
        if (Session('rexkod_vishvin_auth_user_type') == "qc_executive") {
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');
    
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*', 'admins.name as qc_updated_by_name') // Select fields including admin name
                    ->join('admins', 'meter_mains.qc_updated_by', '=', 'admins.id') // Join with admins table
                    ->where('meter_mains.qc_status', '=', '3')
                    ->where('meter_mains.qc_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->whereDate('meter_mains.qc_updated_at', '>=', $start_date)
                    ->whereDate('meter_mains.qc_updated_at', '<=', $end_date)
                    ->orderBy('meter_mains.id', 'DESC')
                    ->get();
            } else {
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*', 'admins.name as qc_updated_by_name') // Select fields including admin name
                    ->join('admins', 'meter_mains.qc_updated_by', '=', 'admins.id') // Join with admins table
                    ->where('meter_mains.qc_status', '=', '3')
                    ->where('meter_mains.qc_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->orderBy('meter_mains.id', 'DESC')
                    ->get();
            }
        } else {
            abort(500, 'Something went wrong');
        }
    
        return view('qcs.preview_meter_report', [
            'meter_mains' => $meter_mains,
            'filter_requests' => session('vishvin_qc_executive_filters')
        ]);
    }  */
	
	
	
    public function preview_meter_reports(Request $req)
    {
        if (Session('rexkod_vishvin_auth_user_type') == "qc_executive") {
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');
        
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*', 'admins.name as qc_updated_by_name') // Select fields including admin name
                    ->join('admins', 'meter_mains.qc_updated_by', '=', 'admins.id') // Join with admins table
                    ->where('meter_mains.qc_status', '=', '3')
                    ->where('admins.type', '=', 'qc_executive') // Filter for QC executives
                    ->whereDate('meter_mains.qc_updated_at', '>=', $start_date)
                    ->whereDate('meter_mains.qc_updated_at', '<=', $end_date)
                    ->orderBy('meter_mains.id', 'DESC')
                    ->get();
            } else {
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*', 'admins.name as qc_updated_by_name') // Select fields including admin name
                    ->join('admins', 'meter_mains.qc_updated_by', '=', 'admins.id') // Join with admins table
                    ->where('meter_mains.qc_status', '=', '3')
                    ->where('admins.type', '=', 'qc_executive') // Filter for QC executives
                    ->orderBy('meter_mains.id', 'DESC')
                    ->get();
            }
        } else {
            abort(500, 'Something went wrong');
        }
    
        return view('qcs.preview_meter_report', [
            'meter_mains' => $meter_mains,
            'filter_requests' => session('vishvin_qc_executive_filters')
        ]);
    }



    public function revokeQCStatus(Request $request, $id) {
        $meter_main = Meter_main::find($id);
    
        if ($meter_main) {
            $meter_main->qc_status = 0;  // Set qc_status to 0
            $meter_main->qc_remark = NULL;
            $meter_main->save();         // Save the changes to the database
            return redirect()->back()->with('success', 'QC Status has been revoked successfully.');
        }
    
        return redirect()->back()->with('error', 'Failed to revoke changes.');
    }
    



  /*  public function update_qc_report(Request $req, $id)
    {
        // return($req);
        // first case: its present in consumer_detail
        $meter_main = Meter_main::find($id);

        // ----------------------commented as the clinet wants---------------------
        // to remove the previous serial number from the contractor inventory used and update the new one

        // $prvious_serial_no_new = $meter_main->serial_no_new;
        // $meter_serial_no = $req->serial_no_new;

        // if($prvious_serial_no_new !== $meter_serial_no){
        //     $get_field_executive_contractor =  Admin::where('id', $meter_main->created_by)->first();
        // //   print_r($get_field_executive_contractor);
        // $contractor_inventories =  Contractor_inventory::where('contractor_id', $get_field_executive_contractor->created_by)->get();

        // foreach ($contractor_inventories as $contractor_inventory) {
        //     $individual_inventory  = $contractor_inventory->unused_meter_serial_no;
        //     // dd($individual_inventory);
        //     $individual_serial_nos = explode(",", $individual_inventory);
        //     foreach ($individual_serial_nos as $individual_serial_no) {
        //         // dd($individual_serial_no);
        //         if ($individual_serial_no == $meter_serial_no) {
        //             $current_inventory_id = $contractor_inventory->id;
        //         }

        //     }
        //     $individual_inventory  = $contractor_inventory->used_meter_serial_no;
        //     $individual_serial_nos = explode(",", $individual_inventory);
        //     foreach ($individual_serial_nos as $individual_serial_no) {

        //         if ($individual_serial_no == $prvious_serial_no_new) {
        //             $previous_inventory_id = $contractor_inventory->id;
        //         }
        //     }
        // }
        //         // dd($previous_inventory_id);

        // if (isset($current_inventory_id)) {
        //     // ****************
        //     $existingInventory = Contractor_inventory::where('id', $current_inventory_id)->first();


        //     $unused_meter_serial_no = explode(',', $existingInventory->unused_meter_serial_no);
        //     $used_meter_serial_no = explode(',', $existingInventory->used_meter_serial_no);

        //     $input_values = $meter_serial_no; // assume the checkbox values are submitted as an array
        //     // dd($input_values);
        //     // Remove the input values from unused data and add them to used data


        //     $key = array_search($input_values, $unused_meter_serial_no);
        //     if ($key !== false) {
        //         unset($unused_meter_serial_no[$key]);
        //         $used_meter_serial_no[] = $input_values;
        //     }


        //     $existingInventory->unused_meter_serial_no = implode(',', $unused_meter_serial_no);
        //     if (empty($existingInventory->unused_meter_serial_no)) {
        //         $existingInventory->unused_meter_serial_no = null;
        //     }
        //     $existingInventory->used_meter_serial_no = implode(',', $used_meter_serial_no);
        //     $existingInventory->used_meter_serial_no  = ltrim($existingInventory->used_meter_serial_no, ',');
        //     $existingInventory->save();


        //     if (isset($previous_inventory_id)) {
        //         // ****************
        //         $previousInventory = Contractor_inventory::where('id', $previous_inventory_id)->first();

        //         // dd($previousInventory);

        //         $unused_meter_serial_no = explode(',', $previousInventory->unused_meter_serial_no);
        //         $used_meter_serial_no = explode(',', $previousInventory->used_meter_serial_no);

        //         $input_values = $prvious_serial_no_new; // assume the checkbox values are submitted as an array
        //         // dd($input_values);


        //         $key = array_search($input_values, $used_meter_serial_no);
        //         if ($key !== false) {
        //             unset($used_meter_serial_no[$key]);
        //             $unused_meter_serial_no[] = $input_values;
        //         }


        //         $previousInventory->used_meter_serial_no = implode(',', $used_meter_serial_no);
        //         if (empty($previousInventory->used_meter_serial_no)) {
        //             $previousInventory->used_meter_serial_no = null;
        //         }
        //         $previousInventory->unused_meter_serial_no = implode(',', $unused_meter_serial_no);
        //         $previousInventory->unused_meter_serial_no  = ltrim($previousInventory->unused_meter_serial_no, ',');
        //         $previousInventory->save();


        //     }
        // }else{
        //     session()->put('failed', 'Entered meter serial not found');
        //     return redirect('/qcs/edit_qc_detail/'. $id);
        // }


        // }

        // ---------------------------------------


        $meter_main->meter_make_old = $req->meter_make_old;
        $meter_main->serial_no_old = $req->serial_no_old;
        $meter_main->mfd_year_old = $req->mfd_year_old;
        $meter_main->final_reading = $req->final_reading;
        $meter_main->meter_make_new = $req->meter_make_new;
        $meter_main->serial_no_new = $req->serial_no_new;
        $meter_main->mfd_year_new = $req->mfd_year_new;
        $meter_main->initial_reading_kwh = $req->initial_reading_kwh;
        $meter_main->initial_reading_kvah = $req->initial_reading_kvah;
        if (!empty($req->file('image_1_old'))) {
            $extension1 = $req->file('image_1_old')->extension();
            if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
                $filename = Str::random(4) . time() . '.' . $extension1;
                $meter_main->image_1_old = $req->file('image_1_old')->move(('uploads'), $filename);
            }
        } else {
            $meter_main->image_1_old = $meter_main->image_1_old;
        }

        if (!empty($req->file('image_2_old'))) {
            $extension1 = $req->file('image_2_old')->extension();
            if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
                $filename = Str::random(4) . time() . '.' . $extension1;
                $meter_main->image_2_old = $req->file('image_2_old')->move(('uploads'), $filename);
            }
        } else {
            $meter_main->image_2_old = $meter_main->image_2_old;
        }
        if (!empty($req->file('image_3_old'))) {
            $extension1 = $req->file('image_3_old')->extension();
            if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
                $filename = Str::random(4) . time() . '.' . $extension1;
                $meter_main->image_3_old = $req->file('image_3_old')->move(('uploads'), $filename);
            }
        } else {
            $meter_main->image_3_old = $meter_main->image_3_old;
        }
        if (!empty($req->file('image_1_new'))) {
            $extension1 = $req->file('image_1_new')->extension();
            if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
                $filename = Str::random(4) . time() . '.' . $extension1;
                $meter_main->image_1_new = $req->file('image_1_new')->move(('uploads'), $filename);
            }
        } else {
            $meter_main->image_1_new = $meter_main->image_1_new;
        }

        if (!empty($req->file('image_2_new'))) {
            $extension1 = $req->file('image_2_new')->extension();
            if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
                $filename = Str::random(4) . time() . '.' . $extension1;
                $meter_main->image_2_new = $req->file('image_2_new')->move(('uploads'), $filename);
            }
        } else {
            $meter_main->image_2_new = $meter_main->image_2_new;
        }


        $meter_main->save();

        // session()->put('success', 'Congrats! The meter status has been submitted successfully');
        return redirect('/qcs/qc_view_detail/' . $id);
    }  */


    public function update_qc_report(Request $req, $id)
    {
        // return($req);
        // first case: its present in consumer_detail
        $meter_main = Meter_main::find($id);

        // ----------------------commented as the clinet wants---------------------
        // to remove the previous serial number from the contractor inventory used and update the new one

        $prvious_serial_no_new = $meter_main->serial_no_new;
         $meter_serial_no = $req->serial_no_new;

        if($prvious_serial_no_new !== $meter_serial_no){
         $get_field_executive_contractor =  Admin::where('id', $meter_main->created_by)->first();
         //print_r($get_field_executive_contractor);
        $contractor_inventories =  Contractor_inventory::where('contractor_id', $get_field_executive_contractor->created_by)->get();

         foreach ($contractor_inventories as $contractor_inventory) {
          $individual_inventory  = $contractor_inventory->unused_meter_serial_no;
           // dd($individual_inventory);
        $individual_serial_nos = explode(",", $individual_inventory);
         foreach ($individual_serial_nos as $individual_serial_no) {
             // dd($individual_serial_no);
                if ($individual_serial_no == $meter_serial_no) {
                  $current_inventory_id = $contractor_inventory->id;
               }

            }
            $individual_inventory  = $contractor_inventory->used_meter_serial_no;
            $individual_serial_nos = explode(",", $individual_inventory);
            foreach ($individual_serial_nos as $individual_serial_no) {

              if ($individual_serial_no == $prvious_serial_no_new) {
               $previous_inventory_id = $contractor_inventory->id;
               }
             }
         }
        //         // dd($previous_inventory_id);

         if (isset($current_inventory_id)) {
        //     // ****************
            $existingInventory = Contractor_inventory::where('id', $current_inventory_id)->first();


          $unused_meter_serial_no = explode(',', $existingInventory->unused_meter_serial_no);
          $used_meter_serial_no = explode(',', $existingInventory->used_meter_serial_no);

          $input_values = $meter_serial_no; // assume the checkbox values are submitted as an array
        //     // dd($input_values);
        //     // Remove the input values from unused data and add them to used data


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
           $existingInventory->used_meter_serial_no  = ltrim($existingInventory->used_meter_serial_no, ',');
            $existingInventory->save();


            if (isset($previous_inventory_id)) {
        //         // ****************
              $previousInventory = Contractor_inventory::where('id', $previous_inventory_id)->first();

        //         // dd($previousInventory);

         $unused_meter_serial_no = explode(',', $previousInventory->unused_meter_serial_no);
            $used_meter_serial_no = explode(',', $previousInventory->used_meter_serial_no);

          $input_values = $prvious_serial_no_new; // assume the checkbox values are submitted as an array
        //        // dd($input_values);


          $key = array_search($input_values, $used_meter_serial_no);
         if ($key !== false) {
         unset($used_meter_serial_no[$key]);
                  $unused_meter_serial_no[] = $input_values;
                }


                   $previousInventory->used_meter_serial_no = implode(',', $used_meter_serial_no);
                   if (empty($previousInventory->used_meter_serial_no)) {
                 $previousInventory->used_meter_serial_no = null;
               }
            $previousInventory->unused_meter_serial_no = implode(',', $unused_meter_serial_no);
              $previousInventory->unused_meter_serial_no  = ltrim($previousInventory->unused_meter_serial_no, ',');
          $previousInventory->save();
           }
         }else{
           session()->put('failed', 'Entered meter serial not found');
            return redirect('/qcs/edit_qc_detail/'. $id);
         }


         }

        // ---------------------------------------


        $meter_main->meter_make_old = $req->meter_make_old;
        $meter_main->serial_no_old = $req->serial_no_old;
        $meter_main->mfd_year_old = $req->mfd_year_old;
        $meter_main->final_reading = $req->final_reading;
        $meter_main->meter_make_new = $req->meter_make_new;
        $meter_main->serial_no_new = $req->serial_no_new;
        $meter_main->mfd_year_new = $req->mfd_year_new;
        $meter_main->initial_reading_kwh = $req->initial_reading_kwh;
        $meter_main->initial_reading_kvah = $req->initial_reading_kvah;
        if (!empty($req->file('image_1_old'))) {
            $extension1 = $req->file('image_1_old')->extension();
            if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
                $filename = Str::random(4) . time() . '.' . $extension1;
                $meter_main->image_1_old = $req->file('image_1_old')->move(('uploads'), $filename);
            }
        } else {
            $meter_main->image_1_old = $meter_main->image_1_old;
        }

        if (!empty($req->file('image_2_old'))) {
            $extension1 = $req->file('image_2_old')->extension();
            if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
                $filename = Str::random(4) . time() . '.' . $extension1;
                $meter_main->image_2_old = $req->file('image_2_old')->move(('uploads'), $filename);
            }
        } else {
            $meter_main->image_2_old = $meter_main->image_2_old;
        }
        if (!empty($req->file('image_3_old'))) {
            $extension1 = $req->file('image_3_old')->extension();
            if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
                $filename = Str::random(4) . time() . '.' . $extension1;
                $meter_main->image_3_old = $req->file('image_3_old')->move(('uploads'), $filename);
            }
        } else {
            $meter_main->image_3_old = $meter_main->image_3_old;
        }
        if (!empty($req->file('image_1_new'))) {
            $extension1 = $req->file('image_1_new')->extension();
            if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
                $filename = Str::random(4) . time() . '.' . $extension1;
                $meter_main->image_1_new = $req->file('image_1_new')->move(('uploads'), $filename);
            }
        } else {
            $meter_main->image_1_new = $meter_main->image_1_new;
        }

        if (!empty($req->file('image_2_new'))) {
            $extension1 = $req->file('image_2_new')->extension();
            if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
                $filename = Str::random(4) . time() . '.' . $extension1;
                $meter_main->image_2_new = $req->file('image_2_new')->move(('uploads'), $filename);
            }
        } else {
            $meter_main->image_2_new = $meter_main->image_2_new;
        }


        $meter_main->save();

        // session()->put('success', 'Congrats! The meter status has been submitted successfully');
        return redirect('/qcs/qc_view_detail/' . $id);
    }




    public function approved_meter_reports(Request $req)
    {

        if (Session('rexkod_vishvin_auth_user_type') == "qc_executive") {
            if ($req->format === 'weekly') {

                $dateSevenDaysAgo = Carbon::now()->subDays(7);

                $start_date = $dateSevenDaysAgo->format('Y-m-d');
                $meter_mains = DB::table('meter_mains')
                ->select('meter_mains.*')
                ->where('qc_status', '=', '1')
                ->where('qc_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                ->whereDate('qc_updated_at', '>=', $start_date)
                // ->whereDate('qc_updated_at', '<=', $end_date)
                ->orderBy('id', 'DESC')
                ->get();


            } else if ($req->format === 'monthly') {

                $dateSevenDaysAgo = Carbon::now()->subDays(30);

                $start_date = $dateSevenDaysAgo->format('Y-m-d');
                $meter_mains = DB::table('meter_mains')
                ->select('meter_mains.*')
                ->where('qc_status', '=', '1')
                ->where('qc_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                ->whereDate('qc_updated_at', '>=', $start_date)
                // ->whereDate('qc_updated_at', '<=', $end_date)
                ->orderBy('id', 'DESC')
                ->get();

            }else{
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('qc_status', '=', '1')
                    ->where('qc_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->whereDate('qc_updated_at', '>=', $start_date)
                    ->whereDate('qc_updated_at', '<=', $end_date)
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('qc_status', '=', '1')
                    ->where('qc_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->orderBy('id', 'DESC')
                    ->get();
            }
        }
    }
        else {
            abort(500, 'Something went wrong');
        }
        // dd($meter_mains);
        return view('qcs.approved_meter_reports', ['meter_mains' => $meter_mains, 'filter_requests' =>session('vishvin_qc_executive_filters')]);
    }
    public function rejected_meter_reports(Request $req)
    {

        if (Session('rexkod_vishvin_auth_user_type') == "qc_executive") {
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');


                $end_date = $req->get('end_date');
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('qc_status', '=', '2')
                    ->where('qc_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->whereDate('qc_updated_at', '>=', $start_date)
                    ->whereDate('qc_updated_at', '<=', $end_date)
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('qc_status', '=', '2')
                    ->where('qc_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->orderBy('id', 'DESC')
                    ->get();
            }

        } else {
            abort(500, 'Something went wrong');
        }
        // dd($meter_mains);
        return view('qcs.rejected_meter_reports', ['meter_mains' => $meter_mains, 'filter_requests' =>session('vishvin_qc_executive_filters')]);
    }


    public function reports(Request $req){

        if ($req->format === 'weekly') {
            # code...
            $dateSevenDaysAgo = Carbon::now()->subDays(7);
            $start_date = $dateSevenDaysAgo->format('Y-m-d');

            $qc_approved_date_wise = DB::table('meter_mains')
            ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
            ->whereNotNull('qc_updated_by')
            ->where('qc_updated_at', '>=', $start_date)
            ->groupBy('created_date', 'qc_updated_by')
            ->get();

             // to get the total aprroved tilldate
             $qc_approved_till_date = DB::table('meter_mains')
             ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                 COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                 COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
             ->whereNotNull('qc_updated_by')
             ->where('qc_updated_at', '<', $start_date)
             ->groupBy('created_date', 'qc_updated_by')
             ->get();

             $total_meter_approved_till_date = 0;
             foreach ($qc_approved_till_date as $qc_approved){
                 $total_meter_approved_till_date += $qc_approved->record_approved;
             }
             $total_approved = [];
             $i=0;
             foreach ($qc_approved_date_wise as $qc_approved){
                 $total_meter_approved_till_date += $qc_approved->record_approved;
                 $total_approved[$i] = $total_meter_approved_till_date;
                 $i++;
             }

        } elseif($req->format === 'monthly') {
            # code...
            $today = Carbon::now();
            $dateSevenDaysAgo = Carbon::now()->subDays(30);
            $start_date = $dateSevenDaysAgo->format('Y-m-d');
            $end_date = $today->format('Y-m-d');

            $qc_approved_date_wise = DB::table('meter_mains')
            ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
            ->whereNotNull('qc_updated_by')
            ->where('qc_updated_at', '>=', $start_date)
            ->groupBy('created_date', 'qc_updated_by')
            ->get();

             // to get the total aprroved tilldate
             $qc_approved_till_date = DB::table('meter_mains')
             ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                 COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                 COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
             ->whereNotNull('qc_updated_by')
             ->where('qc_updated_at', '<', $start_date)
             ->groupBy('created_date', 'qc_updated_by')
             ->get();

             $total_meter_approved_till_date = 0;
             foreach ($qc_approved_till_date as $qc_approved){
                 $total_meter_approved_till_date += $qc_approved->record_approved;
             }
             $total_approved = [];
             $i=0;
             foreach ($qc_approved_date_wise as $qc_approved){
                 $total_meter_approved_till_date += $qc_approved->record_approved;
                 $total_approved[$i] = $total_meter_approved_till_date;
                 $i++;
             }

        } else {
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');

                $qc_approved_date_wise = DB::table('meter_mains')
                ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                    COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                    COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
                ->whereNotNull('qc_updated_by')
                ->where('qc_updated_at', '>=', $start_date)
                ->where('qc_updated_at', '<=', $end_date)
                ->groupBy('created_date', 'qc_updated_by')
                ->get();

                // to get the total aprroved tilldate
                $qc_approved_till_date = DB::table('meter_mains')
                ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                    COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                    COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
                ->whereNotNull('qc_updated_by')
                ->where('qc_updated_at', '<', $start_date)
                ->groupBy('created_date', 'qc_updated_by')
                ->get();

                $total_meter_approved_till_date = 0;
                foreach ($qc_approved_till_date as $qc_approved){
                    $total_meter_approved_till_date += $qc_approved->record_approved;
                }
                $total_approved = [];
                $i=0;
                foreach ($qc_approved_date_wise as $qc_approved){
                    $total_meter_approved_till_date += $qc_approved->record_approved;
                    $total_approved[$i] = $total_meter_approved_till_date;
                    $i++;
                }


            } else {
                $qc_approved_date_wise = DB::table('meter_mains')
                ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                    COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                    COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
                ->whereNotNull('qc_updated_by')
                ->groupBy('created_date', 'qc_updated_by')
                ->get();

                // to get the total aprroved tilldate
                $total_approved = [];
                $total_meter_approved_till_date = 0;
                $i=0;
                foreach ($qc_approved_date_wise as $qc_approved){
                    $total_meter_approved_till_date += $qc_approved->record_approved;
                    $total_approved[$i] = $total_meter_approved_till_date;
                    $i++;
                }
            }
        }



        return view('qcs.reports', ['qc_approved_date_wise' => $qc_approved_date_wise , 'total_approved' => $total_approved, 'filter_requests' =>session('vishvin_qc_executive_filters')]);

    }

    public function executive_reports(Request $req){

        if ($req->format === 'weekly') {
            # code...
            $dateSevenDaysAgo = Carbon::now()->subDays(7);
            $start_date = $dateSevenDaysAgo->format('Y-m-d');

            $qc_approved_date_wise = DB::table('meter_mains')
            ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
            ->whereNotNull('qc_updated_by')
            ->where('qc_updated_at', '>=', $start_date)
            ->where('qc_updated_by',session()->get('rexkod_vishvin_auth_userid'))
            ->groupBy('created_date', 'qc_updated_by')
            ->get();

             // to get the total aprroved tilldate
             $qc_approved_till_date = DB::table('meter_mains')
             ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                 COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                 COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
             ->whereNotNull('qc_updated_by')
             ->where('qc_updated_at', '<', $start_date)
             ->where('qc_updated_by',session()->get('rexkod_vishvin_auth_userid'))
             ->groupBy('created_date', 'qc_updated_by')
             ->get();

             $total_meter_approved_till_date = 0;
             foreach ($qc_approved_till_date as $qc_approved){
                 $total_meter_approved_till_date += $qc_approved->record_approved;
             }
             $total_approved = [];
             $i=0;
             foreach ($qc_approved_date_wise as $qc_approved){
                 $total_meter_approved_till_date += $qc_approved->record_approved;
                 $total_approved[$i] = $total_meter_approved_till_date;
                 $i++;
             }

        } elseif($req->format === 'monthly') {
            # code...
            $today = Carbon::now();
            $dateSevenDaysAgo = Carbon::now()->subDays(30);
            $start_date = $dateSevenDaysAgo->format('Y-m-d');
            $end_date = $today->format('Y-m-d');

            $qc_approved_date_wise = DB::table('meter_mains')
            ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
            ->whereNotNull('qc_updated_by')
            ->where('qc_updated_at', '>=', $start_date)
            ->where('qc_updated_by',session()->get('rexkod_vishvin_auth_userid'))
            ->groupBy('created_date', 'qc_updated_by')
            ->get();

             // to get the total aprroved tilldate
             $qc_approved_till_date = DB::table('meter_mains')
             ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                 COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                 COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
             ->whereNotNull('qc_updated_by')
             ->where('qc_updated_at', '<', $start_date)
             ->where('qc_updated_by',session()->get('rexkod_vishvin_auth_userid'))
             ->groupBy('created_date', 'qc_updated_by')
             ->get();

             $total_meter_approved_till_date = 0;
             foreach ($qc_approved_till_date as $qc_approved){
                 $total_meter_approved_till_date += $qc_approved->record_approved;
             }
             $total_approved = [];
             $i=0;
             foreach ($qc_approved_date_wise as $qc_approved){
                 $total_meter_approved_till_date += $qc_approved->record_approved;
                 $total_approved[$i] = $total_meter_approved_till_date;
                 $i++;
             }

        } else {
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');

                $qc_approved_date_wise = DB::table('meter_mains')
                ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                    COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                    COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
                ->whereNotNull('qc_updated_by')
                ->where('qc_updated_at', '>=', $start_date)
                ->where('qc_updated_at', '<=', $end_date)
                ->where('qc_updated_by',session()->get('rexkod_vishvin_auth_userid'))
                ->groupBy('created_date', 'qc_updated_by')
                ->get();

                // to get the total aprroved tilldate
                $qc_approved_till_date = DB::table('meter_mains')
                ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                    COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                    COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
                ->whereNotNull('qc_updated_by')
                ->where('qc_updated_at', '<', $start_date)
                ->where('qc_updated_by',session()->get('rexkod_vishvin_auth_userid'))
                ->groupBy('created_date', 'qc_updated_by')
                ->get();

                $total_meter_approved_till_date = 0;
                foreach ($qc_approved_till_date as $qc_approved){
                    $total_meter_approved_till_date += $qc_approved->record_approved;
                }
                $total_approved = [];
                $i=0;
                foreach ($qc_approved_date_wise as $qc_approved){
                    $total_meter_approved_till_date += $qc_approved->record_approved;
                    $total_approved[$i] = $total_meter_approved_till_date;
                    $i++;
                }


            } else {
                $qc_approved_date_wise = DB::table('meter_mains')
                ->select(DB::raw('DATE(qc_updated_at) AS created_date, qc_updated_by,
                    COUNT(CASE WHEN qc_status = 1 THEN 1 ELSE NULL END) AS record_approved,
                    COUNT(CASE WHEN qc_status = 2 THEN 1 ELSE NULL END) AS record_rejected'))
                ->whereNotNull('qc_updated_by')
                ->where('qc_updated_by',session()->get('rexkod_vishvin_auth_userid'))
                ->groupBy('created_date', 'qc_updated_by')
                ->get();

                // to get the total aprroved tilldate
                $total_approved = [];
                $total_meter_approved_till_date = 0;
                $i=0;
                foreach ($qc_approved_date_wise as $qc_approved){
                    $total_meter_approved_till_date += $qc_approved->record_approved;
                    $total_approved[$i] = $total_meter_approved_till_date;
                    $i++;
                }
            }
        }



        return view('qc_executives.reports', ['qc_approved_date_wise' => $qc_approved_date_wise , 'total_approved' => $total_approved, 'filter_requests' =>session('vishvin_qc_executive_filters')]);

    }

    public function qc_executive_index()
    {
        $qc_meter_approved_count = 0;
        $get_all_meter_main_data =  Meter_main::where('qc_status', '1')->where('so_status', '1')->where('aao_status', '1')->where('aee_status', '1')->get();
        $qc_meter_approved_count =  count(Meter_main::where('qc_status', '1')->where('qc_updated_by',session()->get('rexkod_vishvin_auth_userid'))->get());
        $qc_meter_rejected_count = 0;
        $get_qc_rejected_main_meter =  Meter_main::where('qc_status', '2')->get();
        $qc_meter_rejected_count =  count($get_qc_rejected_main_meter);
        $qc_meter_pending_count = 0;
        $get_qc_pending_main_meter =  Meter_main::where('qc_status', '0')->whereNotNull('serial_no_new')->get();
        $qc_meter_pending_count =  count($get_qc_pending_main_meter);


        $data = [
            'qc_meter_approved_count' => $qc_meter_approved_count,
            'qc_meter_rejected_count' => $qc_meter_rejected_count,
            'qc_meter_pending_count' => $qc_meter_pending_count,
        ];
        return view('qc_executives.index', ['data' => $data, 'filter_requests' =>session('vishvin_qc_executive_filters')]);
    }
	
	
    public function date_replacement() 
    {
        return view('qcs.date_replacement');
    }

	  public function final_reading_change() 
      {
        return view('qcs.final_reading');
      }


 
    public function date_change(Request $request)
    {
        $searchInput = $request->input('account_id');
        $endDate = $request->input('end_date');
        $data = [
            "account_id" => "notexist"
        ];
    
       /* $existed_account_id = Meter_main::where('account_id', '=', $searchInput)
                                         ->where('download_flag', '=', 0)
                                         ->first(); */
    
		     $existed_account_id = Meter_main::where('account_id', '=', $searchInput)
                                  ->whereNotIn('account_id', function($query)
                                  {
                                          $query->select('account_id')
                                          ->from('successful_records');
                                  })
                                 ->first();
        //dd($existed_account_id);

        if($existed_account_id)
        { 
            // Check if $searchInput and $endDate are not null
            if ($searchInput && $endDate) 
            {
                // Extract the time from the existing created_at timestamp
                $time = Carbon::parse($existed_account_id->created_at)->format('H:i:s');       
                // Create a new DateTime instance with the given date and the extracted time
                $newCreatedAt = Carbon::createFromFormat('Y-m-d', $endDate)->setTimeFromTimeString($time);              
                // Update the created_at column
                $existed_account_id->created_at = $newCreatedAt;
                $existed_account_id->save();               
                $data["account_id"] = "exist";
                session()->put('success', 'Date Changed Successfully');
            }
            else
            {
                $data["account_id"] = "input-error"; 
            }
        }
        else
        {
            $data["account_id"] = "download-flag-error"; // Download flag is not 0
        }
    
        return view('qcs.date_replacement', ['data' => $data]);
    }
	
	   public function final_meter_reading_search(Request $request)
   		 {
        $searchInput = $request->input('account_id');
        $existed_account_id = Meter_main::where('account_id', '=', $searchInput)->first();
       // $data = $existed_account_id;

        $data = [
            'meter_main' => $existed_account_id,
        ];
       // dd($data);

        return view('qcs.final_reading_submission', ['data' => $data]);
  	     }
		  public function final_meter_reading_update(Request $req, $id)
   		 {     
		        $meter_main = Meter_main::find($id);
        		$meter_main->final_reading = $req->final_reading;
                $meter_main->save();	
		
				session()->put('success', 'Congrats! The meter Final Reading has been submitted successfully');
                return view('qcs.final_reading');
    
   		 }

		
}
