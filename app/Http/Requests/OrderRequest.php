<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required",
            "address" => "required",
            "province_id" => "required|numeric",
            "city_id" => "required",
            "postcode" => "required|numeric",
            "phone" => "required",
            "shipping_service" => "required",
        ];

        $data = $this->get('ship_to');
        if ($data) {
            $rules = array_merge(
                $rules,
                [
                    "shipping_firstname" => "required|string",
                    "shipping_lastname" => "required|string",
                    "shipping_email" => "required|email",
                    "shipping_address" => "required",
                    "shipping_province" => "required|numeric",
                    "shipping_city" => "required",
                    "shipping_postcode" => "required|string",
                    "shipping_phone" => "required|string",
                ]
            );
        }

        return $rules;
    }
}
