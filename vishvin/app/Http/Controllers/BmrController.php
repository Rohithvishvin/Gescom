<?php

	namespace App\Http\Controllers;


	use App\Http\Services\BmrDownloadService;
	use App\Http\Services\MeterMainService;
	use App\Models\Admin;
	use App\Models\Inventory;
	use App\Models\Annexure_1;
	use App\Models\Annexure_3;
	use App\Models\Contractor;
	use App\Models\Outward_mrt;
	use App\Models\Consumer_detail;
	use App\Models\Zone_code;
	use Illuminate\Support\Facades\Mail;
	use Illuminate\Support\Str;
	use App\Models\Inward_genus;
	use App\Models\Meter_main;
	use Illuminate\Http\Request;
	use App\Models\Warehouse_meter;
	use App\Models\Inward_mrt_reject;
	use Illuminate\Support\Facades\DB;
	use App\Models\Outward_installation;
	use App\Models\Contractor_inventory;
	use App\Models\Bmr_download;

	use Illuminate\Support\Facades\Hash;
	use App\Models\Inward_meter_sl_no_wise;
	use Illuminate\Support\Facades\Session;
	use App\Models\Inward_released_em_meter;
	use App\Models\Outward_released_em_meter;
	use App\Models\Indent;
	use App\Models\Successful_record;
	use App\Models\Error_record;

	use Illuminate\Routing\Controller;
	use Illuminate\Support\Facades\Redirect;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Support\Facades\Storage;
	use Carbon\Carbon;

	class BmrController extends Controller
	{
		//
	/*	public function bmr_report(Request $req)
		{
//        if ($req->format === 'weekly') {
//            # code...
//            $today = Carbon::now();
//            $dateSevenDaysAgo = Carbon::now()->subDays(7);
//
//            $start_date = $dateSevenDaysAgo->format('Y-m-d');
//            $end_date = $today->format('Y-m-d');
//
//            // dd($start_date);
//
//            $approved_meters = Meter_main::where(function ($query) use ($start_date, $end_date) {
//                $query->where('aao_status', 1)
//                      ->where('aao_updated_at', '>=', $start_date)
//                    //   ->where('aao_updated_at', '<=', $end_date)
//                      ->where('download_flag',0);
//            })
//            ->orWhere(function ($query) use ($start_date, $end_date){
//                $query->where('error_updated_by_aao', 1)
//                ->where('error_updated_at', '>=', $start_date);
//                // ->where('error_updated_at', '<=', $end_date);
//            })
//            ->get();
//
//            // $approved_meters = Meter_main::where('aao_status', 1)->where('aao_updated_at', '>=', $start_date)->where('aao_updated_at', '<=', $end_date)->where('download_flag',0)->get();
//
//                $data = [
//                    'approved_meters' => $approved_meters,
//                ];
//        }
//        else if($req->format === 'monthly'){
//            # code...
//            $today = Carbon::now();
//            $dateSevenDaysAgo = Carbon::now()->subDays(30);
//
//            $start_date = $dateSevenDaysAgo->format('Y-m-d');
//            $end_date = $today->format('Y-m-d');
//            // $approved_meters = Meter_main::where('aao_status', 1)->orWhere('error_updated_by_aao', 1)->where('aao_updated_at', '>=', $start_date)->where('aao_updated_at', '<=', $end_date)->where('download_flag',0)->get();
//                $approved_meters = Meter_main::where(function ($query) use ($start_date, $end_date) {
//                $query->where('aao_status', 1)
//                      ->where('aao_updated_at', '>=', $start_date)
//                    //   ->where('aao_updated_at', '<=', $end_date)
//                      ->where('download_flag',0);
//            })
//            ->orWhere(function ($query) use ($start_date, $end_date){
//                $query->where('error_updated_by_aao', 1)
//                ->where('error_updated_at', '>=', $start_date);
//                // ->where('error_updated_at', '<=', $end_date);
//            })
//            ->get();
//
//                $data = [
//                    'approved_meters' => $approved_meters,
//                ];
//        }
//        else{
//            if ($req->get('start_date') !== NUll) {
//                $start_date = $req->get('start_date');
//                $end_date = $req->get('end_date');
//                // $approved_meters = Meter_main::where('aao_status', 1)->where('aao_updated_at', '>=', $start_date)->where('aao_updated_at', '<=', $end_date)->where('download_flag',0)->get();
//                $approved_meters = Meter_main::where(function ($query) use ($start_date, $end_date) {
//                    $query->where('aao_status', 1)
//                          ->where('aao_updated_at', '>=', $start_date)
//                          ->where('aao_updated_at', '<=', $end_date)
//                          ->where('download_flag',0);
//                })
//                ->orWhere(function ($query) use ($start_date, $end_date){
//                    $query->where('error_updated_by_aao', 1)
//                    ->where('error_updated_at', '>=', $start_date)
//                    ->where('error_updated_at', '<=', $end_date);
//                })
//                ->get();
//
//                $data = [
//                    'approved_meters' => $approved_meters,
//                ];
//            }
//            else {
//                 $approved_meters = Meter_main::where('aao_status', 1)->where('download_flag',0)->orWhere('error_updated_by_aao', 1)->get();
//                $approved_meters = DB::table('meter_mains')->where(function ($query)  {
//                    $query->where('aao_status', 1)
//                          ->where('download_flag',0);
//                })
//                ->orWhere(function ($query) {
//                    $query->where('error_updated_by_aao', 1);
//                    // ->where('download_flag',0);
//                })
//                //->select(DB::raw('count(*) as peding_bmr'))
//                //->limit(10)
//                ->get();
//                dd($approved_meters);
//            }
//        }
			$meter_main_service = new MeterMainService();
			$approved_meters = $meter_main_service->bmr_report_pending_meters();
			//dd($get_meters);

			$bmr_download_service = new BmrDownloadService();
			$bmrDownloads = $bmr_download_service->getAllRecords();

			$data = [
				'approved_meters' => $approved_meters,
				'bmr_downloads' => $bmrDownloads,
			];
			// $approved_meters = Meter_main::where('aao_status', 1)->get();
			// $data = [
			//     'approved_meters' => $approved_meters,
			// ];
			return view('bmrs.bmr_report', compact('data'));
		}  */
		
		
			public function bmr_report(Request $req)
		{
			// Initialize necessary services
			$meter_main_service = new MeterMainService();
			$approved_meters = $meter_main_service->bmr_report_pending_meters();
		
			$bmr_download_service = new BmrDownloadService();
			$bmrDownloads = $bmr_download_service->getAllRecords();
			$getAllaccountsnotexistsuccessError = $bmr_download_service->getAllaccountsnotexistsuccessError();
		
			// Initialize arrays to store the results
			$matchingRecords = [];
			$getAllaccounts_success = [];
		
			// Loop through each record in bmrDownloads
			foreach ($bmrDownloads as $download) {
				// Step 1: Find records in getAllaccountsnotexistsuccessError where download_flag matches
				foreach ($getAllaccountsnotexistsuccessError as $errorRecord) {
					if ($download->id == $errorRecord->download_flag) {
						$matchingRecords[] = $errorRecord;
					}
				}
		
				// Step 2: Convert the comma-separated meter_main_id into an array
				$meter_mains_arry_ids = explode(',', $download->meter_main_id);  // Convert the comma-separated string into an array
		
				// Step 3: Fetch all successful meter mains for the given meter_main_ids
				$getAllaccounts_success[$download->id] = $bmr_download_service->getAllMeterMainsSuccess($meter_mains_arry_ids);
			}
		
			// Prepare the data for the view
			$data = [
				'approved_meters' => $approved_meters,
				'bmr_downloads' => $bmrDownloads,
				'getAllaccountsnotexistsuccessError' => $getAllaccountsnotexistsuccessError,
				'matchingRecords' => $matchingRecords,
				'getAllaccounts_success' => $getAllaccounts_success,
			];
		
			// Debugging output to check the results
			//dd($getAllaccounts_success);
		
			// Return the data to the view
			return view('bmrs.bmr_report', compact('data'));
		}
		

		public function successfull_records()
		{
			# code...
			return view('bmrs.successfull_records');
		}

		public function upload_successfull_records(Request $req, MeterMainService $meterMainService, BmrDownloadService $bmrDownloadService)
		{
			# code...
			set_time_limit(7200);
			$validatedData = $req->validate([
				'successfull_record' => 'required|mimes:txt',
			]);
			$path = $req->file('successfull_record')->store('uploads/file');

			$fileContents = Storage::get($path);
			$lines = explode("\n", $fileContents);

			// dd($lines);
			$count = 0;
			$token = Str::random(10);
			//$existingAccountIDs = Successful_record::pluck('account_id')->toArray();
			foreach ($lines as $line) {
				if (empty(trim($line))) {
					continue;
				}
				$columns = explode("|", $line);
				//dd(sizeof($columns));
				if (sizeof($columns) <= 5) {
					session()->put('failed', 'Please select a proper file to upload');
					return redirect('/bmrs/successfull_records');
				}

				$file_account_id = trim($columns[2]);
				$check_account['account_id'] = $file_account_id;
				$check_meter_main_account_result = $meterMainService->getSingleMeterMainsByFilter($check_account, ['id', 'account_id']);
				$check_successful_record_account_result = $bmrDownloadService->getSingleSuccessDataByFilter($check_account, ['id', 'account_id']);
				//dd($check_meter_main_account_result, isset($check_meter_main_account_result), $check_successful_record_account_result, isset($check_successful_record_account_result));
				if (isset($check_meter_main_account_result)) {
					if (!isset($check_successful_record_account_result)) {
						$successful_record = new Successful_record();
						$successful_record->account_id = $file_account_id;
						$successful_record->token = $token;
						$successful_record->save();
						//dd($successful_record);

						// if account id is present in error_records and updated_by_aao is 0 (not yet worked ) we have change status to 1 so that it doesnt show up in aoo login as its already in successful_records
						$error_filter_data_one['account_id'] = $file_account_id;
						$error_filter_data_one['updated_by_aao'] = 0;
						$error_column_list_data_one = ['id'];
						$check_error_record_account_result_one = $bmrDownloadService->getErrorRecordsDataByFilter($error_filter_data_one, $error_column_list_data_one);
						//dd($check_error_record_account_result_one, isset($check_error_record_account_result_one), is_array($check_error_record_account_result_one), empty($check_error_record_account_result_one));
						if (!empty($check_error_record_account_result_one)) {
							foreach ($check_error_record_account_result_one as $error_record_data) {
								//dd($error_record_data);
								$error_filter_data['id'] = $error_record_data->id;
								$error_column_list_data = ['id', 'account_id', 'updated_by_aao'];
								$check_error_record_account_result_two = $bmrDownloadService->getSingleErrorDataByFilter($error_filter_data, $error_column_list_data);
								if (!empty($check_error_record_account_result_two) && $check_error_record_account_result_two->updated_by_aao == 0) {
									$error_record_insert_update = Error_record::find($check_error_record_account_result_two->id);
									$error_record_insert_update->updated_by_aao = 1;
									$error_record_insert_update->token = $token;
									$error_record_insert_update->update();
									$count++;
								}
							}
						}
						$count++;
					} else {
						$account_id_exists_in_success[] = $file_account_id;
					}
				}

//            if(!empty($columns[2])){
//                if(trim($columns[2])==''){
//                    continue;
//                }else {
//                    $file_account_id = trim($columns[2]);
//                    //dd($file_account_id, Successful_record::find($file_account_id));
//                    if (!in_array($file_account_id, $existingAccountIDs)) {
//                        $successful_record = new Successful_record();
//                        $successful_record->account_id = $file_account_id;
//                        $successful_record->token = $token;
//                        $successful_record->save();
//
//                        $existing_error_id = DB::table('error_records')->where('account_id', $file_account_id)->where('updated_by_aao', '=', 0)->select('id')->first();
//                        if(!empty($existing_error_id->id)) {
//                            $error_record = Error_record::find($existing_error_id->id);
//                            $error_record->updated_by_aao = 1;
//                            $error_record->token = $token;
//                            $error_record->update();
//                        }
//                        // if account id is present in meter_mains and error_updated_by_aao is 1 (he as worked) we have change status to 0 so that it doesnt show up in bmr reports as its already in successful_records
//                        $existing_meter_mainid = DB::table('meter_mains')
//                            ->where('account_id', $file_account_id)
//                            ->where('error_updated_by_aao', '=', 1)
//                            ->where('aao_status', '=', 1)
//                            ->select('id')->first();
//                        if(!empty($existing_meter_mainid->id)) {
//                            $meter_main = Meter_main::find($existing_meter_mainid->id);
//                            $meter_main->error_updated_by_aao = 0;
//                            $meter_main->error_updated_at = date('Y-m-d H:i:s');
//                            $meter_main->update();
//                        }
//
//                        $count++;
//                    }
//                    else{
//                        $account_id_exists[] = $file_account_id;
//                    }
//                }
//            }
//            else{
//                continue;
//            }
				// Set attributes for any other columns you want to save data to
			}
			//if(!empty($account_id_exists_in_success)) dd($account_id_exists_in_success);
			session()->put('success', $count . ' Success Records Uploaded Successfully');
			return redirect('/bmrs/successfull_records');
		}

		public function error_records()
		{
			# code...
			return view('bmrs.error_records');
		}

		public function upload_error_records(Request $req, MeterMainService $meterMainService, BmrDownloadService $bmrDownloadService)
		{
			# code...
			set_time_limit(7200);

			//validate the uploaded file is in txt format or not
			if (!$req->hasFile('error_record')) {
				return redirect()->back()->with('failed', 'No file uploaded');
			}
			$file = $req->file('error_record');
			$allowedMimeTypes = [
				'text/plain',
			];

			if (!in_array($file->getClientMimeType(), $allowedMimeTypes)) {
				return redirect()->back()->with('failed', 'Invalid file type');
			}


			$path = $req->file('error_record')->store('uploads/file');

			$fileContents = Storage::get($path);
			$lines = explode("\n", $fileContents);
			// dd($lines);
			$count = 0;
			$token = Str::random(10);
			//$existingAccountIDs = Error_record::pluck('account_id')->toArray();
			//$existingErrorAccountIDs = DB::table('error_records')->where('updated_by_aao', '=', 0)->select('account_id')->get()->toArray();
			//dd($existingErrorAccountIDs);
			//$existingSuccessFullAccountIDs = Successful_record::pluck('account_id')->toArray();
			//dd($existingAccountIDs);
			foreach ($lines as $line) {
				if (empty(trim($line))) {
					continue;
				}
				$columns = explode("|", $line);
				if (sizeof($columns) > 5) {
					session()->put('failed', 'Please select a proper file to upload');
					return redirect('/bmrs/error_records');
				}
				$file_account_id = trim($columns[2]);
				$error_reason = trim($columns[4]);
				$check_account['account_id'] = $file_account_id;
				$check_meter_main_account_result = $meterMainService->getSingleMeterMainsByFilter($check_account, ['id', 'account_id']);
				$check_successful_record_account_result = $bmrDownloadService->getSingleSuccessDataByFilter($check_account, ['id', 'account_id']);
				//dd($check_meter_main_account_result, isset($check_meter_main_account_result), $check_successful_record_account_result, isset($check_successful_record_account_result));
				if (isset($check_meter_main_account_result)) {
					if (!empty($error_reason) && str_contains($error_reason, 'Meter RemovalDate ')) {
						if (!isset($check_successful_record_account_result)) {
							$successful_record = new Successful_record();
							$successful_record->account_id = $file_account_id;
							$successful_record->token = $token;
							$successful_record->save();
							//dd($successful_record);

							// if account id is present in error_records and updated_by_aao is 0 (not yet worked ) we have change status to 1 so that it doesnt show up in aoo login as its already in successful_records
							$error_filter_data_one['account_id'] = $file_account_id;
							$error_filter_data_one['updated_by_aao'] = 0;
							$error_column_list_data_one = ['id'];
							$check_error_record_account_result_one = $bmrDownloadService->getErrorRecordsDataByFilter($error_filter_data_one, $error_column_list_data_one);
							//dd($check_error_record_account_result_one, isset($check_error_record_account_result_one), is_array($check_error_record_account_result_one), empty($check_error_record_account_result_one));
							if (!empty($check_error_record_account_result_one)) {
								foreach ($check_error_record_account_result_one as $error_record_data) {
									//dd($error_record_data);
									$error_filter_data['id'] = $error_record_data->id;
									$error_column_list_data = ['id', 'account_id', 'updated_by_aao'];
									$check_error_record_account_result_two = $bmrDownloadService->getSingleErrorDataByFilter($error_filter_data, $error_column_list_data);
									if (!empty($check_error_record_account_result_two) && $check_error_record_account_result_two->updated_by_aao == 0) {
										$error_record_insert_update = Error_record::find($check_error_record_account_result_two->id);
										$error_record_insert_update->error_reason = $error_reason;
										$error_record_insert_update->updated_by_aao = 1;
										$error_record_insert_update->token = $token;
										$error_record_insert_update->update();
										$count++;
									}
								}
							}

							// if account id is present in meter_mains and error_updated_by_aao is 1 (he as worked) we have change status to 0 so that it doesnt show up in bmr reports as its already in successful_records
							$meter_mains_filter_data['account_id'] = $file_account_id;
							$meter_mains_filter_data['error_updated_by_aao'] = 1;
							$meter_mains_filter_data['aao_status'] = 1;
							$meter_mains_column_list_data = ['id'];
							$check_error_record_account_result = $meterMainService->getSingleMeterMainsByFilter($meter_mains_filter_data, $meter_mains_column_list_data);
//                        $existing_meter_main_id = DB::table('meter_mains')
//                            ->where('account_id', $file_account_id)
//                            ->where('error_updated_by_aao', '=', 1)
//                            ->where('aao_status', '=', 1)
//                            ->select('id')->first();
							if (!empty($check_error_record_account_result)) {
								$meter_main = Meter_main::find($check_error_record_account_result->id);
								$meter_main->error_updated_by_aao = 0;
								$meter_main->error_updated_at = date('Y-m-d H:i:s');
								$meter_main->update();
							}
							$count++;
						} else {
							$account_id_exists_in_success[] = $file_account_id;
						}
					} else {
						$error_filter_data_three['account_id'] = $file_account_id;
						$error_filter_data_three['updated_by_aao'] = 0;
						$error_column_list_data_three = ['id'];
						//dd($error_column_list_data);
						$check_error_record_account_result_three = $bmrDownloadService->getErrorRecordsDataByFilter($error_filter_data_three, $error_column_list_data_three);
						//dd($error_filter_data_three, $check_error_record_account_result_three, sizeof($check_error_record_account_result_three), isset($check_error_record_account_result_three), is_array($check_error_record_account_result_three), empty($check_error_record_account_result_three));
						if (!is_array($check_error_record_account_result_three) && sizeof($check_error_record_account_result_three) == 0) {
							//dd($check_error_record_account_result);
							$error_record = new Error_record();
							$error_record->account_id = $file_account_id;
							$error_record->error_reason = $error_reason;
							$error_record->token = $token;
							$error_record->save();
							//dd($error_record->id);
							//dd($error_record);
							$count++;
						} else {
							$error_records_updated_by_aao = array();
							//var_dump($check_error_record_account_result_three);
							foreach ($check_error_record_account_result_three as $error_record_data) {
								//var_dump($error_record_data);
								$error_filter_data_four['id'] = $error_record_data->id;
								$error_column_list_data_four = ['id', 'account_id', 'updated_by_aao'];
								$check_error_record_account_result_four = $bmrDownloadService->getSingleErrorDataByFilter($error_filter_data_four, $error_column_list_data_four);
								//dd($check_error_record_account_result_four);
								if (sizeof($check_error_record_account_result_three) == 1 && $check_error_record_account_result_four->updated_by_aao == 0) {
									$error_record_insert_update = Error_record::find($check_error_record_account_result_four->id);
									$error_record_insert_update->error_reason = $error_reason;
									$error_record_insert_update->token = $token;
									$error_record_insert_update->update();
									//dd($error_record->id);
									//dd($error_record);
									$count++;
								} else {
									dd($check_error_record_account_result_four);
									$error_record_insert_update = Error_record::find($check_error_record_account_result_four->id);
									$error_record_insert_update->error_reason = $error_reason;
									$error_record_insert_update->token = $token;
									$error_record_insert_update->update();
								}
								//var_dump($check_error_record_account_result_four);
								$error_records_updated_by_aao[] = $check_error_record_account_result_four->updated_by_aao;
//                            if()
//                            $existing_id = DB::table('error_records')
//                                ->where('account_id', $file_account_id)
//                                ->where('updated_by_aao', '=', 0)->select('id')->first();
//                            dd($existing_id, $account_id);
								//dd($account_id, $existing_id);
//                            if (!empty($check_error_record_account_result_three) && $check_error_record_account_result_three->updated_by_aao == 1) {
//                                $error_record_insert_new = new Error_record();
//                                $error_record_insert_new->account_id = $file_account_id;
//                                $error_record_insert_new->error_reason = $error_reason;
//                                $error_record_insert_new->token = $token;
//                                $error_record_insert_new->save();
//                                $count++;
//                            }
//                            else if(!empty($check_error_record_account_result_three) && $check_error_record_account_result_three->updated_by_aao == 0) {
//                                $error_record_insert_update = Error_record::find($check_error_record_account_result_three->id);
//                                $error_record_insert_update->error_reason = $error_reason;
//                                $error_record_insert_update->token = $token;
//                                $error_record_insert_update->update();
//                                $count++;
//                            }
							}
//                        if(in_array(0, $error_records_updated_by_aao)){
//                            $error_record_insert_update = Error_record::find($check_error_record_account_result_three->id);
//                            $error_record_insert_update->error_reason = $error_reason;
//                            $error_record_insert_update->token = $token;
//                            $error_record_insert_update->update();
//                            $count++;
//                        }
//                        if(sizeof($error_records_updated_by_aao) > 1) dd($error_records_updated_by_aao);
						}
					}
				}

				// Set attributes for any other columns you want to save data to
			}
			//if(!empty($account_id_exists_in_success)) dd($account_id_exists_in_success);

			session()->put('success', $count . ' Error Records Uploaded Successfully');
			return redirect('/bmrs/error_records');
		}


		public function downloaded_batch(Request $req)
		{
			// $distinctValues = DB::table('meter_mains')->distinct()->pluck('download_flag');
			// dd($req);

            $download_ids = implode(',', $req->selected_id);
            $bmr_downloads = new Bmr_download();
            $bmr_downloads->meter_main_id = $download_ids;
            $bmr_downloads->save();

			$maxValue = DB::table('meter_mains')->max('download_flag');
			foreach ($req->selected_id as $selected) {

				$error_records = Meter_main::where('id', $selected)->first();
				if ($error_records->error_updated_by_aao == 1) {
					$meter_main = Meter_main::where('id', $selected)->update(['error_updated_by_aao' => 0]);
				}
				if (is_null($error_records->initial_reading_kwh)) {
					$meter_main = Meter_main::where('id', $selected)->update(['initial_reading_kwh' => "00"]);
				}
				if (is_null($error_records->initial_reading_kwh)) {
					$meter_main = Meter_main::where('id', $selected)->update(['initial_reading_kvah' => "00"]);
				}
				$meter_main = Meter_main::where('id', $selected)->update(['download_flag' => $maxValue + 1]);

			}

			return redirect('/bmrs/bmr_report');

		}

		public function bmr_report_single($flag)
		{
			# code...
			$filter_data = ['id' => $flag];

			$bmr_download_service = new BmrDownloadService();
			$bmr_downloads = $bmr_download_service->getSingleBmrDownloadsByFilter($filter_data);

			//$bmr_downloads = Bmr_download::where('id',$flag)->first();
			$meter_main_ids = explode(',', $bmr_downloads->meter_main_id);

			$meter_main_service = new MeterMainService();
			$approved_meters = $meter_main_service->bmr_report_pending_meters(null, null, null, null, null, $meter_main_ids);

			//dd($meter_main_ids, $approved_meters);

			// $meter_mains = Meter_main::where('download_flag',$flag)->get();
			$data = [
				// 'approved_meters' =>$meter_mains,
				'meter_main_ids' => $meter_main_ids,
				'approved_meters' => $approved_meters,
			];

			return view('/bmrs/bmr_report_single', compact('data'));
		}

		public function successfull_report(Request $req)
		{
			# code...
			if ($req->format === 'weekly') {
				# code...
				$dateSevenDaysAgo = Carbon::now()->subDays(7);
				$start_date = $dateSevenDaysAgo->format('Y-m-d');
                $successful_records = DB::table('successful_records')->where('created_at', '>=', $start_date. ' 00:00:00')->get();
			} else if ($req->format === 'monthly') {
				# code...
				$today = Carbon::now();
				$dateSevenDaysAgo = Carbon::now()->subDays(30);
				$start_date = $dateSevenDaysAgo->format('Y-m-d');
				$end_date = $today->format('Y-m-d');
                $successful_records = DB::table('successful_records')->where('created_at', '>=', $start_date . ' 00:00:00')->get();
			} else {
				if ($req->get('start_date') !== NUll) {
					$start_date = $req->get('start_date');
					$end_date = $req->get('end_date');
					$successful_records = DB::table('successful_records')
                        ->where('created_at', '>=', $start_date . ' 00:00:00')
                        ->where('created_at', '<=', $end_date . ' 23:59:59')
						->get();
				} else {
					$successful_records = DB::table('successful_records')->distinct('account_id')->select('account_id')->get();
				}
			}
			$data = [
				'successful_records' => $successful_records,
				'count' => count($successful_records),
			];
			return view('/bmrs/successfull_report', compact('data'));
		}

		public function successfull_report_single($account_id)
		{
			# code...
			$consumer_detail = Consumer_detail::where('account_id', $account_id)->first();
			$meter_main = Meter_main::where('account_id', $account_id)->first();
			$data = [
				'meter_main' => $meter_main,
				'consumer_detail' => $consumer_detail,
				'id' => $account_id,
			];

			return view('/bmrs/successfull_report_single', compact('data'));
		}

		public function index()
		{
			$logged_in_user_id = session('rexkod_vishvin_auth_userid');
			$logged_in_user_type = session('rexkod_vishvin_auth_user_type');

			$package_name = env('PACKAGE_NAME');
			$get_all_division_codes = DB::table('zone_codes')
				->select('division', 'div_code')
				->where('package', $package_name)
				->distinct()
				->get();

			//$bmr_download_service = new BmrDownloadService();

			//$get_total_error_record_count_bmr = $bmr_download_service->getErrorCount();
			//$get_total_successful_record_count_bmr = $bmr_download_service->getSuccessCount();

			$data = [
				//'get_total_error_record_count_bmr' => $get_total_error_record_count_bmr,
				//'get_total_successful_record_count_bmr' => $get_total_successful_record_count_bmr,
				'current_logged_in_user_type' => $logged_in_user_type,
				'divisions' => $get_all_division_codes
			];
			//dd($data);
			return view('bmrs.index', ['data' => $data]);
		}
	}
