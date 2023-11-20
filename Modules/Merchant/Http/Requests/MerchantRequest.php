<?php

namespace Modules\Merchant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'nullable|exists:merchants,id',
            'name' => 'required|string',
            'address' => 'required|string',
            'avatar' => 'nullable|mimes:png,jpg,jpeg,webp|max:5048',
            'banner' => 'nullable|mimes:png,jpg,jpeg,webp|max:5048',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
