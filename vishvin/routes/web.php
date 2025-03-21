<?php

use illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectHeadController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\QcController;
use App\Http\Controllers\HescomController;
use App\http\Controllers\SurveyController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BmrController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ImageCompressionController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admins/index', [AdminController::class, 'index']);
Route::get('/admins/admin_index', [AdminController::class, 'admin_index']);

Route::get('/admins/add_project_head', [AdminController::class, 'add_project_head']);
/*Route::get('/admins/forget_password', [AdminController::class, 'forget_password']);*/
Route::get('/admins/forget_password', 
function () 
{
    if (Session::has('rexkod_vishvin_auth_name'))
     {    
        $userType = Session::get('rexkod_vishvin_auth_user_type'); 
        if ($userType != 'qc_manager')
         {
            return (new AdminController())->forget_password();
         }
         else if($userType == 'qc_manager')
         {
            return Redirect::to('/qcs/index'); 
         }
     }
});
Route::get('/admins/reset_password', [AdminController::class, 'reset_password']);
Route::get('/admins/qc_report_detail/{id}', [AdminController::class, 'qc_report_detail']);

// Route::get('/admins/add_project_head', function () {
//     return view('admins/add_project_head')->middleware('auth');
// });
Route::post('/update_user_password', [AdminController::class, 'update_user_password']);
Route::post('/create_project_head', [AdminController::class, 'create_project_head']);

// Route::get('/admins/approve_meter_stat_show', function () {
//     return view('admins/approve_meter_stat_show');
// });


Route::get('/admins/approve_meter_stat_show', [AdminController::class, 'approve_meter_stat_show']);
Route::get('/admins/show_users', [AdminController::class, 'show_users'])->name('register');



Route::get('/', [AdminController::class, 'login'])->name('login');
// This will basically not allow the user to go to login page if they are logged in .
// ->middleware('guest');
// Log In User
Route::post('/authenticate', [AdminController::class, 'authenticate']);
// Route::post('/update_user_password', [AdminController::class, 'update_user_password']);


Route::get('/admins/logout', [AdminController::class, 'logout']);

Route::get('/admins/all_consumers', [AdminController::class, 'all_consumers']);

// admin rreports




// Project Head Routes
Route::get('/project-heads/show-users/{filter_mobile?}', [ProjectHeadController::class, 'show_users']);
Route::post('/project-heads/update-user-data/{id}', [ProjectHeadController::class, 'update_user_data']);
Route::get('project_heads/index', [ProjectHeadController::class, 'index']);
Route::get('project_heads/add_inventory_manager', [ProjectHeadController::class, 'add_inventory_manager']);
Route::post('project_heads/create_inventory_manager', [ProjectHeadController::class, 'create_inventory_manager']);
Route::get('project_heads/add_contractor', [ProjectHeadController::class, 'add_contractor']);
Route::post('project_heads/create_contractor_manager', [ProjectHeadController::class, 'create_contractor_manager']);


// half work delete option from Project Head
Route::get('/project_heads/half_work', [ProjectHeadController::class, 'half_work']);
Route::delete('/half_install/delete/{id}', [ProjectHeadController::class, 'half_install_delete']);
Route::get('/half_install/view/{id}', [ProjectHeadController::class, 'viewHalfInstall']);

Route::get('project_heads/add_hescom', [ProjectHeadController::class, 'add_hescom']);
Route::post('project_heads/create_hescom_manager', [ProjectHeadController::class, 'create_hescom_manager']);

Route::get('project_heads/add_inventory', [ProjectHeadController::class, 'add_inventory']);
Route::get('project_heads/consumer_bulk_upload', [ProjectHeadController::class, 'consumer_bulk_upload']);
Route::get('project_heads/add_qc', [ProjectHeadController::class, 'add_qc']);
Route::post('project_heads/create_qc_manager', [ProjectHeadController::class, 'create_qc_manager']);

Route::get('project_heads/all_contractors', [ProjectHeadController::class, 'all_contractors']);
Route::get('project_heads/all_hescoms', [ProjectHeadController::class, 'all_hescoms']);
Route::get('project_heads/all_inventory_managers', [ProjectHeadController::class, 'all_inventory_managers']);
Route::get('project_heads/all_qcs', [ProjectHeadController::class, 'all_qcs']);
Route::get('project_heads/all_users', [ProjectHeadController::class, 'all_users']);

Route::post('project_heads/upload_file',[ProjectHeadController::class,'upload_file']);

Route::get('project_heads/add_bmr', [ProjectHeadController::class, 'add_bmr']);
Route::post('project_heads/create_bmr', [ProjectHeadController::class, 'create_bmr']);
Route::get('project_heads/all_bmr', [ProjectHeadController::class, 'all_bmr']);

Route::get('project_heads/reports', [ProjectHeadController::class, 'reports']);

//bmr status
Route::get('project_heads/view_bmr_status', [ProjectHeadController::class, 'bmr_status_view']);
Route::post('project_heads/view_bmr_status_filter', [ProjectHeadController::class, 'bmr_status_view_filter']);
Route::get('project_heads/view_bmr_status_filter/{section}', [ProjectHeadController::class, 'bmr_status_view_filter_ae']);
Route::get('project_heads/view_bmr_status_filter_view/{start_date}/{end_date?}', [ProjectHeadController::class, 'bmr_status_view_filter_view']);
Route::get('project_heads/view_bmr_status_filter_view_ae/{start_date}/{end_date}/{section?}', [ProjectHeadController::class, 'bmr_status_view_filter_view_ae']);

Route::get('project_heads/view_bmr_status_success_report/{start_date}/{end_date?}', [ProjectHeadController::class, 'view_bmr_status_success_report']);
Route::get('project_heads/view_bmr_status_error_report/{start_date}/{end_date?}', [ProjectHeadController::class, 'view_bmr_status_error_report']);
Route::get('project_heads/view_bmr_status_success_report/{start_date}/{end_date?}/{division?}/{sub_division?}/{section?}/{feeder_code?}', [ProjectHeadController::class, 'view_bmr_status_success_report_view']);
Route::get('project_heads/view_bmr_status_error_report/{start_date}/{end_date?}/{division?}/{sub_division?}/{section?}/{feeder_code?}', [ProjectHeadController::class, 'view_bmr_status_error_report_ae']);


// ------report new

Route::get('/project_heads/report_filter', [ProjectHeadController::class, 'report_filter_view']);
Route::post('/project_heads/report_filter', [ProjectHeadController::class, 'report_filter']);
Route::get('/project_heads/get_sd_code/{division}', [ProjectHeadController::class, 'get_sd_code']);
Route::get('/project_heads/get_so_code/{division}', [ProjectHeadController::class, 'get_so_code']);

Route::get('/project_heads/get_feeder_codes/{division}/{subdivision}/{section}', [ProjectHeadController::class, 'get_feeder_code']);

Route::get('/project_heads/release_meter_report_view/{start_date}/{end_date?}/{division?}/{sub_division?}/{section?}/{feeder_code?}', [ProjectHeadController::class, 'release_meter_report_view']);
Route::get('/project_heads/meter_replacement_report_view/{start_date}/{end_date?}/{division?}/{sub_division?}/{section?}/{feeder_code?}', [ProjectHeadController::class, 'meter_replacement_report_view']);
Route::get('/project_heads/anx_1_detailed_report/{start_date}/{end_date?}/{division?}/{sub_division?}/{section?}', [ProjectHeadController::class, 'anx_1_detailed_report']);
Route::get('/project_heads/anx_1_abstract_report/{start_date}/{end_date?}/{division?}/{sub_division?}/{section?}', [ProjectHeadController::class, 'anx_1_abstract_report']);
Route::get('/project_heads/anx_3_report/{start_date}/{end_date?}/{division?}/{sub_division?}/{section?}', [ProjectHeadController::class, 'anx_3_report']);

Route::get('/project_heads/get-account-id-details', [ProjectHeadController::class, 'get_account_id_details_view']);




Route::get('/project_heads/inventory_report_filter', [ProjectHeadController::class, 'inventory_report_filter_view']);
Route::post('/project_heads/inventory_report_filter', [ProjectHeadController::class, 'inventory_report_filter']);

Route::get('/project_heads/inward_meter_report/{section}/{start_date}/{end_date?}', [ProjectHeadController::class, 'inward_meter_report']);
Route::get('/project_heads/outward_meter_report/{section}/{start_date}/{end_date?}', [ProjectHeadController::class, 'outward_meter_report']);
Route::get('/project_heads/contractor_wise_installation_report/{section}/{start_date}/{end_date?}/{contractor_id?}', [ProjectHeadController::class, 'contractor_wise_installation_report']);
Route::get('/project_heads/qc_report/{section}/{start_date}/{end_date?}', [ProjectHeadController::class, 'qc_report']);
Route::get('/project_heads/fe_wise_installation_report/{section}/{start_date}/{end_date?}', [ProjectHeadController::class, 'fe_wise_installation_report']);

Route::get('project_heads/meter_replacement_statistics', [ProjectHeadController::class, 'meter_replacement_statistics_view']);

Route::get('project_heads/section-wise-inward-installation-report', [ProjectHeadController::class, 'section_wise_inward_installation_report_view']);

Route::get('/project_heads/contractor_wise_stock_report/{section}/{start_date}/{end_date?}/{contractor_id?}', [ProjectHeadController::class, 'contractor_wise_stock_report_view']);

Route::get('project_heads/upload_meter_previous_final_reading', [ProjectHeadController::class, 'upload_previous_final_reading_view']);
//Route::get('project_heads/meter_replacement_statistics_count', [ProjectHeadController::class, 'meter_replacement_statistics_count']);

// ------report new end----

// Route::get('/project_heads', [ProjectHeadController::class, 'login']);
// Route::post('project_heads/authenticate', [ProjectHeadController::class, 'authenticate']);
// Route::get('/project_heads/logout', [ProjectHeadController::class, 'logout']);

// Inventories
// Route::get('/inventories', [InventoryController::class, 'login']);
Route::get('/inventories/add_inventory_executive', [InventoryController::class, 'add_inventory_executive']);
Route::get('/inventories/all_inventory_executives', [InventoryController::class, 'all_inventory_executives']);
// Route::get('/inventories/index', [InventoryController::class, 'index']);
// Route::post('/inventories/authenticate', [InventoryController::class, 'authenticate']);
// Route::get('/inventories/logout', [InventoryController::class, 'logout']);
Route::post('inventories/create_inventory_executive', [InventoryController::class, 'create_inventory_executive']);
// Inventorie Executives
// Route::get('/inventories', [InventoryController::class, 'login']);

// Route::get('/inventory_executives/index', [InventoryController::class, 'inventory_executive_index']);
// Route::get('/inventory_reporters/index', [InventoryController::class, 'inventory_reporter_index']);

Route::get('/inventory_executives/add_inward_genus', function () {
    return view('inventory_executives/add_inward_genus');
});

Route::post('/inventory_executives/create_inward_genus', [InventoryController::class, 'create_inward_genus']);

Route::get('/inventory_executives/add_inward_mrt_rejected', function () {
    return view('inventory_executives/add_inward_mrt_rejected');
});

Route::post('/inventory_executives/create_inward_mrt_rejected', [InventoryController::class, 'create_inward_mrt_rejected']);

// Route::get('/inventory_executives/add_inward_released_em_meter', function () {
//     return view('inventory_executives/add_inward_released_em_meter');
// });

Route::get('/inventory_executives/add_inward_released_em_meter', [InventoryController::class, 'add_inward_released_em_meter']);

Route::post('/inventory_executives/create_inward_released_em_meter', [InventoryController::class, 'create_inward_released_em_meter']);
Route::get('/inventory_executives/add_meter_sl_number_wise/{id}', [InventoryController::class, 'add_meter_sl_number_wise']);

Route::get('/inventory_executives/add_meter_into_box/{id}', [InventoryController::class, 'add_meter_into_box']);

Route::get('/inventory_executives/add_meter_to_stock', [InventoryController::class, 'add_meter_to_stock']);
Route::post('/inventory_executives/add_meter_to_warehouse/{id}', [InventoryController::class, 'add_meter_to_warehouse']);
Route::post('/inventory_executives/update_meter_into_box', [InventoryController::class, 'update_meter_into_box']);

Route::get('/inventory_executives/add_outward_mrt', function () {
    return view('inventory_executives/add_outward_mrt');
});

Route::post('/inventory_executives/create_outward_mrt', [InventoryController::class, 'create_outward_mrt']);

Route::get('/inventory_executives/add_outward_installation', [InventoryController::class, 'add_outward_installation']);

Route::get('get_sd_pincode', [InventoryController::class, 'get_sd_pincode'])->name('get_sd_pincode');
Route::get('get_meter_serial_no', [InventoryController::class, 'get_meter_serial_no'])->name('get_meter_serial_no');


Route::post('/inventory_executives/create_outward_installation', [InventoryController::class, 'create_outward_installation']);








Route::get('/inventory_executives/add_outward_released_em_meter', [InventoryController::class, 'add_outward_released_em_meter']);

Route::post('/inventory_executives/create_outward_released_em_meter', [InventoryController::class, 'create_outward_released_em_meter']);

// Route::get('/inventory_executives/view_inward_genus', function () {
//     return view('inventory_executives/view_inward_genus');});


Route::get('/inventory_executives/view_inward_genus', [InventoryController::class, 'view_inward_genus']);
Route::get('/inventory_executives/view_inward_mrt_rejected', [InventoryController::class, 'view_inward_mrt_rejected']);
Route::get('/inventory_executives/view_inward_released_em_meter', [InventoryController::class, 'view_inward_released_em_meter']);
Route::get('/inventory_executives/view_meter_sl_number_wise', [InventoryController::class, 'view_meter_sl_number_wise']);
Route::get('/inventory_executives/view_outward_mrt', [InventoryController::class, 'view_outward_mrt']);
Route::get('/inventory_executives/view_outward_installation', [InventoryController::class, 'view_outward_installation']);
Route::get('/inventory_executives/view_outward_released_em_meter', [InventoryController::class, 'view_outward_released_em_meter']);

// -------
Route::get('/inventory_executives/indent_form/{box_id}', [InventoryController::class, 'indent_form_view']);
Route::post('/inventory_executives/indent_form/{box_id}', [InventoryController::class, 'indent_form']);
Route::get('/inventory_executives/meter_stocks', [InventoryController::class, 'meter_stocks']);
Route::post('/inventory_executives/meter_stocks_filter', [InventoryController::class, 'meter_stocks_filter']);
Route::get('/inventory_executives/get_so_code/{division}', [InventoryController::class, 'get_so_code']);

Route::get('/inventory_executives/get_box/{division}', [InventoryController::class, 'get_box']);
Route::get('/inventory_executives/add_lot', [InventoryController::class, 'add_lot']);
Route::post('/inventory_executives/create_lot_no', [InventoryController::class, 'create_lot_no']);


Route::post('/inventory_executives/upload_meter/{lot_no}', [InventoryController::class, 'upload_meter']);


// ----
Route::get('/inventory_executives/get_lot/{division}', [InventoryController::class, 'get_lot']);
Route::get('/inventory_executives/get_box_by_lot/{lot}', [InventoryController::class, 'get_box_by_lot']);



// Inventory Reporters

Route::get('/inventory_reporters/annexure1', [InventoryController::class, 'annexure1']);
Route::get('/inventory_reporters/annexure4_list', [InventoryController::class, 'annexure4_list']);
Route::get('/inventory_reporters/annexure3', [InventoryController::class, 'annexure3']);


Route::post('/inventory_reporters/create_annexure1', [InventoryController::class, 'create_annexure1']);

Route::get('/inventory_reporters/annexure4/{id}', [InventoryController::class, 'annexure4']);

Route::post('/inventory_reporters/create_annexure3', [InventoryController::class, 'create_annexure3']);



Route::get('/inventories/reports', [InventoryController::class, 'reports']);
Route::get('/inventories/filter_outward_reports', [InventoryController::class, 'filter_outward_reports']);


//faulty meter route
//getting faulty meter inventory
Route::get('/inventories/faulty_meter_inventories',  [InventoryController::class, 'faulty_meter_inventories_view']);

Route::post('/inventories/check_faulty_meter',  [InventoryController::class, 'check_faulty_meter']);

Route::get('/inventories/edit_faulty_meter/{id}',  [InventoryController::class, 'edit_faulty_meter']);

Route::post('/inventories/update_faulty_meter/{id}', [InventoryController::class, 'update_faulty_meter']);

Route::post('/inventories/update_faulty_meter-unused', [InventoryController::class, 'update_faulty_meter_unused']);

Route::post('/inventories/faulty_meter_inventories', [InventoryController::class, 'faulty_meter_inventories']);

// displaying Faulty meter report
Route::get('/inventories/faulty_meter_report', [InventoryController::class, 'faulty_meter_report']);


//push to unused state
Route::get('/inventories/get_push_unused_state', [InventoryController::class, 'Push_unused_view']);
Route::post('/inventories/Push_serial_number_unused',  [InventoryController::class, 'Push_serial_number_unused']);
Route::post('/inventories/Push_serial_unused_state/{previous_serial_no}/{meter_main_id}', [InventoryController::class, 'used_to_unused']);



// Contractors
// Route::get('/contractors', [ContractorController::class, 'login']);
Route::get('/contractors/add_contractor_executive', [ContractorController::class, 'add_contractor_executive']);
Route::get('/contractors/all_contractor_executives', [ContractorController::class, 'all_contractor_executives']);
Route::get('/contractors/rejected_reports', [ContractorController::class, 'rejected_reports']);
Route::get('/contractors/rejected_report_view/{id}', [ContractorController::class, 'rejected_report_view']);
Route::get('/contractors/add_outward_installation', [ContractorController::class, 'add_outward_installation']);


Route::get('/contractors/index', [ContractorController::class, 'index']);
// Route::post('/contractors/authenticate', [ContractorController::class, 'authenticate']);
// Route::get('/contractors/logout', [ContractorController::class, 'logout']);
Route::post('contractors/create_contractor_executive', [ContractorController::class, 'create_contractor_executive']);
Route::get('/contractors/delete_status_data/{id}', [ContractorController::class, 'delete_status_data']);


Route::get('/contractors/reports', [ContractorController::class, 'reports']);

// Qcs
// Route::get('/qcs', [QcController::class, 'login']);
Route::get('/qcs/add_qc_executive', [QcController::class, 'add_qc_executive']);
Route::get('/qcs/all_qc_executives', [QcController::class, 'all_qc_executives']);
Route::get('/qcs/qc_view', [QcController::class, 'qc_view']);
Route::get('/qcs/qc_view_detail/{id}', [QcController::class, 'qc_view_detail']);
Route::get('/qcs/edit_qc_report/{id}', [QcController::class, 'edit_qc_report']);
Route::get('/qcs/edit_qc_detail/{id}', [QcController::class, 'edit_qc_detail']);
Route::get('/qcs/index', [QcController::class, 'index']);
// Route::post('/qcs/authenticate', [QcController::class, 'authenticate']);
// Route::get('/qcs/logout', [QcController::class, 'logout']);
Route::post('qcs/bulk_approve_qcs_report', [QcController::class, 'bulk_approve_qcs_report']);
Route::post('qcs/create_qc_executive', [QcController::class, 'create_qc_executive']);
Route::post('/qcs/reject_qc_reports_status/{id}', [QcController::class, 'reject_qc_reports_status']);
Route::post('/qcs/approve_qc_reports_status/{id}', [QcController::class, 'approve_qc_reports_status']);
Route::post('/qcs/update_qc_report/{id}', [QcController::class, 'update_qc_report']);
Route::get('/qcs/approved_meter_reports', [QcController::class, 'approved_meter_reports']);
Route::get('/qcs/rejected_meter_reports', [QcController::class, 'rejected_meter_reports']);
Route::post('/qcs/preview_qc_reports_status/{id}', [QcController::class, 'preview_qc_reports_status']);
Route::get('/qcs/preview_meter_reports', [QcController::class, 'preview_meter_reports']);
Route::post('/qcs/revoke_qc_status/{id}', [QCController::class, 'revokeQCStatus']);
Route::post('/qcs/preview_qc_reports_status/{id}', [QcController::class, 'preview_qc_reports_status']);
Route::post('/qcs/revoke_qc_status/{id}', [QCController::class, 'revokeQCStatus']);



Route::get('/qcs/reports', [QcController::class, 'reports']);
Route::get('/qcs/executive_reports', [QcController::class, 'executive_reports']);

Route::get('/qcs/qc_executive_index', [QcController::class, 'qc_executive_index']);

//date change methods
Route::get('/qcs/dateReplacement', function () {
    if (Session::has('rexkod_vishvin_auth_name')) 
    {
        $userType = Session::get('rexkod_vishvin_auth_user_type');     
     
          if ($userType === "project_head" || $userType === "qc_executive" || $userType === "qc_manager") 
		{
           
            return (new QcController())->date_replacement();
        } else {
        
            return Redirect::to('/');
        }
    } else {
       
        return Redirect::to('/');
    }
});
Route::post('/qcs/date_change', [QcController::class, 'date_change']);


//Final Reading change methods
Route::get('/qcs/frchange', function () {
    if (Session::has('rexkod_vishvin_auth_name')) 
    {
        $userType = Session::get('rexkod_vishvin_auth_user_type');     
     
        if ($userType === "project_head") 
        {         
            return (new QcController())->final_reading_change();
        } else {
        
            return Redirect::to('/');
        }
    } else {
       
        return Redirect::to('/');
    }
});

Route::post('/qcs/final_meter_reading_change', [QcController::class, 'final_meter_reading_search']);
//fr updation
Route::post('/qc/final-reading-updation-method/{id}', [QcController::class, 'final_meter_reading_update']);




//SP id Edit functionalities
Route::get('/hescoms/meter_mains_edit', function () {
    if (Session::has('rexkod_vishvin_auth_name')) 
    {
        $userType = Session::get('rexkod_vishvin_auth_user_type');     
     
        if ($userType === "aao" || $userType === "project_head" || $userType === "admin") 
        {         
            return (new HescomController())->sp_id_change();
        } else {
        
            return Redirect::to('/');
        }
    } else {
       
        return Redirect::to('/');
    }
});

//consumer details update
Route::get('/hescoms/consumer_details_update', function () {
    if (Session::has('rexkod_vishvin_auth_name')) 
    {
        $userType = Session::get('rexkod_vishvin_auth_user_type');     
     
        if ($userType === "aao" || $userType === "project_head") 
        {         
            return (new HescomController())->consumer_details_getdetails();
        } else {
        
            return Redirect::to('/');
        }
    } else {
       
        return Redirect::to('/');
    }
});


//meter serial ownerShip functionalities
Route::get('/hescoms/serial_number_ownership', function () {
    if (Session::has('rexkod_vishvin_auth_name')) 
    {
        $userType = Session::get('rexkod_vishvin_auth_user_type');     
     
        if ($userType === "project_head") 
        {         
            return (new HescomController())->serial_number_ownership();
        } else {
        
            return Redirect::to('/');
        }
    } else {
       
        return Redirect::to('/');
    }
});


Route::post('/hescoms/push_to_vishvin_search', [HescomController::class, 'push_to_vishvin_search']);


Route::get('/meter_ownership/user_serial/{id}', [HescomController::class, 'unused_serial_dropdown']);

Route::post('/hescoms/serial_no_search', [HescomController::class, 'serial_no_search']);

Route::post('/meter_ownership/serial_update/{id}', [HescomController::class, 'meterOwnerShip_update']);


Route::post('/hescoms/sp_id_search', [HescomController::class, 'sp_id_search']);

Route::post('/hescoms/consumer_accounts_search', [HescomController::class, 'consumer_accounts_search']);

Route::post('/hescoms/consumer_details_update/{id}', [HescomController::class, 'consumer_details_final_update']);

//Route::post('/qc/sp_id_update/{id}', [HescomController::class, 'sp_id_update']);

Route::post('/qc/sp_id_update/{id}/{meter_id}', [HescomController::class, 'sp_id_update']);

//push to Vishvin functionalities
Route::get('/hescoms/push_to_vishvin_qc', function () {
    if (Session::has('rexkod_vishvin_auth_name')) 
    {
        $userType = Session::get('rexkod_vishvin_auth_user_type');     
     
        if ($userType === "aao" || $userType === "project_head") 
        {         
            return (new HescomController())->push_to_vishvin_qc();
        } else {
        
            return Redirect::to('/');
        }
    } else {
        return Redirect::to('/');
    }
});

// Route to handle Push to Vishvin QC action
Route::post('/push_to_vishvin_qc_account_status/{id}', [HescomController::class, 'pushToVishvinQC']);

Route::get('/account/search', [HescomController::class, 'searchAccountIds']);



// Hescoms
// Route::get('/hescoms', [HescomController::class, 'login']);
Route::get('/hescoms/all_consumers', [HescomController::class, 'all_consumers']);
Route::get('/hescoms/add_hescom_executive', [HescomController::class, 'add_hescom_executive']);
Route::get('/hescoms/all_hescom_executives', [HescomController::class, 'all_hescom_executives']);
Route::get('/hescoms/hescom_view', [HescomController::class, 'hescom_view']);
Route::get('/hescoms/hescom_view_detail/{id}', [HescomController::class, 'hescom_view_detail']);
Route::get('/hescoms/hescom_edit_detail/{id}', [HescomController::class, 'hescom_edit_detail']);
Route::post('/hescoms/hescom_update_detail/{id}', [HescomController::class, 'hescom_update_detail']);
Route::get('/hescoms/approved_meter_reports', [HescomController::class, 'approved_meter_reports']);
Route::get('/hescoms/rejected_meter_reports', [HescomController::class, 'rejected_meter_reports']);
Route::post('/hescoms/reject_so_reports_status/{id}', [HescomController::class, 'reject_so_reports_status'])->name('reject_so_reports_status');
Route::post('/hescoms/reject_aee_reports_status/{id}', [HescomController::class, 'reject_aee_reports_status'])->name('reject_aee_reports_status');
Route::post('/hescoms/reject_aao_reports_status/{id}', [HescomController::class, 'reject_aao_reports_status'])->name('reject_aao_reports_status');


Route::get('/hescoms/index', [HescomController::class, 'index']);
Route::get('/hescoms/aee_index', [HescomController::class, 'aee_index']);
Route::get('/hescoms/ae_index', [HescomController::class, 'ae_index']);
Route::get('/hescoms/aao_index', [HescomController::class, 'aao_index']);
Route::post('/hescoms/index', [ProjectHeadController::class, 'hescom_status']);

Route::get('/hescoms/count/{divisionId?}/{subDivisionId?}/{sectionId?}', [HescomController::class, 'hescomCount']);


// Route::post('/hescoms/authenticate', [HescomController::class, 'authenticate']);
// Route::get('/hescoms/logout', [HescomController::class, 'logout']);
Route::post('hescoms/create_hescom_executive', [HescomController::class, 'create_hescom_executive']);
Route::post('/hescoms/update_so_reports_status/{id}', [HescomController::class, 'update_so_reports_status']);

Route::post('/hescoms/update_aee_reports_status/{id}', [HescomController::class, 'update_aee_reports_status']);

Route::post('/hescoms/update_aao_reports_status/{id}', [HescomController::class, 'update_aao_reports_status']);

Route::post('/hescoms/approve_aao_reports_status/{id}', [HescomController::class, 'approve_aao_reports_status']);
Route::post('/hescoms/approve_so_reports_status/{id}', [HescomController::class, 'approve_so_reports_status']);
Route::post('/hescoms/approve_aee_reports_status/{id}', [HescomController::class, 'approve_aee_reports_status']);
Route::post('hescoms/bulk_approve_hescoms_report', [HescomController::class, 'bulk_approve_hescoms_report']);

Route::get('/hescoms/error_reports', [HescomController::class, 'error_reports']);
Route::get('/hescoms/edit_error_reports/{id}', [HescomController::class, 'edit_error_reports']);
Route::post('hescoms/update_error_reports/{id}', [HescomController::class, 'update_error_reports']);

Route::post('hescoms/update_error_status', [HescomController::class, 'update_error_status']);

// Pages
Route::get('/pages/index', [PageController::class, 'index'])->name('index');
Route::get('/pages/add_old_meter_detail/{id}', [PageController::class, 'add_old_meter_detail']);
Route::get('/pages/add_meter_first_step', [PageController::class, 'add_meter_first_step']);
Route::get('/pages/add_new_meter_detail/{id}', [PageController::class, 'add_new_meter_detail']);
Route::get('/pages/home', [PageController::class, 'home']);
Route::get('/pages/login2', [PageController::class, 'login2']);

// Route::get('/pages/home', 'PageController@index')->name('pages.home');


Route::get('/pages/records', [PageController::class, 'records']);
Route::get('/pages/records2', [PageController::class, 'records2']);
Route::get('/pages/account', [PageController::class, 'account']);
Route::get('/pages', [PageController::class, 'login'])->name('pages_login');;
Route::post('/pages/authenticate', [PageController::class, 'authenticate']);
Route::post('pages/check_rr_number', [PageController::class, 'check_rr_number']);
// Route::post('user_location', [PageController::class, 'user_location']);
Route::post('/user_location', [App\Http\Controllers\PageController::class, 'storeUserLocation'])->name('user_location');
Route::get('pages/location_fetch/{lat}/{lon}', [PageController::class, 'location_fetch']);
Route::get('pages/load_location', [PageController::class, 'load_location']);
Route::get('pages/location', [PageController::class, 'location']);
Route::get('pages/new_location', [PageController::class, 'new_location']);
Route::post('pages/new_location', 'PageController@storeUserLocation')->name('storeUserLocation');

Route::get('/pages/logout', [PageController::class, 'logout']);


Route::post('/pages/update_old_meter_detail/{id}', [PageController::class, 'update_old_meter_detail']);
Route::post('/pages/update_new_meter_detail/{id}', [PageController::class, 'update_new_meter_detail']);

Route::get('pages/load_current_location', [PageController::class, 'load_current_location']);
Route::get('pages/current_location_fetch/{lat}/{lon}', [PageController::class, 'current_location_fetch']);


//suvey executives
Route::get('/Survey/index', [SurveyController::class, 'index'])->name('index');
Route::get('/Survey/add_old_meter_detail/{id}', [SurveyController::class, 'add_old_meter_survey_detail']);
//for bulk upload
Route::get('/Survey/add_old_meter_detail_bulk/{instance}', [SurveyController::class, 'add_old_survey_details_bulk']);
Route::get('/Survey/add_meter_first_step', [SurveyController::class, 'add_meter_first_step']);
Route::get('/Survey/add_new_meter_detail/{id}', [SurveyController::class, 'add_new_meter_detail']);
Route::get('/Survey/home', [SurveyController::class, 'home']);
Route::get('/Survey/login2', [SurveyController::class, 'login2']);
// Route::get('/pages/home', 'PageController@index')->name('pages.home');
Route::get('/Survey/records', [SurveyController::class, 'records']);
Route::get('/Survey/records2', [SurveyController::class, 'records2']);
Route::get('/Survey/account', [SurveyController::class, 'account']);
Route::get('/Survey', [SurveyController::class, 'login'])->name('pages_login');;
Route::post('/Survey/authenticate', [SurveyController::class, 'authenticate']);
Route::post('Survey/check_rr_number', [SurveyController::class, 'check_rr_number']);
Route::post('Survey/check_ack_number', [SurveyController::class, 'check_ack_number']);
// Route::post('user_location', [PageController::class, 'user_location']);
Route::post('/user_location', [App\Http\Controllers\SurveyController::class, 'storeUserLocation'])->name('user_location');
Route::get('Survey/location_fetch/{lat}/{lon}', [SurveyController::class, 'location_fetch']);
Route::get('Survey/load_location', [SurveyController::class, 'load_location']);
Route::get('Survey/location', [SurveyController::class, 'location']);
Route::get('Survey/new_location', [SurveyController::class, 'new_location']);
Route::post('Survey/new_location', 'PageController@storeUserLocation')->name('storeUserLocation');
Route::get('/Survey/logout', [SurveyController::class, 'logout']);
Route::post('/Survey/update_old_meter_detail/{id}', [SurveyController::class, 'update_old_meter_survey_detail']);
Route::post('/Survey/update_new_meter_detail/{id}', [SurveyController::class, 'update_new_meter_detail']);
Route::get('Survey/load_current_location', [SurveyController::class, 'load_current_location']);
Route::get('/Survey/current_location_fetch/{lat}/{lon}', [SurveyController::class, 'current_survey_location_fetch']);
Route::get('/getAccountSuggestions/{account_id}/{section_code}', [SurveyController::class, 'getAccountSuggestions']);

// bmr





Route::get('/bmrs/bmr_report', [BmrController::class, 'bmr_report']);
Route::get('/bmrs/successfull_records', [BmrController::class, 'successfull_records']);
Route::post('/bmrs/upload_successfull_records', [BmrController::class, 'upload_successfull_records']);

Route::get('/bmrs/error_records', [BmrController::class, 'error_records']);
Route::post('/bmrs/upload_error_records', [BmrController::class, 'upload_error_records']);

Route::post('/bmrs/download_excel', [BmrController::class, 'download_excel']);
Route::post('/bmrs/downloaded_batch', [BmrController::class, 'downloaded_batch']);
Route::get('/bmrs/bmr_report_single/{flag}', [BmrController::class, 'bmr_report_single']);
Route::get('/bmrs/successfull_report', [BmrController::class, 'successfull_report']);
Route::get('/bmrs/successfull_report_single/{account_id}', [BmrController::class, 'successfull_report_single']);


Route::post('/inventory_executives/delete_meter_from_box', [InventoryController::class, 'deleteMeterFromBox']);



// index pages
Route::get('inventories/inventory_manager_index', [InventoryController::class, 'inventory_manager_index']);
Route::get('inventories/inventory_executive_index', [InventoryController::class, 'inventory_executive_index']);
Route::get('inventories/inventory_reporter_index', [InventoryController::class, 'inventory_reporter_index']);

Route::get('bmrs/index', [BmrController::class, 'index']);


// change upload file name
Route::get('/admins/change_file', [AdminController::class, 'change_file']);
Route::get('/admins/welcome', [AdminController::class, 'welcome']);

// app apis

Route::post('/pages/authenticate_api', [PageController::class, 'authenticate_api']);


Route::get('/compress-images', [ImageCompressionController::class, 'compress']);

Route::get('/check-images-availability', [PageController::class, 'check_images_availability_api']);

Route::get('/update-account-id', [ProjectHeadController::class, 'update_account_id']);


Route::get('/fetch-image', [ProjectHeadController::class, 'showForm']);

Route::post('/fetch-image', [ProjectHeadController::class, 'showImage']);


//mobile app api
//fetch section code list done
Route::middleware('api')->get('/mobile-app/api/section_codes', [PageController::class, 'getSectionCodes']);
//fetch old meter details pass by section code and that account id should not be installed at meter mains is
Route::middleware('api')->get('/mobile-app/api/old_meter_get', [PageController::class, 'old_meter_get']);
// Define the route for the Fe_installed_records function
Route::get('/mobile-app/api/installed_records/{fieldExecutiveId}', [PageController::class, 'Fe_installed_records']);

//account id search
Route::get('/mobile-app/api/account_id_rr_no/search', [PageController::class, 'account_rr_no_search']);

//accounts id fetch
Route::get('/mobile-app/api/section/fetch', [PageController::class, 'accounts_details_so_details']);
