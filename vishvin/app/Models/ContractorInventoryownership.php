<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorInventoryownership extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the model name convention
    protected $table = 'contractor_inventories_meter_ownership';

    // Specify the fillable fields for mass assignment
    // Specify the fillable attributes
    protected $fillable = [
        'serial_no',
        'unused_meter_serial_no', // Add this line
        'used_meter_serial_no',
        'box_id',
        'division',
        'meter_type',
        'dc_no',
        'contractor_id',
        'vishvin_contractor_id',
        'count_updation',
        'created_by',
        'created_at_array', // Add any other attributes you need to be mass assignable
    ];
    

    // If the table doesn't have the updated_at field, disable timestamps
    public $timestamps = false;
}
