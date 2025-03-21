<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdateOldMeterDetailRequest;
use App\Http\Controllers\PageController;
use App\Http\Services\ConsumerDetailService;
use App\Http\Services\MeterMainService;
use App\Models\Admin;
use App\Models\SurveyMain;
use App\Models\Meter_final_reading;
use App\Models\Meter_main;
use Illuminate\Support\Str;
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

class SurveyController extends Controller
{
    //

      // known functionality codes
      public function index()
      {
          return view('survey.index');
      }
  
      public function login()
      {
          if (session()->get('rexkod_pages_id')) {
              return redirect('Survey/home');
          } else {
              return view('survey.login');
          }
      }
  
  
      /* test   process authentivcation is */
  
      public function authenticate(Request $req)
      {
          // return($req->all());
          $user = Admin::where('phone', $req->phone)->first();
          // return($req->all());
          if ($user && Hash::check($req->password, $user->password) && $user->type == "survey_executive") {
              Session::put('rexkod_pages_name', $user->name);
              Session::put('rexkod_pages_id', $user->id);
              Session::put('rexkod_pages_user_phone', $user->phone);
              Session::put('rexkod_pages_uesr_type', $user->type);
              return redirect('Survey/home');
          } else {
              session()->put('failed', 'Invalid credentials');
              return redirect('/Survey');
          }
      }   
     
     /* public function authenticate(Request $request)
      {
          // Validate incoming request data
          $request->validate([
              'phone' => 'required|string',
              'password' => 'required|string',
          ]);
      
          // Attempt to find the user by phone number
          $user = Admin::where('phone', $request->phone)->first();
      
          // Check if the user exists, their password is correct, and their type is 'field_executive'
          if ($user && Hash::check($request->password, $user->password) && $user->type === "field_executive") {
              // Store user information in the session
              session([
                  'rexkod_pages_name' => $user->name,
                  'rexkod_pages_id' => $user->id,
                  'rexkod_pages_user_phone' => $user->phone,
                  'rexkod_pages_user_type' => $user->type,
              ]);
      
              // Return a success response with user details
              return response()->json([
                  'status' => 'success',
                  'message' => 'Authentication successful',
                  'data' => [
                      'name' => $user->name,
                      'id' => $user->id,
                      'phone' => $user->phone,
                      'type' => $user->type,
                  ]
              ], 200);
          } elseif (!$user) {
              // Return a 404 if the user does not exist
              return response()->json([
                  'status' => 'error',
                  'message' => 'User not found',
              ], 404);
          } else {
              // If authentication fails, return an error response
              return response()->json([
                  'status' => 'error',
                  'message' => 'Invalid credentials',
              ], 401);
          }
      } */
         
      public function home(Request $request, MeterMainService $meter_main_service)
      {
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
  
          $fieldExecutiveId = session()->get('rexkod_pages_id');
  
          $meter_main = $meter_main_service->getSurveyMeterMainsCreatedByFieldExecutiveId($fieldExecutiveId);
  
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
  
          return view('survey.home', compact('data'));
      }
  
     /* public function check_rr_number(Request $req, ConsumerDetailService $consumer_detail_service, MeterMainService $meter_main_service)
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
                  $consumer_detail_column_list_data = array("
                  
                  
                  ");
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
      } */
  
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
                    return redirect('/Survey/add_meter_first_step');
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
                    return redirect('/Survey/add_meter_first_step');
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
                return redirect('/Survey/add_meter_first_step');
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
                  // Assuming $meter_main->id contains the id you're looking for
                     $surveyMain = SurveyMain::where('id', $meter_main->id)->first();

                    // dd($surveyMain);
//                $meter_main-> = 0;
//                $meter_main->save();

                    return redirect('/Survey/add_old_meter_detail/' . $meter_main->id);
                } elseif ((($meter_main->qc_status == 1) && ($meter_main->so_status == 1) && ($meter_main->aee_status == 1) && ($meter_main->aao_status == 1))) {
                    session()->put('success', 'Meter already approved');
                    return redirect('/Survey/add_meter_first_step');
                } 
                /*elseif ($surveyMain->survey_status == 1)
                {

                } */
   
                else {
                    session()->put('failed', 'Meter status under progress');
                    return redirect('/Survey/add_meter_first_step');
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

                return redirect('/Survey/add_old_meter_detail/' . $meter_main->id);

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


    public function check_ack_number(Request $req, ConsumerDetailService $consumer_detail_service, MeterMainService $meter_main_service)
    {
        if (empty(session()->get('rexkod_pages_id'))) {
            return redirect('/Survey')->with('message', 'You have been logged out!');
        }
        else{
           //  dd($req->account_id);
            // delete_flag
            // the pages has been provided with edit page too, in future they can edit the meter reading page also.
            // first case: its present in consumer_detail

            // consumer detail can be search by either accoount_id or rr_number -- added by ashutosh
            if ($req->account_id) {
                $check_consumer_detail_filter_data = array(
                    "account_id" => $req->account_id,
                );

               // dd($check_consumer_detail_filter_data);
                $check_consumer_detail_column_list_data = array("account_id");
                $check_consumer_detail = $consumer_detail_service->getSingleConsumerDetailsByFilterbulk($check_consumer_detail_filter_data, $check_consumer_detail_column_list_data);
               
               // dd($check_consumer_detail);

                if (empty($check_consumer_detail)) {
                    session()->put('failed', 'Account ID Doesnt Exist in Consumer Detail');
                    return redirect('/Survey/add_meter_first_step');
                }

                $consumer_detail_filter_data = array(
                    "account_id" => $req->account_id,
                    "so_pincode" => $req->section_code
                );
                $consumer_detail_column_list_data = array("account_id");

               // dd($consumer_detail_column_list_data);
                
                $consumer_detail = $consumer_detail_service->getSingleConsumerDetailsByFilterbulk($consumer_detail_filter_data, $consumer_detail_column_list_data);
             // dd($check_consumer_detail);

                if ($check_consumer_detail) {

                                    // Assuming $check_consumer_detail is a collection of items
                    // Extract account_id values from the $check_consumer_detail collection
                    $account_ids = $check_consumer_detail->pluck('account_id')->toArray();

                    // Now create the filter array for the meter main query
                    $meter_main_filter_data = array(
                        "account_id" => $account_ids, // This will contain an array of account_id values
                    );

                   // dd($meter_main_filter_data);

                    // You can now pass this filter data to your service method
                    $meter_main = $meter_main_service->getSingleSurveyMeterMainsByFilter($meter_main_filter_data);
                    //dd($meter_main);
                  
                }
                
            } 
          //  
          


            // $consumer_detail = Consumer_detail::where('account_id', $req->account_id)->first();
            // $meter_main = Meter_main::where('account_id', $req->account_id)->first();
            // $count = 0;
            // foreach ($meter_main as $individual_meter_main) {
            //     $count++;
            // }


            if (!($consumer_detail)) {
                if ($req->rr_number) session()->put('failed', 'RR number and Section Code Doesnt Match');
                if ($req->account_id) session()->put('failed', 'Account ID and Section Code Doesnt Match');
                return redirect('/Survey/add_meter_first_step');
            }

           // dd($meter_main);
            
                // Check if $meter_main is an instance, not a collection
                if (!empty($meter_main) && !$meter_main->isEmpty() && $meter_main->count() == 1) {
                    $meter_main = $meter_main->first(); // If it's a collection, we take the first item

                    // Scenario where survey is incomplete (survey_status is 0)
                    if (empty($meter_main->lat) && empty($meter_main->lon)) {
                        if (
                            ($meter_main->lat == null) &&
                            ($meter_main->lon == null) &&
                            ($meter_main->image_1_old == null) &&
                            ($meter_main->image_2_old == null) &&
                            ($meter_main->image_3_old == null)
                        ) {
                            $save_column_list_data = array(
                                "allocation_flag" => 0,
                                "created_by" => session()->get('rexkod_pages_id'),
                                "created_at" => now() // Use Laravel's helper for current timestamp
                            );

                            $meter_main = $meter_main_service->updateSurveyMainData($meter_main->id, $save_column_list_data);

                            return redirect('/Survey/add_old_meter_detail/' . $meter_main->id);
                        } else {
                            session()->put('failed', 'Meter Survey already done');
                            return redirect('/Survey/add_meter_first_step');
                        }
                    }
                    // Scenario where survey is completed (survey_status is 1)
                    elseif (!empty($meter_main->lat) && !empty($meter_main->lon)) {
                        session()->put('success', 'Meter Survey already done');
                        return redirect('/Survey/add_meter_first_step');
                    }
                    else {
                        session()->put('failed', 'Meter survey incomplete or under progress');
                        return redirect('/Survey/add_meter_first_step');
                    }
                } 
                else {
                    // If $meter_main is a collection with multiple items or empty
               
                  //  dd($check_consumer_detail);
                
                      // Initialize an array to collect all the created instances of SurveyMain
    $created_instances = [];

    if (!empty($check_consumer_detail)) {
        // Initialize an array to hold created instances
        $created_instances = [];
    
        // Loop through each account_id and create a new record for each
        foreach ($check_consumer_detail as $consumer_detail) {
            // Prepare data for saving the record
            $save_column_list_data = [
                "account_id" => $consumer_detail->account_id, // Use each account_id
                "created_by" => session()->get('rexkod_pages_id'),
                "created_at" => now() // Use Laravel's helper for current timestamp
            ];
    
            // Call saveSurveyMainData for each account
            $meter_main = $meter_main_service->saveSurveyMainData($save_column_list_data);
    
            // Store the created instance in the array
            $created_instances[] = $meter_main->id; // Save only the id of the created instance
        }
    
        // After all accounts are processed, redirect to the next page
        if (!empty($created_instances)) {
            // Implode the IDs into a comma-separated string
            $ids = implode(',', $created_instances);
    
            // Redirect with the created instance IDs in the URL
            return redirect('/Survey/add_old_meter_detail_bulk/' . $ids);
        }
    
        // If no created instances (which should not happen here)
        return response()->json([
            'status' => 'error',
            'message' => 'No instances were created'
        ]);
    } else {
        // Handle case where $check_consumer_detail is empty
        session()->put('failed', 'Consumer details are missing or invalid');
        return redirect('/Survey/add_meter_first_step');
    }
    
                    
                }
                

            
        
        
        }
    }
  
  
      /*public function add_meter_first_step()
      {
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
          $consumer_detail_service = new ConsumerDetailService();
          $so_pin_codes = $consumer_detail_service->getDistinctSoPincode();


          return view('survey.add_meter_first_step', ['so_pincodes' => $so_pin_codes]);
      }  */

      public function add_meter_first_step() 
{
    // Check if the session 'rexkod_pages_id' is empty and return a message if so
    if (empty(session()->get('rexkod_pages_id'))) {
        return redirect('/Survey')->with('message', 'You have been logged out!');
    }

    // Initialize ConsumerDetailService to fetch distinct so_pincode
    $consumer_detail_service = new ConsumerDetailService();
    $so_pin_codes = $consumer_detail_service->getDistinctSoPincode();

    // Initialize an empty array to hold the consumer details
    $consumer_details = [];

                // Query with alias
                $so_pin_codes = DB::table('consumer_details')
                ->distinct()
                ->select('so_pincode as so_code')
                ->get();

              //  dd($so_pin_codes);

                    // Loop with correct access to 'so_code'
                    foreach ($so_pin_codes as $so_pin_code) {
                    $details = DB::table('consumer_details')
                        ->where('so_pincode', $so_pin_code->so_code) // Corrected property name
                        ->select('account_id', 'rr_no', 'consumer_name', 'consumer_address')
                        ->get();

                  //  dd($details);

                    $consumer_details[$so_pin_code->so_code] = $details;
                    }
    // Return the view with the consumer details and distinct pincode data
    return view('survey.add_meter_first_step', [
        'so_pincodes' => $so_pin_codes,
        'consumer_details' => $consumer_details
    ]);
}


public function getAccountSuggestions($account_id, $section_code)
{
    // Fetch accounts that match the given 5-digit account ID and section_code matches so_pincode
    $suggestions = Consumer_detail::where('account_id', 'like', "{$account_id}%")
                                 ->where('so_pincode', $section_code) // Match section_code with so_pincode
                                 ->select('account_id', 'consumer_name')
                                 ->limit(5) // Limit to 5 suggestions
                                 ->get();

    if ($suggestions->isNotEmpty()) {
        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    } else {
        return response()->json([
            'success' => false,
            'suggestions' => [],
        ]);
    }
}


  
      public function add_old_meter_detail($id, MeterMainService $meter_main_service, ConsumerDetailService $consumer_detail_service)
      {
  
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
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
  
          return view('Survey.add_old_meter_detail', ['data' => $data]);
      }

      public function add_old_meter_survey_detail($id, MeterMainService $meter_main_service, ConsumerDetailService $consumer_detail_service)
      {
  
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
  
          $meter_main = $meter_main_service->getSurveyMainByIds($id);
  
          $get_consumer_detail = $consumer_detail_service->getConsumerDetailByAccountId($meter_main->account_id);
  
         
  
          $data = [
              'meter_main' => $meter_main,
              'get_consumer_detail' => $get_consumer_detail,
            //  'meter_previous_final_reading' => $meter_previous_final_reading,
              'id' => $id,
          ];
         // dd($data);
  
          return view('Survey.add_old_meter_detail', ['data' => $data]);
      }
      public function add_old_survey_details_bulk($instance, MeterMainService $meter_main_service, ConsumerDetailService $consumer_detail_service)
      {
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
      
          // Assuming $instance is a comma-separated string like "33,34,35"
          $instances = explode(',', $instance); // Convert string to an array
      
          // Fetch all SurveyMain records based on the instances
          $meter_mains = $meter_main_service->getSurveyMainByIds($instances);
      
          // Fetch consumer details for each account_id in the meter_mains
          $consumer_details = [];
          foreach ($meter_mains as $meter_main) {
              $consumer_details[] = $consumer_detail_service->getConsumerDetailByAccountId($meter_main->account_id);
          }
      
          // Pass all the data to the view
          $data = [
              'meter_mains' => $meter_mains,        // Array of SurveyMain records
              'consumer_details' => $consumer_details, // Corresponding Consumer details
              'instances' => $instances,             // The instances passed in
          ];

        // dd($data);
      
          return view('survey.add_old_meter_details_bulk', ['data' => $data]);
      }
      public function update_old_meter_survey_detail($ids, Request $request, MeterMainService $meter_main_service): RedirectResponse
      {
          // Check if the session has 'rexkod_pages_id' to ensure the user is logged in
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
      
          // Retrieve the data from the request
          $data = $request->all();
      
         // dd($data); // For debugging, you can remove this later
      
          // Loop through each meter ID and update it
          $idArray = explode(',', $ids);
      
          foreach ($idArray as $id) {
              // Retrieve the meter main data by ID
              $meter_main = $meter_main_service->getSurveyMainByIdz($id);
      
              // If meter data exists, proceed with the update
              if ($meter_main) {
                  $save_column_list_data = []; // Reset for each meter ID
      
                  // Set the serial number for the current meter
                  $save_column_list_data['serial_no_old'] = $data['serial_no_old'][$id] ?? null;
      
                  // Set the creation timestamp
                  $save_column_list_data['created_at'] = now();
      
                  // Handle image 1 upload for the current meter ID
                  $this->handleImageUploadz($request, 'image_1_old', $meter_main, $save_column_list_data, $id);
      
                  // Handle image 2 upload for the current meter ID
                  $this->handleImageUploadz($request, 'image_2_old', $meter_main, $save_column_list_data, $id);
      
                  // Handle the latitude and longitude (defaults to null if not provided)
                  $save_column_list_data['lat'] = $data['latitude'] ?? null;
                  $save_column_list_data['lon'] = $data['longitude'] ?? null;
      
                  // Set the survey status:
                  // If both latitude and longitude are provided, set survey_status to 1, otherwise 0
                  if ($save_column_list_data['lat'] && $save_column_list_data['lon']) {
                      $save_column_list_data['survey_status'] = 1; // Set to 1 if latitude and longitude are both present
                  } else {
                      $save_column_list_data['survey_status'] = 0; // Default to 0 if either latitude or longitude is missing
                  }
      
                  // Create geo_link based on latitude and longitude (only if both are present)
                  if ($save_column_list_data['lat'] && $save_column_list_data['lon']) {
                      $save_column_list_data['geo_link'] = "https://www.google.com/maps?q=" . $save_column_list_data['lat'] . "," . $save_column_list_data['lon'];
                  } else {
                      $save_column_list_data['geo_link'] = null; // If no coordinates, geo_link will be null
                  }
      
                  // Save the updated data for the current meter
                  $meter_main_service->updateSurveyMainData($id, $save_column_list_data);
              }
          }
      
          // Optionally, store the last meter main ID in session for further use
          Session::put('meter_main_id', end($idArray));
      
          // Redirect to the home page after update
          return redirect('/Survey/home');
      }
      
      
      
      
      private function handleImageUploadz($request, $imageField, $meter_main, &$save_column_list_data, $id)
      {
          // Check if the image field exists for this meter ID
          if ($request->hasFile($imageField) && $request->file($imageField)->isValid()) {
              $file = $request->file($imageField); // Get the file associated with the current meter ID
              $mime_type = $file->getClientMimeType();
              $extension = $file->getClientOriginalExtension();
      
              // Validate the image type
              if (in_array($mime_type, ['image/png', 'image/jpeg', 'image/jpg']) && in_array($extension, ['png', 'jpeg', 'jpg'])) {
                  // Generate a random filename
                  $filename = Str::random(4) . $meter_main->account_id . '_' . $imageField . '.' . $extension;
      
                  // Move the file to the uploads directory
                  $filePath = $file->move(public_path('uploads'), $filename);
      
                  // Store the relative file path in the save_column_list_data
                  $save_column_list_data[$imageField] = 'uploads/' . $filename;
              } else {
                  // Invalid image type
                  session()->put('failed', 'Only JPEG and PNG images are allowed for ' . ucfirst($imageField) . '.');
                  return redirect('/Survey/add_old_meter_detail/' . $id);
              }
          }
      }
      
      
      
      
      
      

     /* public function update_old_meter_survey_detail($id, Request $request, MeterMainService $meter_main_service): RedirectResponse
      {
          // Check if the session has 'rexkod_pages_id' to ensure the user is logged in
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
      
          // Validate incoming request data
          $validator = Validator::make($request->all(), [
              "meter_make_old" => 'required|string',
              "serial_no_old" => 'required|numeric',
              "mfd_year_old" => 'required|numeric',
              "image_1_old" => 'required|image|mimes:jpeg,png,jpg', // Validating image files
              "image_2_old" => 'required|image|mimes:jpeg,png,jpg', // Validating image files
              "latitude" => 'required|numeric',  // Validate latitude
              "longitude" => 'required|numeric', // Validate longitude
          ]);
      
          // Handle validation failures
          if ($validator->fails()) {
              Session::put('input data validation check failed', $validator->errors());
              return redirect()->back()->withErrors($validator)->withInput();
          }
      
          // Get validated data
          $data = $validator->validated();
      
          // Prepare the data to save, including latitude and longitude
          $save_column_list_data = [
              "meter_make_old" => $data['meter_make_old'],
              "serial_no_old" => $data['serial_no_old'],
              "mfd_year_old" => $data['mfd_year_old'],
          ];
      
          // Retrieve the meter main data by ID
          $meter_main = $meter_main_service->getSurveyMainById($id);
      
          // Handle image uploads and save them
          $this->handleImageUpload($data, 'image_1_old', $meter_main, $save_column_list_data);
          $this->handleImageUpload($data, 'image_2_old', $meter_main, $save_column_list_data);
          
          // Optionally, you could add a third image upload if required
          if ($request->hasFile('image_3_old')) {
              $this->handleImageUpload($data, 'image_3_old', $meter_main, $save_column_list_data);
          }
      
          // Retrieve the latitude and longitude from the validated request
          $latitude = $data['latitude'];
          $longitude = $data['longitude'];
      
          // Generate the Google Maps shareable link
          $geo_link = "https://www.google.com/maps?q={$latitude},{$longitude}";
      
          // Add the geo link to the save data array under its dedicated column
          $save_column_list_data['geo_link'] = $geo_link;
      
          // Add the created_at timestamp (current timestamp) to the data array
          $save_column_list_data['created_at'] = now();
      
          // Proceed with saving the data to the database
          $meter_main_service->updateSurveyMainData($id, $save_column_list_data);
      
          // Optionally, store the meter main ID in session for further use
          Session::put('meter_main_id', $id);
      
          // Redirect to the home page
          return redirect('/Survey/home');
      }
      */
      /**
       * Helper method to handle image uploads and save file paths.
       */
    /*  protected function handleImageUpload($data, $image_field, $meter_main, &$save_column_list_data)
      {
          if (!empty($data[$image_field])) {
              $file = $data[$image_field];
              $extension = $file->getClientOriginalExtension();
              
              // Ensure the image has a valid extension (jpg, jpeg, png)
              if (in_array($extension, ['jpeg', 'jpg', 'png'])) {
                  // You can use a random filename or any other naming strategy
                  $filename = Str::random(10) . '_' . time() . '.' . $extension;
                  $save_column_list_data[$image_field] = $file->move(public_path('uploads'), $filename);
              } else {
                  session()->put('failed', 'Only JPEG, JPG, and PNG images are allowed.');
                  return redirect()->back();
              }
          }
      } */
    /* public function update_old_meter_survey_detail($ids, Request $request, MeterMainService $meter_main_service): RedirectResponse
{
    // Check if the session has 'rexkod_pages_id' to ensure the user is logged in
    if (empty(session()->get('rexkod_pages_id'))) {
        return redirect('/Survey')->with('message', 'You have been logged out!');
    }

    // Retrieve the data from the request
    $data = $request->all();

    // Prepare the data to save
    $save_column_list_data = [];

    // Split the IDs string into an array (assuming $ids is a comma-separated string like '44,45,46')
    $idArray = explode(',', $ids);

    //dd($idArray);

    // Loop through each meter ID and update it
    foreach ($idArray as $id) {
        // Retrieve the meter main data by ID
        $meter_main = $meter_main_service->getSurveyMainByIdz($id);

        // If meter data exists, proceed with the update
        if ($meter_main) {
            // Set the serial number and images
            $save_column_list_data = [
                "serial_no_old" => $data['serial_no_old'][$id] ?? null,
                "image_1_old" => $data['image_1_old'][$id] ?? null,
                "image_2_old" => $data['image_2_old'][$id] ?? null,
                "created_at" => now(),
            ];

            // Handle image uploads and save them
            $this->handleImageUpload($data, 'image_1_old', $meter_main, $save_column_list_data);
            $this->handleImageUpload($data, 'image_2_old', $meter_main, $save_column_list_data);

            // Save the updated data
            $meter_main_service->updateSurveyMainData($id, $save_column_list_data);
        }
    }

    // Optionally, store the meter main ID in session for further use
    Session::put('meter_main_id', $id);

    // Redirect to the home page
    return redirect('/Survey/home');
}*/

      
      
      
      
private function handleImageUpload($data, $imageField, $meter_main, &$save_column_list_data)
{
    // Check if the image field exists for this meter ID
    if (isset($data[$imageField][$meter_main->id])) {
        $file = $data[$imageField][$meter_main->id]; // Get the file associated with the current meter ID

        if ($file && $file->isValid()) {
            $extension = $file->getClientOriginalExtension(); // Get the file extension

            // Ensure that the extension is valid (jpeg, jpg, or png)
            if (in_array($extension, ['jpeg', 'jpg', 'png'])) {
                $filename = Str::random(4) . $meter_main->account_id . '_' . $imageField . '.' . $extension; // Generate a random filename

                // Move the file to the uploads directory
                $filePath = $file->move(public_path('uploads'), $filename);

                // Save the relative file path in the save_column_list_data
                $save_column_list_data[$imageField] = 'uploads/' . $filename;
            } else {
                // If the file extension is not allowed, store an error message and redirect
                session()->put('failed', 'Only JPEG and PNG images are allowed for ' . ucfirst($imageField) . '.');
                return redirect('/Survey/add_old_meter_detail/' . $meter_main->id);
            }
        }
    }
}

  
      public function load_current_location()
      {
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
          return view('Survey.load_current_location');
      }
  
      public function current_location_fetch($lat, $lon)
      {
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
          if ($lat !== null && $lon !== null) {
              Session::put('rexkod_pages_lat', $lat);
              Session::put('rexkod_pages_lon', $lon);
  
              return redirect('/Survey/add_new_meter_detail/' . session()->get('meter_main_id'));
          } else {
              session()->put('failed', 'Please turn on your mobile location and try again !!');
              return redirect('/Survey/home');
          }
      }

      
    /*  public function current_survey_location_fetch($lat, $lon)
      {
          // Check if the session contains 'rexkod_pages_id'
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
      
          // Fetch 'meter_mains_id' from the session
          $meter_mains_id = session()->get('meter_main_id');
          
          // Debugging the meter_mains_id
          dd($meter_mains_id);
      
          // Validate that latitude and longitude are not null
          if (!is_null($lat) && !is_null($lon)) {
              // Store latitude and longitude in the session
              Session::put('rexkod_pages_lat', $lat);
              Session::put('rexkod_pages_lon', $lon);
      
              // Add success message to the session
              session()->flash('success', 'Location fetched successfully. Survey completed.');
      
              // Redirect to the Survey home route
              return redirect('/Survey/home');
          } else {
              // Add failure message to the session
              session()->flash('failed', 'Unable to fetch location. Please turn on your mobile location and try again.');
      
              // Redirect to the Survey home route
              return redirect('/Survey/home');
          }
      }  */

      public function current_survey_location_fetch(Request $request, $lat = null, $lon = null)
{
    // Check if the session contains 'rexkod_pages_id'
    if (empty(session()->get('rexkod_pages_id'))) {
        return redirect('/Survey')->with('message', 'You have been logged out!');
    }

    // Fetch lat and lon from the request if not provided as route parameters
    $latitude = $lat ?? $request->input('lat');
    $longitude = $lon ?? $request->input('lon');

    // Validate the presence of lat and lon
    if (!is_null($latitude) && !is_null($longitude)) {
        // Store lat and lon in the session
        Session::put('rexkod_pages_lat', $latitude);
        Session::put('rexkod_pages_lon', $longitude);

        // Fetch 'meter_main_id' from the session
        $meter_main_id = session()->get('meter_main_id');

        // Check if meter_main_id exists
        if (empty($meter_main_id)) {
            session()->flash('failed', 'Meter main ID is missing. Please try again.');
            return redirect('/Survey/home');
        }

        // Save data into the survey_mains table
        $surveyMain = SurveyMain::find($meter_main_id);
        if ($surveyMain) {
            $surveyMain->lat = $latitude;
            $surveyMain->lon = $longitude;
            $surveyMain->save();
        } else {
            session()->flash('failed', 'Survey main record not found.');
            return redirect('/Survey/home');
        }

        // Add success message to the session
        session()->flash('success', 'Location fetched successfully and saved to survey mains. Survey completed.');

        // Redirect to the Survey home route
        return redirect('/Survey/home');
    } else {
        // Add failure message to the session
        session()->flash('failed', 'Unable to fetch location. Please turn on your mobile location and try again.');

        // Redirect to the Survey home route
        return redirect('/Survey/home');
    }
}
      
      
  
      public function add_new_meter_detail($id, MeterMainService $meter_main_service, ConsumerDetailService $consumer_detail_service)
      {
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
          $meter_main = $meter_main_service->getMeterMainsById($id);
  
          $get_consumer_detail = $consumer_detail_service->getConsumerDetailByAccountId($meter_main->account_id);
  
          $data = [
              'meter_main' => $meter_main,
              'get_consumer_detail' => $get_consumer_detail,
              'id' => $id,
          ];
  
          return view('Survey.add_new_meter_detail', ['data' => $data]);
      }
  
      public function update_new_meter_detail($id, Request $request, MeterMainService $meter_main_service)
      {
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
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
                      return redirect('/Survey/add_new_meter_detail/' . $id);
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
                      return redirect('/Survey/add_new_meter_detail/' . $id);
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
                      return redirect('/Survey/add_new_meter_detail/' . $id);
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
  
              return redirect('/Survey/home');
  
              // second case: this is present in meter_mains
  
              return ($req);
          } else {
              return redirect('/Survey')->with('message', 'Session Time Out!');
          }
      }
  
      public function account()
      {
          if (empty(session()->get('rexkod_pages_id'))) {
              return redirect('/Survey')->with('message', 'You have been logged out!');
          }
          return view('Survey.account');
      }
  
      public function logout(Request $request)
      {
          Session::forget('rexkod_pages_id');
          auth()->logout();
  
          $request->session()->invalidate();
          $request->session()->regenerateToken();
          Session::flush();
  
          return redirect('/Survey')->with('message', 'You have been logged out!');
      }
  
      // unknown codes
      public function location_fetch($lat, $lon)
      {
          Session::put('rexkod_pages_lat', $lat);
          Session::put('rexkod_pages_lon', $lon);
  
          return redirect('Survey/index');
          // return view('pages.location_fetch');
      }
  
      public function load_location()
      {
          return view('Survey.load_location');
      }
  
      public function add2()
      {
          return view('Survey.add2');
      }
  
      public function records()
      {
          // $meter_main = Meter_main::where('delete_flag', 1)->get();
          // return view('pages.records', ['meter_main' => $meter_main]);
          return view('Survey.records');
      }
  
      public function records2()
      {
          return view('Survey.records2');
      }
  
      public function location()
      {
          return view('Survey.location');
      }
  
      public function login2()
      {
          return view('Survey.login2');
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
          return view('survey.add_meter_first_step');
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
                  return redirect('/Survey/add_old_meter_detail/' . $id);
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
                  return redirect('/Survey/add_old_meter_detail/' . $id);
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
                      return redirect('/Survey/add_new_meter_detail/' . $id);
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
                      return redirect('/Survey/add_new_meter_detail/' . $id);
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
              return redirect('/Survey')->with('message', 'Session Time Out!');
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
          return view('survey.location');
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
          return view('survey.image_validity_report', ['image_validity_data' => $validity_images, 'image_validity_data_count' => count($validity_images)]);
      }
  
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
