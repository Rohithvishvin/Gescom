<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Admin;
// use Session;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Meter_main;
use App\Models\Consumer_detail;
use App\Models\Warehouse_meter;
use App\Models\Annexure_1;
use App\Models\Annexure_3;
use App\Models\Inward_released_em_meter;
use App\Models\Outward_released_em_meter;
use App\Models\Contractor_inventory;
use Illuminate\Support\Facades\DB;
use App\Models\Zone_code;
use App\Models\Indent;
use App\Models\Successful_record;
use App\Models\Error_record;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
// use RealRashid\SweetAlert\Facades\Alert;
// use Auth;
class AdminController extends Controller
{
    public function login()
    {
        // if(session('rexkod_vishvin_auth_userid') !== null){
        //     return view('admins.login');
        // }
        // else{
        //     return redirect('/');

        // }
        return view('admins.login');
    }

    public function index()
    {
        return redirect('/project_heads/index');
    }


    public function approve_meter_stat_show(Request $req)
    {
        if ($req->format === 'weekly') {
            # code...
            $today = Carbon::now();
            $dateSevenDaysAgo = Carbon::now()->subDays(7);
            // dd($today);
            $start_date = $dateSevenDaysAgo->format('Y-m-d');
            $end_date = $today->format('Y-m-d');

            $approved_meters = Meter_main::where('aao_status', 1)->where('aao_updated_at', '>=', $start_date)->where('aao_updated_at', '<=', $end_date)->get();

                $data = [
                    'approved_meters' => $approved_meters,
                ];
        } else if($req->format === 'monthly'){
            # code...
            $today = Carbon::now();
            $dateSevenDaysAgo = Carbon::now()->subDays(30);

            $start_date = $dateSevenDaysAgo->format('Y-m-d');
            $end_date = $today->format('Y-m-d');
            $approved_meters = Meter_main::where('aao_status', 1)->where('aao_updated_at', '>=', $start_date)->where('aao_updated_at', '<=', $end_date)->get();


                $data = [
                    'approved_meters' => $approved_meters,
                ];
        }else{
            if ($req->get('start_date') !== NUll) {
                $start_date = $req->get('start_date');
                $end_date = $req->get('end_date');
                $approved_meters = Meter_main::where('aao_status', 1)->where('aao_updated_at', '>=', $start_date)->where('aao_updated_at', '<=', $end_date)->get();


                $data = [
                    'approved_meters' => $approved_meters,
                ];
            } else {
                $approved_meters = Meter_main::where('aao_status', 1)->get();
                $data = [
                    'approved_meters' => $approved_meters,
                ];
            }
        }






        return view('admins.approve_meter_stat_show', compact('data'));
    }



    public function add_project_head()
    {
        return view('admins.add_project_head');
    }

    //     public function authenticate(Request $request)
    //     {


    //       $user = Admin::where('user_name', $request->user_name)->first();
    // if ($user && Hash::check($request->password, $user->password)) {
    //             return redirect('/');
    // }
    //         $credentials = $request->only('user_name', 'password');

    //         if (auth()->attempt($credentials)) {
    //             return redirect('/admins/index');
    //         }

    //         return redirect('/admins/login')->withErrors(['error' => 'Invalid username or password.']);
    //     }






    public function authenticate(Request $req)
    {
         // return $req;
        // dd($req->phone);
        $user = Admin::where('phone', $req->phone)->first();
        if ($user  && Hash::check($req->password, $user->password)) {
            Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
            return redirect('admins/welcome');

        }

        if ($user  && Hash::check($req->password, $user->password)) {
            if ($user->type == "admin") {
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                // return redirect('admins/index');
                return redirect('admins/admin_index');

                // session()->put('failed', 'Invalid Credentials');
                // return redirect('/');
            }else if($user->type == "project_head"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('project_heads/index');
            }
            else if($user->type == "inventory_manager"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('inventories/inventory_manager_index');
            }
            else if($user->type == "inventory_executive"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('inventories/inventory_executive_index');
            }
            else if($user->type == "inventory_reporter"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('inventories/inventory_reporter_index');
            }
            else if($user->type == "qc_manager"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('qcs/index');
            }
            else if($user->type == "qc_executive"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('qcs/qc_executive_index');
            }
            else if($user->type == "hescom_manager"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('hescoms/index');
            }
            else if($user->type == "aee"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('hescoms/aee_index');
            }
            else if($user->type == "ae"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('hescoms/ae_index');
            }
            else if($user->type == "aao"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('hescoms/aao_index');
            }
            else if($user->type == "contractor_manager"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('contractors/index');
            }
            else if($user->type == "bmr"){
                Session::put('rexkod_vishvin_auth_name', $user->name);
                Session::put('rexkod_vishvin_auth_userid', $user->id);
                Session::put('rexkod_vishvin_auth_phone', $user->phone);
                Session::put('rexkod_vishvin_auth_user_type', $user->type);
                return redirect('bmrs/index');
            }
            else {
                session()->put('failed', 'Invalid Credentials');
                return redirect('/');
            }
            // {Hash::check($req->password,$user->password)
        } else {
            session()->put('failed', 'Invalid Credentials');
            return redirect('/');
        }
    } 
	
	//new authenticated file
	
 /*   public function authenticate(Request $req)
{
    // Function to fetch the MAC address
    function getMacAddress() {
        // Check the OS platform (Windows or Unix-based)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // For Windows, we use `getmac` command
            $macAddress = shell_exec('getmac');
            // Remove the unnecessary text like 'Physical'
            preg_match('/([a-f0-9]{2}[:|-]?){5}[a-f0-9]{2}/i', $macAddress, $matches);
            return isset($matches[0]) ? $matches[0] : 'MAC address not found';
        } else {
            // For Unix/Linux/macOS, we use `ifconfig` or `ip` command
            $macAddress = shell_exec("ifconfig | grep -Eo '([[:xdigit:]]{1,2}:){5}[[:xdigit:]]{1,2}' | head -n 1");
            // Clean up the result to avoid extra spaces or newlines
            $macAddress = trim($macAddress);
            return $macAddress ?: 'MAC address not found';
        }
    }

    $user = Admin::where('phone', $req->phone)->first();

    if ($user && Hash::check($req->password, $user->password)) {
        // Fetch the MAC address of the current device
        $macAddress = getMacAddress();

        // Save the MAC address to the session
        Session::put('rexkod_vishvin_auth_name', $user->name);
        Session::put('rexkod_vishvin_auth_userid', $user->id);
        Session::put('rexkod_vishvin_auth_phone', $user->phone);
        Session::put('rexkod_vishvin_auth_user_type', $user->type);
        Session::put('mac_address', $macAddress);  // Store MAC address in session

        // Check if the user already has MAC addresses saved in the database
        $macAddresses = $user->mac_addresses ? json_decode($user->mac_addresses, true) : [];

        // Append the new MAC address to the existing MAC addresses array
        $macAddresses[] = $macAddress;

        // Update the MAC addresses in the database (as a JSON array)
        $user->mac_addresses = json_encode($macAddresses);
        $user->save();

        // Redirect based on user type
        if ($user->type == "admin") {
            return redirect('admins/admin_index');
        } elseif ($user->type == "project_head") {
            return redirect('project_heads/index');
        } elseif ($user->type == "inventory_manager") {
            return redirect('inventories/inventory_manager_index');
        } elseif ($user->type == "inventory_executive") {
            return redirect('inventories/inventory_executive_index');
        } elseif ($user->type == "inventory_reporter") {
            return redirect('inventories/inventory_reporter_index');
        } elseif ($user->type == "qc_manager") {
            return redirect('qcs/index');
        } elseif ($user->type == "qc_executive") {
            return redirect('qcs/qc_executive_index');
        } elseif ($user->type == "hescom_manager") {
            return redirect('hescoms/index');
        } elseif ($user->type == "aee") {
            return redirect('hescoms/aee_index');
        } elseif ($user->type == "ae") {
            return redirect('hescoms/ae_index');
        } elseif ($user->type == "aao") {
            return redirect('hescoms/aao_index');
        } elseif ($user->type == "contractor_manager") {
            return redirect('contractors/index');
        } elseif ($user->type == "bmr") {
            return redirect('bmrs/index');
        } else {
            session()->put('failed', 'Invalid Credentials');
            return redirect('/');
        }
    } else {
        session()->put('failed', 'Invalid Credentials');
        return redirect('/');
    }
} */


    public function welcome(){
        return view('admins.welcome');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out!');
    }

    // Store Listing Data
    // public function create_project_head(Request $request)
    // {
    //     // print_r($request->all());
    //     // dd($request->all());
    //     $type = 'project_head';
    //     $formFields = $request->validate([
    //         'name' => ['required', 'min:3'],
    //         'project_name' => ['required', 'min:3'],
    //         'user_name' => ['required', 'min:3'],
    //         // 'user_name' => ['required', 'user_name', Rule::unique('admins', 'user_name')],
    //         'password' => 'required|confirmed|min:6',
    //         'phone' => ['required', 'min:3'],



    //     ]);

    //     $formFields['password'] = bcrypt($formFields['password']);
    //     $formFields['type'] = $type;

    //     $user = Admin::create($formFields);


    //     return redirect('/admins/add_project_head')->with('message', 'Project Head Created');
    // }



    public function create_project_head(Request $req)
    {
        // print_r($req->all());
        $auth = new Admin();


        $result = Admin::where('phone', $req->phone)->first();

        if ($result) {
            session()->put('failed', 'Phone already exists');

            return redirect('/admins/add_project_head');
        } else {
            $auth->name = $req->name;

            $auth->phone = $req->phone;

            $auth->project_name = $req->project_name;

            $auth->type = "project_head";
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

                //  "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
                //     redirect('pages/add_user');
                // die;
            }



            $auth->created_by = session()->get('rexkod_vishvin_auth_userid');
            $auth->save();
            session()->put('success', 'Project Head added successfully');

            // $user = Admin::where('user_name', $req->user_email)->first();

            // $req->session()->put('user',$user);

            return redirect('/admins/show_users');
        }
    }


    function update_user_password(Request $req)
    {
        // print_r($req->all());

        // dd($req);

        $auth = Admin::where('id', $req->user_id)->first();
        $auth->password = Hash::make($req->password);

        $uppercase = preg_match('@[A-Z]@', $req->password);
        $lowercase = preg_match('@[a-z]@', $req->password);
        $number    = preg_match('@[0-9]@', $req->password);
        $specialChars = preg_match('@[^\w]@', $req->password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($req->password) < 8) {
            session()->put('failed', 'Password should be atleast 8 characters & must include atleast one upper case letter, one number, and one special character');
            return redirect()->back();

            //  "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
            //     redirect('pages/add_user');
            // die;
        }
        $auth->password = Hash::make($req->password);
        $auth->save();

        session()->put('success', 'Password changed successfully');
        return redirect('/project_heads/index');
    }





    public function show_users()
    {
        return view('admins.show_users', [
            'show_users' => Admin::where('type', 'project_head')->get(),
        ]);
    }


    public function qc_report_detail($id)
    {
        $meter_main = Meter_main::where('id', $id)->first();
        $consumer_detail = Consumer_detail::where('account_id', $meter_main->account_id)->first();
        $data = [
            'meter_main' => $meter_main,
            'consumer_detail' => $consumer_detail,
            'id' => $id,
        ];
        return view('admins.qc_report_detail', ['data' => $data]);
    }
    public function forget_password()
    {
        $created_by  = session()->get('rexkod_vishvin_auth_userid');;
        $user_id = Admin::where('created_by', $created_by)->get();

        $data = [
            'user_id' => $user_id,

        ];
        return view('admins.forget_password', ['data' => $data]);
    }

    public function reset_password()
    {

        $created_by  = session()->get('rexkod_vishvin_auth_userid');
        //dd($created_by);
        $user_id = Admin::where('id', $created_by)->get();

        $data = [
            'user_id' => $user_id,

        ];
        //dd($data);
        return view('admins.reset_password', ['data' => $data]);
    }


    public function all_consumers()
    {

         $show_users = Consumer_detail::paginate(5000);

        return view('admins.all_consumers', compact('show_users'));

        // return view('admins.all_consumers', [
        //     'show_users' => Consumer_detail::take(100)->get(),
        // ]);
    }
}
