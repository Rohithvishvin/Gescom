<?php

    namespace App\Http\Services;

    use App\Models\Zone_code;
    use Illuminate\Support\Facades\DB;

    class ZoneCodeService
    {
        public function getSectionCodes()
        {
            $package = env('PACKAGE_NAME');
            $get_section_codes = DB::table('zone_codes')->select('so_code', 'section_office', 'sd_code', 'sub_division', 'div_code', 'division',)->distinct('so_code')->where('package', $package)->get()->toArray();

            return $get_section_codes;
        }
    }
