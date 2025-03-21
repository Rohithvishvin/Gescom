<?php

    namespace App\Http\Services;

    use App\Models\Indent;
    use Illuminate\Support\Facades\DB;

    class IndentService
    {
        public function getTotalQuantitiesSectionWise($filter_data = null)
        {
            $getQuantitiesQuery = DB::table('indents')
//            ->select(DB::raw('SUM(meter_quantity) as total_quantity'));
            ->select('so_code', 'meter_quantity');

//            if (!empty($filter_data['section_code'])){
//                $getQuantitiesQuery->where('so_code', $filter_data['section_code']);
//            }
            $aResults = $getQuantitiesQuery->get()->toArray();
            //if($filter_data['section_code'] == 550132) dd($getQuantitiesQuery, $aResults->total_quantity);
            $indents_total_count = array();
            foreach($aResults as $aResult){
                $temp_array = (array)$aResult;

                if(!str_contains($temp_array['meter_quantity'], ',')){
                    if(isset($indents_total_count[$temp_array['so_code']])){
                        //dd('here');
                        $indents_total_count[$temp_array['so_code']] = $indents_total_count[$temp_array['so_code']] + (int)(int)$temp_array['meter_quantity'];
                    }
                    else {
                        $indents_total_count[$temp_array['so_code']] = (int)$temp_array['meter_quantity'];
                    }

                }
                else if(str_contains($temp_array['meter_quantity'], ',')){
                    $temp_section_codes_array = explode(',', $temp_array['so_code']);
                    $temp_meter_quantity_codes_array = explode(',', $temp_array['meter_quantity']);
                    foreach($temp_section_codes_array as $temp_section_code_key=>$temp_section_code){
                        if(isset($indents_total_count[$temp_section_code])){
                            //dd('here');
                            $indents_total_count[$temp_section_code] = $indents_total_count[$temp_section_code] + (int)$temp_meter_quantity_codes_array[$temp_section_code_key];
                        }
                        else {
                            $indents_total_count[$temp_section_code] = (int)$temp_meter_quantity_codes_array[$temp_section_code_key];
                        }
                    }
                    //dd($temp_section_codes_array, $temp_meter_quantity_codes_array, $indents_total_count);
                }
            }
            //dd($indents_total_count);
            if (!empty($filter_data['section_code']) && !empty($indents_total_count[$filter_data['section_code']])){
                return $indents_total_count[$filter_data['section_code']];
            }
            return 0;
        }
    }
