<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterMainsVishvinQC extends Model
{
    protected $table = 'meter_mains_vishvin_qc';

    // Define the fields that are mass assignable, or use guarded if needed
    protected $fillable = [
        'account_id', 'image_1_old', 'image_2_old', 'image_3_old', 'meter_make_old', 
        'serial_no_old', 'mfd_year_old', 'final_reading', 'image_1_new', 'image_2_new',
        'meter_make_new', 'serial_no_new', 'mfd_year_new', 'initial_reading_kwh', 
        'initial_reading_kvah', 'lat', 'lon', 'qc_remark', 'qc_status', 'qc_updated_by',
        'qc_updated_at', 'so_status', 'so_remark', 'so_updated_by', 'so_updated_at',
        'aee_status', 'aee_remark', 'aee_updated_by', 'aee_updated_at', 'aao_status',
        'aao_remark', 'aao_updated_by', 'aao_updated_at', 'delete_flag', 'allocation_flag',
        'download_flag', 'error_updated_by_aao', 'error_updated_at', 'created_by', 'created_at'
    ];

    public $timestamps = true;  // if you need Laravel to handle created_at and updated_at automatically
    use HasFactory;
}

