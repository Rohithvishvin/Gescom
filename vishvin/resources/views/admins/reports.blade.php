
<div class="row">
    <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
        <div class="card ongoing-project recent-orders">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Inventory Manager Report report-4</h4>

            </div>
            <br>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl. no.</th>
                                <th>Division Name</th>
                                <th>Contractor Name</th>
                                <th>Qty drawn from Stores</th>
                                <th>Qty installed in the filed</th>
                                {{-- <th>Balance Qty with Vishvin for implementation</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count =1;

                            @endphp
                            @foreach ($data['contractor_inventories'] as $contractor_inventory)
                                @php
                                    $zone_code =Zone_code::where('div_code',$contractor_inventory->division)->first();
                                    $admin = Admin::where('id',$contractor_inventory->contractor_id)->first();
                                    $break_single_meter = explode(',', $contractor_inventory->serial_no);
                                    $unused_count=0;
                                        foreach ($break_single_meter as $es_meter_individual) {
                                            $unused_count++;
                                        }

                                        $single_box = $contractor_inventory->used_meter_serial_no;
                                        $used_count=0;
                                        if ($single_box !== null && $single_box !== '') {
                                        $break_single_meter = explode(',', $single_box);

                                        foreach ($break_single_meter as $es_meter_individual) {
                                            $used_count++;
                                        }
                                    }
                                @endphp
                            <tr style="border-bottom: 1px solid #dee2e6;">
                               <td>{{$count}}</td>
                               <td>{{$zone_code->division}}</td>
                               <td>{{$admin->name}}</td>
                               <td>{{$unused_count}}</td>
                               <td>{{$used_count}}</td>
                               {{-- <td>{{$unused_count-$used_count}}</td> --}}
                            </tr>
                        @php
                            $count++;
                        @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
        <div class="card ongoing-project recent-orders">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Contractor Manager Report report-5</h4>
            </div>
            <br>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>

                                <th>Sl. no.</th>
                                <th>Qty Allocated</th>
                                <th>Qty installed in the field</th>
                                {{-- <th>Balance Qty with Contractor for implementation</th> --}}

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count =1;

                            @endphp
                            @foreach ($data['contractor_inventories'] as $contractor_inventory)
                                @php

                                    $break_single_meter = explode(',', $contractor_inventory->serial_no);
                                    $unused_count=0;
                                        foreach ($break_single_meter as $es_meter_individual) {
                                            $unused_count++;
                                        }

                                        $single_box = $contractor_inventory->used_meter_serial_no;
                                        $used_count=0;
                                        if ($single_box !== null && $single_box !== '') {
                                        $break_single_meter = explode(',', $single_box);

                                        foreach ($break_single_meter as $es_meter_individual) {
                                            $used_count++;
                                        }
                                    }
                                @endphp
                            <tr style="border-bottom: 1px solid #dee2e6;">
                               <td>{{$count}}</td>

                               <td>{{$unused_count}}</td>
                               <td>{{$used_count}}</td>
                               {{-- <td>{{$unused_count-$used_count}}</td> --}}
                            </tr>
                        @php
                            $count++;
                        @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
        <div class="card ongoing-project recent-orders">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1"> Report-6</h4>
            </div>
            <br>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl. no.</th>
                                <th>Field Executive Name</th>
                                <th>No of Meters Replaced</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count =1;

                            @endphp
                            @foreach ($data['get_all_field_executives'] as $get_field_executive)
                                @php
                                    $inventory = count(Inventory::where('created_by',$get_field_executive->id)->get());

                                @endphp
                            <tr style="border-bottom: 1px solid #dee2e6;">
                               <td>{{$count}}</td>
                               <td>{{$get_field_executive->name}}</td>
                               <td>{{$inventory}}</td>

                            </tr>
                        @php
                            $count++;
                        @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
        <div class="card ongoing-project recent-orders">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">QC Internal Manager Report Report-7</h4>
            </div>
            <br>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl. no.</th>
                                <th>QC Executive Name</th>
                                <th>No of records Approved</th>
                                <th>No of records Rejected</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count =1;

                            @endphp
                            @foreach ($data['get_all_qc_executives'] as $get_qc_executives)
                                @php
                                    $approved = count(Meter_main::where('qc_updated_by',$get_qc_executives->id)->where('qc_status',1)->get());
                                    $rejected = count(Meter_main::where('qc_updated_by',$get_qc_executives->id)->where('qc_status',2)->get());
                                @endphp
                            <tr style="border-bottom: 1px solid #dee2e6;">
                               <td>{{$count}}</td>
                               <td>{{$get_qc_executives->name}}</td>
                               <td>{{$approved}}</td>
                               <td>{{$rejected}}</td>
                            </tr>
                        @php
                            $count++;
                        @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
        <div class="card ongoing-project recent-orders">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">AE (Section Officer) Dashboard: Report 1 </h4>
            </div>
            <br>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl. no.</th>
                                <th>Section Name</th>
                                {{-- <th>Meters Drawn from store (ES Meters)</th> --}}
                                <th>ES to EM Meters Replaced</th>
                                {{-- <th>Balance quantity with Vishvin for implementation </th> --}}
                                <th>Record Approved by AE</th>
                                <th>Records pending for approval by AE</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count =1;

                            @endphp
                            @foreach ($data['get_all_so_code_for_belagavi'] as $get_all_so_code)
                                @php
                                    // $indents = Indent::get();
                                    // $meter_drawn_section_wise =0;
                                    // foreach ($indents as $meter_stock) {
                                    //     # code...
                                    //     $so_code = explode(',',$meter_stock->so_code);

                                    //     $count2=0;
                                    //     foreach ($so_code as $code) {
                                    //         if($code == $get_all_so_code->so_code){
                                    //             $meter_quantity =explode(',',$meter_stock->meter_quantity);
                                    //             $meter_drawn_section_wise = $meter_drawn_section_wise + $meter_quantity[$count2];
                                    //         }
                                    //         $count2++;
                                    //     }

                                    // }
                                    // $ware_house = Warehouse_meter::where('complete_status',1)->get();
                                    // $meter_drawn_section_wise =0;
                                    // foreach ($ware_house as $meter_stock) {
                                    //     # code...
                                    //     $so_code = explode(',',$meter_stock->so_code);

                                    //     $count2=0;
                                    //     foreach ($so_code as $code) {
                                    //         if($code == $get_all_so_code->so_code){
                                    //             $meter_quantity =explode(',',$meter_stock->meter_quantity);
                                    //             $meter_drawn_section_wise = $meter_drawn_section_wise + $meter_quantity[$count2];
                                    //         }
                                    //         $count2++;
                                    //     }

                                    // }

                                    $meter_replaced_section_wise = DB::table('meter_mains')
                                    ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                                    ->whereNotNull('meter_mains.serial_no_new')
                                    ->where('consumer_details.so_pincode', '=', $get_all_so_code->so_code)
                                    ->get();
                                    $meter_replaced_section_wise_count =count($meter_replaced_section_wise);

                                   $get_ae_approved_main_meter = DB::table('meter_mains')
                                        ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                                        ->select('meter_mains.*')
                                        ->where('consumer_details.so_pincode',$get_all_so_code->so_code)
                                        ->where('qc_status', '=', '1')
                                        ->where('so_status', '=', '1')
                                        ->get();
                                    $ae_meter_approved_count = count($get_ae_approved_main_meter);
                                    $get_ae_pending_main_meter = DB::table('meter_mains')
                                        ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                                        ->select('meter_mains.*')
                                        ->where('consumer_details.so_pincode',$get_all_so_code->so_code)
                                        ->where('qc_status', '=', '1')
                                        ->where('so_status', '=', '0')
                                        ->get();
                                    $ae_meter_pending_count = count($get_ae_pending_main_meter);

                                @endphp
                            <tr style="border-bottom: 1px solid #dee2e6;">
                               <td>{{$count}}</td>
                               <td>{{$get_all_so_code->section_office}}</td>
                               {{-- <td>{{ $meter_drawn_section_wise}}</td> --}}
                               <td>{{ $meter_replaced_section_wise_count }}</td>
                               {{-- <td>{{ $meter_drawn_section_wise- $meter_replaced_section_wise_count }}</td> --}}
                               <td>{{$ae_meter_approved_count}}</td>
                               <td>{{$ae_meter_pending_count}}</td>

                            </tr>
                        @php
                            $count++;
                        @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
        <div class="card ongoing-project recent-orders">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">AEE Dashboard: Report 2</h4>
            </div>
            <br>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl. no.</th>
                                <th>Sub Division Name</th>
                                <th>Record Approved by AEE </th>
                                <th>Records pending for approval by AEE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count =1;

                            @endphp
                            @foreach ($data['get_all_sd_code_for_belagavi'] as $get_all_sd_code)
                                @php
                                   $get_aee_approved_main_meter = DB::table('meter_mains')
                                        ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                                        ->select('meter_mains.*')
                                        ->where('consumer_details.sd_pincode',$get_all_sd_code->sd_code)
                                        ->where('qc_status', '=', '1')
                                        ->where('so_status', '=', '1')
                                        ->where('aee_status', '=', '1')

                                        ->get();
                                        $aee_meter_approved_count = count($get_aee_approved_main_meter);

                                    $get_aee_pending_main_meter = DB::table('meter_mains')
                                        ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                                        ->select('meter_mains.*')
                                        ->where('consumer_details.sd_pincode',$get_all_sd_code->sd_code)
                                        ->where('qc_status', '=', '1')
                                        ->where('so_status', '=', '1')
                                        ->where('aee_status', '=', '0')

                                        ->get();
                                        $aee_meter_pending_count = count($get_aee_pending_main_meter);
                                @endphp
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td>{{$count}}</td>
                                <td>{{$get_all_sd_code->sub_division}}</td>
                                <td>{{$aee_meter_approved_count}}</td>
                                <td>{{$aee_meter_pending_count}}</td>
                            </tr>
                            @php
                                $count++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
        <div class="card ongoing-project recent-orders">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">AAO Dashboard: Report 3</h4>
            </div>
            <br>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl. no.</th>
                                <th>Sub Division Name</th>
                                <th>Record Approved by AAO </th>
                                <th>Records pending for approval by AAO</th>
                                <th>Successfull records</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count =1;

                            @endphp
                            @foreach ($data['get_all_sd_code_for_belagavi'] as $get_all_sd_code)
                                @php
                                $get_aao_approved_main_meter = DB::table('meter_mains')
                                        ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                                        ->select('meter_mains.*')
                                        ->where('consumer_details.sd_pincode',$get_all_sd_code->sd_code)
                                        ->where('qc_status', '=', '1')
                                        ->where('so_status', '=', '1')
                                        ->where('aee_status', '=', '1')
                                        ->where('aao_status', '=', '1')
                                        ->get();
                                        $aao_meter_approved_count = count($get_aao_approved_main_meter);

                                    $get_aao_pending_main_meter = DB::table('meter_mains')
                                        ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                                        ->select('meter_mains.*')
                                        ->where('consumer_details.sd_pincode',$get_all_sd_code->sd_code)
                                        ->where('qc_status', '=', '1')
                                        ->where('so_status', '=', '1')
                                        ->where('aee_status', '=', '1')
                                        ->where('aao_status', '=', '0')
                                        ->get();
                                        $aao_meter_pending_count = count($get_aao_pending_main_meter);

                                    // $aao_meter_approved_count =  count(Meter_main::where('aao_status', '1')->where('aao_updated_by',$get_all_aao->id)->get());
                                    // $get_aao_pending_main_meter =DB::table('meter_mains')
                                    // ->join('consumer_details', 'meter_mains.account_id', '=', 'consumer_details.account_id')
                                    // ->join('admins', 'consumer_details.sd_pincode', '=', 'admins.sd_pincode')
                                    // ->select('meter_mains.*')
                                    // ->where('admins.id', $get_all_aao->id)
                                    // ->where('qc_status', 1)->where('so_status', 1)->where('aee_status', 1)->where('aao_status', 0)->orderBy('id')->get();
                                    // $aao_meter_approved_count = count($get_aao_pending_main_meter);
                                    $successfull_records = DB::table('successful_records')
                                    ->join('consumer_details', 'successful_records.account_id', '=', 'consumer_details.account_id')
                                    ->where('consumer_details.sd_pincode',$get_all_sd_code->sd_code)
                                    ->get();
                                    $successfull_records_count = count($successfull_records);

                                @endphp
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td>{{$count}}</td>
                                <td>{{$get_all_sd_code->sub_division}}</td>
                                <td>{{$aao_meter_approved_count}}</td>
                                <td>{{$aao_meter_pending_count}}</td>
                                <td>{{$successfull_records_count}}</td>
                            </tr>
                            @php
                                $count++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
