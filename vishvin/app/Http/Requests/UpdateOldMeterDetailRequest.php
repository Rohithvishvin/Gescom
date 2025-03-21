<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateOldMeterDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "meter_make_old" => 'required|string',
            "serial_no_old" => 'required|numeric',
            "mfd_year_old" => 'required|numeric',
            "final_reading" => 'required|numeric',
            "image_1_old" => ['required', File::types(['png', 'jpeg', 'jpg'])], // 'jpg'
            "image_2_old" => ['required', File::types(['png', 'jpeg', 'jpg'])], // 'jpg'
            //"image_3_old" => [File::types(['png', 'jpeg', 'jpg'])],
        ];
    }
}
