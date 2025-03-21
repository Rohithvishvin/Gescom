<?php

    namespace App\Http\Services;

    use App\Models\Consumer_detail;
    use Illuminate\Support\Facades\DB;

    class ConsumerDetailService
    {
        public function getConsumerDetailByAccountId($accountId)
        {
            $consumer_detail = DB::table('consumer_details')->where('account_id', $accountId)->first();

            return $consumer_detail;
        }

       /* public function getDistinctSoPincode()
        {
            $so_pin_codes = DB::table('consumer_details')
                ->distinct('so_pincode')
                ->select('so_pincode as so_code')
                ->orderBy('so_pincode')
                ->get();

            return $so_pin_codes;
        } */

        public function getDistinctSoPincode()
        {
            // Fetching distinct 'so_pincode' from the 'consumer_details' table

                $so_pin_codes = DB::table('consumer_details')
                ->distinct()
                ->select('so_pincode as so_code')  // Alias 'so_pincode' as 'so_code'
                ->orderBy('so_pincode')
                ->get();


            return $so_pin_codes;
        }

        


        public function getSingleConsumerDetailsByFilter($filter_data, $column_list_data = null)
        {
            $consumer_detail_query = DB::table('consumer_details');

            if(is_array($column_list_data)){
                $column_list = implode($column_list_data);
                $consumer_detail_query->select($column_list);
            }

            if(!empty($filter_data['account_id'])) $consumer_detail_query->where('account_id', $filter_data['account_id']);
            if(!empty($filter_data['rr_no'])) $consumer_detail_query->where('rr_no', $filter_data['rr_no']);
            if(!empty($filter_data['so_pincode'])) $consumer_detail_query->where('so_pincode', $filter_data['so_pincode']);

            $consumer_detail = $consumer_detail_query->first();

            return $consumer_detail;
        }

                public function getSingleConsumerDetailsByFilterbulk($filter_data, $column_list_data = null)
                {
                    // Start with a base query for the consumer_details table
                    $consumer_detail_query = DB::table('consumer_details');
                
                    // If column_list_data is provided, select specific columns
                    if (is_array($column_list_data)) {
                        $column_list = implode(',', $column_list_data); // Ensure the columns are comma-separated
                        $consumer_detail_query->select(DB::raw($column_list)); // Use DB::raw to safely insert the column list
                    } else {
                        // If column_list_data is not provided, default to specific columns
                        $consumer_detail_query->select('account_id', 'consumer_name', 'consumer_address', 'rr_no');
                    }
                
                    // Check if account_id is provided and handle it for single or multiple IDs
                    if (!empty($filter_data['account_id'])) {
                        // If account_id is a comma-separated string, convert it into an array
                        $account_ids = explode(',', $filter_data['account_id']);
                        $account_ids = array_map('trim', $account_ids); // Trim any extra spaces from the account IDs
                
                        // If there's more than one account ID, use whereIn; otherwise, use where
                        if (count($account_ids) > 1) {
                            $consumer_detail_query->whereIn('account_id', $account_ids);
                        } else {
                            $consumer_detail_query->where('account_id', $account_ids[0]);
                        }
                    }
                
                    // If rr_no is provided, add to the query
                    if (!empty($filter_data['rr_no'])) {
                        $consumer_detail_query->where('rr_no', $filter_data['rr_no']);
                    }
                
                    // If so_pincode is provided, add to the query
                    if (!empty($filter_data['so_pincode'])) {
                        $consumer_detail_query->where('so_pincode', $filter_data['so_pincode']);
                    }
                
                    // Execute the query and get the results
                    $consumer_detail = $consumer_detail_query->get();

                   // dd($consumer_detail);
                
                    // Return the fetched consumer details
                    return $consumer_detail;
                }
                



        public function getConsumerDetailsByPinCode($so_pincode)
        {
            // Fetching the data from the 'consumer_details' table
            $consumerDetails = DB::table('consumer_details')
                                 ->where('so_pincode', $so_pincode)
                                 ->select('account_id', 'rr_no', 'consumer_name', 'consumer_address')
                                 ->get();
        
            // Returning the fetched data
            return $consumerDetails;
        }
        
    


    }


   