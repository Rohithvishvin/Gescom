<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class MeterReplacementRecordsExport implements FromArray, WithHeadings, WithMapping
{
    use Exportable;
    protected $records;
    protected $extra_data;

    protected $is_project_head;

    public function __construct(array $records, array $extra_data, string $is_project_head)
    {
        $this->records = $records;
        $this->extra_data = $extra_data;
        $this->is_project_head = $is_project_head;
    }

    public function array(): array
    {
        return $this->records;
    }

    public function headings(): array
    {
        $headline = $this->extra_data['first_line'];
        if($this->is_project_head === 'true'){
            return [
                [$headline],
                ['Sl No.', 'Account Id', 'RR No.', 'Consumer Name', 'Feeder Code', 'Feeder Name', 'Division', 'Sub division', 'section', 'Tariff', 'Installation Type', 'EM Meter Sl. No.', 'EM Make', 'EM MFY', 'EM Meter FR', 'ES Meter Sl. No.', 'New Meter Initial Reading', 'ES Make', 'Date of Replacement', 'Latitude', 'Longitude', 'Contractor', 'Field Executive'],
            ];
        }
        else{
            return [
                [$headline],
                ['Sl No.', 'Account Id', 'RR No.', 'Consumer Name', 'Feeder Code', 'Feeder Name', 'Division', 'Sub division', 'section', 'Tariff', 'Installation Type', 'EM Meter Sl. No.', 'EM Make', 'EM MFY', 'EM Meter FR', 'ES Meter Sl. No.', 'New Meter Initial Reading', 'ES Make', 'Date of Replacement', 'Latitude', 'Longitude'],
            ];
        }

    }

    public function map($records): array
    {
        //dd($records->slno);
        //dd($records->slno);
        if($this->is_project_head === 'true'){
            return [
                $records->slno,
                $records->account_id,
                $records->rr_no,
                $records->consumer_name,
                $records->feeder_code,
                $records->feeder_name,
                $records->division,
                $records->sub_division,
                $records->section,
                $records->tariff,
                $records->phase_type,
                $records->serial_no_old,
                $records->meter_make_old,
                $records->mfd_year_old,
                $records->final_reading,
                $records->serial_no_new,
                $records->initial_reading_kvah,
                $records->meter_make_new,
                $records->created_at,
                $records->lat,
                $records->lon,
                $records->contractor_name,
                $records->field_executive_name,
            ];
        }
        else{
            return [
                $records->slno,
                $records->account_id,
                $records->rr_no,
                $records->consumer_name,
                $records->feeder_code,
                $records->feeder_name,
                $records->division,
                $records->sub_division,
                $records->section,
                $records->tariff,
                $records->phase_type,
                $records->serial_no_old,
                $records->meter_make_old,
                $records->mfd_year_old,
                $records->final_reading,
                $records->serial_no_new,
                $records->initial_reading_kvah,
                $records->meter_make_new,
                $records->created_at,
                $records->lat,
                $records->lon,
            ];
        }
//        return [
//            $records->invoice_number,
//            $records->user->name,
//        ];
    }
}
