<?php

namespace App\Http\Controllers;

use App\Http\Services\BmrDownloadService;
use App\Models\Admin;
use App\Models\Contractor;
use App\Models\ContractorInventoryownership;
use App\Models\Meter_final_reading;
use App\Models\Meter_main;
use App\Models\Indent;
use App\Models\Contractor_inventory;
use App\Models\Error_record;
use App\Models\Successful_record;
use App\Models\MeterMainsVishvinQC;
use App\Models\SuccessfulRecord;
// use Session;
use Illuminate\Http\Request;
use App\Models\Consumer_detail;
use App\Models\Zone_code;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class HescomController extends Controller
{

    public function add_hescom_executive()
    {
        // $so_pincode = Consumer_detail::select('so_pincode')->distinct()->get();
        // $sd_pincode = Consumer_detail::select('sd_pincode')->distinct()->get();
        $so_pincode = Zone_code::select('so_code')->distinct()->get();
        $sd_pincode = Zone_code::select('sd_code')->distinct()->get();

        $so_pincodes_bvu = Zone_code::select('so_code')->distinct()->where('package', env('PACKAGE_NAME'))->get();
        $sd_pincodes_bvu = Zone_code::select('sd_code')->distinct()->where('package', env('PACKAGE_NAME'))->get();

        $so_pincodes_hdgu = Zone_code::select('so_code')->distinct()->where('package', env('PACKAGE_NAME'))->get();
        $sd_pincodes_hdgu = Zone_code::select('sd_code')->distinct()->where('package', env('PACKAGE_NAME'))->get();


        $data = [
            'so_pincode' => $so_pincode,
            'sd_pincode' => $sd_pincode,

            'so_pincodes_bvu' => $so_pincodes_bvu,
            'sd_pincodes_bvu' => $sd_pincodes_bvu,
            'so_pincodes_hdgu' => $so_pincodes_hdgu,
            'sd_pincodes_hdgu' => $sd_pincodes_hdgu,
        ];
        return view('hescoms.add_hescom_executive', ['data' => $data]);
    }

    public function login()
    {
        return view('hescoms.login');
    }

    public function hescom_view()
    {
        if (Session('rexkod_vishvin_auth_user_type') == "ae") {

            $meter_mains = DB::table('meter_mains')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->join('admins', 'consumer_details.so_pincode', '=', 'admins.so_pincode')
                ->select('meter_mains.id', 'meter_mains.account_id')
                ->where('admins.id', session('rexkod_vishvin_auth_userid'))
                ->where('qc_status', '=', '1')
                ->where('so_status', '=', '0')
                ->orderBy('id', 'DESC')
                ->get();


            // $meter_mains = Meter_main::where('qc_status', '1')->where('so_status', '0')->orderBy('id')->get();

            //         $meter_mains = DB::table('meter_mains')
            //             ->join('consumer_details', 'consumer_details.account_id', '=', 'meter_mains.account_id')
            //             ->join('admins', 'admins.id', '=',  session('rexkod_vishvin_auth_userid'))
            //             ->where('admins.so_pincode', '=', 'consumer_details.so_pincode')
            // ->get();


            // ->where('qc_status','=','1')
            // ->where('so_status','=','0')
            // ->orderBy('id','DESC')
            // ->get();
        } elseif (Session('rexkod_vishvin_auth_user_type') == "aee") {

            $meter_mains = DB::table('meter_mains')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->join('admins', 'consumer_details.sd_pincode', '=', 'admins.sd_pincode')
                ->select('meter_mains.id', 'meter_mains.account_id')
                ->where('admins.id', session('rexkod_vishvin_auth_userid'))
                ->where('qc_status', '=', '1')
                ->where('so_status', '=', '1')
                ->where('aee_status', '=', '0')
                ->orderBy('id', 'DESC')
                ->get();
        } elseif (Session('rexkod_vishvin_auth_user_type') == "aao") {
            $meter_mains = DB::table('meter_mains')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->join('admins', 'consumer_details.sd_pincode', '=', 'admins.sd_pincode')
                ->select('meter_mains.id', 'meter_mains.account_id')
                ->where('admins.id', session('rexkod_vishvin_auth_userid'))
                ->where('qc_status', 1)
                ->where('so_status', 1)
                ->where('aee_status', 1)
                ->where('aao_status', 0)
                ->orderBy('id')->get();
        } else {
            abort(500, 'Something went wrong');
        }
        // dd($meter_mains);
        return view('hescoms.hescom_view', ['meter_mains' => $meter_mains]);
    }

    public function approved_meter_reports(Request $req)
    {

        if (Session('rexkod_vishvin_auth_user_type') == "ae") {
            if ($req->format === 'weekly') {

                $dateSevenDaysAgo = Carbon::now()->subDays(7);

                $start_date = $dateSevenDaysAgo->format('Y-m-d');
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('so_status', '=', '1')
                    ->where('so_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->whereDate('so_updated_at', '>=', $start_date)
                    // ->whereDate('so_updated_at', '<=', $end_date)
                    ->orderBy('id', 'DESC')
                    ->get();

            } else if ($req->format === 'monthly') {

                $dateSevenDaysAgo = Carbon::now()->subDays(30);

                $start_date = $dateSevenDaysAgo->format('Y-m-d');
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('so_status', '=', '1')
                    ->where('so_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->whereDate('so_updated_at', '>=', $start_date)
                    // ->whereDate('so_updated_at', '<=', $end_date)
                    ->orderBy('id', 'DESC')
                    ->get();

            } else {
                if ($req->get('start_date') !== null) {
                    $start_date = $req->get('start_date');


                    $end_date = $req->get('end_date');
                    $meter_mains = DB::table('meter_mains')
                        ->select('meter_mains.*')
                        ->where('so_status', '=', '1')
                        ->where('so_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                        ->whereDate('so_updated_at', '>=', $start_date)
                        ->whereDate('so_updated_at', '<=', $end_date)
                        ->orderBy('id', 'DESC')
                        ->get();
                } else {
                    $meter_mains = DB::table('meter_mains')
                        ->select('meter_mains.*')
                        ->where('so_status', '=', '1')
                        ->where('so_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                        ->orderBy('id', 'DESC')
                        ->get();
                }
            }
        } elseif (Session('rexkod_vishvin_auth_user_type') == "aee") {
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('aee_status', '=', '1')
                    ->where('aee_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->whereDate('aee_updated_at', '>=', $start_date)
                    ->whereDate('aee_updated_at', '<=', $end_date)
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('aee_status', '=', '1')
                    ->where('aee_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->orderBy('id', 'DESC')
                    ->get();
            }
        } elseif (Session('rexkod_vishvin_auth_user_type') == "aao") {
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');

                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('aao_status', '=', '1')
                    ->where('aao_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->whereDate('aao_updated_at', '>=', $start_date)
                    ->whereDate('aao_updated_at', '<=', $end_date)
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('aao_status', '=', '1')
                    ->where('aao_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->orderBy('id', 'DESC')
                    ->get();
            }
        } else {
            abort(500, 'Something went wrong');
        }
        // dd($meter_mains);
        return view('hescoms.approved_meter_reports', ['meter_mains' => $meter_mains]);
    }

    public function rejected_meter_reports(Request $req)
    {

        if (Session('rexkod_vishvin_auth_user_type') == "ae") {
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');


                $end_date = $req->get('end_date');
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('so_status', '=', '2')
                    ->where('so_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->whereDate('so_updated_at', '>=', $start_date)
                    ->whereDate('so_updated_at', '<=', $end_date)
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('so_status', '=', '2')
                    ->where('so_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->orderBy('id', 'DESC')
                    ->get();
            }
        } elseif (Session('rexkod_vishvin_auth_user_type') == "aee") {
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('aee_status', '=', '2')
                    ->where('aee_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->whereDate('aee_updated_at', '>=', $start_date)
                    ->whereDate('aee_updated_at', '<=', $end_date)
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('aee_status', '=', '2')
                    ->where('aee_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->orderBy('id', 'DESC')
                    ->get();
            }
        } elseif (Session('rexkod_vishvin_auth_user_type') == "aao") {
            if ($req->get('start_date') !== null) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');

                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('aao_status', '=', '2')
                    ->where('aao_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->whereDate('aao_updated_at', '>=', $start_date)
                    ->whereDate('aao_updated_at', '<=', $end_date)
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $meter_mains = DB::table('meter_mains')
                    ->select('meter_mains.*')
                    ->where('aao_status', '=', '2')
                    ->where('aao_updated_by', '=', session('rexkod_vishvin_auth_userid'))
                    ->orderBy('id', 'DESC')
                    ->get();
            }
        } else {
            abort(500, 'Something went wrong');
        }
        // dd($meter_mains);
        return view('hescoms.rejected_meter_reports', ['meter_mains' => $meter_mains]);
    }

    function hescom_status(Request $req)
    {

        // $meter_main = DB::table('meter_mains')
        // ->select('name')
        // ->get();
        // return view('hescoms.index', ['data' => $meter_main]);
        $name = "Tony Stack";
        return view('hescoms.index', compact('name'));
    }

    public function hescom_view_detail($id)
    {
        $meter_main = Meter_main::where('id', $id)->first();

        $consumer_detail = Consumer_detail::where('account_id', $meter_main->account_id)->first();

        $current_logged_user = session('rexkod_vishvin_auth_userid');
        $edit_button_show = 'false';
        if(in_array($current_logged_user, ['12', '13', '14'])){
            $edit_button_show = 'true';
        }

        $data = [
            'meter_main' => $meter_main,
            'consumer_detail' => $consumer_detail,
            'id' => $id,
            'edit_button_show' => $edit_button_show
        ];
        return view('hescoms.hescom_view_detail', ['data' => $data]);
    }

    public function hescom_edit_detail($id)
    {
        $meter_main = Meter_main::where('id', $id)->first();

        $consumer_detail = Consumer_detail::where('account_id', $meter_main->account_id)->first();

        $current_logged_user = session('rexkod_vishvin_auth_userid');
        $show_so_remark_field = 'false';
        if(in_array($current_logged_user, ['12', '13', '14'])){
            $show_so_remark_field = 'true';
        }

        $data = [
            'meter_main' => $meter_main,
            'consumer_detail' => $consumer_detail,
            'id' => $id,
            'show_so_remark_field' => $show_so_remark_field
        ];
        return view('hescoms.hescom_edit_detail', ['data' => $data, 'filter_requests' => session('vishvin_qc_executive_filters')]);
    }

    public function hescom_update_detail(Request $req, $id)
    {
        $meter_main = Meter_main::find($id);

        $meter_main->meter_make_old = $req->meter_make_old;
        $meter_main->serial_no_old = $req->serial_no_old;
        $meter_main->mfd_year_old = $req->mfd_year_old;
        $meter_main->final_reading = $req->final_reading;
        $meter_main->meter_make_new = $req->meter_make_new;
        $meter_main->serial_no_new = $req->serial_no_new;
        $meter_main->mfd_year_new = $req->mfd_year_new;
        $meter_main->initial_reading_kwh = $req->initial_reading_kwh;
        $meter_main->initial_reading_kvah = $req->initial_reading_kvah;
        $meter_main->so_remark = $req->so_remark;
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
        return redirect('/hescoms/hescom_view_detail/' . $id);
    }

    function bulk_approve_hescoms_report(Request $req)
    {

        if ($req->input('meter_main_id') != null) {
            $meter_main_arr = implode(',', $req->input('meter_main_id'));
            $meter_main_id = explode(',', $meter_main_arr);

            for ($i = 0; $i < count($meter_main_id); $i++) {
                $meter_main = Meter_main::find($meter_main_id[$i]);


                if (session()->get('rexkod_vishvin_auth_user_type') == "ae") {

                    $meter_main->so_status = 1;
                    $meter_main->so_updated_by = session()->get('rexkod_vishvin_auth_userid');
                    $meter_main->so_updated_at = now();
                    $meter_main->save();
                } elseif (session()->get('rexkod_vishvin_auth_user_type') == "aee") {

                    $meter_main->aee_status = 1;
                    $meter_main->aee_updated_by = session()->get('rexkod_vishvin_auth_userid');
                    $meter_main->aee_updated_at = now();
                    $meter_main->save();
                } elseif (session()->get('rexkod_vishvin_auth_user_type') == "aao") {


                    $meter_main->aao_status = 1;
                    $meter_main->aao_updated_by = session()->get('rexkod_vishvin_auth_userid');
                    $meter_main->aao_updated_at = now();
                } else {
                    session()->put('failed', 'Please check any QC report to approve.');
                    return redirect()->back();
                }
                $meter_main->save();
            }
            session()->put('success', 'Congrats! The meter status has been approved for: ' . $meter_main_arr);
            // return redirect('/qcs/qc_view_detail/' . $id);
            return redirect('/hescoms/hescom_view');
        } else {
            session()->put('failed', 'Please check any QC report to approve.');
            return redirect()->back();
        }
    }

    public function index(Request $req)
    {
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');
//        $ae_count = Admin::where('type', 'ae')->where('created_by', session('rexkod_vishvin_auth_userid'))->get()->count();
//        //$ae_count = count($ae);
//        $aee_count = Admin::where('type', 'aee')->where('created_by', session('rexkod_vishvin_auth_userid'))->get()->count();
//        //$aee_count = count($aee);
//        $aao_count = Admin::where('type', 'aao')->where('created_by', session('rexkod_vishvin_auth_userid'))->get()->count();
//        //$aao_count = count($aao);
//
//        $so_status_done =  DB::table('consumer_details')
//                ->select('consumer_details.account_id')
//                ->join('meter_mains', 'meter_mains.account_id','=','consumer_details.account_id')
//                ->where('consumer_details.division',$req->division)
//                ->where('consumer_details.sub_division',$req->sub_division)
//                ->where('consumer_details.section',$req->section)
//                ->where('meter_mains.so_status',1)
//                ->get()->count();
//        $so_status_pending =  DB::table('consumer_details')
//                ->select('consumer_details.account_id')
//                ->join('meter_mains', 'meter_mains.account_id','=','consumer_details.account_id')
//                ->where('consumer_details.division',$req->division)
//                ->where('consumer_details.sub_division',$req->sub_division)
//                ->where('consumer_details.section',$req->section)
//                ->where('meter_mains.so_status',0)
//                ->get()->count();
//        $aee_status_done =  DB::table('consumer_details')
//                ->select('consumer_details.account_id')
//                ->join('meter_mains', 'meter_mains.account_id','=','consumer_details.account_id')
//                ->where('consumer_details.division',$req->division)
//                ->where('consumer_details.sub_division',$req->sub_division)
//                ->where('consumer_details.section',$req->section)
//                ->where('meter_mains.aee_status',1)
//                ->get()->count();
//        $aee_status_pending =  DB::table('consumer_details')
//                ->select('consumer_details.account_id')
//                ->join('meter_mains', 'meter_mains.account_id','=','consumer_details.account_id')
//                ->where('consumer_details.division',$req->division)
//                ->where('consumer_details.sub_division',$req->sub_division)
//                ->where('consumer_details.section',$req->section)
//                ->where('meter_mains.aee_status',0)
//                ->get()->count();
//        $aao_status_done =  DB::table('consumer_details')
//                ->select('consumer_details.account_id')
//                ->join('meter_mains', 'meter_mains.account_id','=','consumer_details.account_id')
//                ->where('consumer_details.division',$req->division)
//                ->where('consumer_details.sub_division',$req->sub_division)
//                ->where('consumer_details.section',$req->section)
//                ->where('meter_mains.aao_status',1)
//                ->get()->count();
//        $aao_status_pending =  DB::table('consumer_details')
//                ->select('consumer_details.account_id')
//                ->join('meter_mains', 'meter_mains.account_id','=','consumer_details.account_id')
//                ->where('consumer_details.division',$req->division)
//                ->where('consumer_details.sub_division',$req->sub_division)
//                ->where('consumer_details.section',$req->section)
//                ->where('meter_mains.aao_status',0)
//                ->get()->count();
//        $division_implement =  DB::table('consumer_details')
//                ->select('consumer_details.account_id')
//                ->join('meter_mains', 'meter_mains.account_id','=','consumer_details.account_id')
//                ->where('consumer_details.division',$req->division)
//                ->where('consumer_details.sub_division',$req->sub_division)
//                ->where('consumer_details.section',$req->section)
//                ->get()->count();

        $package_name = env('PACKAGE_NAME');
        $get_all_division_codes = DB::table('zone_codes')
            ->select('division', 'div_code')
            ->where('package', $package_name)
            ->distinct()
            ->get();
        //dd($get_all_division_codes, env('PACKAGE_NAME'));

        $data = [
//                    'ae_count' => $ae_count,
//                    'aee_count' => $aee_count,
//                    'aao_count' => $aao_count,
//                    'name' => 'sunil',
//                    'so_status_done' => $so_status_done,
//                    'so_status_pending' => $so_status_pending,
//                    'aee_status_done' => $aee_status_done,
//                    'aee_status_pending' => $aee_status_pending,
//                    'aao_status_done' => $aao_status_done,
//                    'aao_status_pending' => $aao_status_pending,
//                    'division_implement' => $division_implement,
            'current_logged_in_user_type' => $logged_in_user_type,
            'divisions' => $get_all_division_codes
        ];

        return view('hescoms.index', ['data' => $data]);
    }

    public function all_hescom_executives()
    {
        // ->where('type', 'inventory_reporter')
        return view('hescoms.all_hescom_executives', [
            'show_users' => Admin::where('created_by', session()->get('rexkod_vishvin_auth_userid'))->get(),
        ]);
    }

    public function all_consumers()
    {

        return view('hescoms.all_consumers', [
            'show_users' => Consumer_detail::get(),
        ]);
    }

    function authenticate(Request $req)
    {

        $user = Admin::where('user_name', $req->phone)->first();
        // return($req->all());
        if ($user && Hash::check($req->password, $user->password) && $user->type == "hescom_manager") {
            Session::put('rexkod_hescom_manager_name', $user->name);
            Session::put('rexkod_vishvin_auth_userid', $user->id);
            Session::put('rexkod_hescom_manager_user_name', $user->user_name);
            Session::put('rexkod_vishvin_auth_user_type', $user->type);
            return redirect('hescoms/index');
        } elseif ($user && Hash::check($req->password, $user->password) && $user->type == "aee") {
            Session::put('rexkod_hescom_manager_name', $user->name);
            Session::put('rexkod_vishvin_auth_userid', $user->id);
            Session::put('rexkod_hescom_manager_user_name', $user->user_name);
            Session::put('rexkod_vishvin_auth_user_type', $user->type);
            return redirect('hescoms/index');
        } elseif ($user && Hash::check($req->password, $user->password) && $user->type == "aao") {
            Session::put('rexkod_hescom_manager_name', $user->name);
            Session::put('rexkod_vishvin_auth_userid', $user->id);
            Session::put('rexkod_hescom_manager_user_name', $user->user_name);
            Session::put('rexkod_vishvin_auth_user_type', $user->type);
            return redirect('hescoms/index');
        } elseif ($user && Hash::check($req->password, $user->password) && $user->type == "ae") {
            Session::put('rexkod_hescom_manager_name', $user->name);
            Session::put('rexkod_vishvin_auth_userid', $user->id);
            Session::put('rexkod_hescom_manager_user_name', $user->user_name);
            Session::put('rexkod_vishvin_auth_user_type', $user->type);
            return redirect('hescoms/index');
        } else {
            session()->put('failed', 'Invalid Credentials');
            return redirect('/hescoms');
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/hescoms')->with('message', 'You have been logged out!');
    }

    function create_hescom_executive(Request $req)
    {

        $auth = new Admin;
        $result = Admin::where('phone', $req->phone)->first();

        if ($result) {
            session()->put('failed', 'Phone already exists');
            return redirect('/hescoms/add_hescom_executive');
        } else {

            $auth->name = $req->name;

            $auth->phone = $req->phone;
            // $auth->user_name = $req->user_name;

            $auth->type = $req->type;
            if ($auth->type == 'ae') {
                if ($req->so_pincode) {
                    $auth->so_pincode = $req->so_pincode;
                } else {
                    session()->put('failed', 'Please select the SO Pincode, if the post is AE');
                    return redirect('/hescoms/add_hescom_executive');
                }
            }


            if ($auth->type == 'aee') {
                if ($req->sd_pincode) {
                    $auth->sd_pincode = $req->sd_pincode;
                } else {
                    session()->put('failed', 'Please select the SD Pincode, if the post is AEE');
                    return redirect('/hescoms/add_hescom_executive');
                }
            }
            if ($auth->type == 'aao') {
                if ($req->sd_pincode) {
                    $auth->sd_pincode = $req->sd_pincode;
                } else {
                    session()->put('failed', 'Please select the SD Pincode, if the post is AAO');
                    return redirect('/hescoms/add_hescom_executive');
                }
            }
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
            session()->put('success', 'Executive added successfully');

            // $user = Admin::where('user_name', $req->user_email)->first();

            // $req->session()->put('user',$user);

            return redirect('/hescoms/all_hescom_executives');
        }
    }

    public function approve_so_reports_status(Request $req, $id)
    {
        // first case: its present in consumer_detail

        $meter_main = Meter_main::find($id);
        // $meter_main->so_remark = $req->so_remark;
        $meter_main->so_status = 1;
        $meter_main->so_updated_by = session()->get('rexkod_vishvin_auth_userid');
        $meter_main->so_updated_at = now();
        $meter_main->save();

        session()->put('success', 'Congrats! The meter status has been approved');
        return redirect('/hescoms/hescom_view');
    }

    public function reject_so_reports_status(Request $req, $id)
    {
        // first case: its present in consumer_detail
        // dd('sto23232323p');

        $meter_main = Meter_main::find($id);
        $meter_main->so_remark = $req->remark;
        $meter_main->so_status = 2;
        $meter_main->so_updated_by = session()->get('rexkod_vishvin_auth_userid');
        $meter_main->so_updated_at = now();
        $meter_main->save();

        session()->put('success', 'Congrats! The meter status has been approved');
        return redirect('/hescoms/hescom_view');
    }

    public function approve_aee_reports_status(Request $req, $id)
    {
        // first case: its present in consumer_detail

        $meter_main = Meter_main::find($id);
        // $meter_main->aee_remark = $req->aee_remark;
        $meter_main->aee_status = 1;
        $meter_main->aee_updated_by = session()->get('rexkod_vishvin_auth_userid');
        $meter_main->aee_updated_at = now();
        $meter_main->save();

        session()->put('success', 'Congrats! The meter status has been approved');
        return redirect('/hescoms/hescom_view');
    }

    public function reject_aee_reports_status(Request $req, $id)
    {
        // first case: its present in consumer_detail

        $meter_main = Meter_main::find($id);
        $meter_main->aee_remark = $req->remark;
        $meter_main->aee_status = 2;
        $meter_main->aee_updated_by = session()->get('rexkod_vishvin_auth_userid');
        $meter_main->aee_updated_at = now();
        $meter_main->save();

        session()->put('success', 'Congrats! The meter status has been approved');
        return redirect('/hescoms/hescom_view');
    }

    public function approve_aao_reports_status(Request $req, $id)
    {
        // first case: its present in consumer_detail

        $meter_main = Meter_main::find($id);
        // $meter_main->aao_remark = $req->aao_remark;
        $meter_main->aao_status = 1;
        $meter_main->aao_updated_by = session()->get('rexkod_vishvin_auth_userid');
        $meter_main->aao_updated_at = now();
        $meter_main->save();

        session()->put('success', 'Congrats! The meter status has been approved');
        return redirect('/hescoms/hescom_view');
    }

    public function reject_aao_reports_status(Request $req, $id)
    {


        // first case: its present in consumer_detail

        $meter_main = Meter_main::find($id);
        $meter_main->aao_remark = $req->remark;
        $meter_main->aao_status = 2;
        $meter_main->aao_updated_by = session()->get('rexkod_vishvin_auth_userid');
        $meter_main->aao_updated_at = now();
        $meter_main->save();

        session()->put('success', 'Congrats! The meter status has been approved');
        return redirect('/hescoms/hescom_view');
    }




    // public function update_aee_reports_status(Request $req, $id)
    // {
    //     $meter_main = Meter_main::find($id);
    //     $meter_main->aee_remark = $req->aee_remark;
    //     $meter_main->aee_status = $req->submit_button;
    //     $meter_main->aee_updated_by = session()->get('rexkod_vishvin_auth_userid');
    //     $meter_main->aee_updated_at = now();
    //     $meter_main->save();
    //     session()->put('success', 'Congrats! The meter status has been submitted successfully');
    //     return redirect('/hescoms/hescom_view');
    // }

    // public function update_aao_reports_status(Request $req, $id)
    // {
    //     $meter_main = Meter_main::find($id);
    //     $meter_main->aao_remark = $req->aao_remark;
    //     $meter_main->aao_status = $req->submit_button;
    //     $meter_main->aao_updated_by = session()->get('rexkod_vishvin_auth_userid');
    //     $meter_main->aao_updated_at = now();
    //     $meter_main->save();

    //     session()->put('success', 'Congrats! The meter status has been submitted successfully');
    //     return redirect('/hescoms/hescom_view');
    // }

    public function error_reports()
    {
        # code...

        // $error_records =Error_record::where('updated_by_aao',0)->get();

        $error_records = DB::table('error_records')
            ->join('consumer_details', 'error_records.account_id', '=', 'consumer_details.account_id')
            ->join('admins', 'consumer_details.sd_pincode', '=', 'admins.sd_pincode')
            ->select('consumer_details.*')
            ->where('admins.id', session('rexkod_vishvin_auth_userid'))
            ->where('error_records.updated_by_aao', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $data = [
            'error_records' => $error_records,
        ];

        return view('/hescoms/error_reports', compact('data'));
    }

    public function edit_error_reports($account_id)
    {

        $meter_main = Meter_main::where('account_id', $account_id)->first();

        $consumer_detail = Consumer_detail::where('account_id', $account_id)->first();

        $error_message_detail = Error_record::where('account_id', $account_id)->where('updated_by_aao', '=', 0)->first();

        $meter_previous_final_reading = Meter_final_reading::where('account_id', $meter_main->account_id)->first();

        $data = [
            'meter_main' => $meter_main,
            'consumer_detail' => $consumer_detail,
            'account_id' => $account_id,
            'error_message_detail' => $error_message_detail,
            'meter_previous_final_reading' => $meter_previous_final_reading,
        ];

		//$dd(data);
        return view('/hescoms/edit_error_reports', compact('data'));
    }

    public function update_error_reports(Request $req, $id)
    {
        // return($req);
        // first case: its present in consumer_detail

        $meter_main = Meter_main::find($id);
        $meter_main->meter_make_old = $req->meter_make_old;
        $meter_main->serial_no_old = $req->serial_no_old;
        $meter_main->mfd_year_old = $req->mfd_year_old;
        $meter_main->final_reading = $req->final_reading;
        $meter_main->meter_make_new = $req->meter_make_new;
        $meter_main->serial_no_new = $req->serial_no_new;
        $meter_main->mfd_year_new = $req->mfd_year_new;
        $meter_main->initial_reading_kwh = $req->initial_reading_kwh;
        $meter_main->initial_reading_kvah = $req->initial_reading_kvah;


		$account_id = $meter_main->account_id;
        $consumer_detail = Consumer_detail::where('account_id', $account_id)->first();
       // dd($consumer_detail);
        $consumer_detail->sp_id = $req->sp_id;

//            if (!empty($req->file('image_1_old'))) {
//                $extension1 = $req->file('image_1_old')->extension();
//                if ($extension1 == "png" || $extension1 == "jpeg" || $extension1 == "jpg") {
//                    $filename = Str::random(4) . time() . '.' . $extension1;
//                    $meter_main->image_1_old = $req->file('image_1_old')->move(('uploads'), $filename);
//                }
//            }
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
                return redirect('/hescoms/edit_error_reports/' . $id);
            }
        } else {
            $meter_main->image_1_old = $meter_main->image_1_old;
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
                return redirect('/hescoms/edit_error_reports/' . $id);
            }
        } else {
            $meter_main->image_2_old = $meter_main->image_2_old;
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
            $meter_main->image_3_old = $meter_main->image_3_old;
        }
        if (!empty($req->file('image_1_new'))) {
            $file = $req->file('image_1_new');
            //$file = $data['image_1_new'];
            $mime_type = $file->getClientMimeType();
            $extension = $file->getClientOriginalExtension();
            //if (($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg') && ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
            if (($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')) {
                // $filename = Str::random(4) . time() . '.' . $extension;

                // giving the image name as account id
                $filename = Str::random(4) . $meter_main->account_id . '.' . $extension;

                $meter_main->image_1_new = $file->move(('uploads'), $filename);
                //$save_column_list_data['image_1_new'] = $file->move(('uploads'), $filename);
                //ImageCompressionController::compress_image($meter_main->image_1_new);
            } else {
                session()->put('failed', 'Only JPEG and PNG images are allowed.');
                return redirect('/hescoms/edit_error_reports/' . $id);
            }
        } else {
            $meter_main->image_1_new = $meter_main->image_1_new;
        }

        if (!empty($req->file('image_2_new')) && empty($meter_main->image_2_new)) {
            $file = $req->file('image_2_new');
            //$file = $data['image_2_new'];
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
                return redirect('/hescoms/edit_error_reports/' . $id);
            }
        } else {
            $meter_main->image_2_new = $meter_main->image_2_new;
        }

        $meter_main->created_at = $req->created_at;

        $meter_main->error_updated_at = now();

		$meter_main->save();
        $consumer_detail->save();

        $error_record = Error_record::where('account_id', $meter_main->account_id)->first();

        $error_record->justification_by_aao = $req->justification_by_aao;

        $error_record->save();

        // session()->put('success', 'Congrats! The meter status has been submitted successfully');
        return redirect('/hescoms/error_reports');
    }
	
  public function push_to_vishvin_qc() 
  {
    return view('hescoms.push_vishvin_qc');
  }

	  public function push_to_vishvin_search(Request $request)
  {
      // Get the account ID from the request
      $searchInput = $request->input('account_id');
      
      // Search for the consumer detail and meter main records
      $existed_account_id = Consumer_detail::where('account_id', $searchInput)->first();
      $existed_account_id1 = Meter_main::where('account_id', $searchInput)->first();

      
      // Fetch the successful record and error records for the given account ID
      $successful_record = Successful_record::where('account_id', $searchInput)->first();
      $error_records = Error_record::where('account_id', $searchInput)->get(); // Fetch all error records

      
      

          // If the account_id is not found, redirect with a flash message
    if (is_null($existed_account_id1)) {
        session()->flash('error', 'Account ID not found.');
        return redirect('/hescoms/push_to_vishvin_qc');
    }

      // If the account_id is found and qc_status is 0
      if ($existed_account_id1->download_flag === 0) {
        session()->flash('info', 'The QC status for this account ID is currently inactive. Pushing to the next process...');
        // Here you can include logic to push to /hescoms/push_to_vishvin_qc if needed
        return redirect('/hescoms/push_to_vishvin_qc'); // Redirect to the desired route
    }


  
      // Get so_pincode from consumer_detail if it exists
      $soPincode = $existed_account_id ? $existed_account_id->so_pincode : null;
  
      // Initialize an empty array for account IDs
      $account_ids = [];
      $account_status_msg = '';
  
      // Ensure that existed_account_id1 exists and has created_at as null
      if ($existed_account_id1 && is_null($existed_account_id1->created_at)) {
          // Fetch all account_ids from consumer_details where so_pincode matches
          // and ensure the account_id does not exist in meter_mains
          $account_ids = Consumer_detail::where('so_pincode', $soPincode)
              ->whereNotIn('account_id', Meter_main::pluck('account_id')->toArray())
              ->pluck('account_id')
              ->toArray();
      }

      //dd($account_ids)
  
      // If neither consumer detail, successful_record, nor error records exist, redirect to SP ID change page
      if (is_null($existed_account_id) && is_null($successful_record) && $error_records->isEmpty()) {
          session()->flash('alert', 'Account details not found! Redirecting to push vishvin QC change page.');
          alert('Account details not found! Redirecting to push vishvin QC change page.');
          //$account_status_msg = "Account details not found! Redirecting to push vishvin QC change page.";
          return redirect('/hescoms/push_to_vishvin_qc');
      }
  
      // Prepare the data array to pass to the view
      $data = [
          'consumer_detail' => $existed_account_id,
          'meter_main' => $existed_account_id1,
          'successful_record' => $successful_record,
          'error_records' => $error_records, // Pass all error records to the view
          'account_ids' => $account_ids, // Changed to account_ids to clarify the key
          'account_status' => $account_status_msg,
      ];
  
      // Flash a success message
      session()->flash('alert', 'Account details found successfully.');
  
      // Return the view with the data
      return view('hescoms.vishvin_qc_push_view', ['data' => $data]);
  }
	
	
	
	  public function pushToVishvinQC($id, Request $request)
  {
      // Fetch the record for the provided ID from the Meter_main table
      $meterMain = Meter_main::find($id);
  
      if ($meterMain) {
          // Clone the current data into the Vishvin QC table
          $meterMainsVishvinQC = new MeterMainsVishvinQC();  // Ensure you have a corresponding model
          
          // Copy all relevant fields from $meterMain to $meterMainsVishvinQC
          $meterMainsVishvinQC->account_id = $meterMain->account_id;
          $meterMainsVishvinQC->changed_account_id = $request->account_id;
          $meterMainsVishvinQC->image_1_old = $meterMain->image_1_old;
          $meterMainsVishvinQC->image_2_old = $meterMain->image_2_old;
          $meterMainsVishvinQC->image_3_old = $meterMain->image_3_old;
          $meterMainsVishvinQC->meter_make_old = $meterMain->meter_make_old;
          $meterMainsVishvinQC->serial_no_old = $meterMain->serial_no_old;
          $meterMainsVishvinQC->mfd_year_old = $meterMain->mfd_year_old;
          $meterMainsVishvinQC->final_reading = $meterMain->final_reading;
          $meterMainsVishvinQC->image_1_new = $meterMain->image_1_new;
          $meterMainsVishvinQC->image_2_new = $meterMain->image_2_new;
          $meterMainsVishvinQC->meter_make_new = $meterMain->meter_make_new;
          $meterMainsVishvinQC->serial_no_new = $meterMain->serial_no_new;
          $meterMainsVishvinQC->mfd_year_new = $meterMain->mfd_year_new;
          $meterMainsVishvinQC->initial_reading_kwh = $meterMain->initial_reading_kwh;
          $meterMainsVishvinQC->initial_reading_kvah = $meterMain->initial_reading_kvah;
          $meterMainsVishvinQC->lat = $meterMain->lat;
          $meterMainsVishvinQC->lon = $meterMain->lon;
          $meterMainsVishvinQC->qc_remark = $meterMain->qc_remark;
          $meterMainsVishvinQC->qc_status = $meterMain->qc_status;
          $meterMainsVishvinQC->qc_updated_by = $meterMain->qc_updated_by;
          $meterMainsVishvinQC->qc_updated_at = $meterMain->qc_updated_at;
          $meterMainsVishvinQC->so_status = $meterMain->so_status;
          $meterMainsVishvinQC->so_remark = $meterMain->so_remark;
          $meterMainsVishvinQC->so_updated_by = $meterMain->so_updated_by;
          $meterMainsVishvinQC->so_updated_at = $meterMain->so_updated_at;
          $meterMainsVishvinQC->aee_status = $meterMain->aee_status;
          $meterMainsVishvinQC->aee_remark = $meterMain->aee_remark;
          $meterMainsVishvinQC->aee_updated_by = $meterMain->aee_updated_by;
          $meterMainsVishvinQC->aee_updated_at = $meterMain->aee_updated_at;
          $meterMainsVishvinQC->aao_status = $meterMain->aao_status;
          $meterMainsVishvinQC->aao_remark = $meterMain->aao_remark;
          $meterMainsVishvinQC->aao_updated_by = $meterMain->aao_updated_by;
          $meterMainsVishvinQC->aao_updated_at = $meterMain->aao_updated_at;
          $meterMainsVishvinQC->delete_flag = $meterMain->delete_flag;
          $meterMainsVishvinQC->allocation_flag = $meterMain->allocation_flag;
          $meterMainsVishvinQC->download_flag = $meterMain->download_flag;
          $meterMainsVishvinQC->error_updated_by_aao = $meterMain->error_updated_by_aao;
          $meterMainsVishvinQC->error_updated_at = $meterMain->error_updated_at;
          $meterMainsVishvinQC->created_by = $meterMain->created_by;
          $meterMainsVishvinQC->created_at = $meterMain->created_at;
  
          // Save the cloned data to the Vishvin QC table
          $meterMainsVishvinQC->save();
          
          // Now, update the statuses in the original Meter_main table
          $meterMain->qc_status = 0;
          $meterMain->so_status = 0;
          $meterMain->aee_status = 0;
          $meterMain->aao_status = 0;
          $meterMain->download_flag = 0;
          $meterMain->account_id = $request->account_id;
  
          // Save the updated Meter_main
          $meterMain->save();
  
          // Insert a new record into the SuccessfulRecord table
          $successfulRecord = new SuccessfulRecord();
          $successfulRecord->account_id = $meterMainsVishvinQC->account_id; // Set account_id
          $successfulRecord->token = $request->input('token'); // Assuming token is passed in the request
          // No need to set created_at and updated_at as they will be handled automatically
  
          // Save the new SuccessfulRecord
          $successfulRecord->save();
  
            // Fetch all records from the ErrorRecord table
            $errorRecords = Error_record::where('account_id', $meterMain->account_id)->get();

            foreach ($errorRecords as $errorRecord) {
                // Clone the current error record into the error_records_vishvin_qc table
                $errorRecordVishvinQC = new ErrorRecord();  // Ensure you have a corresponding model
                
                // Copy all relevant fields from $errorRecord to $errorRecordVishvinQC
                $errorRecordVishvinQC->account_id = $errorRecord->account_id;
                $errorRecordVishvinQC->error_reason = $errorRecord->error_reason; // Copy the error_reason field
                $errorRecordVishvinQC->token = $errorRecord->token; // Copy the token field
                $errorRecordVishvinQC->updated_by_aao = $errorRecord->updated_by_aao; // Copy updated_by_aao
                $errorRecordVishvinQC->justification_by_aao = $errorRecord->justification_by_aao; // Copy justification_by_aao
                // The timestamps will be automatically handled
                // Save the cloned data to the error_records_vishvin_qc table
                $errorRecordVishvinQC->save();
            }

  
          // Check if account_id exists in the success and error tables
          if (Successful_record::where('account_id', $meterMainsVishvinQC->account_id)->exists()) {
              // If it exists, delete the corresponding record from the success table
              Successful_record::where('account_id', $meterMainsVishvinQC->account_id)->delete();
          }
  
          if (Error_record::where('account_id', $meterMainsVishvinQC->account_id)->exists()) {
              // If it exists, delete the corresponding record from the error table
              Error_record::where('account_id', $meterMainsVishvinQC->account_id)->delete();
          }
  
          session()->flash('success', 'Account details have been pushed to Vishvin QC.');
          return redirect('hescoms/push_to_vishvin_qc');
      } else {
          // If record not found, flash an error message and redirect
          session()->flash('error', 'Account details not found or not updated');
          return redirect('hescoms/push_to_vishvin_qc');
      }
  }
  
  

  public function searchAccountIds(Request $request) 
  {
      // Validate the request to ensure a query is provided
      $request->validate([
          'query' => 'required|string|min:1', // Require at least 1 character
      ]);
  
      $search = $request->input('query'); // Get the search term from the request
  
      // Fetch account IDs that match the search term in consumer_details 
      // and do NOT exist in meyter_mains
      $accountIds = Consumer_detail::where('account_id', 'LIKE', '%' . $search . '%')
          ->whereNotExists(function ($query) {
              $query->select(DB::raw(1))
                  ->from('meter_mains')
                  ->whereColumn('meter_mains.account_id', 'consumer_details.account_id');
          })
          ->pluck('account_id') // Use pluck to directly get the account_id
          ->toArray();
        
             if (empty($accountIds)) {
    // Check if the error has not been flashed yet
    if (!session()->has('error')) {
        // Flash the error message to the session so it's displayed only once
        session()->flash('error', 'No accounts found or the selected account ID already exists at meter mains.');
    }

    // Return a response with success status and message
    return response()->json([
        'status' => 'error',
        'message' => session('error')
    ], 404);
}

                

  
      return response()->json($accountIds); // Return as JSON
  }
  

    public function sp_id_change() 
    {
        return view('hescoms.sp_id_get');
    }
	
	
	   public function consumer_details_getdetails()
    {
        return view('hescoms.consumer_details_get');
    }
	
	    public function serial_number_ownership() 
    {
        return view('hescoms.serial_number_ownership_get');
    }


    public function sp_id_search(Request $request)
    {
        $searchInput = $request->input('account_id');
        
        // Search for the consumer detail and meter main records
        $existed_account_id = Consumer_detail::where('account_id', '=', $searchInput)->first();
        $existed_account_id1 = Meter_main::where('account_id', '=', $searchInput)->first();

        // Check if either of the records is not found
        if (is_null($existed_account_id) || is_null($existed_account_id1)) {
            // Flash a session message that will be displayed as an alert
            session()->flash('alert', 'Account details not found! Redirecting to SP ID change page.');

            // Redirect to the specified route
            return redirect('hescoms/sp_id_change');
        }

        // If both records are found, prepare the data array
        $data = [
            'consumer_detail' => $existed_account_id,
            'meter_main' => $existed_account_id1
        ];

        // Flash a success message
        session()->flash('alert', 'Account details found successfully.');

        // Return the view with the data
        return view('hescoms.sp_id_view', ['data' => $data]);
    }
	
	
	
    public function consumer_accounts_search(Request $request)
    {
        $searchInput = $request->input('account_id');
    
        // Fetch the first matching consumer detail and meter main records
        $existed_account_id = Consumer_detail::where('account_id', '=', $searchInput)
                                              ->orderBy('id', 'asc') // Ensure the first record is selected in case of duplicates
                                              ->first();
    
        $existed_account_id1 = Meter_main::where('account_id', '=', $searchInput)->first();
    
        // Check if consumer_detail exists before proceeding
        if (is_null($existed_account_id)) {
            session()->flash('alert', 'Account details not found.');
            return redirect('/hescoms/sp_id_change');
        }
    
        // Fetch admins data from Zone_code table where so_code matches so_pincode of the consumer detail
        $zone_info = DB::table('zone_codes')
        ->where('so_code', '=', $existed_account_id->so_pincode)
        ->select('division', 'sub_division', 'section_office')
        ->first();

      //  dd($zone_info);

    
        // If the meter main exists, redirect with a message
        //if (!is_null($existed_account_id1)) {
        //    session()->flash('alert', 'Account ID is already installed.');
          //  return redirect('/hescoms/consumer_details_update');
       // }
		
		  
        // If the meter main exists, redirect with a message
       /* if ($existed_account_id1->qc_status == 1 || !is_null($existed_account_id1))
         {
            session()->flash('alert', 'Account ID is already installed.');
            return redirect('/hescoms/consumer_details_update');
        }  */
    
        // Prepare the data array, including admin details if available
        $data = [
            'consumer_detail' => $existed_account_id,
            'meter_main' => $existed_account_id1,
            'zone_info' => $zone_info
        ];
    
        // Debugging output to verify fetched data
        //dd($data);
    
        session()->flash('alert', 'Account details found successfully.');
    
        // Return the view with the data
        return view('hescoms.consumer_id_view', ['data' => $data]);
    }
    
	
	  public function serial_no_search(Request $request)
    {
        $searchInput = $request->input('serial_no');
        
        // Search for the consumer detail using serial_no
        $existed_account_id = Meter_main::where('serial_no_new', '=', $searchInput)->first();
    
        // Search for the contractor inventory using serial_no with LIKE operator
        $contractor_inventories = Contractor_inventory::where('serial_no', 'LIKE', '%' . $searchInput . '%')->first();
    
        // If neither exists, redirect with an alert message
        if (!$existed_account_id && !$contractor_inventories) {
            session()->flash('alert', 'Account details not found! Redirecting to account ID ownership page.');
            return redirect('hescoms/serial_number_ownership');
        }
    
        // If consumer detail is found, fetch the account_id and get the consumer detail
        $existed_account_id1 = null;
        if ($existed_account_id) {
            $account_id = $existed_account_id->account_id;  
            $existed_account_id1 = Consumer_detail::where('account_id', '=', $account_id)->first();
        }
    
        // Fetch contractor details if contractor_inventory exists
        $name = "Not Found";
        if ($contractor_inventories) {
            $contractor_id = $contractor_inventories->contractor_id;
            $admin = Admin::where('id', $contractor_id)->first();
            $name = $admin ? $admin->name : "Not Found";
        }
        
        // Prepare the data array
        $data = [
            'meter_main' => $existed_account_id,
            'consumer_detail' => $existed_account_id1,
            'contractor_inventories' => $contractor_inventories,
            'contractor_name' => $name
        ];
    
        // Flash a success message
        session()->flash('alert', 'Account details found successfully.');
        
        // Return the view with the data
        return view('hescoms.serial_number_ownership_get_view', ['data' => $data]);
    }
	
	
	
  public function meterOwnerShip_update(Request $request, $id)
  {
      // Fetch the Contractor_inventory record using the provided ID
      $Contractor_inventory = Contractor_inventory::find($id);
      $original_inventory = $Contractor_inventory;
  
      // If no unused serial numbers are provided, return with an error
      $unusedSerialNumbers = $request->input('unused_meter_serial_no');
      if (empty($unusedSerialNumbers)) {
          session()->put('error', 'No unused meter serial numbers provided.');
          return redirect()->back();
      }
  
      // If Contractor_inventory exists
      if ($Contractor_inventory) {
          // Convert unused serial numbers into an array if it's a string
          $unusedSerialNumbersArray = is_string($unusedSerialNumbers) 
              ? array_map('trim', explode(',', $unusedSerialNumbers)) 
              : array_map('trim', $unusedSerialNumbers);
  
          // Retrieve the current unused serial numbers in the original record
          $existingUnusedSerials = explode(',', $Contractor_inventory->unused_meter_serial_no);
          $existingUnusedSerials = array_filter(array_map('trim', $existingUnusedSerials));
  
          // Remove selected serial numbers from the original list
          $updatedUnusedSerials = array_diff($existingUnusedSerials, $unusedSerialNumbersArray);
  
          // Convert the updated unused serials array back into a comma-separated string
          $updatedUnusedSerialsString = implode(',', $updatedUnusedSerials);
  
          // Update the original Contractor_inventory record to remove the selected serial numbers
          $Contractor_inventory->update([
              'unused_meter_serial_no' => $updatedUnusedSerialsString,
          ]);
  
          // Check if a Contractor_inventory record with contractor_id = 117 already exists
          $existingContractorInventory117 = Contractor_inventory::where('box_id', $Contractor_inventory->box_id)
              ->where('contractor_id', 177)
              ->first();
  
          if ($existingContractorInventory117) {
              // Update only unused_meter_serial_no, leaving serial_no and used_meter_serial_no unchanged
              $existingUnusedSerialNo117 = explode(',', $existingContractorInventory117->unused_meter_serial_no);
              $updatedUnusedSerialNo117Array = array_merge($existingUnusedSerialNo117, $unusedSerialNumbersArray);
              $updatedUnusedSerialNo117Array = array_unique($updatedUnusedSerialNo117Array); // Remove duplicates
  
              $existingContractorInventory117->update([
                  'unused_meter_serial_no' => implode(',', $updatedUnusedSerialNo117Array),
              ]);
  
          } else {
              // If no existing record with contractor_id = 117, clone and create a new one
              $clonedInventory = $Contractor_inventory->replicate(); // Create a copy of the original record
              $clonedInventory->contractor_id = 177; // Set the contractor_id to 177
              $clonedInventory->serial_no = $Contractor_inventory->serial_no;
           //   $clonedInventory->used_meter_serial_no = $Contractor_inventory->used_meter_serial_no;
              $clonedInventory->used_meter_serial_no = NULL;
              $clonedInventory->unused_meter_serial_no = implode(',', $unusedSerialNumbersArray); // Set serial_no from selected unused serial numbers
              $clonedInventory->save(); // Save the cloned record as a new entry
          }
  
          // Count the number of selected unused serial numbers
          $check_seriallength = count($unusedSerialNumbersArray);
  
          // Retrieve or initialize the array that stores the count of serials after each update
          $serialLengthArray = json_decode($Contractor_inventory->serial_length_array, true) ?? [];
          $serialLengthArray[] = $check_seriallength; // Add the new serial count into the array
          $serializedLengthJson = json_encode($serialLengthArray); // Convert array into JSON string for storage
  
          // Update the original record's serial_length_array with the new count
          $Contractor_inventory->update([
              'serial_length_array' => $serializedLengthJson
          ]);
  
          // Handle the ContractorInventoryownership
          $ContractorInventoryownership = ContractorInventoryownership::where('box_id', $Contractor_inventory->box_id)->first();
          $currentTimestamp = now(); // Get the current date and time
  
          if ($ContractorInventoryownership) {
              // Fetch existing unused serial numbers from the ownership record
              $existingUnusedSerialsOwnership = json_decode($ContractorInventoryownership->unused_meter_serial_no, true) ?? [];
  
              // Merge existing ownership serial numbers with the user-provided unused serial numbers
              $finalUnusedSerials = array_merge($existingUnusedSerialsOwnership, $unusedSerialNumbersArray);
              $finalUnusedSerials = array_unique($finalUnusedSerials); // Remove duplicates
  
              // Optionally, limit the number of serial numbers to a specific amount (e.g., 500)
              if (count($finalUnusedSerials) > 5000000) {
                  $finalUnusedSerials = array_slice($finalUnusedSerials, -500);
              }
  
              // Fetch and update count_updation array from ContractorInventoryownership
              $existingCountsJson = $ContractorInventoryownership->count_updation;
              $serialLengthArray = json_decode($existingCountsJson, true) ?? []; // Decode the existing counts or initialize
              $serialLengthArray[] = $check_seriallength; // Append the new count
              $serializedLengthJson = json_encode($serialLengthArray);
  
              // Update the ContractorInventoryownership record with new values
              $ContractorInventoryownership->update([
                  'serial_no' => $Contractor_inventory->serial_no,
                  'unused_meter_serial_no' => json_encode($finalUnusedSerials),
                  'used_meter_serial_no' => $Contractor_inventory->used_meter_serial_no,
                  'division' => $Contractor_inventory->division,
                  'meter_type' => $Contractor_inventory->meter_type,
                  'dc_no' => $Contractor_inventory->dc_no,
                  'contractor_id' => $original_inventory->contractor_id,
                  'vishvin_contractor_id' => 177,
                  'count_updation' => $serializedLengthJson, // Store the updated serial length array as JSON
                  'created_by' => $Contractor_inventory->created_by,
                  'created_at' => $currentTimestamp,
                  'created_at_array' => json_encode(array_merge(json_decode($ContractorInventoryownership->created_at_array, true) ?? [], [$currentTimestamp->toDateTimeString()])),
              ]);
  
              session()->put('success', 'Meter ownership details updated successfully');
          } else {
              // Create a new ContractorInventoryownership record
              ContractorInventoryownership::create([
                  'box_id' => $Contractor_inventory->box_id,
                  'serial_no' => $Contractor_inventory->serial_no,
                  'unused_meter_serial_no' => json_encode($unusedSerialNumbersArray), // Store unused serial numbers as JSON
                  'used_meter_serial_no' => $Contractor_inventory->used_meter_serial_no,
                  'division' => $Contractor_inventory->division,
                  'meter_type' => $Contractor_inventory->meter_type,
                  'dc_no' => $Contractor_inventory->dc_no,
                  'contractor_id' => $original_inventory->contractor_id,
                  'vishvin_contractor_id' => 177,
                  'count_updation' => $serializedLengthJson, // Store the updated serial length array as JSON
                  'created_by' => $Contractor_inventory->created_by,
                  'created_at' => $currentTimestamp,
                  'created_at_array' => json_encode([$currentTimestamp->toDateTimeString()]),
              ]);
  
			   session()->put('success', 'Meter Ownership Takeover successfully ');
          }
      } else {
          session()->put('error', 'Contractor inventory record not found');
      }
  
      // Redirect back to the appropriate page
      return redirect('/hescoms/serial_number_ownership');
  }
  
  
    

	
	/*public function sp_id_update(Request $req, $id, $meter_id)
    {
        $meter_main = Meter_main::find($meter_id);
    
        $prvious_serial_no_new = $meter_main->serial_no_new;
        $meter_serial_no = $req->serial_no_new;
    
        if ($prvious_serial_no_new !== $meter_serial_no) {
            // Update contractor inventories for previous and new serial numbers
            $this->updatePreviousSerialInventory($prvious_serial_no_new, $meter_main);
            $this->updateNewSerialInventory($meter_serial_no);
        } 
    
        // Update meter_main record
        $meter_main->serial_no_old = $req->serial_no_old;
        $meter_main->final_reading = $req->final_reading;
        $meter_main->serial_no_new = $req->serial_no_new;
    
        // Handle image uploads
        $this->handleImageUploads($req, $meter_main);
    
        $meter_main->save();
    
        return view('hescoms.sp_id_get');
    } */
	
	public function sp_id_update(Request $req, $id, $meter_id)
{
    $meter_main = Meter_main::find($meter_id);

    $previous_serial_no_new = $meter_main->serial_no_new;
    $meter_serial_no = $req->serial_no_new;

    // Avoid updating inventories if the new serial number is not provided
    if (!is_null($meter_serial_no) && $previous_serial_no_new !== $meter_serial_no) {
        // Update contractor inventories for previous and new serial numbers
        $this->updatePreviousSerialInventory($previous_serial_no_new, $meter_main);
        $this->updateNewSerialInventory($meter_serial_no);
    } 

    // Update meter_main record only if values are provided
    if ($req->has('serial_no_old') && !is_null($req->serial_no_old)) {
        $meter_main->serial_no_old = $req->serial_no_old;
    }

    if ($req->has('final_reading') && !is_null($req->final_reading)) {
        $meter_main->final_reading = $req->final_reading;
    }

    if ($req->has('serial_no_new') && !is_null($req->serial_no_new)) {
        $meter_main->serial_no_new = $req->serial_no_new;
    }

    // Handle image uploads if provided
    $this->handleImageUploads($req, $meter_main);

    $meter_main->save();

    return view('hescoms.sp_id_get');
}

	
	/*  public function sp_id_update(Request $req, $id, $meter_id)
    {
        DB::beginTransaction();
        
        try {
            // Find consumer detail and meter main by their respective IDs
            $consumer_detail = Consumer_detail::findOrFail($id);
            $meter_main = Meter_main::findOrFail($meter_id);
			
			
				$prvious_serial_no_new = $meter_main->serial_no_new;
				$meter_serial_no = $req->serial_no_new;
			
								// Check if both serial numbers are null
					if (is_null($prvious_serial_no_new) || is_null($meter_serial_no)) {
						// Set error message if account_id exists and return view immediately
						session()->put('error', 'Error: This account ID already exists in successful records.');
						return view('hescoms.sp_id_get');
					}


				if ($prvious_serial_no_new !== $meter_serial_no) {
					// Update contractor inventories for previous and new serial numbers
					$this->updatePreviousSerialInventory($prvious_serial_no_new, $meter_main);
					$this->updateNewSerialInventory($meter_serial_no);
				}
    
            // Check if account_id exists in successful_records
            $accountExists = DB::table('successful_records')->where('account_id', $meter_main->account_id)->exists();
    
            if ($accountExists) {
                // Set error message if account_id exists and return view immediately
                session()->put('error', 'Error: This account ID already exists in successful records.');
                return view('hescoms.sp_id_get');
            }
    
            // Proceed with image upload and updates
     
			
			
			  if (!empty($req->file('image_1_old'))) {
                $extension1 = $req->file('image_1_old')->extension();
                if (in_array($extension1, ["png", "jpeg", "jpg"])) {
                    $filename = Str::random(4) . time() . '.' . $extension1;
                    $meter_main->image_1_old = $req->file('image_1_old')->move(('uploads'), $filename);
                }
            }
    
            if (!empty($req->file('image_2_old'))) {
                $extension2 = $req->file('image_2_old')->extension();
                if (in_array($extension2, ["png", "jpeg", "jpg"])) {
                    $filename = Str::random(4) . time() . '.' . $extension2;
                    $meter_main->image_2_old = $req->file('image_2_old')->move(('uploads'), $filename);
                }
            }
			
			       if (!empty($req->file('image_1_new'))) {
                $extension1 = $req->file('image_1_new')->extension();
                if (in_array($extension1, ["png", "jpeg", "jpg"])) {
                    $filename = Str::random(4) . time() . '.' . $extension1;
                    $meter_main->image_1_new = $req->file('image_1_new')->move(('uploads'), $filename);
                }
            }
    
            if (!empty($req->file('image_2_new'))) {
                $extension2 = $req->file('image_2_new')->extension();
                if (in_array($extension2, ["png", "jpeg", "jpg"])) {
                    $filename = Str::random(4) . time() . '.' . $extension2;
                    $meter_main->image_2_new = $req->file('image_2_new')->move(('uploads'), $filename);
                }
            }
    
            // Update fields in consumer_detail and meter_main
            $consumer_detail->sp_id = $req->sp_id;
            $meter_main->final_reading = $req->final_reading;
			 $meter_main->serial_no_new = $req->serial_no_new;
    
            // Save both models
            $consumer_detail->save();
            $meter_main->save();
    
            // Commit the transaction
            DB::commit();
    
            // Set success message
            session()->put('success', 'Congrats! Meter mains has been submitted successfully');
        } catch (\Exception $e) {
         
            session()->put('error', 'Error: Failed to submit meter mains. Please try again.');
        }
    
        return view('hescoms.sp_id_get');
    }  */
	
	    public function consumer_details_final_update(Request $req, $consumer_id)
    {
        // Validate the incoming request
        $req->validate([
            'meter_type' => 'required|in:1,2',
            'phase_type' => 'required|string',
        ]);
    
        // Fetch the Consumer_detail record by ID
        $consumer_details = Consumer_detail::find($consumer_id);
    
        // Check if the record exists
        if (!$consumer_details) {
            return redirect()->back()->with('error', 'Consumer details not found.');
        }
    
        // Update the meter_type and phase_type fields
        $consumer_details->meter_type = $req->input('meter_type');
        $consumer_details->phase_type = $req->input('phase_type');
    
        // Save the updated record
        $consumer_details->save();

        return redirect('/hescoms/consumer_details_update');
    
        // Redirect to the desired view with a success message
        //return redirect()->route('hescoms.consumer_details_get')->with('success', 'Consumer details updated successfully.');
    }
	
	
    public function updatePreviousSerialInventory($prvious_serial_no_new, $meter_main)
{
    $get_field_executive_contractor =  Admin::where('id', $meter_main->created_by)->first();
    //print_r($get_field_executive_contractor);
   $contractor_inventories =  Contractor_inventory::where('contractor_id', $get_field_executive_contractor->created_by)->get();
   // $contractor_inventories = Contractor_inventory::where('contractor_id', 177)->get();

    foreach ($contractor_inventories as $contractor_inventory) {
        $individual_inventory = $contractor_inventory->used_meter_serial_no;
        $individual_serial_nos = explode(',', $individual_inventory);

        if (in_array($prvious_serial_no_new, $individual_serial_nos)) {
            $previous_inventory_id = $contractor_inventory->id;

            $used_meter_serial_no = explode(',', $contractor_inventory->used_meter_serial_no);
            $unused_meter_serial_no = explode(',', $contractor_inventory->unused_meter_serial_no);

            $key = array_search($prvious_serial_no_new, $used_meter_serial_no);
            if ($key !== false) {
                unset($used_meter_serial_no[$key]);
                $unused_meter_serial_no[] = $prvious_serial_no_new;
            }

            // Update the contractor inventory
            $contractor_inventory->used_meter_serial_no = implode(',', $used_meter_serial_no) ?: null;
            $contractor_inventory->unused_meter_serial_no = implode(',', $unused_meter_serial_no);
            $contractor_inventory->save();

            break;
        }
    }
}


public function updateNewSerialInventory($meter_serial_no)
{
    $contractor_inventories = Contractor_inventory::where('contractor_id', 177)->get();

    foreach ($contractor_inventories as $contractor_inventory) {
        $individual_inventory = $contractor_inventory->unused_meter_serial_no;
        $individual_serial_nos = explode(',', $individual_inventory);

        if (in_array($meter_serial_no, $individual_serial_nos)) {
            $current_inventory_id = $contractor_inventory->id;

            $unused_meter_serial_no = explode(',', $contractor_inventory->unused_meter_serial_no);
            $used_meter_serial_no = explode(',', $contractor_inventory->used_meter_serial_no);

            $key = array_search($meter_serial_no, $unused_meter_serial_no);
            if ($key !== false) {
                unset($unused_meter_serial_no[$key]);
                $used_meter_serial_no[] = $meter_serial_no;
            }

            // Update the contractor inventory
            $contractor_inventory->unused_meter_serial_no = implode(',', $unused_meter_serial_no) ?: null;
            $contractor_inventory->used_meter_serial_no = implode(',', $used_meter_serial_no);
            $contractor_inventory->save();

            break;
        }
    }
}
	
	
public function handleImageUploads($req, $meter_main)
{
    $imageFields = [
        'image_1_old', 'image_2_old', 'image_3_old',
        'image_1_new', 'image_2_new'
    ];

    foreach ($imageFields as $field) {
        if (!empty($req->file($field))) {
            $extension = $req->file($field)->extension();
            if (in_array($extension, ['png', 'jpeg', 'jpg'])) {
                $filename = Str::random(4) . time() . '.' . $extension;
                $meter_main->{$field} = $req->file($field)->move('uploads', $filename);
            }
        } else {
            $meter_main->{$field} = $meter_main->{$field};
        }
    }
}


	
	
	
    public function unused_serial_dropdown(Request $req, $id)
    {
        // Fetch the contractor inventory based on the given ID
        $inventory = Contractor_inventory::find($id);

        if ($inventory) {
            // Return the relevant data as JSON
            // Ensure that 'unused_meter_serial_no' is properly formatted
            return response()->json([
                'unused_meter_serial_no' => $inventory->unused_meter_serial_no,
            ]);
        }

        return response()->json(['message' => 'Inventory not found'], 404);
    }
    
	
	
    public function update_error_status(Request $req)
    {
        # code...
        // dd($req->selected_account_id);
        foreach ($req->selected_account_id as $account_id) {
            # code...
            // dd($account_id);
            $meter_main = Meter_main::where('account_id', $account_id)->first();
            // dd($meter_main);
            $meter_main->error_updated_by_aao = 1;
            $meter_main->save();
            $error_record = Error_record::where('account_id', $account_id)->where('updated_by_aao', '=', 0)->first();
            $error_record->updated_by_aao = 1;
            $error_record->save();
        }
        return redirect('/hescoms/error_reports');
    }

    public function aee_index()
    {
        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');

        $admin = Admin::where('id', $logged_in_user_id)->first();

        $package_name = env('PACKAGE_NAME');
        $get_all_division_codes = DB::table('zone_codes')
            ->select('division', 'div_code')
            ->where('package', $package_name)
            ->distinct()
            ->get();
        //dd($get_all_division_codes, env('PACKAGE_NAME'));

        $sub_division = DB::table('zone_codes')
            ->select('sub_division', 'sd_code', 'div_code')
            ->where('sd_code', $admin->sd_pincode)
            ->distinct()
            ->get();

        $section_codes = DB::table('zone_codes')
            ->select('sub_division', 'sd_code', 'div_code')
            ->where('so_code', $admin->so_pincode)
            ->distinct()
            ->get();

        $data = [
            'current_logged_in_user_type' => $logged_in_user_type,
            'divisions' => $get_all_division_codes,
            'sub_division' => $sub_division,
            'section_codes' => $section_codes
        ];

        //dd($data);

        return view('hescoms.aee_index', ['data' => $data]);
    }

    public function ae_index()
    {
//            $indents = Indent::get();
//            //dd($indents);
//            $meter_drawn_section_wise = 0;
//            $ae_detail = Admin::where('id', session('rexkod_vishvin_auth_userid'))->first();
//            foreach ($indents as $meter_stock) {
//                # code...
//                $so_code = explode(',', $meter_stock->so_code);
//                // if(in_array($req->so_code,$so_code)){
//                //     $meter_quantity =explode(',',$meter_stock->meter_quantity)
//                //     $meter_count = $meter_count + $meter_stock->
//                // }
//                $count = 0;
//                foreach ($so_code as $code) {
//                    if ($code == $ae_detail->so_pincode) {
//                        $meter_quantity = explode(',', $meter_stock->meter_quantity);
//                        // foreach($meter_quantity as $meter){
//                        //     $meter_drawn_section_wise = $meter_drawn_section_wise + $meter;
//
//                        // }
//                        $meter_drawn_section_wise = $meter_drawn_section_wise + $meter_quantity[$count];
//
//                    }
//                    $count++;
//                }
//
//            }
//        $bmr_error_query = DB::table('error_records')
//            ->join('meter_mains', 'error_records.account_id', '=', 'meter_mains.account_id')
//            ->join('consumer_details', 'error_records.account_id', '=', 'consumer_details.account_id')
//            ->where('consumer_details.so_pincode', '=', $ae_detail->so_pincode)
//            ->whereNotNull('meter_mains.serial_no_new')
//            ->whereNotNull('serial_no_old')
//            ->select(\DB::raw('error_records.error_reason, error_records.updated_by_aao as error_updated_by_aao, error_records.created_at as error_report_created_at, error_records.updated_at as error_report_updated_at'), 'meter_mains.*', 'consumer_details.*');
//       try{
//            $bmr_error_query_results = $bmr_error_query->get();
//            //dd($results);
//        }
//        catch(\Exception $e){
//            dd($e);
//        }
//
//        $bmr_success_query = DB::table('successful_records')
//            ->join('meter_mains', 'successful_records.account_id', '=', 'meter_mains.account_id')
//            ->join('consumer_details', 'successful_records.account_id', '=', 'consumer_details.account_id')
//            ->whereNotNull('meter_mains.serial_no_new')
//            ->whereNotNull('serial_no_old')
//            ->where('consumer_details.so_pincode', '=', $ae_detail->so_pincode)
//            ->select(\DB::raw('successful_records.created_at as successful_records_report_created_at, successful_records.updated_at as successful_records_report_updated_at'), 'meter_mains.*', 'consumer_details.*');
//        try{
//            $bmr_success_query_results = $bmr_success_query->get();
//            //dd($results);
//        }
//        catch(\Exception $e){
//            dd($e);
//        }
//
//            $bmrObj = new BmrController();
//
//            $success_count_so_pincode = $bmrObj->getSuccessCount(null, null, null, null, $ae_detail->so_pincode, null, null);
//
//            $error_count_so_pincode = $bmrObj->getErrorCount(null, null, null, null, $ae_detail->so_pincode, null, null);
//
//            $meter_replaced_section_wise_count = DB::table('meter_mains')
//                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
//                ->whereNotNull('meter_mains.serial_no_new')
//                ->whereNotNull('meter_mains.serial_no_old')
//                ->where('consumer_details.so_pincode', '=', $ae_detail->so_pincode)
//                ->select('meter_mains.id')
//                ->get()
//                ->count();
//        $meter_replaced_section_wise_count = count($meter_replaced_section_wise);
//
//            $ae_meter_approved_count = 0;
//            $ae_meter_approved_count = Meter_main::where('so_status', '1')->where('so_updated_by', session()->get('rexkod_vishvin_auth_userid'))->get()->count();
//            $ae_meter_rejected_count = 0;
//            //$get_ae_rejected_main_meter = Meter_main::where('so_status', '0')->where('so_updated_by',session()->get('rexkod_vishvin_auth_userid'))->get();
//            //$ae_meter_rejected_count =  count($get_ae_rejected_main_meter);
//            $ae_meter_rejected_count = 0;
//            $ae_meter_pending_count = 0;
//            $ae_meter_pending_count = DB::table('meter_mains')
//                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
//                ->join('admins', 'consumer_details.so_pincode', '=', 'admins.so_pincode')
//                ->select('meter_mains.*')
//                ->where('admins.id', session('rexkod_vishvin_auth_userid'))
//                ->where('qc_status', '=', '1')
//                ->where('so_status', '=', '0')
//                ->orderBy('id', 'DESC')
//                ->get();
//            $ae_meter_pending_count = count($ae_meter_pending_count);
//            $admin = Admin::where('id', session('rexkod_vishvin_auth_userid'))->first();
//            $section_name = Zone_code::where('so_code', $admin->so_pincode)->get();
//            $sub_division = DB::table('zone_codes')->select('sub_division', 'sd_code', 'div_code', "so_code", "section_office")->where('so_code', $admin->so_pincode)->distinct()->get();
//
//            $data = [
//                'meter_drawn_section_wise' => $meter_drawn_section_wise,
//                'meter_replaced_section_wise_count' => $meter_replaced_section_wise_count,
//                'ae_meter_approved_count' => $ae_meter_approved_count,
//                'ae_meter_pending_count' => $ae_meter_pending_count,
//                'ae_meter_rejected_count' => $ae_meter_rejected_count,
//                'bmr_success_query_results' => $success_count_so_pincode,
//                'bmr_error_query_results' => $error_count_so_pincode,
//                'sub_division' => $sub_division,
//                'sd_pincode' => $admin->sd_pincode,
//
//            ];
//            return view('hescoms.ae_index', ['data' => $data]);

        $logged_in_user_id = session('rexkod_vishvin_auth_userid');
        $logged_in_user_type = session('rexkod_vishvin_auth_user_type');

        $admin = Admin::where('id', $logged_in_user_id)->first();

        $package_name = env('PACKAGE_NAME');
        $get_all_division_codes = DB::table('zone_codes')
            ->where('package', $package_name)
            ->distinct()
            ->get();
        //dd($get_all_division_codes, env('PACKAGE_NAME'));

        $sub_division = DB::table('zone_codes')
            ->where('sd_code', $admin->sd_pincode)
            ->distinct()
            ->get();

        $section_codes = DB::table('zone_codes')
            ->where('so_code', $admin->so_pincode)
            ->distinct()
            ->get();

        $data = [
            'current_logged_in_user_type' => $logged_in_user_type,
            'divisions' => $get_all_division_codes,
            'sub_division' => $sub_division,
            'section_codes' => $section_codes
        ];

        //dd($data);

        return view('hescoms.ae_index', ['data' => $data]);
    }

    public function aao_index()
    {
//        $aao_meter_approved_count = 0;
//        $aao_meter_approved_count =  count(Meter_main::where('aao_status', '1')->where('aao_updated_by',session()->get('rexkod_vishvin_auth_userid'))->get());
//
//        $aao_meter_rejected_count = 0;
//        $get_aao_rejected_main_meter =  Meter_main::where('aao_status', '2')->get();
//        $aao_meter_rejected_count =  count($get_aao_rejected_main_meter);
//
//        $aao_meter_pending_count = 0;
//        $get_aao_pending_main_meter =DB::table('meter_mains')
//        ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
//        ->join('admins', 'consumer_details.sd_pincode', '=', 'admins.sd_pincode')
//        ->select('meter_mains.*')
//        ->where('qc_status', 1)->where('so_status', 1)->where('aee_status', 1)->where('aao_status', 0)->orderBy('id')->get();
//        $aao_meter_pending_count =  count($get_aao_pending_main_meter);
//
//        $get_total_successful_record = DB::table('successful_records')
//                            ->join('consumer_details', 'successful_records.account_id', '=', 'consumer_details.account_id')
//                            ->join('admins', 'consumer_details.sd_pincode', '=', 'admins.sd_pincode')
//                            ->where('admins.id', session('rexkod_vishvin_auth_userid'))
//                            ->get();
//        $get_total_successful_record_count = count($get_total_successful_record);
//
//        $get_total_error_record = DB::table('error_records')
//        ->join('consumer_details', 'error_records.account_id', '=', 'consumer_details.account_id')
//        ->join('admins', 'consumer_details.sd_pincode', '=', 'admins.sd_pincode')
//        ->where('admins.id', session('rexkod_vishvin_auth_userid'))
//        ->where('error_records.updated_by_aao', '=', 0)
//        ->get();
//        $get_total_error_record_count = count($get_total_error_record);
//
//
//
//        $error_record_pending_for_aao = DB::table('error_records')
//                            ->join('consumer_details', 'error_records.account_id', '=', 'consumer_details.account_id')
//                            ->join('admins', 'consumer_details.sd_pincode', '=', 'admins.sd_pincode')
//                            ->where('admins.id', session('rexkod_vishvin_auth_userid'))
//                            ->where('error_records.updated_by_aao', 0)
//                            ->get();
//        $error_record_pending_for_aao_count = count($error_record_pending_for_aao);
//        $error_updated_but_not_downloaded = DB::table('error_records')
//                            ->join('consumer_details', 'error_records.account_id', '=', 'consumer_details.account_id')
//                            ->join('meter_mains', 'error_records.account_id', '=', 'meter_mains.account_id')
//                            ->join('admins', 'consumer_details.sd_pincode', '=', 'admins.sd_pincode')
//                            ->where('admins.id', session('rexkod_vishvin_auth_userid'))
//                            ->where('meter_mains.error_updated_by_aao','!=', 0)
//                            ->get();
//        $error_updated_but_not_downloaded_count = count($error_updated_but_not_downloaded);
        $admin = Admin::where('id', session('rexkod_vishvin_auth_userid'))->first();
        $section_name = Zone_code::where('sd_code', $admin->sd_pincode)->get();
        $sub_division = DB::table('zone_codes')->select('sub_division', 'sd_code', 'div_code')->where('sd_code', $admin->sd_pincode)->distinct()->get();

        $data = [
//'aao_meter_approved_count' => $aao_meter_approved_count,
//'aao_meter_rejected_count' => $aao_meter_rejected_count,
//'aao_meter_pending_count' => $aao_meter_pending_count,
//'get_total_successful_record_count' => $get_total_successful_record_count,
//'get_total_error_record_count' => $get_total_error_record_count,
//'error_record_pending_for_aao_count' => $error_record_pending_for_aao_count,
//'error_updated_but_not_downloaded_count' => $error_updated_but_not_downloaded_count,
            'sub_division' => $sub_division,
//            'sd_pincode' => $admin->sd_pincode,
//
        ];
        return view('hescoms.aao_index', ['data' => $data]);
    }

    public function hescomCount(Request $req)
    {

        $admin = Admin::where('id', session('rexkod_vishvin_auth_userid'))->first();
        $vishvin_qc_status_done = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereNotNull('meter_mains.serial_no_new')
            ->where('meter_mains.qc_status', '=', 1);
       // if ($admin->type == 'ae') {
        //    $vishvin_qc_status_done->where('meter_mains.so_updated_by', session()->get('rexkod_vishvin_auth_userid'));
      //  }
//            if ($admin->type == 'aee') {
//                $vishvin_qc_status_done->where('meter_mains.aee_updated_by', session()->get('rexkod_vishvin_auth_userid'));
//            }
//            if ($admin->type == 'aao') {
//                $vishvin_qc_status_done->where('meter_mains.aao_updated_by', session()->get('rexkod_vishvin_auth_userid'));
//            }
        if ($req->divisionId != null) {
            $vishvin_qc_status_done->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $vishvin_qc_status_done->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $vishvin_qc_status_done->where('consumer_details.section', $req->sectionId);
        }
        $vishvin_qc_status_done_res = count($vishvin_qc_status_done->get());

        $vishvin_qc_status_pending = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereNotNull('meter_mains.serial_no_new')
            ->where('meter_mains.qc_status', '=', 0);

//            if ($admin->type == 'ae') {
//                $vishvin_qc_status_pending->where('meter_mains.so_updated_by', session()->get('rexkod_vishvin_auth_userid'));
//            }
//                if($admin->type == 'aee'){
//                    $vishvin_qc_status_pending->where('meter_mains.aee_updated_by',session()->get('rexkod_vishvin_auth_userid'));
//                }
//                if($admin->type == 'aao'){
//                    $vishvin_qc_status_pending->where('meter_mains.aao_updated_by',session()->get('rexkod_vishvin_auth_userid'));
//                }
        if ($req->divisionId != null) {
            $vishvin_qc_status_pending->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $vishvin_qc_status_pending->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $vishvin_qc_status_pending->where('consumer_details.section', $req->sectionId);
        }
        $vishvin_qc_status_pending_res = count($vishvin_qc_status_pending->get());

        $ae_status_done = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereNotNull('meter_mains.serial_no_new')
            ->where('meter_mains.qc_status', '=', 1)
            ->where('meter_mains.so_status', '=', 1);
        if ($admin->type == 'ae') {
            $ae_status_done->where('meter_mains.so_updated_by', session()->get('rexkod_vishvin_auth_userid'));
        }
//            if ($admin->type == 'aee') {
//                $ae_status_done->where('meter_mains.aee_updated_by', session()->get('rexkod_vishvin_auth_userid'));
//            }
//            if ($admin->type == 'aao') {
//                $ae_status_done->where('meter_mains.aao_updated_by', session()->get('rexkod_vishvin_auth_userid'));
//            }
        if ($req->divisionId != null) {
            $ae_status_done->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $ae_status_done->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $ae_status_done->where('consumer_details.section', $req->sectionId);
        }
        $ae_status_done_res = count($ae_status_done->get());
        $ae_status_pending = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereNotNull('meter_mains.serial_no_new')
            ->where('meter_mains.qc_status', '=', 1)
            ->where('meter_mains.so_status', '=', 0);

//            if ($admin->type == 'ae') {
//                $ae_status_pending->where('meter_mains.so_updated_by', session()->get('rexkod_vishvin_auth_userid'));
//            }
//                if($admin->type == 'aee'){
//                    $ae_status_pending->where('meter_mains.aee_updated_by',session()->get('rexkod_vishvin_auth_userid'));
//                }
//                if($admin->type == 'aao'){
//                    $ae_status_pending->where('meter_mains.aao_updated_by',session()->get('rexkod_vishvin_auth_userid'));
//                }
        if ($req->divisionId != null) {
            $ae_status_pending->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $ae_status_pending->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $ae_status_pending->where('consumer_details.section', $req->sectionId);
        }
        $ae_status_pending_res = $ae_status_pending->get()->count();
        $aee_status_done = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereNotNull('meter_mains.serial_no_new')
            ->where('meter_mains.qc_status', '=', 1)
            ->where('meter_mains.so_status', '=', 1)
            ->where('meter_mains.aee_status', "=", 1);
        if ($admin->type == 'ae') {
            $aee_status_done->where('meter_mains.so_updated_by', session()->get('rexkod_vishvin_auth_userid'));
        }
//                if($admin->type == 'aee'){
//                    $aee_status_done->where('meter_mains.aee_updated_by',session()->get('rexkod_vishvin_auth_userid'));
//                }
//                if($admin->type == 'aao'){
//                    $aee_status_done->where('meter_mains.aao_updated_by',session()->get('rexkod_vishvin_auth_userid'));
//                }
        if ($req->divisionId != null) {
            $aee_status_done->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $aee_status_done->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $aee_status_done->where('consumer_details.section', $req->sectionId);
        }
        $aee_status_done_res = $aee_status_done->get()->count();
        $aee_status_pending = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereNotNull('meter_mains.serial_no_new')
            ->where('meter_mains.qc_status', '=', 1)
            ->where('meter_mains.so_status', '=', 1)
            ->where('meter_mains.aee_status', '=', 0);
//            if ($admin->type == 'ae') {
//                $aee_status_pending->where('meter_mains.so_updated_by', $admin->id);
//            }
//                if($admin->type == 'aee'){
//                    $aee_status_pending->where('meter_mains.aee_updated_by',$admin->id);
//                }
//                if($admin->type == 'aao'){
//                    $aee_status_pending->where('meter_mains.aao_updated_by',$admin->id);
//                }
        if ($req->divisionId != null) {
            $aee_status_pending->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $aee_status_pending->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $aee_status_pending->where('consumer_details.section', $req->sectionId);
        }
        $aee_status_pending_res = $aee_status_pending->get()->count();
        $aao_status_done = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereNotNull('meter_mains.serial_no_new')
            ->where('meter_mains.qc_status', '=', 1)
            ->where('meter_mains.so_status', '=', 1)
            ->where('meter_mains.aee_status', '=', 1)
            ->where('meter_mains.aao_status', '=', 1);
		   $aao_status_rejected = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereNotNull('meter_mains.serial_no_new')
            ->where('meter_mains.qc_status', '=', 1)
            ->where('meter_mains.so_status', '=', 1)
            ->where('meter_mains.aee_status', '=', 1)
            ->where('meter_mains.aao_status', '=', 2);
        if ($admin->type == 'ae') {
            $aao_status_done->where('meter_mains.so_updated_by', $admin->id);
        }
//                if($admin->type == 'aee'){
//                    $aao_status_done->where('meter_mains.aee_updated_by',$admin->id);
//                }
//                if($admin->type == 'aao'){
//                    $aao_status_done->where('meter_mains.aao_updated_by',$admin->id);
//                }
        if ($req->divisionId != null) {
            $aao_status_done->where('consumer_details.division', $req->divisionId);
			$aao_status_rejected->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $aao_status_done->where('consumer_details.sub_division', $req->subDivisionId);
			 $aao_status_rejected->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $aao_status_done->where('consumer_details.section', $req->sectionId);
		   $aao_status_rejected->where('consumer_details.section', $req->sectionId);
        }
		
		
        $aao_status_done_res = $aao_status_done->get()->count();
		   $aao_status_rejected_res = $aao_status_rejected->get()->count();

        $aao_status_pending = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereNotNull('meter_mains.serial_no_new')
            ->where('meter_mains.qc_status', '=', 1)
            ->where('meter_mains.so_status', '=', 1)
            ->where('meter_mains.aee_status', '=', 1)
            ->where('meter_mains.aao_status', '=', 0);
//            if ($admin->type == 'ae') {
//                $aao_status_pending->where('meter_mains.so_updated_by', $admin->id);
//            }
//                if($admin->type == 'aee'){
//                    $aao_status_pending->where('meter_mains.aee_updated_by',$admin->id);
//                }
//                if($admin->type == 'aao'){
//                    $aao_status_pending->where('meter_mains.aao_updated_by',$admin->id);
//                }
        if ($req->divisionId != null) {
            $aao_status_pending->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $aao_status_pending->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $aao_status_pending->where('consumer_details.section', $req->sectionId);
        }
        $aao_status_pending_res = $aao_status_pending->get()->count();
        $division_implement = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')->whereNotNull('meter_mains.serial_no_new');
//        if ($admin->type == 'ae') {
//            $division_implement->where('meter_mains.so_updated_by', $admin->id);
//        }
//                if($admin->type == 'aee'){
//                    $division_implement->where('meter_mains.aee_updated_by',$admin->id);
//                }
//                if($admin->type == 'aao'){
//                    $division_implement->where('meter_mains.aao_updated_by',$admin->id);
//                }
        if ($req->divisionId != null) {
            $division_implement->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $division_implement->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $division_implement->where('consumer_details.section', $req->sectionId);
        }
        $division_implement_res = $division_implement->get()->count();

        $section_code = DB::table('zone_codes')
            ->select('so_code', 'section_office');
        if ($req->subDivisionId != null) {
            $section_code->where('sd_code', $req->subDivisionId);
            $section_code_res = $section_code->get();
        } else {
            $section_code_res = null;
        }
        $sub_division_code = DB::table('zone_codes')
            ->select('sd_code', 'sub_division');
        if ($req->divisionId != null) {
            $sub_division_code->where('div_code', $req->divisionId)->distinct();
            $sub_division_code_res = $sub_division_code->get();
        } else {
            $sub_division_code_res = null;
        }
        $bmr_success_query = DB::table('successful_records')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'successful_records.account_id')
            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id');
        if ($admin->type == 'ae') {
            $bmr_success_query->where('meter_mains.so_updated_by', $admin->id);
        }
//                   if($admin->type == 'aee'){
//                       $bmr_success_query->where('meter_mains.aee_updated_by',$admin->id);
//                   }
//                   if($admin->type == 'aao'){
//                       $bmr_success_query->where('meter_mains.aao_updated_by',$admin->id);
//                   }
        if ($req->divisionId != null) {
            $bmr_success_query->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $bmr_success_query->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $bmr_success_query->where('consumer_details.section', $req->sectionId);
        }
        $bmr_success_query->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('meter_mains.serial_no_old')
//                       ->where('meter_mains.qc_status','=',1)
//                       ->where('meter_mains.so_status','=',1)
//                       ->where('meter_mains.aee_status','=',1)
//                       ->where('meter_mains.aao_status','=',1)
            ->select('consumer_details.account_id');
        //   ->select(\DB::raw('successful_records.created_at as successful_records_report_created_at, successful_records.updated_at as successful_records_report_updated_at'), 'meter_mains.*', 'consumer_details.*');
        //->select(\DB::raw('successful_records.account_id'));


        try {
            $bmr_success_query_results = $bmr_success_query->get()->count();
            //dd($results);
        } catch (\Exception $e) {
            dd($e);
        }

        $bmr_error_query = DB::table('error_records')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'error_records.account_id')
            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id');
        if ($admin->type == 'ae') {
            $bmr_error_query->where('meter_mains.so_updated_by', $admin->id);
        }
//            if ($admin->type == 'aee') {
//                $bmr_error_query->where('meter_mains.aee_updated_by', $admin->id);
//            }
//            if ($admin->type == 'aao') {
//                $bmr_error_query->where('meter_mains.aao_updated_by', $admin->id);
//            }
        if ($req->divisionId != null) {
            $bmr_error_query->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $bmr_error_query->where('consumer_details.sub_division', $req->subDivisionId);
        }

        if ($req->sectionId != null) {
            $bmr_error_query->where('consumer_details.section', $req->sectionId);
        }
        $bmr_error_query->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('serial_no_old')
            ->where('error_records.updated_by_aao', '=', 0)
//                    ->where('meter_mains.qc_status','=',1)
//                    ->where('meter_mains.so_status','=',1)
//                    ->where('meter_mains.aee_status','=',1)
//                    ->where('meter_mains.aao_status','=',1)
            ->select('consumer_details.account_id');
        //->select(\DB::raw('error_records.error_reason, error_records.updated_by_aao as error_updated_by_aao, error_records.created_at as error_report_created_at, error_records.updated_at as error_report_updated_at'), 'meter_mains.*', 'consumer_details.*');


        try {
            $bmr_error_query_results = $bmr_error_query->get()->count();
            //dd($results);
        } catch (\Exception $e) {
            dd($e);
        }

        $bmr_pending_query = DB::table('meter_mains')
            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id');
        if ($admin->type == 'ae') {
            $bmr_pending_query->where('meter_mains.so_updated_by', $admin->id);
        }
//            if ($admin->type == 'aee') {
//                $bmr_pending_query->where('meter_mains.aee_updated_by', $admin->id);
//            }
//            if ($admin->type == 'aao') {
//                $bmr_pending_query->where('meter_mains.aao_updated_by', $admin->id);
//            }
        if ($req->divisionId != null) {
            $bmr_pending_query->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $bmr_pending_query->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $bmr_pending_query->where('consumer_details.section', $req->sectionId);
        }
        $bmr_pending_query->whereNotNull('meter_mains.serial_no_new')
            ->whereNotNull('meter_mains.serial_no_old')
//                        ->where('meter_mains.qc_status','=',1)
//                        ->where('meter_mains.so_status','=',1)
//                        ->where('meter_mains.aee_status','=',1)
//                        ->where('meter_mains.aao_status','=',1)
//                        ->where('meter_mains.download_flag','=',0);
            ->whereRaw('(meter_mains.aao_status = 1 and meter_mains.download_flag = 0 or meter_mains.error_updated_by_aao = 1)');
//                        });
        try {
            $bmr_pending_query_results = $bmr_pending_query->get()->count();
            //dd($results);
        } catch (\Exception $e) {
            dd($e);
        }

        $division_implement_today = DB::table('consumer_details')
            ->select('consumer_details.account_id')
            ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
            ->whereNotNull('meter_mains.serial_no_old')
            ->whereNotNull('meter_mains.serial_no_new')
            //->whereBetween('meter_mains.created_at', ['2023-11-08 00:00:00', '2023-11-08 23:59:59']);
            ->whereBetween('meter_mains.created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')]);
//        if ($admin->type == 'ae') {
//            $division_implement_today->where('meter_mains.so_updated_by', $admin->id);
//        }
//            if ($admin->type == 'aee') {
//                $division_implement_today->where('meter_mains.aee_updated_by', $admin->id);
//            }
//            if ($admin->type == 'aao') {
//                $division_implement_today->where('meter_mains.aao_updated_by', $admin->id);
//            }
        if ($req->divisionId != null) {
            $division_implement_today->where('consumer_details.division', $req->divisionId);
        }
        if ($req->subDivisionId != null) {
            $division_implement_today->where('consumer_details.sub_division', $req->subDivisionId);
        }
        if ($req->sectionId != null) {
            $division_implement_today->where('consumer_details.section', $req->sectionId);
        }
        $division_implement_today_results = $division_implement_today->get()->count();

        $data = [
            'vishvin_qc_status_done' => $vishvin_qc_status_done_res,
            'vishvin_qc_status_pending' => $vishvin_qc_status_pending_res,
            'so_status_done' => $ae_status_done_res,
            'so_status_pending' => $ae_status_pending_res,
            'aee_status_done' => $aee_status_done_res,
            'aee_status_pending' => $aee_status_pending_res,
            'aao_status_done' => $aao_status_done_res,
			 'aao_status_rejected' => $aao_status_rejected_res,
            'aao_status_pending' => $aao_status_pending_res,
            'division_implement' => $division_implement_res,
            'section_code_res' => $section_code_res,
            'sub_division_code_res' => $sub_division_code_res,
            'bmr_success_query_results' => $bmr_success_query_results,
            'bmr_error_query_results' => $bmr_error_query_results,
            'bmr_pending_query_results' => $bmr_pending_query_results,
            'division_implement_today_results' => $division_implement_today_results,
        ];

        return response()->json($data);
    }

}
