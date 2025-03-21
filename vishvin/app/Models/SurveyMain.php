<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyMain extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'survey_mains';

    // Disable timestamps if not required
    public $timestamps = false;

    // Specify the fillable properties
    protected $fillable = [
        'account_id',
        'created_by',
        'created_at',
        'meter_make_old',
        'serial_no_old',
        'mfd_year_old',
        'final_reading',
        'image_1_old',
        'image_2_old',
        'image_3_old',
        'geo_link',
        'lat',
        'lon',
    ];
}