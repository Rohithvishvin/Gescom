<?php

    namespace App\Http\Services;

    use App\Models\Meter_main;
    use App\Models\SurveyMain;
    use Illuminate\Support\Facades\DB;

    class MeterMainService
    {
        public function getMeterMainsById($id)
        {
            $meter_main = DB::table('meter_mains')->where('id', $id)->first();

            return $meter_main;
        }

        public function getSurveyMainByIds($ids)
        {
            // Fetch all SurveyMain records that match the provided array of IDs
            $survey_mains = DB::table('survey_mains')->whereIn('id', $ids)->get();
        
            return $survey_mains;
        }
        

        public function getSurveyMainByIdz($ids)
{
    // Ensure that the provided $ids is an array, if it's not convert it into one
    if (!is_array($ids)) {
        $ids = explode(',', $ids); // Assuming the IDs are passed as a comma-separated string
    }

    // Fetch all SurveyMain records that match the provided array of IDs
    $survey_mains = DB::table('survey_mains')->whereIn('id', $ids)->get();

    return $survey_mains;
}


        public function getMeterMainsCreatedByFieldExecutiveId($fieldExecutiveId)
        {
            $meter_main = DB::table('meter_mains')
                ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->where('meter_mains.created_by', $fieldExecutiveId)
                ->where('meter_mains.created_at', '>=', date('Y-m-d') . ' 00:00:00')
                ->whereNotNull('meter_mains.serial_no_new')
                ->whereNotNull('meter_mains.serial_no_old')
                ->select('meter_mains.account_id', 'consumer_details.rr_no', 'meter_mains.created_at')
                ->orderBy('meter_mains.created_at', 'desc')
                ->get();

            return $meter_main;
        }

        public function getSurveyMeterMainsCreatedByFieldExecutiveId($fieldExecutiveId)
        {
            // Query to fetch relevant survey data from the survey_mains table
            $meter_main = DB::table('survey_mains')
                ->join('consumer_details', 'survey_mains.account_id', '=', 'consumer_details.account_id')
                ->where('survey_mains.created_by', $fieldExecutiveId)
                ->select(
                     '*'
                )
                ->orderBy('survey_mains.created_at', 'desc') // Order by the most recent created_at
                ->get();
         //dd($meter_main);
            return $meter_main;
        }
        

        public function getSingleMeterMainsByFilter($filter_data, $column_list_data = null)
        {
            $meter_main_query = DB::table('meter_mains');

            if (is_array($column_list_data)) {
                //$column_list = implode(',', $column_list_data);
                $meter_main_query->select($column_list_data);
            }

            if (!empty($filter_data['account_id'])) $meter_main_query->where('account_id', $filter_data['account_id']);

            $meter_main = $meter_main_query->first();

            return $meter_main;
        }

                 public function getSingleSurveyMeterMainsByFilter($filter_data, $column_list_data = null)
            {
                // Start building the query for survey_mains table
                $meter_main_query = DB::table('survey_mains');

                // If column_list_data is provided, select specific columns
                if (is_array($column_list_data)) {
                    $meter_main_query->select($column_list_data); // Use the provided column list for selection
                }

                // If account_id is provided in the filter data, use whereIn to fetch multiple accounts
                if (!empty($filter_data['account_id'])) {
                    // Use whereIn to handle multiple account IDs
                    $meter_main_query->whereIn('account_id', $filter_data['account_id']);
                }

                // Execute the query and get the result
                $meter_main = $meter_main_query->get();

                //dd($meter_main);

                // Return the results
                return $meter_main;
            }


       
        public function saveMeterMainData($column_list_data)
        {
            $meter_main = new Meter_main();

            if (!empty($column_list_data['account_id'])) $meter_main->account_id = $column_list_data['account_id'];
            if (!empty($column_list_data['created_by'])) $meter_main->created_by = $column_list_data['created_by'];
            if (!empty($column_list_data['allocation_flag'])) $meter_main->allocation_flag = $column_list_data['allocation_flag'];
            if (!empty($column_list_data['created_at'])) $meter_main->created_at = $column_list_data['created_at'];
            if (!empty($column_list_data['meter_make_old'])) $meter_main->meter_make_old = $column_list_data['meter_make_old'];
            if (!empty($column_list_data['serial_no_old'])) $meter_main->serial_no_old = $column_list_data['serial_no_old'];
            if (!empty($column_list_data['mfd_year_old'])) $meter_main->mfd_year_old = $column_list_data['mfd_year_old'];
            if (!empty($column_list_data['final_reading'])) $meter_main->final_reading = $column_list_data['final_reading'];
            if (!empty($column_list_data['image_1_old'])) $meter_main->image_1_old = $column_list_data['image_1_old'];
            if (!empty($column_list_data['image_2_old'])) $meter_main->image_2_old = $column_list_data['image_2_old'];
            if (!empty($column_list_data['image_3_old'])) $meter_main->image_3_old = $column_list_data['image_3_old'];

            if (!empty($column_list_data['image_1_new'])) $meter_main->image_1_new = $column_list_data['image_1_new'];
            if (!empty($column_list_data['image_2_new'])) $meter_main->image_2_new = $column_list_data['image_2_new'];
            if (!empty($column_list_data['serial_no_new'])) $meter_main->serial_no_new = $column_list_data['serial_no_new'];
            if (!empty($column_list_data['mfd_year_new'])) $meter_main->mfd_year_new = $column_list_data['mfd_year_new'];
            if (!empty($column_list_data['initial_reading_kwh'])) $meter_main->initial_reading_kwh = $column_list_data['initial_reading_kwh'];
            if (!empty($column_list_data['initial_reading_kvah'])) $meter_main->initial_reading_kvah = $column_list_data['initial_reading_kvah'];
            if (!empty($column_list_data['lat'])) $meter_main->lat = $column_list_data['lat'];
            if (!empty($column_list_data['lon'])) $meter_main->lon = $column_list_data['lon'];
            if (!empty($column_list_data['created_at'])) $meter_main->created_at = $column_list_data['created_at'];

            $meter_main->save();

            return $meter_main;
        }


        public function updateMeterMainData($meter_main_id, $column_list_data)
        {
            $meter_main = Meter_main::find($meter_main_id);
            //dd($meter_main);

            if (!empty($column_list_data['account_id'])) $meter_main->account_id = $column_list_data['account_id'];
            if (!empty($column_list_data['created_by'])) $meter_main->created_by = $column_list_data['created_by'];
            if (!empty($column_list_data['allocation_flag'])) $meter_main->allocation_flag = $column_list_data['allocation_flag'];
            if (!empty($column_list_data['created_at'])) $meter_main->created_at = $column_list_data['created_at'];
            if (!empty($column_list_data['meter_make_old'])) $meter_main->meter_make_old = $column_list_data['meter_make_old'];
            if (!empty($column_list_data['serial_no_old'])) $meter_main->serial_no_old = $column_list_data['serial_no_old'];
            if (!empty($column_list_data['mfd_year_old'])) $meter_main->mfd_year_old = $column_list_data['mfd_year_old'];
            if (!empty($column_list_data['final_reading'])) $meter_main->final_reading = $column_list_data['final_reading'];
            if (!empty($column_list_data['image_1_old'])) $meter_main->image_1_old = $column_list_data['image_1_old'];
            if (!empty($column_list_data['image_2_old'])) $meter_main->image_2_old = $column_list_data['image_2_old'];
            if (!empty($column_list_data['image_3_old'])) $meter_main->image_3_old = $column_list_data['image_3_old'];

            if (!empty($column_list_data['image_1_new'])) $meter_main->image_1_new = $column_list_data['image_1_new'];
            if (!empty($column_list_data['image_2_new'])) $meter_main->image_2_new = $column_list_data['image_2_new'];
            if (!empty($column_list_data['serial_no_new'])) $meter_main->serial_no_new = $column_list_data['serial_no_new'];
            if (!empty($column_list_data['mfd_year_new'])) $meter_main->mfd_year_new = $column_list_data['mfd_year_new'];
            if (!empty($column_list_data['initial_reading_kwh'])) $meter_main->initial_reading_kwh = $column_list_data['initial_reading_kwh'];
            if (!empty($column_list_data['initial_reading_kvah'])) $meter_main->initial_reading_kvah = $column_list_data['initial_reading_kvah'];
            if (!empty($column_list_data['lat'])) $meter_main->lat = $column_list_data['lat'];
            if (!empty($column_list_data['lon'])) $meter_main->lon = $column_list_data['lon'];
            if (!empty($column_list_data['created_at'])) $meter_main->created_at = $column_list_data['created_at'];

            $meter_main->save();

            return $meter_main;
        }

        public function saveSurveyMainData($column_list_data)
{
    $survey_main = new SurveyMain();

    // Assign values only if they are present in the input
    if (!empty($column_list_data['account_id'])) $survey_main->account_id = $column_list_data['account_id'];
    if (!empty($column_list_data['created_by'])) $survey_main->created_by = $column_list_data['created_by'];
    if (!empty($column_list_data['created_at'])) $survey_main->created_at = $column_list_data['created_at'];
    if (!empty($column_list_data['meter_make_old'])) $survey_main->meter_make_old = $column_list_data['meter_make_old'];
    if (!empty($column_list_data['serial_no_old'])) $survey_main->serial_no_old = $column_list_data['serial_no_old'];
    if (!empty($column_list_data['mfd_year_old'])) $survey_main->mfd_year_old = $column_list_data['mfd_year_old'];
    if (!empty($column_list_data['final_reading'])) $survey_main->final_reading = $column_list_data['final_reading'];
    if (!empty($column_list_data['image_1_old'])) $survey_main->image_1_old = $column_list_data['image_1_old'];
    if (!empty($column_list_data['image_2_old'])) $survey_main->image_2_old = $column_list_data['image_2_old'];
    if (!empty($column_list_data['image_3_old'])) $survey_main->image_3_old = $column_list_data['image_3_old'];
    if (!empty($column_list_data['lat'])) $survey_main->lat = $column_list_data['lat'];
    if (!empty($column_list_data['lon'])) $survey_main->lon = $column_list_data['lon'];

    // Save the new record into the database
    $survey_main->save();

    return $survey_main;
}



public function updateSurveyMainData($survey_main_ids, $column_list_data)
{
    // Convert the input IDs into an array if it's a comma-separated string
    $survey_main_ids = is_array($survey_main_ids) ? $survey_main_ids : explode(',', $survey_main_ids);

    foreach ($survey_main_ids as $survey_main_id) {
        // Find the SurveyMain record by ID or create a new instance if it doesn't exist
        $survey_main = SurveyMain::find($survey_main_id) ?? new SurveyMain();

        // Update only the specified fields if they are present in the input
        if (!empty($column_list_data['account_id'])) $survey_main->account_id = $column_list_data['account_id'];
        if (!empty($column_list_data['created_by'])) $survey_main->created_by = $column_list_data['created_by'];
        if (!empty($column_list_data['created_at'])) $survey_main->created_at = $column_list_data['created_at'];
        if (!empty($column_list_data['meter_make_old'])) $survey_main->meter_make_old = $column_list_data['meter_make_old'];
        if (!empty($column_list_data['serial_no_old'])) $survey_main->serial_no_old = $column_list_data['serial_no_old'];
        if (!empty($column_list_data['mfd_year_old'])) $survey_main->mfd_year_old = $column_list_data['mfd_year_old'];
        if (!empty($column_list_data['final_reading'])) $survey_main->final_reading = $column_list_data['final_reading'];
        if (!empty($column_list_data['image_1_old'])) $survey_main->image_1_old = $column_list_data['image_1_old'];
        if (!empty($column_list_data['image_2_old'])) $survey_main->image_2_old = $column_list_data['image_2_old'];
        if (!empty($column_list_data['image_3_old'])) $survey_main->image_3_old = $column_list_data['image_3_old'];
        if (!empty($column_list_data['geo_link'])) $survey_main->geo_link = $column_list_data['geo_link']; // Adding geo_link
        if (!empty($column_list_data['lat'])) $survey_main->lat = $column_list_data['lat'];
        if (!empty($column_list_data['lon'])) $survey_main->lon = $column_list_data['lon'];

        // Save the record to the database
        $survey_main->save();
    }

    // Return the last saved record for consistency, or modify as needed
    return $survey_main;
}




        public function bmr_report_pending_meters($format = null, $start_date = null, $end_date = null, $error_updated_by_aao = null, $download_flag = null, $meter_main_ids = null)
        {
            $approved_meters_query = DB::table('meter_mains')
                ->leftJoin('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                //->where('meter_mains.qc_status', 1)
                //->where('meter_mains.so_status', 1)
                //->where('meter_mains.aee_status', 1)
            ;
            if (!empty($meter_main_ids) && is_array($meter_main_ids)) {
                $approved_meters_query->whereIn('meter_mains.id', $meter_main_ids);
            } else {
                $approved_meters_query->where(function ($query) {
                    $query->where('meter_mains.aao_status', 1)
                        ->where('meter_mains.download_flag', 0);
                })
                    ->orWhere(function ($query) {
                        $query->where('meter_mains.error_updated_by_aao', 1);
                    });
            }
            $approved_meters = $approved_meters_query->select('meter_mains.id',
                'meter_mains.account_id',
                'meter_mains.created_at',
                'meter_mains.serial_no_old',
                'meter_mains.meter_make_old',
                'meter_mains.mfd_year_old',
                'meter_mains.final_reading',
                'meter_mains.serial_no_new',
                'meter_mains.serial_no_new',
                'meter_mains.initial_reading_kwh',
                'meter_mains.initial_reading_kvah',
                'consumer_details.sp_id',
                'consumer_details.sd_pincode',
                'consumer_details.meter_type',
            )
                ->get();
            //dd($approved_meters_query);

            return $approved_meters;
        }

        public function getUnAccountedIds()
        {
            $approved_meters_query = DB::table('meter_mains')
                ->leftJoin('successful_records', 'meter_mains.account_id', '=', 'successful_records.account_id')
                ->leftJoin('error_records', 'meter_mains.account_id', '=', 'error_records.account_id')->get();

            return $approved_meters_query;
        }

        public function meter_replacement_statistics_monthly()
        {
            $monthly_filter = array(
                'first' => array(
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("30 days")), 'Y-m-d 23:59:59'),
                    date('Y-m-d 23:59:59')
                ),
                'second' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("60 days")), 'Y-m-d 23:59:59'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("30 days")), 'Y-m-d 23:59:59')
                ],
                'third' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("90 days")), 'Y-m-d 23:59:59'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("60 days")), 'Y-m-d 23:59:59')
                ],
                'fourth' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("120 days")), 'Y-m-d 23:59:59'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("90 days")), 'Y-m-d 23:59:59')
                ],
                'fifth' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("150 days")), 'Y-m-d 00:00:00'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("120 days")), 'Y-m-d 23:59:59')
                ]
            );
            $division_implement_monthly_results = array();

            $package = env('PACKAGE_NAME');
            $get_section_codes = DB::table('zone_codes')->select('sd_code', 'division', 'sub_division', 'div_code')->distinct('sd_code')->where('package', $package)->get();

            $section_codes = json_decode(json_encode($get_section_codes, true), true);
            //dd($section_codes);

            $meter_available_section_code = array(
                '540004' => [
                    'first_phase' => 29736,
                    'third_phase' => 83,
                    'total' => 29819,
                ],
                '540002' => [
                    'first_phase' => 53178,
                    'third_phase' => 15,
                    'total' => 53193,
                ],
                '540001' => [
                    'first_phase' => 24560,
                    'third_phase' => 8,
                    'total' => 24568,
                ],
                '540076' => [
                    'first_phase' => 20107,
                    'third_phase' => 32,
                    'total' => 20139,
                ],
                '540006' => [
                    'first_phase' => 8372,
                    'third_phase' => 57,
                    'total' => 8429,
                ],
                '540008' => [
                    'first_phase' => 10213,
                    'third_phase' => 34,
                    'total' => 10247,
                ],
                '540011' => [
                    'first_phase' => 18108,
                    'third_phase' => 2,
                    'total' => 18110,
                ],
            );

            $grand_total_first_phase_meters_present = 0;
            $grand_total_third_phase_meters_present = 0;
            $grand_total_meters_present = 0;
            $grand_total_meters_replaced = 0;

            foreach ($section_codes as $sectionKey => $section_code) {
                //dd($section_code['sd_code']);
                $total_meter_replaced = 0;

                foreach ($monthly_filter as $monthlyKey => $filter_record) {
                    //dd($filter_record);
                    $division_implement_monthly = DB::table('consumer_details')
                        ->select('consumer_details.account_id', 'consumer_details.division', 'consumer_details.sub_division', 'consumer_details.section')
                        ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                        ->whereNotNull('meter_mains.serial_no_old')
                        ->whereNotNull('meter_mains.serial_no_new')
                        ->whereBetween('meter_mains.created_at', [$filter_record[0], $filter_record[1]])
                        ->where('consumer_details.sub_division', $section_code['sd_code'])
                        ->select(DB::raw('count(consumer_details.account_id) as total_meter_replaced_count'));
                    $division_implement_monthly_result = $division_implement_monthly->get();
                    $json_decoded_result = json_decode(json_encode($division_implement_monthly_result, true), true)[0];
                    $division_implement_monthly_results[$section_code['sd_code']][$monthlyKey] = $json_decoded_result;
                    $total_meter_replaced = $total_meter_replaced + $json_decoded_result['total_meter_replaced_count'];
//					dd($total_meter_replaced);
                }
                $division_implement_monthly_results[$section_code['sd_code']]['sd_code'] = $section_code['sd_code'];
                $division_implement_monthly_results[$section_code['sd_code']]['division'] = $section_code['division'];
                $division_implement_monthly_results[$section_code['sd_code']]['sub_division'] = $section_code['sub_division'];
                $division_implement_monthly_results[$section_code['sd_code']]['div_code'] = $section_code['div_code'];
                $division_implement_monthly_results[$section_code['sd_code']]['first_phase_meters'] = $meter_available_section_code[$section_code['sd_code']]['first_phase'];
                $division_implement_monthly_results[$section_code['sd_code']]['third_phase_meters'] = $meter_available_section_code[$section_code['sd_code']]['third_phase'];
                $division_implement_monthly_results[$section_code['sd_code']]['total_meters'] = $meter_available_section_code[$section_code['sd_code']]['total'];
                $division_implement_monthly_results[$section_code['sd_code']]['total_replaced_meters'] = $total_meter_replaced;
                $grand_total_first_phase_meters_present = $grand_total_first_phase_meters_present + $meter_available_section_code[$section_code['sd_code']]['first_phase'];
                $grand_total_third_phase_meters_present = $grand_total_third_phase_meters_present + $meter_available_section_code[$section_code['sd_code']]['third_phase'];
                $grand_total_meters_present = $grand_total_meters_present + $meter_available_section_code[$section_code['sd_code']]['total'];
                $grand_total_meters_replaced = $grand_total_meters_replaced + $total_meter_replaced;
                if ($total_meter_replaced > 0) {
                    $division_implement_monthly_results[$section_code['sd_code']]['total_completion_percent'] = ceil(($total_meter_replaced / $meter_available_section_code[$section_code['sd_code']]['total']) * 100);
                } else {
                    $division_implement_monthly_results[$section_code['sd_code']]['total_completion_percent'] = 0;
                }

            }

            $data = [
                "division_implement_monthly" => $division_implement_monthly_results,
                "grand_total_first_phase_meters_present" => $grand_total_first_phase_meters_present,
                "grand_total_third_phase_meters_present" => $grand_total_third_phase_meters_present,
                "grand_total_meters_present" => $grand_total_meters_present,
                "grand_total_meters_replaced" => $grand_total_meters_replaced,
                "grand_total_completion_percent" => ceil((($grand_total_meters_replaced / $grand_total_meters_present) * 100)),
            ];

            return $data;
        }

        public function meter_replacement_statistics_weekly()
        {
            $weekly_filter = array(
                'first' => array(
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("7 days")), 'Y-m-d 23:59:59'),
                    date('Y-m-d 23:59:59')
                ),
                'second' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("14 days")), 'Y-m-d 23:59:59'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("7 days")), 'Y-m-d 23:59:59')
                ],
                'third' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("21 days")), 'Y-m-d 23:59:59'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("14 days")), 'Y-m-d 23:59:59')
                ],
                'fourth' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("28 days")), 'Y-m-d 23:59:59'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("21 days")), 'Y-m-d 23:59:59')
                ],
                'fifth' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("35 days")), 'Y-m-d 00:00:00'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("28 days")), 'Y-m-d 23:59:59')
                ]
            );
            $division_implement_weekly_results = array();

            $package = env('PACKAGE_NAME');
            $get_section_codes = DB::table('zone_codes')->select('sd_code', 'division', 'sub_division', 'div_code')->distinct('sd_code')->where('package', $package)->get();

            $section_codes = json_decode(json_encode($get_section_codes, true), true);
            //dd($section_codes);

            $meter_available_section_code = array(
                '540004' => [
                    'first_phase' => 29736,
                    'third_phase' => 83,
                    'total' => 29819,
                ],
                '540002' => [
                    'first_phase' => 53178,
                    'third_phase' => 15,
                    'total' => 53193,
                ],
                '540001' => [
                    'first_phase' => 24560,
                    'third_phase' => 8,
                    'total' => 24568,
                ],
                '540076' => [
                    'first_phase' => 20107,
                    'third_phase' => 32,
                    'total' => 20139,
                ],
                '540006' => [
                    'first_phase' => 8372,
                    'third_phase' => 57,
                    'total' => 8429,
                ],
                '540008' => [
                    'first_phase' => 10213,
                    'third_phase' => 34,
                    'total' => 10247,
                ],
                '540011' => [
                    'first_phase' => 18108,
                    'third_phase' => 2,
                    'total' => 18110,
                ],
            );

            $grand_total_first_phase_meters_present = 0;
            $grand_total_third_phase_meters_present = 0;
            $grand_total_meters_present = 0;
            $grand_total_meters_replaced = 0;

            foreach ($section_codes as $sectionKey => $section_code) {
                //dd($section_code['sd_code']);
                $total_meter_replaced = 0;

                foreach ($weekly_filter as $weeklyKey => $filter_record) {
                    //dd($filter_record);
                    $division_implement_weekly = DB::table('consumer_details')
                        ->select('consumer_details.account_id', 'consumer_details.division', 'consumer_details.sub_division', 'consumer_details.section')
                        ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                        ->whereNotNull('meter_mains.serial_no_old')
                        ->whereNotNull('meter_mains.serial_no_new')
                        ->whereBetween('meter_mains.created_at', [$filter_record[0], $filter_record[1]])
                        ->where('consumer_details.sub_division', $section_code['sd_code'])
                        ->select(DB::raw('count(consumer_details.account_id) as total_meter_replaced_count'));
                    $division_implement_weekly_result = $division_implement_weekly->get();
                    $json_decoded_result = json_decode(json_encode($division_implement_weekly_result, true), true)[0];
                    $division_implement_weekly_results[$section_code['sd_code']][$weeklyKey] = $json_decoded_result;
                    $total_meter_replaced = $total_meter_replaced + $json_decoded_result['total_meter_replaced_count'];
//					dd($total_meter_replaced);
                }
                $division_implement_weekly_results[$section_code['sd_code']]['sd_code'] = $section_code['sd_code'];
                $division_implement_weekly_results[$section_code['sd_code']]['division'] = $section_code['division'];
                $division_implement_weekly_results[$section_code['sd_code']]['sub_division'] = $section_code['sub_division'];
                $division_implement_weekly_results[$section_code['sd_code']]['div_code'] = $section_code['div_code'];
                $division_implement_weekly_results[$section_code['sd_code']]['first_phase_meters'] = $meter_available_section_code[$section_code['sd_code']]['first_phase'];
                $division_implement_weekly_results[$section_code['sd_code']]['third_phase_meters'] = $meter_available_section_code[$section_code['sd_code']]['third_phase'];
                $division_implement_weekly_results[$section_code['sd_code']]['total_meters'] = $meter_available_section_code[$section_code['sd_code']]['total'];
                $division_implement_weekly_results[$section_code['sd_code']]['total_replaced_meters'] = $total_meter_replaced;
                $grand_total_first_phase_meters_present = $grand_total_first_phase_meters_present + $meter_available_section_code[$section_code['sd_code']]['first_phase'];
                $grand_total_third_phase_meters_present = $grand_total_third_phase_meters_present + $meter_available_section_code[$section_code['sd_code']]['third_phase'];
                $grand_total_meters_present = $grand_total_meters_present + $meter_available_section_code[$section_code['sd_code']]['total'];
                $grand_total_meters_replaced = $grand_total_meters_replaced + $total_meter_replaced;
                if ($total_meter_replaced > 0) {
                    $division_implement_weekly_results[$section_code['sd_code']]['total_completion_percent'] = ceil(($total_meter_replaced / $meter_available_section_code[$section_code['sd_code']]['total']) * 100);
                } else {
                    $division_implement_weekly_results[$section_code['sd_code']]['total_completion_percent'] = 0;
                }

            }

            $data = [
                "division_implement_weekly" => $division_implement_weekly_results,
                "grand_total_first_phase_meters_present" => $grand_total_first_phase_meters_present,
                "grand_total_third_phase_meters_present" => $grand_total_third_phase_meters_present,
                "grand_total_meters_present" => $grand_total_meters_present,
                "grand_total_meters_replaced" => $grand_total_meters_replaced,
                "grand_total_completion_percent" => ceil((($grand_total_meters_replaced / $grand_total_meters_present) * 100)),
            ];

            return $data;
        }

        public function meter_replacement_statistics_daily()
        {
            $daily_filter = array(
                'first' => array(
                    date('Y-m-d 00:00:00'),
                    date('Y-m-d 23:59:59')
                ),
                'second' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("1 days")), 'Y-m-d 00:00:00'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("1 days")), 'Y-m-d 23:59:59')
                ],
                'third' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("2 days")), 'Y-m-d 00:00:00'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("2 days")), 'Y-m-d 23:59:59')
                ],
                'fourth' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("3 days")), 'Y-m-d 00:00:00'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("3 days")), 'Y-m-d 23:59:59')
                ],
                'fifth' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("4 days")), 'Y-m-d 00:00:00'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("4 days")), 'Y-m-d 23:59:59')
                ],
                'sixth' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("5 days")), 'Y-m-d 00:00:00'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("5 days")), 'Y-m-d 23:59:59')
                ],
                'seventh' => [
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("6 days")), 'Y-m-d 00:00:00'),
                    date_format(date_sub(date_create(date('c')), date_interval_create_from_date_string("6 days")), 'Y-m-d 23:59:59')
                ]
            );
            $division_implement_daily_results = array();

            $package = env('PACKAGE_NAME');
            $get_section_codes = DB::table('zone_codes')->select('sd_code', 'division', 'sub_division', 'div_code')->distinct('sd_code')->where('package', $package)->get();

            $section_codes = json_decode(json_encode($get_section_codes, true), true);
            //dd($section_codes);

            $meter_available_section_code = array(
                '540004' => [
                    'first_phase' => 29736,
                    'third_phase' => 83,
                    'total' => 29819,
                ],
                '540002' => [
                    'first_phase' => 53178,
                    'third_phase' => 15,
                    'total' => 53193,
                ],
                '540001' => [
                    'first_phase' => 24560,
                    'third_phase' => 8,
                    'total' => 24568,
                ],
                '540076' => [
                    'first_phase' => 20107,
                    'third_phase' => 32,
                    'total' => 20139,
                ],
                '540006' => [
                    'first_phase' => 8372,
                    'third_phase' => 57,
                    'total' => 8429,
                ],
                '540008' => [
                    'first_phase' => 10213,
                    'third_phase' => 34,
                    'total' => 10247,
                ],
                '540011' => [
                    'first_phase' => 18108,
                    'third_phase' => 2,
                    'total' => 18110,
                ],
            );

            $grand_total_first_phase_meters_present = 0;
            $grand_total_third_phase_meters_present = 0;
            $grand_total_meters_present = 0;
            $grand_total_meters_replaced = 0;

            foreach ($section_codes as $sectionKey => $section_code) {
                //dd($section_code['sd_code']);
                $total_meter_replaced = 0;

                foreach ($daily_filter as $dailyKey => $filter_record) {
                    //dd($filter_record);
                    $division_implement_daily = DB::table('consumer_details')
                        ->select('consumer_details.account_id', 'consumer_details.division', 'consumer_details.sub_division', 'consumer_details.section')
                        ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                        ->whereNotNull('meter_mains.serial_no_old')
                        ->whereNotNull('meter_mains.serial_no_new')
                        ->whereBetween('meter_mains.created_at', [$filter_record[0], $filter_record[1]])
                        ->where('consumer_details.sub_division', $section_code['sd_code'])
                        ->select(DB::raw('count(consumer_details.account_id) as total_meter_replaced_count'));
                    $division_implement_daily_result = $division_implement_daily->get();
                    $json_decoded_result = json_decode(json_encode($division_implement_daily_result, true), true)[0];
                    $division_implement_daily_results[$section_code['sd_code']][$dailyKey] = $json_decoded_result;
                    $total_meter_replaced = $total_meter_replaced + $json_decoded_result['total_meter_replaced_count'];
//					dd($total_meter_replaced);
                }
                $division_implement_daily_results[$section_code['sd_code']]['sd_code'] = $section_code['sd_code'];
                $division_implement_daily_results[$section_code['sd_code']]['division'] = $section_code['division'];
                $division_implement_daily_results[$section_code['sd_code']]['sub_division'] = $section_code['sub_division'];
                $division_implement_daily_results[$section_code['sd_code']]['div_code'] = $section_code['div_code'];
                $division_implement_daily_results[$section_code['sd_code']]['first_phase_meters'] = $meter_available_section_code[$section_code['sd_code']]['first_phase'];
                $division_implement_daily_results[$section_code['sd_code']]['third_phase_meters'] = $meter_available_section_code[$section_code['sd_code']]['third_phase'];
                $division_implement_daily_results[$section_code['sd_code']]['total_meters'] = $meter_available_section_code[$section_code['sd_code']]['total'];
                $division_implement_daily_results[$section_code['sd_code']]['total_replaced_meters'] = $total_meter_replaced;
                $grand_total_first_phase_meters_present = $grand_total_first_phase_meters_present + $meter_available_section_code[$section_code['sd_code']]['first_phase'];
                $grand_total_third_phase_meters_present = $grand_total_third_phase_meters_present + $meter_available_section_code[$section_code['sd_code']]['third_phase'];
                $grand_total_meters_present = $grand_total_meters_present + $meter_available_section_code[$section_code['sd_code']]['total'];
                $grand_total_meters_replaced = $grand_total_meters_replaced + $total_meter_replaced;
                if ($total_meter_replaced > 0) {
                    $division_implement_daily_results[$section_code['sd_code']]['total_completion_percent'] = ceil(($total_meter_replaced / $meter_available_section_code[$section_code['sd_code']]['total']) * 100);
                } else {
                    $division_implement_daily_results[$section_code['sd_code']]['total_completion_percent'] = 0;
                }

            }

            $data = [
                "division_implement_daily" => $division_implement_daily_results,
                "grand_total_first_phase_meters_present" => $grand_total_first_phase_meters_present,
                "grand_total_third_phase_meters_present" => $grand_total_third_phase_meters_present,
                "grand_total_meters_present" => $grand_total_meters_present,
                "grand_total_meters_replaced" => $grand_total_meters_replaced,
                "grand_total_completion_percent" => ceil((($grand_total_meters_replaced / $grand_total_meters_present) * 100)),
            ];

            return $data;
        }

        public function getMeterMainsInstallationCountByFilter($filter_data)
        {
            $meter_main_query = DB::table('consumer_details')
                ->select('consumer_details.account_id', 'consumer_details.division', 'consumer_details.sub_division', 'consumer_details.section')
                ->join('meter_mains', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                ->whereNotNull('meter_mains.serial_no_old')
                ->whereNotNull('meter_mains.serial_no_new')
                ->select(DB::raw('count(consumer_details.account_id) as total_meter_replaced_count'));

            if (!empty($filter_data['division_code'])) $meter_main_query->where('consumer_details.division', $filter_data['division_code']);
            if (!empty($filter_data['sub_division_code'])) $meter_main_query->where('consumer_details.sub_division', $filter_data['sub_division_code']);
            if (!empty($filter_data['section_code'])) $meter_main_query->where('consumer_details.section', $filter_data['section_code']);

            $meter_main = $meter_main_query->get();

            return $meter_main[0]->total_meter_replaced_count;
        }
    }