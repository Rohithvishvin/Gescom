<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessfulRecord extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the model name convention
    protected $table = 'successful_records_vishvin_qc';

    // Specify the fillable fields
    protected $fillable = ['account_id', 'token'];
}