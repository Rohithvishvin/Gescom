<?php

    namespace App\Http\Services;

    use App\Models\Bmr_download;
    use App\Models\Successful_record;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\File;
    use Carbon\Carbon;
    use ZipArchive; 

    class BmrDownloadService
    {
        public function getAllRecords()
        {
            $bmr_downloads = DB::table('bmr_downloads')->orderBy('id', 'DESC')->get();

            return $bmr_downloads;
        }
		
		        public function getAllaccountsnotexistsuccessError()
        {
            $notexistErrorSuccess = DB::table('meter_mains as mm')
                ->select(
                    'mm.account_id as account_id', 
                    'mm.id as meter_mains_id',
                    'cd.rr_no as rr_no', 
                    'cd.consumer_name as consumer_name',
                    'mm.serial_no_new', 
                    'aao_admin.name as aao_updated_by_name', 
                    'mm.download_flag', 
                    'mm.aao_updated_at',
                    'created_by_admin.name as Field_executive_name', 
                    'so.section_office as section_office_name', 
                    'mm.created_at'
                )
                ->join('consumer_details as cd', 'mm.account_id', '=', 'cd.account_id')
                ->join('admins as created_by_admin', 'mm.created_by', '=', 'created_by_admin.id')
                ->leftJoin('admins as aao_admin', 'mm.aao_updated_by', '=', 'aao_admin.id')
                ->leftJoin('zone_codes as so', 'cd.so_pincode', '=', 'so.so_code')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('successful_records as st')
                        ->whereColumn('mm.account_id', 'st.account_id');
                })
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('error_records as et')
                        ->whereColumn('mm.account_id', 'et.account_id');
                })
                ->where('mm.aao_status', 1)
                ->orderBy('mm.id', 'DESC')
                ->get();

            return $notexistErrorSuccess;
        }
		
		
		
		
        public function getAllMeterMainsSuccess($meter_mains_ids)
        {

           // dd($meter_mains_ids);
            // Step 1: Ensure the input is an array of integers
            //$meter_mains_ids = array_map('intval', $meter_mains_ids);
           // dd($meter_mains_ids);
            // Step 2: Query the database to find records in meter_mains with corresponding successful_records
            $meterMainsSuccess = DB::table('meter_mains as mm')
            ->select(
                'mm.id as meter_mains_id',
                'mm.account_id', 
                'mm.created_at',
                'mm.serial_no_old', 
                'mm.meter_make_old', 
                'mm.mfd_year_old', 
                'mm.final_reading',
                'mm.serial_no_new', 
                'mm.initial_reading_kwh', 
                'mm.initial_reading_kvah', 
                'cd.sp_id', 
                'cd.sd_pincode', 
                'cd.meter_type'
            )
            ->join('consumer_details as cd', 'mm.account_id', '=', 'cd.account_id') // Join with consumer_details
            ->whereIn('mm.id', $meter_mains_ids) // Use the array of meter_mains_id values
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('successful_records as st')
                    ->whereRaw('mm.account_id = st.account_id'); // Ensure matching account_id in successful_records
            })
            ->orderBy('mm.id', 'DESC')
            ->get();
        
   //dd($meterMainsSuccess);
               
        
            return $meterMainsSuccess;
        }

        public function getSingleBmrDownloadsByFilter($filter_data)
        {
            $bmr_downloads_query = DB::table('bmr_downloads');
            if(!empty($filter_data['id'])) $bmr_downloads_query->where('id', $filter_data['id']);
            $bmr_downloads = $bmr_downloads_query->first();

            return $bmr_downloads;
        }

        public function getSuccessCount($start_date = null, $end_date = null, $division = null, $sub_division = null,$section = null, $feeder_code = null, $updated_by = null){
            $success_count_query = DB::table('successful_records')
                ->leftJoin('meter_mains', 'meter_mains.account_id', '=', 'successful_records.account_id')
                ->leftJoin('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id');

            if(!empty($start_date)) $success_count_query->where('successful_records.created_at', '>=', $start_date . ' 00:00:00');

            if(!empty($end_date)) $success_count_query->where('successful_records.created_at', '<=', $end_date . ' 23:59:59');

            $success_count_query->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('meter_mains.serial_no_old')
                ->select(DB::raw('count(*) as success_count'));

            if (!empty($division) && $division != "null") {
                $success_count_query->where('consumer_details.division', '=', $division);
            }
            if (!empty($sub_division) && $sub_division != "null") {
                $success_count_query->where('consumer_details.sd_pincode', '=', $sub_division);
            }
            if (!empty($section) && $section != "null") {
                $success_count_query->where('consumer_details.so_pincode', '=', $section);
            }
            if (!empty($feeder_code) && $feeder_code != "null") {
                $success_count_query->where('consumer_details.feeder_code', '=', $feeder_code);
            }

            try{
                $success_count_query_result = $success_count_query->first();
                //dd($success_count_query_result);
            }
            catch(\Exception $e){
                dd($e);
            }

            return $success_count_query_result->success_count;
        }

        public function getErrorCount($start_date = null, $end_date = null, $division = null, $sub_division = null,$section = null, $feeder_code = null, $updated_by = null){
            $error_count_query = DB::table('error_records')
                ->leftJoin('meter_mains', 'meter_mains.account_id', '=', 'error_records.account_id')
                ->leftJoin('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id');

            if(!empty($start_date)) $error_count_query->where('error_records.created_at', '>=', $start_date . ' 00:00:00');

            if(!empty($end_date)) $error_count_query->where('error_records.created_at', '<=', $end_date . ' 23:59:59');

            $error_count_query->where('error_records.updated_by_aao', '=', 0)
                ->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('meter_mains.serial_no_old')
                ->select(DB::raw('count(*) as error_count'));

            if (!empty($division) && $division != "null") {
                $error_count_query->where('consumer_details.division', '=', $division);
            }
            if (!empty($sub_division) && $sub_division != "null") {
                $error_count_query->where('consumer_details.sd_pincode', '=', $sub_division);
            }
            if (!empty($section) && $section != "null") {
                $error_count_query->where('consumer_details.so_pincode', '=', $section);
            }
            if (!empty($feeder_code) && $feeder_code != "null") {
                $error_count_query->where('consumer_details.feeder_code', '=', $feeder_code);
            }

            try{
                $error_count_query_result = $error_count_query->first();
            }
            catch(\Exception $e){
                dd($e);
            }

            return $error_count_query_result->error_count;
        }

        public function getSingleSuccessDataByFilter($filter_data, $column_list_data = null)
        {
            $successful_records_query = DB::table('successful_records');

            if (is_array($column_list_data)) {
                //$column_list = implode(',', $column_list_data);
                $successful_records_query->select($column_list_data);
            }

            if (!empty($filter_data['id'])) $successful_records_query->where('id', $filter_data['id']);
            if (!empty($filter_data['account_id'])) $successful_records_query->where('account_id', $filter_data['account_id']);

            //dd($successful_records_query);

            $successful_record = $successful_records_query->first();

            return $successful_record;
        }

        public function getSingleErrorDataByFilter($filter_data, $column_list_data = null)
        {
            $error_records_query = DB::table('error_records');

            if (is_array($column_list_data)) {
                //$column_list = implode(',', $column_list_data);
                $error_records_query->select($column_list_data);
            }

            if (isset($filter_data['id'])) $error_records_query->where('id', $filter_data['id']);
            if (isset($filter_data['account_id'])) $error_records_query->where('account_id', $filter_data['account_id']);
            if (isset($filter_data['updated_by_aao'])) $error_records_query->where('updated_by_aao', $filter_data['updated_by_aao']);

            $error_record = $error_records_query->first();

            return $error_record;
        }

        public function getErrorRecordsDataByFilter($filter_data, $column_list_data = null)
        {
            $error_records_query = DB::table('error_records');

            if (is_array($column_list_data)) {
                //$column_list = implode(',', $column_list_data);
                //dd($column_list);
                $error_records_query->select($column_list_data);
                //dd($error_records_query);
            }

            if (!empty($filter_data['id'])) $error_records_query->where('id', $filter_data['id']);
            if (!empty($filter_data['account_id'])) $error_records_query->where('account_id', $filter_data['account_id']);
            if (isset($filter_data['updated_by_aao'])) $error_records_query->where('updated_by_aao', $filter_data['updated_by_aao']);

            //dd($column_list_data, $filter_data, $error_records_query);

            $error_records = $error_records_query->get();
            //dd($error_records, $filter_data, $column_list_data);

            return $error_records;
        }

     /*public function getSuccessRecordsDataByFilter($module, $filter_data, $column_list_data = null, $pagination_data = null, $downloadExcel = null)
        {
            set_time_limit(72000);
            $contractors = DB::table('admins')->where('type', '=', 'contractor_manager')->select('admins.id as contractor_id', 'admins.name as contractor_name')->get();

            $index = 0;
            $select_query = '';
            if($module === 'success_records' || $module === 'meter_mains_success_records_with_image'){
                $select_query = DB::table('successful_records')
                    ->join('meter_mains', 'successful_records.account_id', '=', 'meter_mains.account_id')
                    ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                    ->join('admins', 'meter_mains.created_by', '=', 'admins.id')
                    ->whereNotNull('meter_mains.serial_no_new')
                    ->whereNotNull('meter_mains.serial_no_old');
                $total_count_query_result = $select_query->select(DB::raw('count(successful_records.account_id) as total_records'))->get()->first();
                $total_count = $total_count_query_result->total_records;
            }
            elseif($module === 'error_records'){
                $select_query = DB::table('error_records')
                    ->join('meter_mains', 'successful_records.account_id', '=', 'meter_mains.account_id')
                    ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                    ->join('admins', 'meter_mains.created_by', '=', 'admins.id')
                    ->whereNotNull('meter_mains.serial_no_new')
                    ->whereNotNull('meter_mains.serial_no_old');

                $total_count_query_result = $select_query->select(DB::raw('count(error_records.account_id) as total_records'))->get()->first();
                $total_count = $total_count_query_result->total_records;
            }
            else{
                $select_query = DB::table('meter_mains')
                    ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                    ->join('admins', 'meter_mains.created_by', '=', 'admins.id')
                    ->whereNotNull('meter_mains.serial_no_new')
                    ->whereNotNull('meter_mains.serial_no_old');
                $total_count_query_result = $select_query->select(DB::raw('count(meter_mains.account_id) as total_records'))->get()->first();
                $total_count = $total_count_query_result->total_records;
            }


            if (!empty($filter_data['id'])) $select_query->where('id', $filter_data['id']);
            if (!empty($filter_data['account_id']) && $filter_data['account_id'] != "null") $select_query->where('meter_mains.account_id', 'LIKE', $filter_data['account_id'].'%');
            if (!empty($filter_data['rr_no']) && $filter_data['rr_no'] != "null") $select_query->where('consumer_details.rr_no', 'LIKE', $filter_data['rr_no'].'%');
            if (!empty($filter_data['meter_serial_no_new']) && $filter_data['meter_serial_no_new'] != "null") $select_query->where('meter_mains.serial_no_new', 'LIKE', $filter_data['meter_serial_no_new'].'%');
            if($module === 'success_records' || $module === 'meter_mains_success_records_with_image'){
                if (isset($filter_data['start_date']) && $filter_data['start_date'] != "null") $select_query->where('successful_records.created_at', '>=', $filter_data['start_date']. ' 00:00:00');
                if (isset($filter_data['end_date']) && $filter_data['end_date'] != "null") $select_query->where('successful_records.created_at', '<=', $filter_data['end_date']. ' 23:59:59');
            }
            elseif($module === 'error_records'){
                if (isset($filter_data['start_date']) && $filter_data['start_date'] != "null") $select_query->where('error_records.created_at', '>=', $filter_data['start_date']. ' 00:00:00');
                if (isset($filter_data['end_date']) && $filter_data['end_date'] != "null") $select_query->where('error_records.created_at', '<=', $filter_data['end_date']. ' 23:59:59');
            }
            else{
                if (isset($filter_data['start_date']) && $filter_data['start_date'] != "null") $select_query->where('meter_mains.created_at', '>=', $filter_data['start_date']. ' 00:00:00');
                if (isset($filter_data['end_date']) && $filter_data['end_date'] != "null") $select_query->where('meter_mains.created_at', '<=', $filter_data['end_date']. ' 23:59:59');
            }

            if (!empty($filter_data['division']) && $filter_data['division'] != "null") $select_query->where('consumer_details.division', '=', $filter_data['division']);
            if (!empty($filter_data['subdivision']) && $filter_data['subdivision'] != "null") $select_query->where('consumer_details.sd_pincode', '=', $filter_data['subdivision']);
            if (!empty($filter_data['section']) && $filter_data['section'] != "null") $select_query->where('consumer_details.so_pincode', '=', $filter_data['section']);
            if (!empty($filter_data['feeder_code']) && $filter_data['feeder_code'] != "null") $select_query->where('consumer_details.feeder_code', '=', $filter_data['feeder_code']);
            if (!empty($filter_data['search_value']) && $filter_data['search_value'] != "null"){
                $search_value = $filter_data['search_value'];
                $select_query->where(function ($query) use ($search_value){
                    $query->whereOr('successful_records.account_id', 'LIKE', '%'.$search_value.'%')
                          ->whereOr('consumer_details.rr_no', 'LIKE', '%'.$search_value.'%');
                });
            }


            if($module === 'success_records' || $module === 'meter_mains_success_records_with_image'){
                $filtered_count_query_result = $select_query->select(DB::raw('count(successful_records.account_id) as filtered_records'))->get()->first();
                $filtered_count = $filtered_count_query_result->filtered_records;
            }
            elseif($module === 'error_records'){
                $filtered_count_query_result = $select_query->select(DB::raw('count(error_records.account_id) as filtered_records'))->get()->first();
                $filtered_count = $filtered_count_query_result->filtered_records;
            }
            else{
                $filtered_count_query_result = $select_query->select(DB::raw('count(meter_mains.account_id) as filtered_records'))->get()->first();
                $filtered_count = $filtered_count_query_result->filtered_records;
            }

            //dd($column_list_data, $filter_data, $select_query);

            if (is_array($column_list_data)) {
                //$column_list = implode(',', $column_list_data);
                //dd($column_list);
                $select_query->select($column_list_data);
                //dd($select_query);
            }

            if (!empty($pagination_data)) {
                $select_query->limit($pagination_data['limit']);
                $select_query->offset($pagination_data['start']);
                $index = $pagination_data['start'];
            }
            $filtered_records = $select_query->orderBy('meter_mains.created_at', 'ASC')->get()->all();

            foreach($filtered_records as $key=>$value){
                $index = $index + 1;
                $value->slno = $index;
                if ( empty($value->meter_make_new)) {
                    $value->meter_make_new = 'GENUS POWER INFRASTRUCTURE LTD';
                }
                if ( !empty($value->created_at)) {
                    $value->created_at = date('d-m-Y', strtotime($value->created_at));
                }
                if ( !empty($value->successful_records_created_at)) {
                    $value->successful_records_created_at = date('d-m-Y', strtotime($value->successful_records_created_at));
                }
                if ( !empty($value->error_records_created_at)) {
                    $value->error_records_created_at = date('d-m-Y', strtotime($value->error_records_created_at));
                }
                if($module === 'release_meter' || $module === 'meter_replacement'){
                    foreach ($contractors as $contractorKey => $contractorValue){
                        if($value->field_executive_contractor_id == $contractorValue->contractor_id){
                            $value->contractor_name = $contractorValue->contractor_name;
                        }
                    }
                }
            }
//            $formatted_data = array();
//            foreach ($filtered_records as $key => $value) {
//                $formatted_data[$key] = array_merge(['slno' => ++$key], (array)$value);
//                if (empty($formatted_data[$key]['meter_make_new'])) {
//                    $formatted_data[$key]['meter_make_new'] = 'GENUS POWER INFRASTRUCTURE LTD';
//                }
//                if (!empty($formatted_data[$key]['created_at'])) {
//                    $formatted_data[$key]['created_at'] = date('d-m-Y', strtotime($formatted_data[$key]['created_at']));
//                }
//                if (!empty($formatted_data[$key]['success_reported_at'])) {
//                    $formatted_data[$key]['success_reported_at'] = date('d-m-Y', strtotime($formatted_data[$key]['success_reported_at']));
//                }
////                    dd($key, $value, $formatted_data);
//            }
//            dd($filtered_records, $select_query, $filter_data, $column_list_data);

            $data = [
                'data' => $filtered_records,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $filtered_count
            ];

            return $data;
        }  */
		
		
		
	 public function getSuccessRecordsDataByFilter($module, $filter_data, $column_list_data = null, $pagination_data = null, $downloadExcel = null)
        {
            set_time_limit(72000);
            $contractors = DB::table('admins')->where('type', '=', 'contractor_manager')->select('admins.id as contractor_id', 'admins.name as contractor_name')->get();
        
            $index = 0;
            $select_query = '';
            if ($module === 'success_records' || $module === 'meter_mains_success_records_with_image') {
                $select_query = DB::table('successful_records')
                    ->join('meter_mains', 'successful_records.account_id', '=', 'meter_mains.account_id')
                    ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                    ->join('admins', 'meter_mains.created_by', '=', 'admins.id')
                    ->whereNotNull('meter_mains.serial_no_new')
                    ->whereNotNull('meter_mains.serial_no_old');
                $total_count_query_result = $select_query->select(DB::raw('count(successful_records.account_id) as total_records'))->get()->first();
                $total_count = $total_count_query_result->total_records;
            } elseif ($module === 'error_records') {
                $select_query = DB::table('error_records')
                    ->join('meter_mains', 'error_records.account_id', '=', 'meter_mains.account_id')
                    ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                    ->join('admins', 'meter_mains.created_by', '=', 'admins.id')
                    ->whereNotNull('meter_mains.serial_no_new')
                    ->whereNotNull('meter_mains.serial_no_old');
                $total_count_query_result = $select_query->select(DB::raw('count(error_records.account_id) as total_records'))->get()->first();
                $total_count = $total_count_query_result->total_records;
            } else {
                $select_query = DB::table('meter_mains')
                    ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                    ->join('admins', 'meter_mains.created_by', '=', 'admins.id')
                    ->whereNotNull('meter_mains.serial_no_new')
                    ->whereNotNull('meter_mains.serial_no_old');
                $total_count_query_result = $select_query->select(DB::raw('count(meter_mains.account_id) as total_records'))->get()->first();
                $total_count = $total_count_query_result->total_records;
            }
        
            if (!empty($filter_data['id'])) $select_query->where('id', $filter_data['id']);
            if (!empty($filter_data['account_id']) && $filter_data['account_id'] != "null") $select_query->where('meter_mains.account_id', 'LIKE', $filter_data['account_id'].'%');
            if (!empty($filter_data['rr_no']) && $filter_data['rr_no'] != "null") $select_query->where('consumer_details.rr_no', 'LIKE', $filter_data['rr_no'].'%');
            if (!empty($filter_data['meter_serial_no_new']) && $filter_data['meter_serial_no_new'] != "null") $select_query->where('meter_mains.serial_no_new', 'LIKE', $filter_data['meter_serial_no_new'].'%');
            if ($module === 'success_records' || $module === 'meter_mains_success_records_with_image') {
                if (isset($filter_data['start_date']) && $filter_data['start_date'] != "null") $select_query->where('successful_records.created_at', '>=', $filter_data['start_date']. ' 00:00:00');
                if (isset($filter_data['end_date']) && $filter_data['end_date'] != "null") $select_query->where('successful_records.created_at', '<=', $filter_data['end_date']. ' 23:59:59');
            } elseif ($module === 'error_records') {
                if (isset($filter_data['start_date']) && $filter_data['start_date'] != "null") $select_query->where('error_records.created_at', '>=', $filter_data['start_date']. ' 00:00:00');
                if (isset($filter_data['end_date']) && $filter_data['end_date'] != "null") $select_query->where('error_records.created_at', '<=', $filter_data['end_date']. ' 23:59:59');
            } else {
                if (isset($filter_data['start_date']) && $filter_data['start_date'] != "null") $select_query->where('meter_mains.created_at', '>=', $filter_data['start_date']. ' 00:00:00');
                if (isset($filter_data['end_date']) && $filter_data['end_date'] != "null") $select_query->where('meter_mains.created_at', '<=', $filter_data['end_date']. ' 23:59:59');
            }
        
            if (!empty($filter_data['division']) && $filter_data['division'] != "null") $select_query->where('consumer_details.division', '=', $filter_data['division']);
            if (!empty($filter_data['subdivision']) && $filter_data['subdivision'] != "null") $select_query->where('consumer_details.sd_pincode', '=', $filter_data['subdivision']);
            if (!empty($filter_data['section']) && $filter_data['section'] != "null") $select_query->where('consumer_details.so_pincode', '=', $filter_data['section']);
            if (!empty($filter_data['feeder_code']) && $filter_data['feeder_code'] != "null") $select_query->where('consumer_details.feeder_code', '=', $filter_data['feeder_code']);
            if (!empty($filter_data['search_value']) && $filter_data['search_value'] != "null") {
                $search_value = $filter_data['search_value'];
                $select_query->where(function ($query) use ($search_value) {
                    $query->orWhere('successful_records.account_id', 'LIKE', '%'.$search_value.'%')
                          ->orWhere('consumer_details.rr_no', 'LIKE', '%'.$search_value.'%');
                });
            }
        
            if ($module === 'success_records' || $module === 'meter_mains_success_records_with_image') {
                $filtered_count_query_result = $select_query->select(DB::raw('count(successful_records.account_id) as filtered_records'))->get()->first();
                $filtered_count = $filtered_count_query_result->filtered_records;
            } elseif ($module === 'error_records') {
                $filtered_count_query_result = $select_query->select(DB::raw('count(error_records.account_id) as filtered_records'))->get()->first();
                $filtered_count = $filtered_count_query_result->filtered_records;
            } else {
                $filtered_count_query_result = $select_query->select(DB::raw('count(meter_mains.account_id) as filtered_records'))->get()->first();
                $filtered_count = $filtered_count_query_result->filtered_records;
            }
        
            if (is_array($column_list_data)) {
                $select_query->select($column_list_data);
            }
        
            if (!empty($pagination_data)) {
                $select_query->limit($pagination_data['limit']);
                $select_query->offset($pagination_data['start']);
                $index = $pagination_data['start'];
            }
        
            $filtered_records = $select_query->orderBy('meter_mains.created_at', 'ASC')->get()->all();
					
				//	dd($filtered_records);
        
            // Collect images for download
            $imageUrls = [];
            foreach ($filtered_records as $key => $value) {
                $index = $index + 1;
                $value->slno = $index;
        
                if (empty($value->meter_make_new)) {
                    $value->meter_make_new = 'GENUS POWER INFRASTRUCTURE LTD';
                }
                if (!empty($value->created_at)) {
                    $value->created_at = date('d-m-Y', strtotime($value->created_at));
                }
                if (!empty($value->successful_records_created_at)) {
                    $value->successful_records_created_at = date('d-m-Y', strtotime($value->successful_records_created_at));
                }
                if (!empty($value->error_records_created_at)) {
                    $value->error_records_created_at = date('d-m-Y', strtotime($value->error_records_created_at));
                }
                if ($module === 'release_meter' || $module === 'meter_replacement') {
                    foreach ($contractors as $contractorKey => $contractorValue) {
                        if ($value->field_executive_contractor_id == $contractorValue->contractor_id) {
                            $value->contractor_name = $contractorValue->contractor_name;
                        }
                    }
                }
        
                // Collect image URLs for download
                if (!empty($value->image_1_old)) $imageUrls[] = $value->image_1_old;
                if (!empty($value->image_2_old)) $imageUrls[] = $value->image_2_old;
                if (!empty($value->image_3_old)) $imageUrls[] = $value->image_3_old;
                if (!empty($value->image_1_new)) $imageUrls[] = $value->image_1_new;
                if (!empty($value->image_2_new)) $imageUrls[] = $value->image_2_new;
            }
        
			//dd($total_count);
			
            // Download and zip images
            $zipFilePath = $this->downloadAndZipImages($imageUrls, $filter_data['start_date'], $filter_data['end_date']);
        
            $data = [
                'data' => $filtered_records,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $filtered_count,
                'zip_file_path' => $zipFilePath // Include the path to the zip file in the response
            ];
        
            return $data;
        }
        
        private function downloadAndZipImages(array $imageUrls, $startDate, $endDate)
        {
            $savedPaths = [];
            $todayDate = Carbon::now()->format('Y-m-d_H-i-s');
            $saveDir = public_path('downloads//download_meter_mains_success_report/');
        
            // Ensure the directory exists
            if (!file_exists($saveDir)) {
                if (!mkdir($saveDir, 0755, true) && !is_dir($saveDir)) {
                    Log::error("Failed to create directory: " . $saveDir);
                    return null;
                }
            }
        
            // Download the images
            foreach ($imageUrls as $imageUrl) {
                $fullUrl = "https://hdgu.vishvin.com/" . ltrim($imageUrl, '/');
                $headers = @get_headers($fullUrl);
        
                if ($headers === false || strpos($headers[0], '200') === false) {
                    Log::error("Image not found at URL: " . $fullUrl);
                    continue; // Skip to the next image URL
                }
        
                $imageContent = @file_get_contents($fullUrl);
                if ($imageContent === false) {
                    Log::error("Failed to download image from URL: " . $fullUrl);
                    continue;
                }
        
                $imageName = basename($imageUrl);
                $savePath = $saveDir . '/' . $imageName;
                $saveResult = @file_put_contents($savePath, $imageContent);
        
                if ($saveResult === false) {
                    Log::error("Failed to save image to: " . $savePath);
                    continue;
                }
        
                $savedPaths[] = $savePath;
            }
        
            // If no images were saved successfully, return an error
            if (empty($savedPaths)) {
                Log::error("No images were successfully downloaded and saved.");
                return null;
            }
        
            // Create a zip file
            $zip = new ZipArchive();
            $zipFileName = 'download_meter_mains_success_report.zip';
            $zipFilePath = $saveDir . $zipFileName;
        
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Add files to the zip file
                foreach ($savedPaths as $filePath) {
                    $relativePath = basename($filePath);
                    $zip->addFile($filePath, $relativePath);
                }
                $zip->close();
            } else {
                Log::error("Failed to create zip file: " . $zipFilePath);
                return null;
            }
        
            // Return the path to the zip file
            return $zipFilePath;
        }   
		 
		

        public function updateOldRecordsUsingBackUpData()
        {
            $back_up_success_records = DB::table('successful_records_bk_2')->get()->all();
            //dd($back_up_success_records);
            $total_records = 0;
            $update_records = 0;
            foreach($back_up_success_records as $back_up_data){
                //dd($back_up_data->account_id);
                $current_success_record = DB::table('successful_records')->where('account_id', '=', $back_up_data->account_id)->first();
                //dd($current_success_record);
                if(isset($current_success_record)){
                    if($current_success_record->created_at !== $back_up_data->created_at && $current_success_record->updated_at !== $back_up_data->updated_at) {
                        $successful_record = Successful_record::find($current_success_record->id);
                        //dd($successful_record);
                        $successful_record->token = $back_up_data->token;
                        $successful_record->created_at = $back_up_data->created_at;
                        $successful_record->updated_at = $back_up_data->updated_at;
                        $successful_record->save();
                        //dd( DB::table('successful_records')->where('account_id', '=', $back_up_data->account_id)->first());
                        $update_records++;
                    }
                }
                $total_records++;
            }
            return response()->json(['total_records' => $total_records, 'updated_records' => $update_records]);
        }
    }
