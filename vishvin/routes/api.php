<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectHeadController;
use App\Http\Controllers\ImageCompressionController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\FieldExecutiveController as APIFieldExecutiveController;
use App\Http\Controllers\API\ContractorMangerController as APIcontractorManagerController;
use App\Http\Controllers\API\MeterMainController as APIMetermainController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::controller(RegisterController::class)->group(function () {
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/user-test', function (Request $request) {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $request->user();
    });
    Route::post('/update-consumer', [APIFieldExecutiveController::class, 'upadteConsumer']);

    //fetch new meter dropdown value from the un-used  list
    Route::get('/fetch-new-meter', [APIcontractorManagerController::class, 'fetchnewserial']);

    //old meter upload post method
    Route::post('/old-meter-upload', [APIMetermainController::class, 'upload_old_meter']);


        //new meter upload post method
    Route::post('/new-meter-upload', [APIMetermainController::class, 'upload_new_meter']);

 
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// api
Route::post('/pages/authenticate_api', [PageController::class, 'authenticate_api']);
Route::get('/pages/add_meter_first_step', [PageController::class, 'add_meter_first_step']);
Route::post('/pages/check_rr_number_api', [PageController::class, 'check_rr_number_api']);
Route::get('/pages/add_old_meter_detail_api/{id}', [PageController::class, 'add_old_meter_detail_api']);
Route::post('/pages/update_old_meter_detail_api/{id}', [PageController::class, 'update_old_meter_detail_api']);
Route::get('/pages/add_new_meter_detail_api/{id}', [PageController::class, 'add_new_meter_detail_api']);
Route::post('/pages/update_new_meter_detail_api/{id}', [PageController::class, 'update_new_meter_detail_api']);
Route::get('/pages/home_api', [PageController::class, 'home_api']);
Route::get('/pages/logout_api', [PageController::class, 'logout_api']);
Route::get('pages/location_api', [PageController::class, 'location_api']);

Route::get('/compress-all-images-backup', [ImageCompressionController::class, 'compress_all_images_backup']);

Route::get('/get-un-accounted-accounts', [\App\Http\Services\MeterMainService::class, 'compress_all_images_backup']);

Route::get('/get-section-codes', [\App\Http\Services\ZoneCodeService::class, 'getSectionCodes']);

Route::get('/get-total-indents-section-wise', [\App\Http\Services\IndentService::class, 'getTotalQuantitiesSectionWise']);

Route::get('/get-section-wise-inward-installation-report', [ProjectHeadController::class, 'section_wise_inward_installation_report']);
Route::get('/get-contractor-wise-stock-report', [ProjectHeadController::class, 'contractor_wise_stock_report']);

Route::post('/pages/check-new-meter-serial', [PageController::class, 'check_new_meter_serial']);

Route::get('/project-heads/check-unused-meter-serial', [ProjectHeadController::class, 'check_unused_meters_in_meter_mains']);

Route::get('/project_heads/download_bmr_status_success_report/{start_date?}/{end_date?}/{division?}/{sub_division?}/{section?}/{feeder_code?}', [ProjectHeadController::class, 'download_bmr_successful_records']);
Route::get('/project_heads/download_meter_replacement_report/{start_date?}/{end_date?}/{division?}/{sub_division?}/{section?}/{feeder_code?}', [ProjectHeadController::class, 'download_meter_replacement_records']);
Route::get('/project_heads/download_release_meter_report/{start_date?}/{end_date?}/{division?}/{sub_division?}/{section?}/{feeder_code?}', [ProjectHeadController::class, 'download_release_meter_records']);
Route::get('/project_heads/download_bmr_status_success_report', [ProjectHeadController::class, 'download_bmr_successful_records']);
Route::get('/project_heads/bmr_status_success_report_data', [ProjectHeadController::class, 'bmr_status_success_report_data']);
Route::get('/project_heads/release_meter_report_data', [ProjectHeadController::class, 'release_meter_report_data']);
Route::get('/project_heads/meter_replacement_report_data', [ProjectHeadController::class, 'meter_replacement_report_data']);

Route::get('/bmr/update-success-records-from-backup', [\App\Http\Services\BmrDownloadService::class, 'updateOldRecordsUsingBackUpData']);

Route::get('/project_heads/download_meter_mains_success_report/{start_date?}/{end_date?}/{division?}/{sub_division?}/{section?}/{feeder_code?}', [ProjectHeadController::class, 'download_meter_mains_successful_records']);

Route::post('/project_heads/upload_previous_final_reading',[ProjectHeadController::class,'upload_previous_final_reading']);

Route::get('/images/move-image-files/{from_date}/{to_date}',[ImageCompressionController::class,'moveToVishvinData']);


// Route::get('/pages/index', [PageController::class, 'index'])->name('index');
// Route::get('/pages/login2', [PageController::class, 'login2']);
// Route::get('/pages/records', [PageController::class, 'records']);
// Route::get('/pages/records2', [PageController::class, 'records2']);
// Route::get('/pages/account', [PageController::class, 'account']);
// Route::get('/pages', [PageController::class, 'login'])->name('pages_login');;
// Route::post('/user_location', [App\Http\Controllers\PageController::class, 'storeUserLocation'])->name('user_location');
// Route::get('pages/location_fetch/{lat}/{lon}', [PageController::class, 'location_fetch']);
// Route::get('pages/load_location', [PageController::class, 'load_location']);
// Route::get('pages/new_location', [PageController::class, 'new_location']);
// Route::post('pages/new_location', 'PageController@storeUserLocation')->name('storeUserLocation');
//


