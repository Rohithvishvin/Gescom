<?php

namespace App\Exports;

use Excel;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Illuminate\Support\Facades\DB;

class SuccessFullRecordsWithImagesExport implements FromArray, WithHeadings, WithMapping
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

    public function custom()
    {
        Excel::extend(static::class, function (SuccessFullRecordsWithImagesExport $export, Sheet $sheet) {
            /** @var Worksheet $sheet */
            foreach ($sheet->getColumnIterator('O', 'O') as $row) {
                //dd($row);
                foreach ($row->getCellIterator() as $cell) {
                    //var_dump($cell->getValue());
                    if (str_contains($cell->getValue(), 'uploads')) {
                        $cell->setHyperlink(new Hyperlink(str_replace('/', '\\', $cell->getValue()), str_replace('/', '\\', $cell->getValue())));
//                        $cell->setHyperlink(new Hyperlink('uploads\test1.jpg', str_replace('/', '\\', $cell->getValue())));
                        $sheet->getStyle($cell->getCoordinate())->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => '0000FF'],
                                'underline' => 'single'
                            ]
                        ]);
                    }
                }
            }
            foreach ($sheet->getColumnIterator('P', 'P') as $row) {
                //dd($row);
                foreach ($row->getCellIterator() as $cell) {
                    //var_dump($cell->getValue());
                    if (str_contains($cell->getValue(), 'uploads')) {
                        $cell->setHyperlink(new Hyperlink(str_replace('/', '\\', $cell->getValue()), str_replace('/', '\\', $cell->getValue())));
//                        $cell->setHyperlink(new Hyperlink('uploads\test1.jpg', str_replace('/', '\\', $cell->getValue())));
                        $sheet->getStyle($cell->getCoordinate())->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => '0000FF'],
                                'underline' => 'single'
                            ]
                        ]);
                    }
                }
            }
            foreach ($sheet->getColumnIterator('Q', 'Q') as $row) {
                //dd($row);
                foreach ($row->getCellIterator() as $cell) {
                    //var_dump($cell->getValue());
                    if (str_contains($cell->getValue(), 'uploads')) {
                        $cell->setHyperlink(new Hyperlink(str_replace('/', '\\', $cell->getValue()), str_replace('/', '\\', $cell->getValue())));
//                        $cell->setHyperlink(new Hyperlink('uploads\test1.jpg', str_replace('/', '\\', $cell->getValue())));
                        $sheet->getStyle($cell->getCoordinate())->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => '0000FF'],
                                'underline' => 'single'
                            ]
                        ]);
                    }
                }
            }
            foreach ($sheet->getColumnIterator('U', 'U') as $row) {
                //dd($row);
                foreach ($row->getCellIterator() as $cell) {
                    //var_dump($cell->getValue());
                    if (str_contains($cell->getValue(), 'uploads')) {
                        $cell->setHyperlink(new Hyperlink(str_replace('/', '\\', $cell->getValue()), str_replace('/', '\\', $cell->getValue())));
//                        $cell->setHyperlink(new Hyperlink('uploads\test1.jpg', str_replace('/', '\\', $cell->getValue())));
                        $sheet->getStyle($cell->getCoordinate())->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => '0000FF'],
                                'underline' => 'single'
                            ]
                        ]);
                    }
                }
            }
            foreach ($sheet->getColumnIterator('V', 'V') as $row) {
                //dd($row);
                foreach ($row->getCellIterator() as $cell) {
                    //var_dump($cell->getValue());
                    if (str_contains($cell->getValue(), 'uploads')) {
                        $cell->setHyperlink(new Hyperlink(str_replace('/', '\\', $cell->getValue()), str_replace('/', '\\', $cell->getValue())));
//                        $cell->setHyperlink(new Hyperlink('uploads\test1.jpg', str_replace('/', '\\', $cell->getValue())));
                        $sheet->getStyle($cell->getCoordinate())->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => '0000FF'],
                                'underline' => 'single'
                            ]
                        ]);
                    }
                }
            }
        }, AfterSheet::class);
    }

    public function array(): array
    {
//        foreach ($this->records as $row) {
//            $temp_old_1_image_url = str_replace('/', '\\', $row->image_1_old);
//            $temp_old_2_image_url = str_replace('/', '\\', $row->image_2_old);
//            $temp_old_3_image_url = str_replace('/', '\\', $row->image_3_old);
//            $temp_new_1_image_url = str_replace('/', '\\', $row->image_1_new);
//            $temp_new_2_image_url = str_replace('/', '\\', $row->image_2_new);
////            $row->image_1_old = "=HYPERLINK('".str_replace('/', '\\', $row->image_1_old)."', '".$row->image_1_old."')";
////            $row->image_2_old = "=HYPERLINK('".str_replace('/', '\\', $row->image_2_old)."', '".$row->image_2_old."')";
////            $row->image_3_old = "=HYPERLINK('".str_replace('/', '\\', $row->image_3_old)."', '".$row->image_3_old."')";
////            $row->image_1_new = "=HYPERLINK('".str_replace('/', '\\', $row->image_1_new)."', '".$row->image_1_new."')";
////            $row->image_2_new = "=HYPERLINK('".str_replace('/', '\\', $row->image_2_new)."', '".$row->image_2_new."')";
//
//            $row->image_1_old = new Hyperlink($temp_old_1_image_url, $temp_old_1_image_url);
//            $row->image_2_old = new Hyperlink($temp_old_2_image_url, $temp_old_2_image_url);
//            $row->image_3_old = new Hyperlink($temp_old_3_image_url, $temp_old_3_image_url);
//            $row->image_1_new = new Hyperlink($temp_new_1_image_url, $temp_new_1_image_url);
//            $row->image_2_new = new Hyperlink($temp_new_2_image_url, $temp_new_2_image_url);
//        }
        //dd($this->records);
        return $this->records;
    }

    public function headings(): array
    {
        $headline = $this->extra_data['first_line'];
        return [
            [$headline],
            ['Sl No.', 'Account Id', 'RR No.', 'Consumer Name', 'Feeder Code', 'Feeder Name', 'Division', 'Sub division', 'section', 'Tariff', 'Installation Type', 'EM Meter Sl. No.', 'EM Make', 'EM MFY', 'Old Image 1', 'Old Image 2', 'Old Image 3', 'EM Meter FR', 'ES Meter Sl. No.', 'ES Make', 'New Image 1', 'New Image 2', 'New Meter Initial Reading', 'Latitude', 'Longitude', 'Date of Replacement', 'Success Reported on'],
        ];
    }

    public function map($records): array
    {
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
//            new Hyperlink(str_replace('/', '\\', $records->image_1_old), str_replace('/', '\\', $records->image_1_old)),
//            new Hyperlink(str_replace('/', '\\', $records->image_2_old), str_replace('/', '\\', $records->image_2_old)),
//            new Hyperlink(str_replace('/', '\\', $records->image_3_old), str_replace('/', '\\', $records->image_3_old)),
            $records->image_1_old,
            $records->image_2_old,
            $records->image_3_old,
//            str_replace('/', '\\', $records->image_1_old),
//            str_replace('/', '\\', $records->image_2_old),
//            str_replace('/', '\\', $records->image_3_old),
            $records->final_reading,
            $records->serial_no_new,
            $records->meter_make_new,
//            new Hyperlink(str_replace('/', '\\', $records->image_1_new), str_replace('/', '\\', $records->image_1_new)),
//            new Hyperlink(str_replace('/', '\\', $records->image_2_new), str_replace('/', '\\', $records->image_2_new)),
            $records->image_1_new,
            $records->image_2_new,
//            str_replace('/', '\\', $records->image_1_new),
//            str_replace('/', '\\', $records->image_2_new),
            $records->initial_reading_kvah,
            $records->lat,
            $records->lon,
            $records->created_at,
            $records->success_reported_at,

        ];
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class    => function(AfterSheet $event) {

                $data = [];
                foreach ($event->sheet->getColumnIterator('A','A') as $row) {

                    foreach ($row->getCellIterator() as $cell) {

                        if (str_contains($cell->getValue(), 'custom_url://')) {

                            $value = str_replace('custom_url://','',$cell->getValue());

                            $user = explode('||',$value);
                            $cell->setValue($user[0]);
                            $cell->setHyperlink(new Hyperlink('sheet://\''.$user[1].' '.$user[2].'\'!A1', $user[0]));

                            // Upd: Link styling added
                            $event->sheet->getStyle($cell->getCoordinate())->applyFromArray([
                                'font' => [
                                    'color' => ['rgb' => '0000FF'],
                                    'underline' => 'single'
                                ]
                            ]);
                        }
                    }
                }


            },
        ];
    }
}
