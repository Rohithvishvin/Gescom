<?php

namespace App\Exports;

use App\Models\Successful_record;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class SuccessFullRecordsExport implements FromArray
{
    use Exportable;
    protected $records;
//    public function __construct($start_date, $end_date, $division, $subdivision, $section, $feeder_code)
//    {
//        $this->start_date = $start_date;
//        $this->end_date = $end_date;
//        $this->division = $division;
//        $this->subdivision = $subdivision;
//        $this->section = $section;
//        $this->feeder_code = $feeder_code;
//    }

    public function __construct(array $records)
    {
        $this->records = $records;
    }

//    public function headings(): array
//    {
//        return [
//            [' ', ' ','', ' ',' ', ' ',' ', ' ',' ', ' ',' ', ' ',' ', ' Annexure - II ',' ', ' '],
//            [' ', ' ','', ' ',' ', ' ',' ', ' ',' ', ' ',' ', ' ',' Second Row ', ' Second Row ',' ', ' '],
//        ];
//    }

    public function array(): array
    {
        return $this->records;
    }

//    public function query()
//    {
//        $success_query = DB::table('successful_records')
//            ->join('meter_mains', 'successful_records.account_id', '=', 'meter_mains.account_id')
//            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
//            ->where('successful_records.created_at', '>=', $this->start_date)
//            ->where('successful_records.created_at', '<=', $this->end_date)
//            ->whereNotNull('meter_mains.serial_no_new')
//            ->whereNotNull('meter_mains.serial_no_old')
//            ->select(\DB::raw('successful_records.created_at as successful_records_report_created_at,
//                successful_records.updated_at as successful_records_report_updated_at,
//                meter_mains.created_by as field_executive_id,
//                meter_mains.created_at,
//                meter_mains.serial_no_old,
//                meter_mains.meter_make_old,
//                meter_mains.mfd_year_old,
//                meter_mains.final_reading,
//                meter_mains.serial_no_new,
//                meter_mains.initial_reading_kvah,
//                consumer_details.meter_type,
//                consumer_details.division,
//                consumer_details.sub_division,
//                consumer_details.section,
//                consumer_details.account_id,
//                consumer_details.rr_no,
//                consumer_details.consumer_name,
//                consumer_details.feeder_name,
//                consumer_details.feeder_code,
//                consumer_details.section,
//                consumer_details.sub_division,
//                consumer_details.tariff,
//                consumer_details.phase_type,
//                consumer_details.sub_division'
//            ));
//        if (!empty($this->division) && $this->division != "null") {
//            $success_query->where('consumer_details.division', '=', $this->division);
//        }
//        if (!empty($this->subdivision) && $this->subdivision != "null") {
//            $success_query->where('consumer_details.sd_pincode', '=', $this->subdivision);
//        }
//        if (!empty($this->section) && $this->section != "null") {
//            $success_query->where('consumer_details.so_pincode', '=', $this->section);
//        }
//        if (!empty($this->feeder_code) && $this->feeder_code != "null") {
//            $success_query->where('consumer_details.feeder_code', '=', $this->feeder_code);
//        }
//    }

    /**
    * @return \Illuminate\Support\Collection
    */
//    public function collection()
//    {
//        $success_query = DB::table('successful_records')
//            ->join('meter_mains', 'successful_records.account_id', '=', 'meter_mains.account_id')
//            ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
//            ->where('successful_records.created_at', '>=', $start_date)
//            ->where('successful_records.created_at', '<=', $end_date)
//            ->whereNotNull('meter_mains.serial_no_new')
//            ->whereNotNull('meter_mains.serial_no_old')
//            ->select(\DB::raw('successful_records.created_at as successful_records_report_created_at,
//                successful_records.updated_at as successful_records_report_updated_at,
//                meter_mains.created_by as field_executive_id,
//                meter_mains.created_at,
//                meter_mains.serial_no_old,
//                meter_mains.meter_make_old,
//                meter_mains.mfd_year_old,
//                meter_mains.final_reading,
//                meter_mains.serial_no_new,
//                meter_mains.initial_reading_kvah,
//                consumer_details.meter_type,
//                consumer_details.division,
//                consumer_details.sub_division,
//                consumer_details.section,
//                consumer_details.account_id,
//                consumer_details.rr_no,
//                consumer_details.consumer_name,
//                consumer_details.feeder_name,
//                consumer_details.feeder_code,
//                consumer_details.section,
//                consumer_details.sub_division,
//                consumer_details.tariff,
//                consumer_details.phase_type,
//                consumer_details.sub_division'
//            ));
//        if (!empty($division) && $division != "null") {
//            $success_query->where('consumer_details.division', '=', $division);
//        }
//        if (!empty($subdivision) && $subdivision != "null") {
//            $success_query->where('consumer_details.sd_pincode', '=', $subdivision);
//        }
//        if (!empty($section) && $section != "null") {
//            $success_query->where('consumer_details.so_pincode', '=', $section);
//        }
//        if (!empty($feeder_code) && $feeder_code != "null") {
//            $success_query->where('consumer_details.feeder_code', '=', $feeder_code);
//        }
//        $success_query_results = $success_query->get();
//        return $success_query_results;
//    }
}
